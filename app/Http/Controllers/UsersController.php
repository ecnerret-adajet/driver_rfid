<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\User;
use Auth;
use App\Role;
use DB;
use Image;
use Hash;
use App\Permission;
use Flashy;
use App\Hauler;
use App\Driver;
use App\Truck;
use App\Company;
use App\UserLocation;


use App\SapUser;
use App\SapServer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('display_name','id');
        $haulers = Hauler::pluck('name','id');
        $companies = Company::pluck('name','id');
        $userLocations = UserLocation::pluck('name','id');
        return view('users.create',compact('roles','haulers','companies','userLocations'));  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'roles_list' => 'required',
            'phone_number' => 'required'
        ]);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->password = Hash::make($request->input('password'));
        $user->bypass_rfid = $request->has('bypass_rfid');

        if($request->hasFile('avatar')){
        $user->avatar = $request->file('avatar')->store('users');
        }
        $user->hauler()->associate($request->input('hauler_list'));
        $user->company()->associate($request->input('company_list'));
        $user->userLocation()->associate($request->input('user_location'));

        $user->save();

         $user->roles()->sync( (array) $request->input('roles_list') );

         $activity = activity()
         ->log('Created');

         flashy()->success('User has successfully created!');

        return redirect('users');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('display_name','id');
        $userRole = $user->roles->pluck('id','id')->toArray();
        $haulers = Hauler::pluck('name','id');
        $companies = Company::pluck('name','id');

        return view('users.edit',compact('user','roles','userRole','haulers','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            // 'password' => 'required|confirmed',
            'roles_list' => 'required',
            'phone_number' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }

        $user->update($input);
        $user->roles()->sync( (array) $request->input('roles_list'));
        $user->hauler()->associate($request->input('hauler_list'));
        $user->company()->associate($request->input('company_list'));
        $user->save();

        $activity = activity()
        ->log('Updated');

        flashy()->success('Driver has successfully updated!');
        return redirect('users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        $activity = activity()
        ->log('Deleted');

        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    public function haulerOnline()
    {
        return view('users.haulers-online');
    }

    public function userDriverHauler($id)
    {
        $user = User::findOrFail($id);

        if(!empty($user->hauler_id)) {
            $drivers = Driver::whereHas('haulers', function($q) use ($user) {
                $q->where('id',$user->hauler_id);
            })->with(['image','haulers','trucks','cardholder','card','cardholder.logs'])
            ->orderBy('id','DESC')
            ->get();
    
        }
        
        return $drivers;
    }

    public function userTruckHauler($id)
    {
        $user = User::findOrFail($id);

        if(!empty($user->hauler_id)){
            $trucks = Truck::whereHas('haulers', function($q) use ($user) {
                $q->where('id',$user->hauler_id);
            })->with(['drivers','haulers','drivers.cardholder','card','hauler'])
            ->orderBy('id','DESC')
            ->get();
        }

        return $trucks;
    }


    public function getPFMCEmailAddress($id){

        $to = $id;
        $from = $id;
        
        $client = new Client();

        $sap_user =  SapUser::where('server' , 'PFMC')->first();
       
        $sap_server = SapServer::where('server' , 'PFMC')->where('name' , 'PROD')->first();

        $connection = [
            'ashost' => $sap_server['host'],
            'sysnr' => $sap_server['system_number'],
            'client' => $sap_server['client'],
            'user' => $sap_user['username'],
            'passwd' => $sap_user['password']
        ];
    
       $all_user = User::select('id','email','pfmc_customer_code','lfug_customer_code')->where('id','>=',$from)->where('id','<=',$to)->whereNotNull('company_id')->get();
       $user_data = [];     

       foreach($all_user as $key => $user){

            
            $email = $user['email'];

            try{
                $address_number = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
                                ['query' => 
                                    ['connection' => $connection,
                                        'table' => [
                                            'table' => ['ADR6' => 'address_number'],
                                            'fields' => [
                                                'ADDRNUMBER' => 'address_number',
                                                'SMTP_ADDR' => 'email_address',
                                            ],
                                            'options' => [
                                                ['TEXT' => "SMTP_ADDR = '$email'"]
                                            ],
                                        ]
                                    ]
                                ],
                                ['timeout' => 600],
                                ['delay' => 10000]
                            );

                $address_number_datas = json_decode($address_number->getBody(), true);
                
                if($address_number_datas){
                    
                    $customer_data = [];
                    $customer_codes_data = [];

                    foreach($address_number_datas as $k => $address_number_data){
                        
                        $address_number = $address_number_data['address_number'];

                        if($address_number){
                            $customers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
                                ['query' => 
                                    ['connection' => $connection,
                                        'table' => [
                                            'table' => ['KNA1' => 'do_headers'],
                                            'fields' => [
                                                'KUNNR' => 'customer_code',
                                            ],
                                            'options' => [
                                                ['TEXT' => "ADRNR = '$address_number'"]
                                            ],
                                        ]
                                    ]
                                ],
                                ['timeout' => 600],
                                ['delay' => 10000]
                            );
                            $customers_data = json_decode($customers->getBody(), true);
                            if($customers_data){
                                foreach( $customers_data as $customer_code){
                                    array_push($customer_codes_data, $customer_code['customer_code']);
                                }
                            }
                        }
                    }
                    
                    $user_data[$key]['id'] = $user['id'];
                    $user_data[$key]['email'] = $email;
                    $compiled_customer_codes = $customer_codes_data ? implode(',',$customer_codes_data) : "";
                    $user_data[$key]['customer_code'] = $compiled_customer_codes;

                    if($compiled_customer_codes){
                        if($user['pfmc_customer_code'] != $compiled_customer_codes){
                            $data = [];
                            $data['pfmc_customer_code'] = $compiled_customer_codes;
                            $user->update($data);
                        }
                    }
                }
            }
            catch(ServerException $e){}
        }

        if($user_data){
            return 'Customer code exist';
        }else{
            return 'Customer not found';
        }
        
    }

    public function getLFUGEmailAddress($id){

        $to = $id;
        $from = $id;
        
        $client = new Client();

        $sap_user =  SapUser::where('server' , 'LFUG')->first();
       
        $sap_server = SapServer::where('server' , 'LFUG')->where('name' , 'PROD')->first();

        $connection = [
            'ashost' => $sap_server['host'],
            'sysnr' => $sap_server['system_number'],
            'client' => $sap_server['client'],
            'user' => $sap_user['username'],
            'passwd' => $sap_user['password']
        ];
    
       $all_user = User::select('id','email','pfmc_customer_code','lfug_customer_code')->where('id','>=',$from)->where('id','<=',$to)->whereNotNull('company_id')->get();
       $user_data = [];     

       foreach($all_user as $key => $user){
            $email = $user['email'];
            try{    
                $address_number = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
                                ['query' => 
                                    ['connection' => $connection,
                                        'table' => [
                                            'table' => ['ADR6' => 'address_number'],
                                            'fields' => [
                                                'ADDRNUMBER' => 'address_number',
                                                'SMTP_ADDR' => 'email_address',
                                            ],
                                            'options' => [
                                                ['TEXT' => "SMTP_ADDR = '$email'"]
                                            ],
                                        ]
                                    ]
                                ],
                                ['timeout' => 600],
                                ['delay' => 10000]
                            );

                $address_number_datas = json_decode($address_number->getBody(), true);
                
                if($address_number_datas){
                    
                    $customer_data = [];
                    $customer_codes_data = [];
                    foreach($address_number_datas as $k => $address_number_data){
                        
                        $address_number = $address_number_data['address_number'];

                        if($address_number){
                            $customers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
                                ['query' => 
                                    ['connection' => $connection,
                                        'table' => [
                                            'table' => ['KNA1' => 'do_headers'],
                                            'fields' => [
                                                'KUNNR' => 'customer_code',
                                            ],
                                            'options' => [
                                                ['TEXT' => "ADRNR = '$address_number'"]
                                            ],
                                        ]
                                    ]
                                ],
                                ['timeout' => 600],
                                ['delay' => 10000]
                            );
                            $customers_data = json_decode($customers->getBody(), true);
                            if($customers_data){
                                foreach( $customers_data as $customer_code){
                                    array_push($customer_codes_data, $customer_code['customer_code']);
                                }
                            }
                        }
                    }
                    
                    $user_data[$key]['id'] = $user['id'];
                    $user_data[$key]['email'] = $email;
                    $compiled_customer_codes = $customer_codes_data ? implode(',',$customer_codes_data) : "";
                    $user_data[$key]['customer_code'] = $compiled_customer_codes;

                    if($compiled_customer_codes){
                        if($user['lfug_customer_code'] != $compiled_customer_codes){
                            $data = [];
                            $data['lfug_customer_code'] = $compiled_customer_codes;
                            $user->update($data);
                        }
                    }
                }
            }
            catch(ServerException $e){}
        }

        if($user_data){
            return 'Customer code exist';
        }else{
            return 'Customer not found';
        }
        
    }
}
