
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
  getMaps(-7.70846958469117, 113.99706862505585);
  get_listGedung();
  $(".tanpaSpasi").keypress(function(data) {
    if (data.which == 32 || data.which == 222 || data.key == "(" || data.key == ")") {
      // alert(data.which);
      return false;
    }
  });
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
      get_listGedung();
      getMaps(response.latitude, response.longtitude);
    }
  });
}

function get_listGedung() {
  var idkampus = $("#kampus").val();
  $.ajax({
    type: 'POST',
    url: '<?php echo base_url();?>Gedung/get_listgedung/'+idkampus,
    data: {},
    dataType: "json",
    error: function(e) {
         // alert(e);
    },
    success: function(response) {
      var html = "<option value='' readonly>Pilih Gedung</option>";
      $.each(response, function(i) {
            html += '<option value="'+response[i].idgedung+'">'+response[i].nama_gedung+'</option>';
        });
        // alert(html);
        $("#gedung").html(html);
        // $('#gedung').select2();
    }
  });
}

function getGedung() {
  var idgedung = $("#gedung").val();
  $.ajax({
    type: 'POST',
    url: '<?php echo base_url();?>Gedung/get_gedung/'+idgedung,
    data: {},
    dataType: "json",
    error: function(e) {
         alert(e);
    },
    success: function(response) {
      if (response.latitude != null || response.longtitude != null) {
        getMaps(response.latitude, response.longtitude);
      }
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
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Input Kegiatan</h3>
        <div>

        </div>
      </div>
      <div class="card-body">
        <?php echo form_open_multipart('Kegiatan/insert');?>
        <div class="row">
          <div class="col-6">
            <label>Lokasi Kantor :</label>
            <select name="kampus" id="kampus" class="form-control select2 col-md-12" required onchange="getkampus()">
              <?php foreach ($kampus as $value): ?>
                <option value="<?php echo $value->idkampus; ?>" <?php if ($value->idkampus == @$kegiatan['idkampus']): ?>
                  <?php echo 'selected'; ?>
                <?php endif; ?>><?php echo $value->nama_kampus ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6">
            <label>Lokasi Gedung / Ruangan :</label>
            <select name="gedung" id="gedung" class="form-control select2 col-md-12" required onchange="getGedung()">

            </select>
          </div>
          <div class="col-md-12">
            <br>
            <label>Lokasi Kegiatan :</label>
            <div id="myMap" style="width:100%; height:400px"></div>
            <br>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Latitude :</label>
              <input type="text" name="latitude" id="latitude" class="form-control" value="<?php echo @$kegiatan["latitude"] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Longitude :</label>
              <input type="text" name="longtitude" id="longitude" class="form-control" value="<?php echo @$kegiatan["longitude"] ?>" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Radius :</label>
              <input type="text" name="radius" class="form-control" value="50" readonly>
            </div>
          </div>
          <div class="col-md-6">

          </div>
          <div class="col-md-6">
            <hr>
            <div class="form-group">
              <label>Kode Kegiatan :</label>
              <input type="text" name="idkegiatan" id="idkegiatan" onchange="cek()" class="form-control tanpaSpasi" value="<?php echo @$kegiatan["idkegiatan"] ?>" required>
              <small class="text-danger form-control-feedback" id="msgID"> Kode Sudah Ada / Kode Tidak Terisi. </small>
            </div>
          </div>
          <div class="col-md-6">
            <hr>
            <div class="form-group">
              <label>Nama Kegiatan :</label>
              <input type="text" name="nama_kegiatan" class="form-control" value="<?php echo @$kegiatan["nama_kegiatan"] ?>" required>
            </div>
          </div>
          <div class="col-md-12">
            <label>Tanggal Kegiatan:</label>
            <div class="input-daterange input-group" id="date-range">
              <input type="text" class="form-control inputnone" name="tanggal" id="tanggal" value="<?php echo date("d-m-Y") ?>"/>
              <div class="input-group-append">
                <span class="input-group-text bg-info b-0 text-white">S/D</span>
              </div>
              <input type="text" class="form-control inputnone" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo date("d-m-Y") ?>"/>
            </div>
            <br>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Jam Mulai :</label>
              <input type="text" name="jam_mulai" class="form-control waktu-input inputnone" value="<?php echo @$kegiatan["jam_mulai"] ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Jam Selesai :</label>
              <input type="text" name="jam_selesai" class="form-control waktu-input inputnone" value="<?php echo @$kegiatan["jam_selesai"] ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Unit :</label>
              <select name="unit" class="form-control select2 col-md-12" required>
                <?php foreach ($unit as $value): ?>
                  <option value="<?php echo $value->idunit; ?>" <?php if ($value->idunit == @$kegiatan['unit_idunit']): ?>
                    <?php echo 'selected'; ?>
                  <?php endif; ?>><?php echo $value->nama_unit ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Penanggung Jawab (PIC) :</label>
              <select name="pic" class="form-control select2 col-md-12" required>
                <?php foreach ($pegawai as $value): ?>
                  <option value="<?php echo $value->uuid; ?>" <?php if ($value->uuid == @$kegiatan['uuid_pic']): ?>
                    <?php echo 'selected'; ?>
                  <?php endif; ?>><?php echo $value->nama_pegawai ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-actions" >
            <button type="submit" class="btn btn-success" id="simpan" disabled> <i class="fa fa-check"></i> Simpan</button>
            <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
          </div>
        </div>

        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    cek();
  });

  function cek() {
    var id  = $('#idkegiatan').val();
    // alert(start+" - "+end);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Kegiatan/cekKodeKegiatan",
      data: {id:id},
      success: function(data){
        // alert(data);  //as a debugging message.
        if (data == 1) {
          $('#simpan').attr('disabled', false);
          $('#msgID').attr('hidden', true);
        }else {
          $('#simpan').attr('disabled', true);
          $('#msgID').attr('hidden', false);
        }
      },
      error: function(e) {
        alert(e);
      },
    });

  }
</script>
