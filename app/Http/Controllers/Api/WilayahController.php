<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    // public function getPropinsi()
    // {
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "GET",
    //         CURLOPT_HTTPHEADER => array(
    //             "cache-control: no-cache"
    //         ),
    //     ));

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);

    //     curl_close($curl);

    //     if ($err) {
    //         return response()->json(["error" => "cURL Error #:" . $err], 500);
    //     } else {
    //         return response()->json(json_decode($response), 200);
    //     }
    // }

    public function getPropinsi()
    {
        $result = DB::table('smis_rg_prop')->get();
        return response()->json($result);
    }
}
