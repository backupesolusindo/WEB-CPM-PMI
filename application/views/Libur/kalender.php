<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<style>
    .fc-daygrid-day:hover {
        cursor: pointer;
        background-color: #f0f8ff !important;
    }

    .badge-orange {
        background-color: #fd7e14;
        color: white;
    }

    .bg-orange {
        background-color: #fd7e14 !important;
        color: white;
    }

    .pegawai-row-libur {
        background-color: #fff3cd !important;
    }

    .checkbox-pegawai,
    #checkAll {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #e74c3c;
        vertical-align: middle;
        display: inline-block !important;
        opacity: 1 !important;
        position: relative !important;
    }

    #tableBodyPegawai tr td {
        vertical-align: middle;
    }

    .fc-event {
        cursor: pointer;
    }

    .select-all-wrap {
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 8px;
        margin-bottom: 5px;
    }

    #pegawaiSearch {
        border-radius: 20px;
    }
</style>

<div class="row">
    <!-- Filter -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body py-3">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="font-weight-bold">Bulan :</label>
                        <select id="bulan" class="form-control" onchange="loadCalendar()">
                            <?php
                            $namaBulan = [
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
                            foreach ($namaBulan as $val => $label):
                            ?>
                                <option value="<?= $val ?>" <?= (date('m') == $val) ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="font-weight-bold">Tahun :</label>
                        <select id="tahun" class="form-control" onchange="loadCalendar()">
                            <?php for ($i = date("Y") - 2; $i <= date("Y") + 2; $i++): ?>
                                <option value="<?= $i ?>" <?= ($i == date("Y")) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="d-flex align-items-center">
                            <span class="badge badge-danger mr-2 p-2"><i class="fa fa-circle"></i> Libur Nasional</span>
                            <span class="badge badge-orange mr-2 p-2"><i class="fa fa-circle"></i> Libur Pegawai</span>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3 mt-md-0 text-right">
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Klik tanggal di kalender untuk kelola libur pegawai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kalender -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0"><i class="fa fa-calendar-alt text-danger mr-2"></i>Kalender Libur Pegawai</h4>
                </div>
                <div id='calendarLibur'></div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL KELOLA LIBUR PEGAWAI ===== -->
<div class="modal fade" id="modalLiburPegawai" tabindex="-1" role="dialog" aria-labelledby="modalLiburPegawaiLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalLiburPegawaiLabel">
                    <i class="fa fa-calendar-times mr-2"></i>Kelola Libur Pegawai &mdash; <span id="lblTanggalModal"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Alert libur nasional -->
                <div id="alertLiburNasional" class="alert alert-danger d-none">
                    <i class="fa fa-exclamation-triangle"></i> <strong>Hari Libur Nasional:</strong> <span id="lblLiburNasional"></span>
                </div>

                <div class="row">
                    <!-- Kiri: Form tambah + daftar pegawai libur -->
                    <div class="col-md-7">
                        <!-- Form Tambah -->
                        <div class="card border-danger mb-3">
                            <div class="card-header bg-danger text-white py-2">
                                <b><i class="fa fa-plus-circle mr-1"></i> Tambah Libur Pegawai</b>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="inputTanggal" />
                                <div class="form-group mb-2">
                                    <label class="font-weight-bold">Keterangan Libur :</label>
                                    <input type="text" id="inputKeterangan" class="form-control" placeholder="Contoh: Libur Khusus, Cuti Bersama, dll">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="font-weight-bold">Filter Jabatan :</label>
                                    <select id="filterJabatan" class="form-control select2" onchange="loadPegawaiList()">
                                        <option value="">Semua Jabatan</option>
                                        <?php if (!empty($jabatan)) foreach ($jabatan as $j): ?>
                                            <option value="<?= $j->idjabatan ?>"><?= $j->namajabatan ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label class="font-weight-bold">Cari Pegawai :</label>
                                    <input type="text" id="pegawaiSearch" class="form-control" placeholder="🔍 Ketik nama atau NIP..." oninput="filterPegawaiTable()">
                                </div>

                                <!-- Tabel pilih pegawai -->
                                <div class="select-all-wrap d-flex justify-content-between align-items-center px-1">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" id="checkAll" onchange="toggleCheckAll(this)" style="width:18px;height:18px;cursor:pointer;accent-color:#e74c3c;display:inline-block !important;opacity:1 !important;position:relative !important;vertical-align:middle;">
                                        <label for="checkAll" class="font-weight-bold ml-2 mb-0" style="cursor:pointer;">Pilih Semua</label>
                                    </div>
                                    <span id="lblJmlDipilih" class="badge badge-primary">0 dipilih</span>
                                </div>
                                <div style="max-height:280px; overflow-y:auto;">
                                    <table class="table table-sm table-hover table-bordered mb-0" id="tablePegawai">
                                        <thead class="thead-light sticky-top">
                                            <tr>
                                                <th width="40" class="text-center">#</th>
                                                <th>Nama Pegawai</th>
                                                <th>NIP</th>
                                                <th>Bagian</th>
                                                <th width="70" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBodyPegawai">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    <i class="fa fa-spinner fa-spin"></i> Memuat data...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3 text-right">
                                    <button type="button" class="btn btn-danger" onclick="simpanLiburPegawai()">
                                        <i class="fa fa-save mr-1"></i> Simpan Libur
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Daftar pegawai yang sudah libur hari itu -->
                    <div class="col-md-5">
                        <div class="card border-orange mb-3" style="border-color:#fd7e14 !important;">
                            <div class="card-header bg-orange text-white py-2">
                                <b><i class="fa fa-list mr-1"></i> Pegawai Libur Tanggal Ini</b>
                                <span id="badgeJmlLibur" class="badge badge-light ml-2">0</span>
                            </div>
                            <div class="card-body p-0">
                                <div style="max-height:500px; overflow-y:auto;">
                                    <table class="table table-sm table-hover table-bordered mb-0" id="tableLiburPegawai">
                                        <thead class="thead-light sticky-top">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Bagian</th>
                                                <th>Ket.</th>
                                                <th width="50" class="text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBodyLibur">
                                            <tr>
                                                <td colspan="4" class="text-center text-muted p-3">Belum ada pegawai libur</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var calendarLibur;
    var allPegawaiData = [];
    var tanggalDipilih = '';

    $(document).ready(function() {
        initCalendar();
    });

    function initCalendar() {
        var calendarEl = document.getElementById('calendarLibur');
        calendarLibur = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari'
            },
            events: function(info, successCallback, failureCallback) {
                loadCalendarData(successCallback, failureCallback);
            },
            dateClick: function(info) {
                bukaModal(info.dateStr);
            },
            eventClick: function(info) {
                bukaModal(info.event.startStr);
            },
            height: 'auto'
        });
        calendarLibur.render();
    }

    function loadCalendarData(successCallback, failureCallback) {
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>Libur/data_kalender_libur",
            data: {
                bulan: bulan,
                tahun: tahun
            },
            dataType: "json",
            success: function(data) {
                successCallback(data);
            },
            error: function(e) {
                console.error("Error load calendar", e);
                if (failureCallback) failureCallback(e);
            }
        });
    }

    function loadCalendar() {
        if (calendarLibur) {
            // Sinkronkan bulan/tahun ke navigasi kalender
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();
            calendarLibur.gotoDate(tahun + '-' + bulan + '-01');
            calendarLibur.refetchEvents();
        }
    }

    // ===== MODAL =====

    function bukaModal(tanggal) {
        tanggalDipilih = tanggal;
        $('#inputTanggal').val(tanggal);

        // Format tampilan tanggal
        var d = new Date(tanggal + 'T00:00:00');
        var opts = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        $('#lblTanggalModal').text(d.toLocaleDateString('id-ID', opts));

        // Reset form
        $('#inputKeterangan').val('');
        $('#pegawaiSearch').val('');
        $('#checkAll').prop('checked', false);
        $('#lblJmlDipilih').text('0 dipilih');
        $('#alertLiburNasional').addClass('d-none');

        // Load detail
        loadDetailModal(tanggal);
        loadPegawaiList();

        $('#modalLiburPegawai').modal('show');
    }

    function loadDetailModal(tanggal) {
        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>Libur/detail_libur_pegawai",
            data: {
                tanggal: tanggal
            },
            dataType: "json",
            success: function(data) {
                // Libur nasional
                if (data.libur_nasional) {
                    $('#lblLiburNasional').text(data.libur_nasional.keterangan);
                    $('#alertLiburNasional').removeClass('d-none');
                } else {
                    $('#alertLiburNasional').addClass('d-none');
                }
                // Render tabel pegawai libur
                renderTabelLibur(data.pegawai);
            }
        });
    }

    function renderTabelLibur(pegawaiLibur) {
        $('#badgeJmlLibur').text(pegawaiLibur.length);
        var html = '';
        if (pegawaiLibur.length === 0) {
            html = '<tr><td colspan="4" class="text-center text-muted p-3">Belum ada pegawai libur</td></tr>';
        } else {
            $.each(pegawaiLibur, function(i, p) {
                html += '<tr>';
                html += '<td>' + p.nama_pegawai + '<br><small class="text-muted">' + (p.nip || '-') + '</small></td>';
                html += '<td><small>' + (p.unit || '-') + '</small></td>';
                html += '<td><small>' + (p.keterangan || '-') + '</small></td>';
                html += '<td class="text-center">';
                html += '<button class="btn btn-danger btn-sm btn-floating" onclick="hapusLiburPegawai(' + p.id + ')" title="Hapus">';
                html += '<i class="fas fa-trash fa-sm"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tableBodyLibur').html(html);
    }

    function loadPegawaiList() {
        var jabatan = $('#filterJabatan').val();
        var tanggal = tanggalDipilih;

        $('#tableBodyPegawai').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat...</td></tr>');
        $('#checkAll').prop('checked', false);
        $('#lblJmlDipilih').text('0 dipilih');

        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>Libur/get_pegawai_list",
            data: {
                jabatan: jabatan,
                tanggal: tanggal
            },
            dataType: "json",
            success: function(data) {
                allPegawaiData = data;
                renderTabelPegawai(data);
            },
            error: function() {
                $('#tableBodyPegawai').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data pegawai</td></tr>');
            }
        });
    }

    function renderTabelPegawai(data) {
        var html = '';
        if (data.length === 0) {
            html = '<tr><td colspan="5" class="text-center text-muted">Tidak ada data pegawai</td></tr>';
        } else {
            $.each(data, function(i, p) {
                var rowClass = p.sudah_libur ? 'class="pegawai-row-libur"' : '';
                var checkedAttr = p.sudah_libur ? 'checked disabled' : '';
                var badgeStatus = p.sudah_libur ?
                    '<span class="badge badge-warning">Libur</span>' :
                    '';
                html += '<tr ' + rowClass + ' data-nama="' + p.nama_pegawai.toLowerCase() + '" data-nip="' + (p.nip || '').toLowerCase() + '">';
                html += '<td class="text-center">';
                if (!p.sudah_libur) {
                    html += '<input type="checkbox" id="checkboxPegawai-' + p.uuid + '" class="checkbox-pegawai chkPegawai" value="' + p.uuid + '" ' + checkedAttr + ' onchange="updateCountChecked()">';
                } else {
                    html += '<i class="fa fa-check-circle text-warning"></i>';
                }
                html += '</td>';
                html += '<td><label for="checkboxPegawai-' + p.uuid + '">' + p.nama_pegawai + '</label></td>';
                html += '<td><label for="checkboxPegawai-' + p.uuid + '">' + (p.nip || '-') + '</label></td>';
                html += '<td><small>' + (p.unit || '-') + '</small></td>';
                html += '<td class="text-center">' + badgeStatus + '</td>';
                html += '</tr>';
            });
        }
        $('#tableBodyPegawai').html(html);
        updateCountChecked();
    }

    function filterPegawaiTable() {
        var keyword = $('#pegawaiSearch').val().toLowerCase();
        $('#tableBodyPegawai tr').each(function() {
            var nama = $(this).data('nama') || '';
            var nip = $(this).data('nip') || '';
            if (nama.indexOf(keyword) > -1 || nip.indexOf(keyword) > -1 || keyword === '') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function toggleCheckAll(el) {
        $('.chkPegawai:not(:disabled)').prop('checked', el.checked);
        updateCountChecked();
    }

    function updateCountChecked() {
        var jml = $('.chkPegawai:checked').length;
        $('#lblJmlDipilih').text(jml + ' dipilih');
        // Update indeterminate state
        var total = $('.chkPegawai:not(:disabled)').length;
        if (jml === 0) {
            $('#checkAll').prop('indeterminate', false).prop('checked', false);
        } else if (jml === total) {
            $('#checkAll').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#checkAll').prop('indeterminate', true);
        }
    }

    function simpanLiburPegawai() {
        var tanggal = $('#inputTanggal').val();
        var keterangan = $('#inputKeterangan').val().trim();
        var uuids = [];

        $('.chkPegawai:checked').each(function() {
            uuids.push($(this).val());
        });

        if (uuids.length === 0) {
            Swal.fire('Perhatian', 'Pilih minimal satu pegawai terlebih dahulu.', 'warning');
            return;
        }
        if (!keterangan) {
            Swal.fire('Perhatian', 'Isi keterangan libur terlebih dahulu.', 'warning');
            return;
        }

        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>Libur/insert_libur_pegawai",
            data: {
                tanggal: tanggal,
                keterangan: keterangan,
                'pegawai_uuid[]': uuids
            },
            dataType: "json",
            beforeSend: function() {
                $('button[onclick="simpanLiburPegawai()"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan...');
            },
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire('Berhasil!', res.message, 'success').then(function() {
                        loadDetailModal(tanggal);
                        loadPegawaiList();
                        calendarLibur.refetchEvents();
                    });
                } else {
                    Swal.fire('Info', res.message, 'info').then(function() {
                        loadDetailModal(tanggal);
                        loadPegawaiList();
                    });
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
            },
            complete: function() {
                $('button[onclick="simpanLiburPegawai()"]').prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Libur');
            }
        });
    }

    function hapusLiburPegawai(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data libur pegawai ini akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "<?= base_url() ?>Libur/delete_libur_pegawai/" + id,
                    dataType: "json",
                    success: function(res) {
                        if (res.status === 'success') {
                            loadDetailModal(tanggalDipilih);
                            loadPegawaiList();
                            calendarLibur.refetchEvents();
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function() {
                        toastr.error('Terjadi kesalahan.');
                    }
                });
            }
        });
    }
</script>