<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
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

            $sales_document_headers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
                ['query' => 
                    ['connection' => $connection,
                        'table' => [
                            'table' => ['VBUK' => 'sales_document_headers'],
                            'fields' => [
                                'WBSTK' => 'status',
                            ],
                            'options' => [
                                ['TEXT' => "VBELN = '$do_number'"]
                            ],
                        ]
                    ]
                ],
                ['timeout' => 60],
                ['delay' => 10000]
            );

            $sales_document = json_decode($sales_document_headers->getBody(), true); 

            $status="";
          
            $pickup_shiptypes=[
                'L1','LO','M1','M7','MO','P2','P3','P7','PO','C1','C2','A2','P2','Y1','PB'
            ];
            if(in_array($do_details[0]['ship_type'], $pickup_shiptypes) ){
                $status="For pickup";
            }else{
                $status="DO Number is not for Pickup";
            }

            $customer = User::select('name','email','pfmc_customer_code')->where('pfmc_customer_code','like','%'.$do_details[0]['customer_code'].'%')->get();
            
            //Check if DO exist
            $is_exist = "No";
            $validate_do = SapDoNumber::where('do_number',$do_number)->where('server','PFMC')->first();
            if($validate_do){
                $is_exist = "Yes";
            }

            return $data = [
                'do_details'=>$do_details[0],
                'sales_document'=>$sales_document[0],
                'status'=>$status, 
                'is_save'=>$is_exist, 
                'customer'=>$customer, 
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

            $sales_document_headers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
                ['query' => 
                    ['connection' => $connection,
                        'table' => [
                            'table' => ['VBUK' => 'sales_document_headers'],
                            'fields' => [
                                'WBSTK' => 'status',
                            ],
                            'options' => [
                                ['TEXT' => "VBELN = '$do_number'"]
                            ],
                        ]
                    ]
                ],
                ['timeout' => 60],
                ['delay' => 10000]
            );

            $sales_document = json_decode($sales_document_headers->getBody(), true); 

            $status="";
          
            $pickup_shiptypes=[
                'L1','LO','M1','M7','MO','P2','P3','P7','PO','C1','C2','A2','P2','Y1','PB'
            ];
            if(in_array($do_details[0]['ship_type'], $pickup_shiptypes) ){
                $status="For pickup";
            }else{
                $status="DO Number is not for Pickup";
            }
            
            $customer = User::select('name','email','lfug_customer_code')->where('lfug_customer_code','like','%'.$do_details[0]['customer_code'].'%')->get();

            //Check if DO exist
            $is_exist = "No";
            $validate_do = SapDoNumber::where('do_number',$do_number)->where('server','LFUG')->first();
            if($validate_do){
                $is_exist = "Yes";
            }

            return $data = [
                'do_details'=>$do_details[0],
                'sales_document'=>$sales_document[0],
                'status'=>$status, 
                'is_save'=>$is_exist, 
                'customer'=>$customer, 
            ];
        }else{
            return "not_exist";
        }

    }


    public function checkDOLfug(Request $request)
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
                            'WBSTK' => 'sales_document',
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

            // $sales_document_headers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
            //     ['query' => 
            //         ['connection' => $connection,
            //             'table' => [
            //                 'table' => ['VBUK' => 'sales_document_headers'],
            //                 'fields' => [
            //                     'WBSTK' => 'status',
            //                 ],
            //                 'options' => [
            //                     ['TEXT' => "VBELN = '$do_number'"]
            //                 ],
            //             ]
            //         ]
            //     ],
            //     ['timeout' => 60],
            //     ['delay' => 10000]
            // );

            // $sales_document = json_decode($sales_document_headers->getBody(), true); 

            $status="";
          
            $pickup_shiptypes=[
                'L1','LO','M1','M7','MO','P2','P3','P7','PO','C1','C2','A2','P2','Y1','PB'
            ];
            if(in_array($do_details[0]['ship_type'], $pickup_shiptypes) ){
                $status="For pickup";
            }else{
                $status="DO Number is not for Pickup";
            }
            
            $customer = User::select('name','email','lfug_customer_code')->where('lfug_customer_code','like','%'.$do_details[0]['customer_code'].'%')->get();

            //Check if DO exist
            $is_exist = "No";
            $validate_do = SapDoNumber::where('do_number',$do_number)->where('server','LFUG')->first();
            if($validate_do){
                $is_exist = "Yes";
            }

            return $data = [
                'do_details'=>$do_details[0],
                'sales_document'=>$do_details[0]['sales_document'],
                'status'=>$status, 
                'is_save'=>$is_exist, 
                'customer'=>$customer, 
            ];
        }else{
            return "not_exist";
        }

    }
}
