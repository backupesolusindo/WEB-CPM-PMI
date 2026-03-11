
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
  getMaps("masuk",<?php echo $absensi['latitude'] ?>, <?php echo $absensi['longitude'] ?>);
  <?php if ($absensi_pulang != null): ?>
    getMaps("pulang",<?php echo $absensi_pulang['latitude'] ?>, <?php echo $absensi_pulang['longitude'] ?>);
  <?php endif; ?>
  <?php if ($istirahat != null): ?>
  getMaps("istirahat",<?php echo $istirahat['latitude'] ?>, <?php echo $istirahat['longitude'] ?>);
  <?php endif; ?>
  <?php if ($selesaiIstirahat != null): ?>
  getMaps("selesai",<?php echo $selesaiIstirahat['latitude'] ?>, <?php echo $selesaiIstirahat['longitude'] ?>);
  <?php endif; ?>

});

function getMaps(id,lat,lng) {
  var map;
  var marker;
  var myLatlng = new google.maps.LatLng(lat,lng);
  var geocoder = new google.maps.Geocoder();
  var infowindow = new google.maps.InfoWindow();
  var mapOptions = {
      zoom: 18,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById(id), mapOptions);

    marker = new google.maps.Marker({
      map: map,
      position: myLatlng,
      draggable: false
    });

    geocoder.geocode({'latLng': myLatlng }, function(results, status) {
      geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        $("#latitude").val(marker.getPosition().lat());
        $("#longitude").val(marker.getPosition().lng());
      });
    });

    google.maps.event.addListener(marker, 'dragend', function() {
      geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        $("#latitude").val(marker.getPosition().lat());
        $("#longitude").val(marker.getPosition().lng());
      });
    });
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Detail Presensi</h3>
        <div>

        </div>
      </div>
      <div class="card-body row">
        <div class="col-6">
          <table width="100%" border="0">
            <tr>
              <td>NIP</td>
              <td>: <?php echo $pegawai['NIP'] ?></td>
            </tr>
            <tr>
              <td>Nama Pegawai</td>
              <td>: <?php echo $pegawai['nama_pegawai'] ?></td>
            </tr>
          </table>
        </div>
        <div class="col-6">
          <table width="100%" border="0">
            <tr>
              <td>Email SSO</td>
              <td>: <?php echo $pegawai['email'] ?></td>
            </tr>
            <tr>
              <td>Unit</td>
              <td>: <?php echo $pegawai['unit'] ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="card-body row">
        <div class="col-3">
          <table width="100%" border="0">
            <tr>
              <h2>Presensi Masuk : </h2>
            </tr>
            <tr>
              <td>Waktu</td>
              <td>: <?php echo date("H:i:s", strtotime($absensi['waktu'])) ?></td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>: <?php echo date("d-m-Y", strtotime($absensi['waktu'])) ?></td>
            </tr>
          </table>
        </div>
        <div class="col-3" >
          <label>Foto Presensi Masuk :</label>
          <img src="<?php echo base_url().$absensi['foto'] ?>" style="height:250px">
        </div>
        <div class="col-6">
          <label>Lokasi Presensi Masuk :</label>
          <div id="masuk" style="width:100%; height:250px"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="card-body row">
        <?php if ($absensi_pulang == null): ?>
            <h2>Presensi Pulang : Belum Melakukan Presensi</h2>
          <?php else: ?>
            <div class="col-3">
              <table width="100%" border="0">
                <tr>
                  <h2>Presensi Pulang : </h2>
                </tr>
                <tr>
                  <td>Waktu</td>
                  <td>: <?php echo date("H:i:s", strtotime($absensi_pulang['waktu'])) ?></td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td>: <?php echo date("d-m-Y", strtotime($absensi_pulang['waktu'])) ?></td>
                </tr>
              </table>
            </div>
            <div class="col-3" >
              <label>Foto Presensi Pulang :</label>
              <img src="<?php echo base_url().$absensi_pulang['foto'] ?>" style="height:250px">
            </div>
            <div class="col-6">
              <label>Lokasi Presensi Pulang :</label>
              <div id="pulang" style="width:100%; height:250px"></div>
            </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="card-body row">
        <?php if ($istirahat == null): ?>
            <h2>Presensi Istirahat : Belum Melakukan Presensi</h2>
          <?php else: ?>
            <div class="col-3">
              <table width="100%" border="0">
                <tr>
                  <h2>Presensi Istirahat : </h2>
                </tr>
                <tr>
                  <td>Waktu</td>
                  <td>: <?php echo date("H:i:s", strtotime($istirahat['waktu'])) ?></td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td>: <?php echo date("d-m-Y", strtotime($istirahat['waktu'])) ?></td>
                </tr>
              </table>
            </div>
            <div class="col-3" >
              <label>Foto Presensi Istirahat :</label>
              <img src="<?php echo base_url().$istirahat['foto'] ?>" style="height:250px">
            </div>
            <div class="col-6">
              <label>Lokasi Presensi Istirahat :</label>
              <div id="istirahat" style="width:100%; height:250px"></div>
            </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="card-body row">
        <?php if ($selesaiIstirahat == null): ?>
            <h2>Presensi Istirahat Masuk : Belum Melakukan Presensi</h2>
          <?php else: ?>
            <div class="col-3">
              <table width="100%" border="0">
                <tr>
                  <h2>Presensi Istirahat Masuk : </h2>
                </tr>
                <tr>
                  <td>Waktu</td>
                  <td>: <?php echo date("H:i:s", strtotime($selesaiIstirahat['waktu'])) ?></td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td>: <?php echo date("d-m-Y", strtotime($selesaiIstirahat['waktu'])) ?></td>
                </tr>
              </table>
            </div>
            <div class="col-3" >
              <label>Foto Presensi Istirahat Masuk :</label>
              <img src="<?php echo base_url().$selesaiIstirahat['foto'] ?>" style="height:250px">
            </div>
            <div class="col-6">
              <label>Lokasi Presensi Istirahat Masuk :</label>
              <div id="selesai" style="width:100%; height:250px"></div>
            </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
