<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\SapUser;
use App\SapServer;
use App\SapDoNumber;
use App\CustomerDoNumber;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class DoNumberServedStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:do_number_status_server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Do Number Status Served';

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
        $lfug_server = $this->updateLFUGDONumberStatusServed();
        $pfmc_server = $this->updatePFMCDONumberStatusServed();
        $this->info( $lfug_server . ' LFUG DO Numbers | ' . $pfmc_server . ' PFMC DO Numbers');
    }

    public function updateLFUGDONumberStatusServed(){

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

        $sap_do_numbers = SapDoNumber::where('status','!=', 'C')->where('server','LFUG')->get();

        $x = 0;
        if($sap_do_numbers){

            foreach($sap_do_numbers as $key => $do_detail){

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
                $data['status'] = $sales_document ? $sales_document[0]['status'] : "";

                $validate_sap_do = SapDoNumber::where('do_number',$do_detail['do_number'])->first();

                if($validate_sap_do){
                    $validate_sap_do->update($data);
                    $x++;
                }
            }
        }

        return $x;
    }

    public function updatePFMCDONumberStatusServed(){

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

        $sap_do_numbers = SapDoNumber::where('status','!=', 'C')->where('server','PFMC')->get();

        $x = 0;
        if($sap_do_numbers){

            foreach($sap_do_numbers as $key => $do_detail){

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
                $data['status'] = $sales_document ? $sales_document[0]['status'] : "";

                $validate_sap_do = SapDoNumber::where('do_number',$do_detail['do_number'])->first();

                if($validate_sap_do){
                    $validate_sap_do->update($data);
                    $x++;
                }
            }
        }

        return $x;
    }
}
