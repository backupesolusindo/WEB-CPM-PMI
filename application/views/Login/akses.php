<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Pindah Shift</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url();?>desain/Login/css/style.css" rel='stylesheet' type='text/css' />
  <link href="<?php echo base_url();?>desain/Login/css/loader.css" rel='stylesheet' type='text/css' />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
  <!--webfonts-->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:600italic,400,300,600,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
  <!--//webfonts-->
</head>
<body>

       <!---start-main---->
      <div class="login-form animated fadeInDown delay-0.5s">

        <div class="loader animated flipInX" style="display:none;">
          <div class="sk-folding-cube">
            <div class="sk-cube1 sk-cube"></div>
            <div class="sk-cube2 sk-cube"></div>
            <div class="sk-cube4 sk-cube"></div>
            <div class="sk-cube3 sk-cube"></div>
        </div>
        </div>

        <div id="login-form">
          <div class="head">
            <img src="<?php echo base_url();?>desain/logoCaksis.jpg" alt=""/>
          </div>
        <form action="#" method="POST" id="FormLogin">
          <h5 ><span class="badge badge-success">Pilih Sesi</span></h5>

          <select class="form-control" name="sesi" id="sesi"  required>
            <!-- <option value="">PAGI</option> -->
            <?php foreach ($shift as $value): ?>
              <option value="<?php echo $value->nama_shift?>" <?php if (date("H:i:s",strtotime($value->jam_akhir)) < date("H:i:s") || date("H:i:s",strtotime($value->jam_mulai)) > date("H:i:s")): ?>
                disabled style="background-color:#d2d7d3"
              <?php endif; ?>><?php echo $value->nama_shift?></option>
            <?php endforeach; ?>
          </select>
          <div class="p-container">
                <!-- <label class="checkbox"><input type="checkbox" name="checkbox" checked><i></i>Remember Me</label> -->
                <input type="submit" value="Lanjutkan" >
              <div class="clear"> </div>
          </div>
        </form>
        </div>
    </div>
          <div class="copy-right">
          <p>copy-right <a href="#" onclick="coba()">E-Solusindo</a></p>
        </div>
        <?php
          $this->load->view("modal_large",array(
            'id'=>'kas',
            'judul' => 'Jumlah Kas',
            'icon' => 'fas fa-user-secret',
            'view' => 'Login/form_sesi',
            'edit' => 0

          ));
        ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>desain/dist/simple.money.format.js"></script>

<script type="text/javascript">
  $('.money').simpleMoneyFormat();
</script>
<script>
  $(document).ready(function(){
    $(document).on("submit","#FormLogin",function(e){
      // alert("kakaka")
      e.preventDefault(); // avoid to execute the actual submit of the form.

      var form = $(this);
      var url = '<?php echo base_url()."Login/cek_sesi"?>';

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
                  window.location.href = window.location.origin;
               }
             }
      });
    })
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
                  window.location.href = window.location.origin;
               }
             }
      });
    })
  })
</script>
</body>
</html>
