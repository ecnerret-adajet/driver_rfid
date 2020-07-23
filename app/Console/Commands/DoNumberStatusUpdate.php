<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\SapUser;
use App\SapServer;
use App\SapDoNumber;
use App\CustomerDoNumber;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class DoNumberStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:do_number_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto update of DO Number';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lfug_server = $this->getLFUGDONumbers();
        $pfmc_server = $this->getPFMCDONumbers();
        $this->info( $lfug_server . ' LFUG DO Numbers | ' . $pfmc_server . ' PFMC DO Numbers');
    }

    public function getLFUGDONumbers(){

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
        $date = date('Ymd');
        $do_headers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
                ['query' => 
                    ['connection' => $connection,
                        'table' => [
                            'table' => ['/SPE/LIKPUK' => 'pickups'],
                            'fields' => [
                                'VBELN' => 'do_number',
                                'KUNNR' => 'customer_code'
                            ],
                            'options' => [
                                ['TEXT' => "(ERDAT = '$date' AND "],
                                ['TEXT' => " VBTYP = 'J') AND "],
                                ['TEXT' => "(VSART = 'L1' OR "],  
                                ['TEXT' => "VSART = 'L7' OR "],  
                                ['TEXT' => "VSART = 'LO' OR "],  
                                ['TEXT' => "VSART = 'M1' OR "],  
                                ['TEXT' => "VSART = 'M7' OR "],  
                                ['TEXT' => "VSART = 'MO' OR "],  
                                ['TEXT' => "VSART = 'P2' OR "],  
                                ['TEXT' => "VSART = 'P3' OR "],  
                                ['TEXT' => "VSART = 'P7' OR "],  
                                ['TEXT' => "VSART = 'PO' OR "],  
                                ['TEXT' => "VSART = 'Y1')"],  
                            ],
                        ]
                    ]
                ],
                ['timeout' => 60],
                ['delay' => 10000]
            );

        $do_details = json_decode($do_headers->getBody(), true);

        if($do_details){
            $x = 0;
            foreach($do_details as $key => $do_detail){


                $do_number = $do_detail['do_number'];
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

                $data = [];
                $data['customer_code'] = $do_detail['customer_code'];
                $data['do_number'] = $do_detail['do_number'];
                $data['sap_date'] = date('Ymd');
                $data['status'] = $sales_document ? $sales_document[0]['status'] : "";
                $data['server'] = "LFUG";

                $validate_sap_do = SapDoNumber::where('do_number',$do_detail['do_number'])->first();
                if(empty($validate_sap_do)){
                    SapDoNumber::create($data);
                    $x++;
                }else{
                    $validate_sap_do->update($data);
                }  
            }
        }

        return $x;
    }

    public function getPFMCDONumbers(){

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
        $date = date('Ymd');
        $do_headers = $client->request('GET', 'http://10.96.4.39:8012/api/read-table',
                ['query' => 
                    ['connection' => $connection,
                        'table' => [
                            'table' => ['/SPE/LIKPUK' => 'pickups'],
                            'fields' => [
                                'VBELN' => 'do_number',
                                'KUNNR' => 'customer_code'
                            ],
                            'options' => [
                                ['TEXT' => "(ERDAT = '$date' AND "],
                                ['TEXT' => " VBTYP = 'J') AND "],
                                ['TEXT' => "(VSART = 'L1' OR "],  
                                ['TEXT' => "VSART = 'L7' OR "],  
                                ['TEXT' => "VSART = 'LO' OR "],  
                                ['TEXT' => "VSART = 'M1' OR "],  
                                ['TEXT' => "VSART = 'M7' OR "],  
                                ['TEXT' => "VSART = 'MO' OR "],  
                                ['TEXT' => "VSART = 'P2' OR "],  
                                ['TEXT' => "VSART = 'P3' OR "],  
                                ['TEXT' => "VSART = 'P7' OR "],  
                                ['TEXT' => "VSART = 'PO' OR "],  
                                ['TEXT' => "VSART = 'Y1')"],  
                            ],
                        ]
                    ]
                ],
                ['timeout' => 60],
                ['delay' => 10000]
            );

        $do_details = json_decode($do_headers->getBody(), true);

        if($do_details){
            $x = 0;
            foreach($do_details as $key => $do_detail){

                //Check Status
                $do_number = $do_detail['do_number'];
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

                $data = [];
                $data['customer_code'] = $do_detail['customer_code'];
                $data['do_number'] = $do_detail['do_number'];
                $data['sap_date'] = date('Ymd');
                $data['status'] = $sales_document ? $sales_document[0]['status'] : "";
                $data['server'] = "PFMC";

                $validate_sap_do = SapDoNumber::where('do_number',$do_detail['do_number'])->first();
                if(empty($validate_sap_do)){
                    SapDoNumber::create($data);
                    $x++;
                }else{
                    $validate_sap_do->update($data);
                }  
            }
        }

        return $x;
    }
}
