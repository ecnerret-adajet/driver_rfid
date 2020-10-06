<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SapUser;
use App\SapServer;
use App\SapDoNumber;
use App\CustomerDoNumber;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class DoCheckerApiController extends Controller
{
    public function checkPfmc(Request $request)
    {
        $do_number =  $request->do_number;
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
      
        $do_headers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
            ['query' => 
                ['connection' => $connection,
                    'table' => [
                        'table' => ['/SPE/LIKPUK' => 'pickups'],
                        'fields' => [
                            'VBELN' => 'do_number',
                            'KUNNR' => 'customer_code',
                            'VSART' => 'ship_type',
                            'ERDAT' => 'created_date',
                            'LFDAT' => 'delivery_date',
                        ],
                        'options' => [
                            ['TEXT' => "VBELN = '$do_number'"],
                        ],
                    ]
                ]
            ],
            ['timeout' => 60],
            ['delay' => 10000]
        );

        $do_details = json_decode($do_headers->getBody(), true);
        
        if($do_details){
            $status="";
          
            $pickup_shiptypes=[
                'L1','LO','M1','M7','MO','P2','P3','P7','PO','C1','C2','A2','P2','Y1'
            ];
            if(in_array($do_details[0]['ship_type'], $pickup_shiptypes) ){
                $status="For pickup";
            }else{
                $status="DO Number is not for Pickup";
            }
            

            //Check if DO exist
            $is_exist = "No";
            $validate_do = SapDoNumber::where('do_number',$do_number)->first();
            if($validate_do){
                $is_exist = "Yes";
            }

            return $data = [
                'do_details'=>$do_details[0],
                'status'=>$status, 
                'is_save'=>$is_exist, 
                'pickup_shiptypes'=>$pickup_shiptypes, 
            ];
        }else{
            return "not_exist";
        }
        
    }

    public function checkLfug(Request $request)
    {
        $do_number =  $request->do_number;
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
    
        $do_headers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
            ['query' => 
                ['connection' => $connection,
                    'table' => [
                        'table' => ['/SPE/LIKPUK' => 'pickups'],
                        'fields' => [
                            'VBELN' => 'do_number',
                            'KUNNR' => 'customer_code',
                            'VSART' => 'ship_type',
                            'ERDAT' => 'created_date',
                            'LFDAT' => 'delivery_date',
                        ],
                        'options' => [
                            ['TEXT' => "VBELN = '$do_number'"],
                        ],
                    ]
                ]
            ],
            ['timeout' => 60],
            ['delay' => 10000]
        );

        $do_details = json_decode($do_headers->getBody(), true);

        if($do_details){
            $status="";
          
            $pickup_shiptypes=[
                'L1','LO','M1','M7','MO','P2','P3','P7','PO','C1','C2','A2','P2','Y1'
            ];
            if(in_array($do_details[0]['ship_type'], $pickup_shiptypes) ){
                $status="For pickup";
            }else{
                $status="DO Number is not for Pickup";
            }
            
            //Check if DO exist
            $is_exist = "No";
            $validate_do = SapDoNumber::where('do_number',$do_number)->first();
            if($validate_do){
                $is_exist = "Yes";
            }

            return $data = [
                'do_details'=>$do_details[0],
                'status'=>$status, 
                'is_save'=>$is_exist, 
                'pickup_shiptypes'=>$pickup_shiptypes, 
            ];
        }else{
            return "not_exist";
        }

    }
}
