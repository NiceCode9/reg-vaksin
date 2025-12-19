<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $result = DB::table('smis_rg_propinsi')->select('nama', DB::raw('MIN(id) AS id'))->groupBy('nama')->orderBy('nama', 'ASC')->get();
        return response()->json($result);
    }

    public function getKabupaten($prop_id)
    {
        $result = DB::table('smis_rg_kabupaten')->select('id', 'nama')->where('no_prop', $prop_id)->get();
        return response()->json($result);
    }

    public function getKecamatan($kab_id)
    {
        $result = DB::table('smis_rg_kec')->select('id', 'nama')->where('no_kab', $kab_id)->get();
        return response()->json($result);
    }

    public function getKelurahan($kec_id)
    {
        $result = DB::table('smis_rg_kelurahan')->select('id', 'nama')->where('no_kec', $kec_id)->get();
        return response()->json($result);
    }
}
