<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Libur Nasional</h3>
        <div>
          <a href="<?php base_url(); ?>Libur/input" class="float-right">
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <label>Tahun :</label>
                <select id="Tahun" class="form-control select2 col-md-12" required onchange="search()">
                  <?php for ($i=2021; $i <= date("Y"); $i++) { ?>
                    <option value="<?php echo $i ?>" <?php if ($i == date("Y")): ?>selected<?php endif; ?>><?php echo $i ?></option>
                  <?php } ?>
                </select>
                <br>
                <div class="table-responsive hasilSearch">

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

function search() {
  var tahun  = $('#tahun').val();
  $.ajax({
    type: "POST",
    url: "<?php echo base_url();?>Libur/tabel",
    data: {tahun: tahun},
    success: function(data){
      $('.hasilSearch').html(data);
      // alert(data);  //as a debugging message.
    },
    error: function(e) {
      alert(e);
    },
  });

}
</script>
