<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
            <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
                <h3 class="white-text mx-3">Riwayat Pekerjaan</h3>
            </div>
            <div class="card-body"> 
                <div class="d-flex flex-wrap justify-content-between align-items-end p-2">
                    <div class="col-md-3 me-3">
                        <label>Jabatan :</label>
                        <select id="jabatan" class="form-control select2" required>
    <option value="">Semua Jabatan</option>
    <?php foreach ($jabatan as $value): ?>
        <option value="<?php echo $value->namajabatan ?>">
            <?php echo $value->namajabatan ?>
        </option>
    <?php endforeach; ?>
</select>
                    </div>
                    <div class="col-md-4 me-3">
                        <label>Menurut Tanggal :</label>
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control" name="start" id="start" value="<?php echo date("01-m-Y") ?>" readonly/>
                            <div class="input-group-append">
                                <span class="input-group-text bg-info b-0 text-white">S/D</span>
                            </div>
                            <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly/>
                        </div>
                    </div>
                    <div class="me-3">
                        <button type="button" class="btn btn-info btn-md" onclick="search()">
                            <i class="fa fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="mt-4">
    <div class="loader__figure" hidden="true"></div>
</div>

                
                <table id="searchTable" class="table color-table table-hover table-striped">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th>Tanggal</th>
            <th>Jabatan</th>
            <th>Nama Pekerjaan</th>
            <th>Nama Pegawai</th>
            <th>Jumlah</th>
            <th>Point</th>
            <th>Total Point</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; 
         if (!empty($listpekerjaan)) {
            foreach ($listpekerjaan as $row): 
                // Format tanggal dengan bulan sebagai teks
                $bulan = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', 
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', 
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                $tanggal = date('d-m-Y', strtotime($row['created_at']));
                $parts = explode('-', $tanggal);
                $tanggal_formatted = $parts[0] . ' ' . $bulan[$parts[1]] . ' ' . $parts[2];
            ?>
            <tr id="row-<?php echo $row['id_riwayatpekerjaan']; ?>">
                <td><?php echo $no; ?></td>
                <td><?php echo $tanggal_formatted; ?></td>
                <td><?php echo $row['jabatan_idjabatan']; ?></td>
                <td><?php echo $row['nama_pekerjaan']; ?></td>
                <td><?php echo $row['nama_pegawai']; ?></td>
                <td><?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                <td><?php echo number_format($row['point'], 0, ',', '.'); ?></td>
                <td><?php echo number_format($row['total_point'], 0, ',', '.'); ?></td>
                <td>
                    <div class="status-container-<?php echo $row['id_riwayatpekerjaan']; ?>">
                        <?php if($row['status'] == 'complete'): ?>
                            <button class="btn btn-success btn-sm approve-btn" data-id="<?php echo $row['id_riwayatpekerjaan']; ?>">
                                <i class="fa fa-check"></i> Terima
                            </button>
                            <button class="btn btn-danger btn-sm reject-btn" data-id="<?php echo $row['id_riwayatpekerjaan']; ?>">
                                <i class="fa fa-times"></i> Tolak
                            </button>
                        <?php elseif($row['status'] == 'approve'): ?>
                            <span class="badge bg-success">Disetujui</span>
                        <?php elseif($row['status'] == 'reject'): ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php endif; ?>
                    </div>
                    <div class="loading-indicator-<?php echo $row['id_riwayatpekerjaan']; ?>" style="display:none;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </td>
            </tr>
        <?php   
            $no++;
            endforeach;
        } else { ?>
            <tr>
                <td colspan="9" class="text-center">Data tidak ditemukan</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Handle approve button click
    $(document).on('click', '.approve-btn', function() {
        var id = $(this).data('id');
        updateStatus(id, 'approve');
    });
    $('#date-range').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'id'
    });
    
    // Handle reject button click
    $(document).on('click', '.reject-btn', function() {
        var id = $(this).data('id');
        updateStatus(id, 'reject');
    });
    
    function updateStatus(id, status) {
        // Show loading spinner for this specific row
        $('.status-container-' + id).hide();
        $('.loading-indicator-' + id).show();
        
        $.ajax({
            url: '<?php echo base_url("Pekerjaan/update_status"); ?>',
            type: 'POST',
            data: { id_riwayatpekerjaan: id, status: status },
            dataType: 'json',
            success: function(response) {
                // Hide loading spinner
                $('.loading-indicator-' + id).hide();
                
                if(response.status == 'success') {
                    // Update the status display without reloading the page
                    var newStatusHtml = '';
                    if(status == 'approve') {
                        newStatusHtml = '<span class="badge bg-success">Disetujui</span>';
                    } else if(status == 'reject') {
                        newStatusHtml = '<span class="badge bg-danger">Ditolak</span>';
                    }
                    
                    $('.status-container-' + id).html(newStatusHtml).show();
                    
                    // Show success message
                    alert(response.message);
                } else {
                    // Show error and restore the original buttons
                    alert('Error: ' + response.message);
                    $('.status-container-' + id).show();
                }
            },
            error: function() {
                // Hide loading spinner and show error
                $('.loading-indicator-' + id).hide();
                $('.status-container-' + id).show();
                alert('Terjadi kesalahan pada server');
            }
        });
    }
});



