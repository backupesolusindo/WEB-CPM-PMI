<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core {


  public function NamaAPP()
  {
    return "E-PRESENSI PMI";
  }

  public function VersionAndroidAPP()
  {
    return 1;
  }

  public function VersionMonitoringAPP()
  {
    return "1.1.1";
  }

  public function Rupiah($data)
  {
    return "Rp. ".number_format($data,0,".",".");
  }


  function encrypt_url($string) {
    $output = str_replace("/","---",$string);
    return $output;
  }
  function decrypt_url($string) {
    $output = str_replace("---","/",$string);
    return $output;
  }

  function formatDurasiLengkap($detik)
  {
    $jam = floor($detik / 3600);
    $menit = floor(($detik % 3600) / 60);
    $detik = $detik % 60;

    $result = [];

    if ($jam > 0) {
      $result[] = $jam . ' jam';
    }

    if ($menit > 0) {
      $result[] = $menit . ' menit';
    }

    if ($detik > 0 && count($result) < 2) { // hanya tampilkan detik jika durasi pendek
      $result[] = $detik . ' detik';
    }

    return implode(' ', $result);
  }


  function getAccessApi()
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

  public function curlNotif($token, $title, $body)
  {
    $curl = curl_init();
    $key = "AAAA1tAbue0:APA91bHuCAkaPPIgDfaNjJI9XKRmGwf0fqRDsfz1XFdmwoSQwPUI1k2SBfFXJCiUV5QZ0sW2-a2_7DMe8k2tmHibUe78HexTZYrU8CjU8t16zF2d7kBx6w3yyLDYg-6GkaCENmkktgWn";
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n
      \"registration_ids\":[\"$token\"],\n
      \"notification\": {\n
        \"title\":\"$title\",\n
        \"body\":\"$body\"\n
      }\n}",
      CURLOPT_HTTPHEADER => array(
      "authorization: key=".$key,
      "cache-control: no-cache",
      "content-type: application/json",
      "postman-token: 144e811b-851a-94db-1751-2373bab60f0f"
      ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        return "cURL Error #:" . $err;
      } else {
        return $response;
      }
    }

    public function NotifSuccess($isi)
    {
      return "<script type=\"text/javascript\">
        $(document).ready(function(){
          $.toast({
            heading: 'Berhasil',
            text: '".$isi."',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'success',
            hideAfter: 5000,
            stack: 6
          });
        });
      </script>
      ";
    }

    public function NotifError($isi)
    {
      return "<script type=\"text/javascript\">
        $(document).ready(function(){
          $.toast({
            heading: 'ERROR',
            text: '".$isi."',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'error',
            hideAfter: 5000,
            stack: 6
          });
        });
      </script>
      ";
    }

    public function NotifInfo($judul, $isi)
    {
      return "<script type=\"text/javascript\">
        $(document).ready(function(){
          $.toast({
            heading: '".$judul."',
            text: '".$isi."',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'info',
            hideAfter: 2000,
            stack: 6
          });
        });
      </script>
      ";
    }


    function Fungsi_JS_Hapus()
    {
      return "<script type=\"text/javascript\">
        $('document').ready(function() {
          $(\"#alert\").show();
          $(\"#modal\").hide();

          $(\".id_checkbox\").on('click', function (e) {
            $(\"#jumlah_pilih\").html($(\"input.id_checkbox:checked\").length);
            if ($(\"input.id_checkbox:checked\").length == 0) {
              $(\"#alert\").show();
              $(\"#modal\").hide();
            } else {
              $(\"#alert\").hide();
              $(\"#modal\").show();
            }
          });
        });
      </script>";
    }

    public function Hapus_aktif()
    {
      return "<button type=\"button\" class=\"btn btn-hapus btn-circle btn-lg btn-danger\" data-toggle=\"modal\" data-target=\"#Hapus_aktif\">
        <i class=\"fa fa-trash\"></i>
      </button>
      <div class=\"modal fade\" id=\"Hapus_aktif\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallmodalLabel\" aria-hidden=\"true\">
        <div class=\"modal-dialog modal-sm\" role=\"document\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h5 class=\"modal-title\" id=\"smallmodalLabel\">Hapus Data</h5>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>
                Apakah Anda Yakin Untuk Menghapus Data
              </p>
            </div>
            <div class=\"modal-footer\">
              <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Cancel</button>
              <button type=\"submit\" class=\"btn btn-danger\">Hapus</button>
            </div>
          </div>
        </div>
      </div>";
    }

    public function Hapus_disable()
    {
      return "<button type=\"button\" class=\"btn btn-hapus btn-circle btn-lg btn-secondary\" data-toggle=\"modal\" data-target=\"#Hapus_disable\">
        <i class=\"fa fa-trash\"></i>
      </button>
      <div class=\"modal fade\" id=\"Hapus_disable\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallmodalLabel\" aria-hidden=\"true\">
        <div class=\"modal-dialog modal-sm\" role=\"document\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <h5 class=\"modal-title\" id=\"smallmodalLabel\">Hapus Data</h5>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>
            <div class=\"modal-body\">
              <p>
                Pilih Data Terlebih Dahulu !!!!
              </p>
            </div>
            <div class=\"modal-footer\">
              <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Cancel</button>
            </div>
          </div>
        </div>
      </div>";
    }

    public function alert_succcess($isi)
    {
      return "<div class=\"sufee-alert alert with-close alert-success alert-dismissible \">
        <span class=\"badge badge-pill badge-success\">Success</span> $isi
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
          <span aria-hidden=\"true\">&times;</span>
        </button>";
      }

      public function alert_danger($isi)
      {
        return "<div class=\"sufee-alert alert with-close alert-danger alert-dismissible \">
          <span class=\"badge badge-pill badge-danger\">Peringatan</span> $isi
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
            <span aria-hidden=\"true\">&times;</span>
          </button>";
        }

        public function modal($name, $judul, $isi)
        {
          return "<button type=\"button\" class=\"btn btn-secondary mb-1\" data-toggle=\"modal\" data-target=\"#$name\">
            $name
          </button>
          <div class=\"modal fade\" id=\"$name\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"smallmodalLabel\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-sm\" role=\"document\">
              <div class=\"modal-content\">
                <div class=\"modal-header\">
                  <h5 class=\"modal-title\" id=\"smallmodalLabel\">$judul</h5>
                  <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                    <span aria-hidden=\"true\">&times;</span>
                  </button>
                </div>
                <div class=\"modal-body\">
                  <p>
                    $isi
                  </p>
                </div>
                <div class=\"modal-footer\">
                  <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Cancel</button>
                  <button type=\"button\" class=\"btn btn-primary\">Confirm</button>
                </div>
              </div>
            </div>
          </div>";
        }


      }
