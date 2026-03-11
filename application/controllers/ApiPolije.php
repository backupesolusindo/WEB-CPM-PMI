<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiPolije extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function test()
  {
    echo "string";
  }

  function urlApi($url, $param=null)
  {
    $credentials = $this->getAccess();
    $curl = curl_init("http://api.polije.ac.id/resources/".$url."?".http_build_query($param));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$credentials['accessToken'], "User-Agent: ".strtolower($_SERVER['HTTP_USER_AGENT'])));

    $json_response = curl_exec($curl);
    curl_close($curl);

    echo $json_response;
  }

  function tes_api_mahasiswa()
    {
        $credentials = $this->getAccess();
        $param = array(
          "limit" => 1000,
          "offset" => 1800,
          // "nim" => "E41160352",
          "angkatan" => "2018",
        );
        $curl = curl_init("http://api.polije.ac.id/resources/akademik/mahasiswa"."?".http_build_query($param));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$credentials['accessToken'], "User-Agent: ".strtolower($_SERVER['HTTP_USER_AGENT'])));

        $json_response = curl_exec($curl);
        curl_close($curl);
        echo "<pre>";
        print_r(json_decode($json_response));
        echo "</pre>";
        // echo $json_response;
    }

  function tes_api_jurusan()
    {
        $credentials = $this->getAccess();
        $param = array(
          "limit" => 0,
          "offset" => 100,
          "jenis" => "PROGRAM STUDI",
          // "parent" => "JURUSAN TEKNOLOGI INFORMASI",
          "informations" => false,
        );
        $curl = curl_init("http://api.polije.ac.id/resources/global/unit"."?".http_build_query($param));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$credentials['accessToken'], "User-Agent: ".strtolower($_SERVER['HTTP_USER_AGENT'])));

        $json_response = curl_exec($curl);
        curl_close($curl);

        echo $json_response;
    }

  function tes_api_pegawai()
    {
        $credentials = $this->getAccess();
        $param = array(
          // "limit"             => 0,
          // "offset"            => 1,
          // "nip"               => "195909181989031003",
          // "unit"              => "MANAJEMEN AGRIBISNIS",
          // "badge"             => "1147",
          // "jab_struktur"      => "Teknisi / PLP",
          // "jab_struktur"      => "Administrasi",
          // "email"             => "andi_m_ismail@polije.ac.id",
          // 'jenis_unit' => "JURUSAN",
          "informations"      => false,
          "with_informations" => false,
        );
        $curl = curl_init("http://api.polije.ac.id/resources/kepegawaian/pegawai"."?".http_build_query($param));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$credentials['accessToken'], "User-Agent: ".strtolower($_SERVER['HTTP_USER_AGENT'])));

        $json_response = curl_exec($curl);
        curl_close($curl);
        // echo "<pre>";
        // print_r(json_decode($json_response));
        // echo "</pre>";
        echo $json_response;
    }

  function tes_api_unit()
    {
        $credentials = $this->getAccess();
        // $curl = curl_init("http://api.polije.ac.id/resources/global/unit?limit=0&offset=1000&jenis=ALL&level=ALL&parent=ALL&informations=false");
        $param = array(
          // "limit" => 0,
          // "offset" => 100,
          // "jenis" => "LABORATORIUM",
          // "level" => 2,
          // "uuid" => "c9eb9bef-00f3-11eb-ab7b-fefcfe8d8c7c",
          // "parent" => "PUSAT PENGEMBANGAN PEMBELAJARAN DAN PENJAMINAN MUTU",
          // "informations" => false,
        );
        $curl = curl_init("http://api.polije.ac.id/resources/global/unit"."?".http_build_query($param));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$credentials['accessToken'], "User-Agent: ".strtolower($_SERVER['HTTP_USER_AGENT'])));

        $json_response = curl_exec($curl);
        curl_close($curl);
        // echo "<pre>";
        // print_r(json_decode($json_response));
        // echo "</pre>";
        $data = array(
          'size' => sizeof(json_decode($json_response)),
          'res'  => json_decode($json_response)
        );
        echo json_encode($data);
    }

 function getAccess()
  {
    $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_URL            => "http://api.polije.ac.id/oauth/token",
                CURLOPT_POST           => TRUE,
                CURLOPT_POSTFIELDS     => http_build_query(
                    array(
                        'grant_type'    => "client_credentials",
                        'client_id'     => "25",
                        'client_secret' => "W1FkBgcjAXOJngt8KzHnLuI2CUJFEtwA93bSdBsN",
                    )
                )
            )
        );

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        $access_token = (isset($response->access_token) && $response->access_token != "") ? $response->access_token : die("Error - access token missing from response!");
        // $instance_url = (isset($response->instance_url) && $response->instance_url != "") ? $response->instance_url : die("Error - instance URL missing from response!");

        return array(
            "accessToken" => $access_token,
            // "instanceUrl" => $instance_url
        );
  }

}
