<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
            <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
                <h3 class="white-text mx-3">Recap Pekerjaan</h3>
                
            
                <button id="btn-refresh-all" class="btn btn-outline-white btn-sm">
                    <i class="fas fa-sync-alt"></i> Memperbarui Semua Total Poin
                </button>
            </div>
            <div class="card-body"> 
              
                <div id="server-message" class="alert d-none mb-3">
                    <strong>Server said:</strong> <span id="message-text"></span>
                </div>
                
                <div class="d-flex flex-wrap justify-content-between align-items-end p-2"style="position: sticky; top: 0; z-index: 1000;">
    <div class="col-md-4 me-3">
        <label>Nama Pegawai :</label>
        <select id="pegawai" class="form-control select2" required onchange="refreshTotalPoint()">
            <option value="">Semua Pegawai</option>
            <?php foreach ($pegawai as $value): ?>
                <option value="<?php echo $value->uuid ?>">
                    <?php echo $value->nama_pegawai ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4 me-3">
    <label>Filter Berdasarkan Tanggal Persetujuan :</label>
    <div class="input-daterange input-group" id="date-range">
        <input type="text" class="form-control" name="start" id="start" value="<?php echo date("01-m-Y") ?>" readonly/>
        <div class="input-group-append">
            <span class="input-group-text bg-info b-0 text-white">S/D</span>
        </div>
        <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly/>
    </div>
</div>
    <div class="col-md-2">
        <button id="btn-refresh-selected" type="button" class="btn btn-info btn-md">
            <i class="fa fa-search"></i> Cari
        </button>
    </div>
</div>
                
                <div class="mt-4">
                    <div class="loader__figure" hidden="true"></div>
                    <div id="result-container">
                        <?php $this->load->view('Pekerjaan/RekapPekerjaan/table_content', ['total_poin_pegawai' => $total_poin_pegawai]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
// Ini ada di file index.php
$(document).ready(function() {
  
  $('.select2').select2();
  
  // Initialize date range picker
  $('#date-range').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
    language: 'id'
  });
  
  // Perbaiki fungsi tombol Cari
  $('#btn-refresh-selected').click(function() {
  var pegawai_idpegawai = $('#pegawai').val();
  var start_date = $('#start').val();
  var end_date = $('#end').val();
  
  console.log("Start date from picker:", start_date);
  console.log("End date from picker:", end_date);
  
  searchData(pegawai_idpegawai);
});
  
  // Biarkan fungsi refresh all
  $('#btn-refresh-all').click(function() {
      refreshData('');
  });
});

// Tambahkan fungsi search baru
function searchData(pegawai_idpegawai) {
  $('.loader__figure').prop('hidden', false);
  
  // Get date range values
  var start_date = $('#start').val();
  var end_date = $('#end').val();
  
  $.ajax({
      url: '<?php echo base_url("Pekerjaan/SearchPegawai"); ?>',
      type: 'POST',
      data: { 
          pegawai_idpegawai: pegawai_idpegawai,
          start_date: start_date,
          end_date: end_date,
          limit: 10
      },
      dataType: 'json',
      success: function(response) {
          $('.loader__figure').prop('hidden', true);
          $('#result-container').html(response.html);
          
          if (response.status == 200) {
              // Optional: show success message if needed
              console.log("Search successful:", response.message);
          } else {
              Swal.fire({
                  title: 'Gagal',
                  text: response.message || 'Pencarian gagal',
                  icon: 'error',
                  confirmButtonText: 'OK'
              });
          }
      },
      error: function(xhr, status, error) {
          $('.loader__figure').prop('hidden', true);
          showServerMessage('Server error: ' + error, false);
          
          Swal.fire({
              title: 'Error',
              text: 'Terjadi kesalahan saat mencari data: ' + error,
              icon: 'error',
              confirmButtonText: 'OK'
          });
      }
  });
}


function showServerMessage(message, isSuccess) {
    var serverMsg = $('#server-message');
    $('#message-text').text(message);
    
    serverMsg.removeClass('d-none alert-success alert-danger');
    
    if (isSuccess) {
        serverMsg.addClass('alert-success');
    } else {
        serverMsg.addClass('alert-danger');
    }
    

    setTimeout(function() {
        serverMsg.fadeOut('slow', function() {
            $(this).addClass('d-none').css('display', '');
        });
    }, 5000);
}


function refreshData(pegawai_idpegawai) {
    $('.loader__figure').prop('hidden', false);
    
    // Get date range values
    var start_date = $('#start').val();
    var end_date = $('#end').val();
    
    $.ajax({
        url: '<?php echo base_url("Pekerjaan/RefreshTotalPoin"); ?>',
        type: 'POST',
        data: { 
            pegawai_idpegawai: pegawai_idpegawai,
            start_date: start_date,
            end_date: end_date,
            limit: 10
        },
        dataType: 'json',
        success: function(response) {
            $('.loader__figure').prop('hidden', true);
            $('#result-container').html(response.html);
            showServerMessage('Server said: ' + response.message, response.status == 200);

            if (response.status == 200) {
                Swal.fire({
                    title: 'Berhasil',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Gagal',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            $('.loader__figure').prop('hidden', true);
            showServerMessage('Server error: ' + error, false);
            
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat memperbarui data: ' + error,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}
// Modifikasi di fungsi refreshTotalPoint untuk menambahkan parameter limit
function refreshTotalPoint() {
    var pegawai_idpegawai = $('#pegawai').val();
    var start_date = $('#start').val();
    var end_date = $('#end').val();
    
    if (pegawai_idpegawai) {
        $.ajax({
            url: '<?php echo base_url("Pekerjaan/RekapPekerjaan"); ?>',
            type: 'POST',
            data: { 
                pegawai_idpegawai: pegawai_idpegawai,
                start_date: start_date,
                end_date: end_date,
                limit: 10
            },
            success: function(response) {
                $('.hasilSearch').html(response);
            }
        });
    } else {
        $('.hasilSearch').html('');
    }
}
</script>