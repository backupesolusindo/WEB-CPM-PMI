
let idmaster_harga = [];
let nama_master = [];

$(document).ready(function(){
  $(".master_harga").each(function(){
    idmaster_harga.push($(this).attr("id"));
    nama_master.push($(this).attr("nama"));
  })
  console.log(idmaster_harga);
  $(document).on("change","#varian",function(){
      let varian = $(this).val();
      if (varian==0) {
        $("#tambah_varian").attr("hidden","hidden");
        $("#b_pro").removeAttr("hidden");
        $("#list_varian").children("button").remove();
      }else{
        $("#tambah_varian").removeAttr("hidden");
        $("#b_pro").attr("hidden","hidden");
      }

      $("#daftar_master_harga").children("tr").remove();
  })
  $(document).on("click","#tambah_varian",function(){
    $("#modal_varian").modal("toggle");
  })
  function prefix() {
      var text = "";
      var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      for (var i = 0; i < 5; i++)
      text += possible.charAt(Math.floor(Math.random() * possible.length));
      return text;
  }

  $(document).on("click","#tambahkan_varian",function(){
    let nama_varian = $("#input_nama_varian").val();
    let harga_produksi = $("#input_harga_varian").val();
    if (nama_varian=="") {
      $("#input_nama_varian").focus();
    }else{
      var pr = prefix();
      let html = '<button kode="'+pr+'" type="button" harga="'+harga_produksi+'" nama="'+nama_varian+'" class="item_varian btn btn-outline-primary btn-sm">'+nama_varian+' Rp.'+harga_produksi+'<i kode="'+pr+'" class="pull-right fa fa-trash text-red" ></i></button>';
      html += '<input type="hidden" name="kode[]" value="'+pr+'" class="'+pr+'">';
      html += '<input type="hidden" name="varian[]" value="'+nama_varian+'" class="'+pr+'">';
      html += '<input type="hidden" name="harga_produksi_varian[]" value="'+harga_produksi+'" class="'+pr+'">';
      $("#list_varian").append(html);
      $("#input_nama_varian").val("");
      $("#input_harga_varian").val("");
      $("#modal_varian").modal("toggle");
      if ($("#daftar_master_harga").length>0) {
        let html = "";
        let harga = 0;
        for (var i = 0; i < idmaster_harga.length; i++) {
          html += '<tr style="background-color:#6c7a89;"><td colspan="3" style="color:white;">'+nama_master[i]+'</td></tr>';
          $(".item_varian").each(function(){
            let kode = $(this).attr("kode");
            harga = $(this).attr("harga");
            html += '<tr class="'+kode+'">'+
              '<td>'+$(this).attr("nama")+'</td>'+
              '<td><input readonly class="form-control new-form produksi angkasaja uang" type="text" value="'+harga+'" name="harga_produksi_'+idmaster_harga[i]+'_'+kode+'"></td>'+
              '<td><input required class="form-control new-form angkasaja uang master'+idmaster_harga[i]+'" type="text" name="harga_jual_'+idmaster_harga[i]+'_'+kode+'"></td>'+
            '</tr>';
          })
        }
        $("#daftar_master_harga").html(html);
      }
    }
  })

  $(document).on("change","#harga",function(){
    let harga = $(this).val();
    $(".produksi").val(harga);
  })

  $(document).on("click",".item_varian",function(){
    let kode = $(this).attr("kode");
    $(this).remove();
    $("."+kode).remove();
    if ($(".item_varian").length==0) {
      console.log($("#daftar_master_harga").children(".new-form").length);
      $("#daftar_master_harga").children("tr").remove();
    }
  })

  $(document).on("click","#atur_harga",function(){
    let status_varian = $("#varian").val();
    if (status_varian==1) {
      //ada varian
      let varian = $(".item_varian").length;
      console.log(varian);
      if (varian==0) {
        alert("isi varian terlebih dahulu");
      }else{
        let html = "";
        let harga = $("#harga").val();
        if (harga=="") {
          harga=0;
        }
        for (var i = 0; i < idmaster_harga.length; i++) {
          html += '<tr style="background-color:#6c7a89;"><td colspan="3" style="color:white;">'+nama_master[i]+'</td></tr>';
          $(".item_varian").each(function(){
            let kode = $(this).attr("kode");
            harga = $(this).attr("harga");
            html += '<tr class="'+kode+'">'+
              '<td>'+$(this).attr("nama")+'</td>'+
              '<td><input readonly class="form-control new-form produksi angkasaja uang" type="text" value="'+harga+'" name="harga_produksi_'+idmaster_harga[i]+'_'+kode+'"></td>'+
              '<td><input required class="form-control new-form angkasaja uang master'+idmaster_harga[i]+'" type="text" name="harga_jual_'+idmaster_harga[i]+'_'+kode+'"></td>'+
            '</tr>';
          })
        }
        $("#daftar_master_harga").html(html);
      }
    }else{
      //tidak ada varian
      let html = "";
      let harga = $("#harga").val();
      if (harga=="") {
        harga=0;
      }
      for (var i = 0; i < idmaster_harga.length; i++) {
          html += '<tr>'+
            '<td>'+nama_master[i]+'</td>'+
            '<td><input readonly class="form-control new-form produksi angkasaja uang" type="text" value="'+harga+'" name="harga_produksi[]"></td>'+
            '<td><input class="form-control new-form angkasaja uang master'+idmaster_harga[i]+'" type="text" name="harga_jual_'+idmaster_harga[i]+'"></td>'+
          '</tr>';
        // })
      }
      $("#daftar_master_harga").html(html);
    }

            // console.log($("#daftar_master_harga").children(".new-form").length);
    $('.uang').mask('000.000.000.000', {
      reverse: true
    });

    $(".angkasaja").keypress(function(data) {
      if (data.which != 8 && data.which != 46 && data.which != 0 && (data.which < 48 || data.which > 57)) {
        // alert(data.which);
        return false;
      }
    });

  })


  function addCommas(nStr){
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
  }
  function removeComas(nilai){
    var hasil   = nilai.split(',');
    var shasil = "";
    for (var i = 0; i < hasil.length; i++) {
        shasil = shasil +""+ hasil[i];
    }
    return shasil;

  }

  $(document).on("click","#atur_semua",function(){
    $("#modal_atur_harga").modal("toggle");

  })
  $(document).on("click","#simpan_harga",function(){
    $(".master_harga").each(function(){
      let id = $(this).attr("id");
      let harga = $(this).val();
      if (harga=="") {
        harga = 0;
      }
      $(".master"+id).val(harga);
    })
    $("#modal_atur_harga").modal("toggle");
  })


})
