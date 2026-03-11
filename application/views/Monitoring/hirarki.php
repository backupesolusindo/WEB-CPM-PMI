<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Pegawai Hirarki Monitoring</h3>
        <div>
          <!-- <a href="<?php base_url(); ?>Monitoring/input" class="float-right">
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
          </a> -->
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-5">
            <label>Unit :</label>
            <select id="unit" class="form-control select2 col-md-12" onchange="search()" readonly="true">
              <?php foreach ($unit as $value): ?>
                <option value="<?php echo $value->nama_unit; ?>"><?php echo $value->nama_unit ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <br>
            <button type="button" class="btn btn-info btn-md" onclick="search()"> <i class="fa fa-search"></i> Cari</button>
            <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>

          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" border="1">
                    <thead>
                      <tr>
                        <th>UNIT / SUB UNIT (Kepala Unit) </th>
                        <th>NIP</th>
                        <th>Nama Pegawai</th>
                        <th>Email</th>
                        <th>Struktur Jabatan</th>
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
<div class="printableArea" hidden>
  <table class="table" border="1">
    <thead>
      <tr>
        <th>UNIT / SUB UNIT (Kepala Unit) </th>
        <th>NIP</th>
        <th>Nama Pegawai</th>
        <th>Email</th>
        <th>Struktur Jabatan</th>
      </tr>
    </thead>
    <tbody class="hasilSearch">
    </tbody>
  </table>
</div>
<script src="<?php echo base_url() ?>/desain/dist/js/pages/jquery.PrintArea.js" type="text/JavaScript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    search();
  });


  function search() {
    var unit  = $('#unit').val();
    // alert(start+" - "+end);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Monitoring/tabel_hirarki",
      data: {unit:unit},
      success: function(data){
        $('.hasilSearch').html(data);
        // alert(data);  //as a debugging message.
      },
      error: function(e) {
        alert(e);
      },
    });
  }

  $("#print").click(function() {
    var mode = 'iframe'; //popup
    var close = mode == "popup";
    var options = {
      mode: mode,
      popClose: close
    };
    $("div.printableArea").printArea(options);
  });
</script>
