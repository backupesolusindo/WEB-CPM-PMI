<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Jadwal Kegiatan Yang Terlaksana</h3>
        <div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-5">
            <label>Unit :</label>
            <select id="unit" class="form-control select2 col-md-12" onchange="sub_unit()" readonly="true">
              <option value="">Semua Unit</option>
              <?php foreach ($unit as $value): ?>
                <option value="<?php echo $value->nama_unit; ?>"
                  <?php if ($_SESSION['unit'] == $value->nama_unit): ?>selected<?php endif; ?>
                  ><?php echo $value->nama_unit ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-5">
            <label>Sub Unit :</label>
            <select id="sub_unit" class="form-control select2 col-md-12" required onchange="search()">
              <option value="">Semua Sub Unit</option>
            </select>
          </div>
          <div class="col-md-2">
            <br>
            <button type="button" class="btn btn-info btn-md" onclick="search()"> <i class="fa fa-search"></i> Cari</button>
          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="Table-Kegiatan" class="table color-table table-hover table-striped ">
                    <thead>
                      <tr>
                        <th width="10%">#</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Unit</th>
                        <th>PIC</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody class="hasilSearch">

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    search();
  });

  function sub_unit() {
    var unit = $("#unit").val();
    // alert(unit);
    $.ajax({
        type  : 'POST',
        url   : '<?php echo base_url() ?>Laporan/sub_unit',
        data  : {unit:unit},
        success : function(response){
          // alert(response);
          $("#sub_unit").html(response);
        }
    });
  }

  function search() {
    var unit  = $('#unit').val();
    var sub_unit  = $('#sub_unit').val();
    // alert(start+" - "+end);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Dashboard/data_kegiatan",
      data: {unit:unit, sub_unit:sub_unit},
      success: function(data){
        $('.hasilSearch').html(data);
        $('#Table-Kegiatan').DataTable();
        // alert(data);  //as a debugging message.
      },
      error: function(e) {
        alert(e);
      },
    });

  }
</script>
