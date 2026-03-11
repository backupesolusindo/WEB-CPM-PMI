
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header text-center">
        <h4>Detail Rekapitulasi Presensi Pegawai</h4>
      </div>
      <div class="card-body row">
        <div class="col-6">
          <table width="100%" border="0">
            <tr>
              <td>NIP</td>
              <td>: <?php echo $pegawai['NIP'] ?></td>
            </tr>
            <tr>
              <td>Email SSO</td>
              <td>: <?php echo $pegawai['email'] ?></td>
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
              <td>Unit</td>
              <td>: <?php echo $pegawai['unit'] ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-9">
          <br>
          <label>Menurut Tanggal :</label>
          <div class="input-daterange input-group" id="date-range">
                                                  <input type="text" class="form-control" name="start" id="start" value="<?php echo '01'.date("-m-Y")?>" readonly/>
                                                  <div class="input-group-append">
                                                      <span class="input-group-text bg-info b-0 text-white">S/D</span>
                                                  </div>
                                                  <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly/>
                                              </div>
                                              <input type="hidden" id="uuid" value="<?php echo $pegawai['uuid'] ?>">
                                              <br>
                                              <br>
        </div>
        <div class="col-md-3">
          <br>
          <br>
          <button type="button" class="btn btn-info btn-md" onclick="search()"> <i class="fa fa-search"></i> Cari</button>
        </div>
        <div class="col-12 hasilSearch">

        </div>

      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url() ?>/desain/dist/js/pages/jquery.PrintArea.js" type="text/JavaScript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  search();
});
function search() {
  var start  = $('#start').val();
  var end  = $('#end').val();
  var uuid  = $('#uuid').val();
  // alert(uuid);
  $.ajax({
         type: "POST",
         url: "<?php echo base_url();?>Laporan/tabelDetailRekap",
         data: {start: start, end:end, uuid:uuid},
         success: function(data){
                    $('.hasilSearch').html(data);
                    // alert(data);  //as a debugging message.
              },
        error: function(e) {
              alert(e);
              },
        });

}



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
