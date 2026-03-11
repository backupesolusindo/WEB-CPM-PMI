<form action="#" method="POST" id="SimpanKas">

<div class="row form-group">
  <div class="col col-md-4">
      <label for="noBPJS" class=" form-control-label">Jumlah Kas</label>
  </div>
  <div class="col-8 col-md-8">
    <input type="hidden" name="sesi" value="" id="sesi_jaga">
    <input type="text" style="outline:1px solid #ced4da" name="jumlah" value="" class="money form-control" value="" id="jumlah">
  </div>
</div>

<div class="row form-group">
  <div class="col col-md-4">
      <!-- <label for="noBPJS" class=" form-control-label">Jumlah Bayar</label> -->
  </div>
  <div class="col-8 col-md-8">
    <button type="submit" class="btn btn-sm btn-success">Masukkan</button>
  </div>
</div>

<!-- <input type="hidden" id="nokun" value="" name="nokun">
<input type="hidden" id="no_rm" value="" name="no_rm"> -->
<?php echo form_close()?>
<script type="text/javascript" src="<?php echo base_url();?>desain/dist/simple.money.format.js"></script>

<script type="text/javascript">
  $('.money').simpleMoneyFormat();
</script>
<script>
$(document).on("submit","#SimpanKas",function(e){

  e.preventDefault(); // avoid to execute the actual submit of the form.

  var form = $(this);
  var url = '<?php echo base_url()."Login/simpan_kas"?>';

  $.ajax({
         type: "POST",
         url: url,
         data: form.serialize(), // serializes the form's elements.
         success: function(data)
         {
           // alert(data);
           if (data==0) {
              $("#sesi_jaga").val($("#sesi").val());
              $("#kas").modal("toggle");
           }else{
              window.location.href ='<?php echo base_url('Billing/transaksi') ?>';
           }
         }
  });
})

</script>
