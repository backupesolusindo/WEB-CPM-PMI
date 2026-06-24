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
                        <button type="button" class="btn btn-info btn-md" onclick="loadTable()">
                            <i class="fa fa-search"></i> Cari
                        </button>
                        <button type="button" class="btn btn-success btn-md" id="btn-approve-selected" onclick="approveSelected()" disabled>
                            <i class="fa fa-check"></i> Setujui <span id="selected-count" class="badge ms-1" style="display:none;">0</span>
                        </button>
                    </div>
                </div>

                <table id="searchTable" class="table color-table table-hover table-striped" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">
                                <input type="checkbox" class="check" id="check-all" data-checkbox="icheckbox_flat-red">
                                <label for="check-all">&nbsp;#</label>
                            </th>
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
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    var table;

    $(document).ready(function() {
        // Datepicker
        $('#date-range').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'id'
        });

        // Init DataTable AJAX
        table = $('#searchTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?php echo base_url("Pekerjaan/get_riwayat_ajax"); ?>',
                type: 'GET',
                data: function(d) {
                    d.jabatan = $('#jabatan').val();
                    d.pegawai = $('#pegawai').val();
                    d.start = $('#start').val();
                    d.end = $('#end').val();
                }
            },
            columns: [{
                    data: 'no',
                    orderable: false
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'namajabatan'
                },
                {
                    data: 'nama_pekerjaan'
                },
                {
                    data: 'nama_pegawai'
                },
                {
                    data: 'jumlah'
                },
                {
                    data: 'point'
                },
                {
                    data: 'total_point'
                },
                {
                    data: 'status',
                    orderable: false
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            drawCallback: function() {
                // Init iCheck pada checkbox baris yang baru dirender
                if ($.fn.iCheck) {
                    $('#searchTable tbody .row-check').iCheck({
                        checkboxClass: 'icheckbox_flat-red'
                    });
                    // Re-init check-all juga
                    $('#check-all').iCheck({
                        checkboxClass: 'icheckbox_flat-red'
                    });
                }
                bindActionButtons();
                updateSelectedCount();
            }
        });

        // Checkbox "pilih semua" — native change
        $(document).on('change', '#check-all', function() {
            var checked = $(this).is(':checked');
            $('#searchTable tbody .row-check').prop('checked', checked);
            // iCheck sync
            if ($.fn.iCheck) {
                checked
                    ?
                    $('#searchTable tbody .row-check').iCheck('check') :
                    $('#searchTable tbody .row-check').iCheck('uncheck');
            }
            updateSelectedCount();
        });

        // iCheck event untuk check-all
        $(document).on('ifChecked ifUnchecked', '#check-all', function(e) {
            var checked = (e.type === 'ifChecked');
            if ($.fn.iCheck) {
                checked
                    ?
                    $('#searchTable tbody .row-check').iCheck('check') :
                    $('#searchTable tbody .row-check').iCheck('uncheck');
            } else {
                $('#searchTable tbody .row-check').prop('checked', checked);
            }
            updateSelectedCount();
        });

        // Tiap checkbox baris — native
        $(document).on('change', '#searchTable tbody .row-check', function() {
            syncCheckAll();
            updateSelectedCount();
        });

        // iCheck event untuk row-check
        $(document).on('ifChecked ifUnchecked', '#searchTable tbody .row-check', function() {
            syncCheckAll();
            updateSelectedCount();
        });
    });

    function bindActionButtons() {
        // Approve per baris
        $('#searchTable tbody').off('click', '.approve-btn').on('click', '.approve-btn', function() {
            var id = $(this).data('id');
            updateStatus(id, 'approve');
        });

        // Reject per baris
        $('#searchTable tbody').off('click', '.reject-btn').on('click', '.reject-btn', function() {
            var id = $(this).data('id');
            updateStatus(id, 'reject');
        });
    }

    function loadTable() {
        table.ajax.reload(function() {
            updateSelectedCount();
        }, false);
    }

    function syncCheckAll() {
        var total = $('#searchTable tbody .row-check').length;
        var checked = $('#searchTable tbody .row-check:checked').length;
        var isAll = total > 0 && checked === total;
        var isIndet = checked > 0 && checked < total;

        $('#check-all')
            .prop('indeterminate', isIndet)
            .prop('checked', isAll);

        // Sync tampilan iCheck
        if ($.fn.iCheck) {
            if (isAll) {
                $('#check-all').iCheck('check');
            } else {
                $('#check-all').iCheck('uncheck');
            }
        }
    }

    function updateSelectedCount() {
        var count = $('#searchTable tbody .row-check:checked').length;
        if (count > 0) {
            $('#btn-approve-selected').prop('disabled', false);
            $('#selected-count').text(count).show();
        } else {
            $('#btn-approve-selected').prop('disabled', true);
            $('#selected-count').hide();
        }
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

                if (response.status === 'success') {
                    if (!callback) {
                        alert(response.message);
                        loadTable();
                    }
                } else {
                    if (!callback) alert('Error: ' + response.message);
                    $('.status-container-' + id).show();
                }

                if (callback) callback(response.status === 'success');
            },
            error: function() {
                $('.loading-indicator-' + id).hide();
                $('.status-container-' + id).show();
                if (!callback) alert('Terjadi kesalahan pada server');
                if (callback) callback(false);
            }
        });
    }

    function approveSelected() {
        var selectedIds = [];
        $('#searchTable tbody .row-check:checked').each(function() {
            selectedIds.push($(this).attr('value'));
        });

        if (selectedIds.length === 0) {
            alert('Tidak ada data yang dipilih.');
            return;
        }

        if (!confirm('Setujui ' + selectedIds.length + ' data yang dipilih?')) return;

        $('#btn-approve-selected').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        $('#check-all').prop('disabled', true);

        var processed = 0;
        var failed = 0;

        $.each(selectedIds, function(index, id) {
            updateStatus(id, 'approve', function(success) {
                if (!success) failed++;
                processed++;

                if (processed === selectedIds.length) {
                    var msg = (selectedIds.length - failed) + ' data berhasil disetujui.';
                    if (failed > 0) msg += ' ' + failed + ' data gagal.';
                    alert(msg);

                    // Reset tombol lalu reload tabel
                    $('#btn-approve-selected').html('<i class="fa fa-check"></i> Setujui <span id="selected-count" class="badge ms-1" style="display:none;">0</span>');
                    $('#check-all').prop('disabled', false).prop('checked', false);
                    loadTable();
                }
            });
        });
    }
</script>