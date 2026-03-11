
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFqXa0x5_ScgmTPrrJwNU4QoseYLsBUj0&sensor=false"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  <?php if ($this->uri->segment(2) == "edit"): ?>
    getMaps(<?php echo @$gedung["latitude"] ?>, <?php echo @$gedung["longtitude"] ?>);
    <?php else: ?>
    getkampus();
  <?php endif; ?>
});
function getkampus() {
  var idkampus = $("#kampus").val();
  $.ajax({
    type: 'POST',
    url: '<?php echo base_url();?>Kampus/get_lokasi/'+idkampus,
    data: {},
    dataType: "json",
    error: function(e) {
         alert(e);
    },
    success: function(response) {
      // alert(response.latitude);
      getMaps(response.latitude, response.longtitude);
    }
  });
}

function getMaps(lat,lng) {
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

    map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

    marker = new google.maps.Marker({
      map: map,
      position: myLatlng,
      draggable: true
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

<div class="row form-group">
        <div class="col-md-3">
          <label>Kampus :</label>
        </div>
        <div class="col-12 col-md-9">
              <select name="kampus_idkampus" id="kampus" class="form-control select2 col-md-12" required onchange="getkampus()">
                <?php foreach ($kampus as $value): ?>
                  <option value="<?php echo $value->idkampus; ?>" <?php if ($value->idkampus == @$kampus['kampus_idkampus']): ?>
                    <?php echo 'selected'; ?>
                  <?php endif; ?>><?php echo $value->nama_kampus ?></option>
                <?php endforeach; ?>
              </select>
        </div>
</div>
<div class="col-md-12">
  <br>
  <label>Lokasi Gedung :</label>
  <div id="myMap" style="width:100%; height:400px"></div>
  <br>
</div>
<div class="row">
  <div class="col-md-12">
      <div class="form-group">
          <label>Nama Gedung :</label>
          <input type="text" name="nama_gedung" class="form-control" value="<?php echo @$gedung["nama_gedung"] ?>" required>
      </div>
  </div>
  <div class="col-md-6">
      <div class="form-group">
          <label>Latitude :</label>
          <input type="text" name="latitude" id="latitude" class="form-control" value="<?php echo @$gedung["latitude"] ?>" required>
      </div>
  </div>
  <div class="col-md-6">
      <div class="form-group">
          <label>Longtitude :</label>
          <input type="text" name="longtitude" id="longitude" class="form-control" value="<?php echo @$gedung["longtitude"] ?>" required>
      </div>
  </div>
</div>
<div class="form-actions" >
    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
    <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
</div>
