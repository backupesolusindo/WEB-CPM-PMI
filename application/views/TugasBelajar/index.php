<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Tugas Belajar</h3>
        <div>
          <a href="<?php base_url(); ?>TugasBelajar/input" class="float-right">
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="loader__figure" hidden="true"></div>
          </div>
          <div class="col-12">
            <h4>PENCARIAN DATA :</h4>
          </div>
          <div class="col-md-5">
            <label>Tahun :</label>
            <select id="tahun" class="form-control select2 col-md-12" required onchange="sub_unit()">
              <?php for ($i=2021; $i >= date("Y"); $i--) { ?>
                <option value="<?= $i ?>">Tahun <?= $i ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-5">
            <label>Status Tugas Belajar :</label>
            <select id="status" class="form-control select2 col-md-12" required onchange="sub_unit()">
              <option value="">Semua Status</option>
              <option value="1">Aktif Tugas Belajar</option>
              <option value="2">Selesai Tugas Belajar</option>
            </select>
          </div>
          <div class="col-md-2">
            <br>
            <button type="button" class="btn btn-info btn-md btn-block" onclick="search()"> <i class="fa fa-search"></i> Cari</button>
          </div>
          <div class="col-12">
            <br>
            <br>
          </div>
          <div class="table-responsive hasilSearch">

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

function search() {
  var tahun = $('#tahun').val();
  var status = $('#status').val();
  $.ajax({
    type: "POST",
    url: "<?php echo base_url();?>TugasBelajar/get_tabel",
    data: {tahun: tahun, status:status},
    beforeSend: function(){
      $('.loader__figure').attr("hidden",false);
      $('.hasilSearch').attr("hidden",true);
    },
    success: function(data){
      $('.loader__figure').attr("hidden",true);
      $('.hasilSearch').attr("hidden",false);
      $('.hasilSearch').html(data);
      $('#table-print').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel'],
      })
      // alert(data);  //as a debugging message.
    },
    error: function(e) {
      $('.loader__figure').attr("hidden",true);
      alert(e);
    },
  });

}

</script>
