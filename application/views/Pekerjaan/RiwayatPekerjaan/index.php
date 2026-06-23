<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
            <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
                <h3 class="white-text mx-3">Riwayat Pekerjaan</h3>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-end p-2">
                    <div class="col-md-2 mb-2">
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
                    <div class="col-md-3 mb-2">
                        <label>Pegawai :</label>
                        <select id="pegawai" class="form-control select2">
                            <option value="">Semua Pegawai</option>
                            <?php foreach ($pegawai as $value): ?>
                                <option value="<?php echo htmlspecialchars($value->nama_pegawai ?? $value->nama ?? '') ?>">
                                    <?php echo htmlspecialchars($value->nama_pegawai ?? $value->nama ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 me-3 mb-2">
                        <label>Menurut Tanggal :</label>
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control" name="start" id="start" value="<?php echo date("01-m-Y") ?>" readonly />
                            <div class="input-group-append">
                                <span class="input-group-text bg-info b-0 text-white">S/D</span>
                            </div>
                            <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly />
                        </div>
                    </div>
                    <div class="me-3 mb-2 d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-info btn-md" onclick="search()">
                            <i class="fa fa-search"></i> Cari
                        </button>
                        <button type="button" class="btn btn-success btn-md" id="btn-approve-selected" onclick="approveSelected()" disabled>
                            <i class="fa fa-check"></i> Setujui <span id="selected-count" class="badge bg-white text-success ms-1" style="display:none;">0</span>
                        </button>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="loader__figure" hidden="true"></div>
                </div>

                <table id="searchTable" class="table color-table table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="3%">
                                <!-- Checkbox pilih semua (hanya baris complete yang terlihat) -->
                                <input type="checkbox" id="check-all" title="Pilih semua">
                            </th>
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
                            // DEBUG SEMENTARA - hapus setelah fix
                            $debug_statuses = array_unique(array_column($listpekerjaan, 'status'));
                            echo '<div class="alert alert-warning"><strong>DEBUG status:</strong> ' . implode(', ', array_map(function ($s) {
                                return '"' . htmlspecialchars($s) . '"';
                            }, $debug_statuses)) . '</div>';
                            foreach ($listpekerjaan as $row):
                                $bulan = [
                                    '01' => 'Januari',
                                    '02' => 'Februari',
                                    '03' => 'Maret',
                                    '04' => 'April',
                                    '05' => 'Mei',
                                    '06' => 'Juni',
                                    '07' => 'Juli',
                                    '08' => 'Agustus',
                                    '09' => 'September',
                                    '10' => 'Oktober',
                                    '11' => 'November',
                                    '12' => 'Desember'
                                ];
                                $tanggal = date('d-m-Y', strtotime($row['created_at']));
                                $parts = explode('-', $tanggal);
                                $tanggal_formatted = $parts[0] . ' ' . $bulan[$parts[1]] . ' ' . $parts[2];
                        ?>
                                <tr id="row-<?php echo $row['id_riwayatpekerjaan']; ?>">
                                    <td>
                                        <?php if (strtolower($row['status']) == 'complete'): ?>
                                            <input type="checkbox" class="row-check" value="<?php echo $row['id_riwayatpekerjaan']; ?>">
                                        <?php endif; ?>
                                    </td>
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
                                            <?php if (strtolower($row['status']) == 'complete'): ?>
                                                <button class="btn btn-success btn-sm approve-btn" data-id="<?php echo $row['id_riwayatpekerjaan']; ?>">
                                                    <i class="fa fa-check"></i> Terima
                                                </button>
                                                <button class="btn btn-danger btn-sm reject-btn" data-id="<?php echo $row['id_riwayatpekerjaan']; ?>">
                                                    <i class="fa fa-times"></i> Tolak
                                                </button>
                                            <?php elseif (strtolower($row['status']) == 'approve'): ?>
                                                <span class="badge bg-success">Disetujui</span>
                                            <?php elseif (strtolower($row['status']) == 'reject'): ?>
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
                                <td colspan="10" class="text-center">Data tidak ditemukan</td>
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
        // Datepicker
        $('#date-range').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'id'
        });

        // Handle approve button click (per baris)
        $(document).on('click', '.approve-btn', function() {
            var id = $(this).data('id');
            updateStatus(id, 'approve');
        });

        // Handle reject button click (per baris)
        $(document).on('click', '.reject-btn', function() {
            var id = $(this).data('id');
            updateStatus(id, 'reject');
        });

        // Checkbox "pilih semua" — hanya centang baris complete yang terlihat
        $(document).on('change', '#check-all', function() {
            var checked = $(this).is(':checked');
            $('#searchTable tbody tr:visible .row-check').prop('checked', checked);
            updateSelectedCount();
        });

        // Tiap checkbox baris diubah
        $(document).on('change', '.row-check', function() {
            // Sinkron state check-all
            var total = $('#searchTable tbody tr:visible .row-check').length;
            var totalChecked = $('#searchTable tbody tr:visible .row-check:checked').length;
            $('#check-all').prop('indeterminate', totalChecked > 0 && totalChecked < total);
            $('#check-all').prop('checked', total > 0 && totalChecked === total);
            updateSelectedCount();
        });

        // Reset checkbox saat filter jabatan berubah
        $('#jabatan').on('change', function() {
            resetCheckboxes();
        });
    });

    // Update jumlah yang dipilih dan state tombol Setujui
    function updateSelectedCount() {
        var count = $('#searchTable tbody tr:visible .row-check:checked').length;
        if (count > 0) {
            $('#btn-approve-selected').prop('disabled', false);
            $('#selected-count').text(count).show();
        } else {
            $('#btn-approve-selected').prop('disabled', true);
            $('#selected-count').hide();
        }
    }

    function resetCheckboxes() {
        $('.row-check, #check-all').prop('checked', false).prop('indeterminate', false);
        updateSelectedCount();
    }

    function updateStatus(id, status, callback) {
        $('.status-container-' + id).hide();
        $('.loading-indicator-' + id).show();

        $.ajax({
            url: '<?php echo base_url("Pekerjaan/update_status"); ?>',
            type: 'POST',
            data: {
                id_riwayatpekerjaan: id,
                status: status
            },
            dataType: 'json',
            success: function(response) {
                $('.loading-indicator-' + id).hide();

                if (response.status == 'success') {
                    var newStatusHtml = '';
                    if (status == 'approve') {
                        newStatusHtml = '<span class="badge bg-success">Disetujui</span>';
                    } else if (status == 'reject') {
                        newStatusHtml = '<span class="badge bg-danger">Ditolak</span>';
                    }
                    $('.status-container-' + id).html(newStatusHtml).show();
                    // Hapus checkbox karena sudah tidak perlu
                    $('#row-' + id + ' .row-check').remove();
                    if (!callback) alert(response.message);
                } else {
                    if (!callback) alert('Error: ' + response.message);
                    $('.status-container-' + id).show();
                }

                if (callback) callback(response.status == 'success');
            },
            error: function() {
                $('.loading-indicator-' + id).hide();
                $('.status-container-' + id).show();
                if (!callback) alert('Terjadi kesalahan pada server');
                if (callback) callback(false);
            }
        });
    }

    // Setujui data yang dicentang
    function approveSelected() {
        var selectedIds = [];
        $('#searchTable tbody tr:visible .row-check:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert('Tidak ada data yang dipilih.');
            return;
        }

        if (!confirm('Setujui ' + selectedIds.length + ' data yang dipilih?')) {
            return;
        }

        // Nonaktifkan kontrol selama proses
        $('#btn-approve-selected').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        $('#check-all').prop('disabled', true);

        var processed = 0;
        var failed = 0;

        $.each(selectedIds, function(index, id) {
            updateStatus(id, 'approve', function(success) {
                if (!success) failed++;
                processed++;

                if (processed === selectedIds.length) {
                    // Selesai semua
                    $('#check-all').prop('disabled', false).prop('checked', false).prop('indeterminate', false);
                    $('#btn-approve-selected').html('<i class="fa fa-check"></i> Setujui <span id="selected-count" class="badge bg-white text-success ms-1" style="display:none;">0</span>');
                    updateSelectedCount();

                    var msg = (selectedIds.length - failed) + ' data berhasil disetujui.';
                    if (failed > 0) msg += ' ' + failed + ' data gagal.';
                    alert(msg);
                }
            });
        });
    }

    // Filter tabel
    function search() {
        var jabatan = $('#jabatan').val();
        var pegawai = $('#pegawai').val();
        var start = $('#start').val();
        var end = $('#end').val();

        var startDate = new Date(start.split('-').reverse().join('-'));
        var endDate = new Date(end.split('-').reverse().join('-'));

        $('.loader__figure').show();
        $('#no-data-row').remove();
        resetCheckboxes();

        $('#searchTable tbody tr').each(function() {
            var row = $(this);
            var showRow = true;

            var rowJabatan = row.find('td:eq(3)').text().trim();
            var rowPegawai = row.find('td:eq(5)').text().trim();
            var dateText = row.find('td:eq(2)').text().trim();
            var dateParts = parseIndonesianDate(dateText);
            var rowDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

            if (jabatan && jabatan !== '') {
                if (rowJabatan !== jabatan) showRow = false;
            }

            if (pegawai && pegawai !== '') {
                if (rowPegawai !== pegawai) showRow = false;
            }

            if (!isNaN(startDate.getTime()) && !isNaN(endDate.getTime())) {
                if (rowDate < startDate || rowDate > endDate) showRow = false;
            }

            showRow ? row.show() : row.hide();
        });

        $('.loader__figure').hide();

        if ($('#searchTable tbody tr:visible').length === 0) {
            $('#searchTable tbody').append('<tr id="no-data-row"><td colspan="10" class="text-center">Data tidak ditemukan</td></tr>');
        }
    }

    function parseIndonesianDate(dateString) {
        var months = {
            'Januari': 1,
            'Februari': 2,
            'Maret': 3,
            'April': 4,
            'Mei': 5,
            'Juni': 6,
            'Juli': 7,
            'Agustus': 8,
            'September': 9,
            'Oktober': 10,
            'November': 11,
            'Desember': 12
        };
        var parts = dateString.split(' ');
        return [parseInt(parts[0], 10), months[parts[1]], parseInt(parts[2], 10)];
    }

    function checkAutoApproveTasks() {
        $.ajax({
            url: '<?php echo base_url("Pekerjaan/check_auto_approve_tasks"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.auto_approved_tasks && response.auto_approved_tasks.length > 0) {
                    $.each(response.auto_approved_tasks, function(index, taskId) {
                        $('.status-container-' + taskId).html('<span class="badge bg-success">Disetujui</span>').show();
                        $('#row-' + taskId + ' .row-check').remove();
                    });
                    updateSelectedCount();
                }
            }
        });
    }
</script>