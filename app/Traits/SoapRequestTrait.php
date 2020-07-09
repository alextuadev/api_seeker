<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait SoapRequestTrait {


    /** ?soap_method=QueryByName&name=spiderman
     */

    public function makeSoapRequest($base_url, $qry_params)
    {

        $client     = new \SoapClient($base_url.$qry_params, array("trace" => 1, "exception" => 0));


    }



}
