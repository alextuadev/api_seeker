<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait RestRequestTrait {

    public function makeRequest($base_url, $qry_params)
    {
        $client = new Client();
        $response = $client->get("$base_url?$qry_params");
        $data_body = [];

        if ($response->getStatusCode() == 200) {
            $data_body = json_decode($response->getBody(), true);
        }
        $code = $response->getStatusCode();
        return ["data" => $data_body, 'code' => $code];
    }



}
