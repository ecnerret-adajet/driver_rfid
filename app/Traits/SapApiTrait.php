<?php

namespace App\Traits;

trait SapApiTrait
{
    public function readSapTableApi($connection, $table)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', env('SAP_API_URL') . '/read-table', ['query' => ['connection' => $connection, 'table' => $table]]);
        return collect(json_decode($response->getBody()));
    }

    public function executeSapFunction($connection, $function, $parameters, $return)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', env('SAP_API_URL') . '/execute-fm', [
            'form_params' => [
                'connection' => $connection,
                'function' => $function,
                'parameters' => $parameters,
                'return' => $return,
            ]
        ]);
        return collect(json_decode($response->getBody()));
    }

}