// Fungsi untuk mencari dan memfilter tabel
function search() {
    var jabatan = $('#jabatan').val();
    var start = $('#start').val();
    var end = $('#end').val();
    
    // Parsing tanggal untuk perbandingan
    var startDate = new Date(start.split('-').reverse().join('-'));
    var endDate = new Date(end.split('-').reverse().join('-'));
    
    // Tampilkan indikator loading
    $('.loader__figure').show();
    
    // Filter baris tabel
    $('#searchTable tbody tr').each(function() {
        var row = $(this);
        var showRow = true;
        
        // Ambil nilai jabatan dari baris (kolom ke-3)
        var rowJabatan = row.find('td:eq(2)').text();
        
        // Ambil tanggal dari baris (kolom ke-2)
        var dateText = row.find('td:eq(1)').text();
        var dateParts = parseIndonesianDate(dateText);
        var rowDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
        
        // Filter berdasarkan jabatan jika dipilih
        if (jabatan && jabatan !== '') {
            if (rowJabatan.trim() !== jabatan) {
                showRow = false;
            }
        }
        
        // Filter berdasarkan rentang tanggal
        if (!isNaN(startDate.getTime()) && !isNaN(endDate.getTime())) {
            if (rowDate < startDate || rowDate > endDate) {
                showRow = false;
            }
        }
        
        // Tampilkan atau sembunyikan baris
        if (showRow) {
            row.show();
        } else {
            row.hide();
        }
    });
    
    // Sembunyikan indikator loading
    $('.loader__figure').hide();
    
    // Tampilkan pesan "tidak ada data" jika semua baris disembunyikan
    var visibleRows = $('#searchTable tbody tr:visible').length;
    if (visibleRows === 0) {
        if ($('#no-data-row').length === 0) {
            $('#searchTable tbody').append('<tr id="no-data-row"><td colspan="9" class="text-center">Data tidak ditemukan</td></tr>');
        } else {
            $('#no-data-row').show();
        }
    } else {
        $('#no-data-row').hide();
    }
}

// Fungsi pembantu untuk mengurai format tanggal Indonesia (DD Bulan YYYY)
function parseIndonesianDate(dateString) {
    var months = {
        'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4, 
        'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8, 
        'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
    };
    
    var parts = dateString.split(' ');
    var day = parseInt(parts[0], 10);
    var month = months[parts[1]];
    var year = parseInt(parts[2], 10);
    
    return [day, month, year];
}


function checkAutoApproveTasks() {
        $.ajax({
            url: '<?php echo base_url("Pekerjaan/check_auto_approve_tasks"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.auto_approved_tasks && response.auto_approved_tasks.length > 0) {
                    // Update UI for auto-approved tasks
                    $.each(response.auto_approved_tasks, function(index, taskId) {
                        var newStatusHtml = '<span class="badge bg-success">Disetujui</span>';
                        $('.status-container-' + taskId).html(newStatusHtml).show();
                    });
                }
            }
        });
    }
    
</script>




