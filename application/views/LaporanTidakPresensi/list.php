
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header text-center">
        <h4>Laporan Tidak Presensi</h4>
      </div>
      <div class="card-body row">
        <div class="col-md-4">
          <label>Menurut Tanggal :</label>
          <input type="text" class="form-control inputnone mydatepicker" name="tanggal" id="tanggal" value="<?php echo date("d-m-Y") ?>">
        </div>
        <div class="col-md-2">
          <br>
          <button type="button" class="btn btn-info btn-md" onclick="search()"> <i class="fa fa-search"></i> Cari</button>
        </div>
        <div class="col-12">
          <div class="table-responsive hasilSearch">

          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url() ?>/desain/dist/js/pages/jquery.PrintArea.js" type="text/JavaScript"></script>
<script type="text/javascript">
$(document).ready(function(){
  search();
});


function search() {
  var tanggal  = $('#tanggal').val();
  // var unit  = $('#unit').val();
  $.ajax({
    type: "POST",
    url: "<?php echo base_url();?>LaporanTidakPresensi/tabel",
    data: {tanggal: tanggal},
    success: function(data){
      $('.hasilSearch').html(data);
      $('#table-print').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel'],
      });
      // alert(data);  //as a debugging message.
    },
    error: function(e) {
      alert(e);
    },
  });

}
</script>
