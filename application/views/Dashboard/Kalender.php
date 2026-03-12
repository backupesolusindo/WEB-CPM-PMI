<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Filter Data</h4>
                <div class="row">
                    <div class="col-md-3">
                        <label>Bulan :</label>
                        <select id="bulan" class="form-control" onchange="loadCalendar()">
                            <option value="01" <?php echo (date('m') == '01') ? 'selected' : ''; ?>>Januari</option>
                            <option value="02" <?php echo (date('m') == '02') ? 'selected' : ''; ?>>Februari</option>
                            <option value="03" <?php echo (date('m') == '03') ? 'selected' : ''; ?>>Maret</option>
                            <option value="04" <?php echo (date('m') == '04') ? 'selected' : ''; ?>>April</option>
                            <option value="05" <?php echo (date('m') == '05') ? 'selected' : ''; ?>>Mei</option>
                            <option value="06" <?php echo (date('m') == '06') ? 'selected' : ''; ?>>Juni</option>
                            <option value="07" <?php echo (date('m') == '07') ? 'selected' : ''; ?>>Juli</option>
                            <option value="08" <?php echo (date('m') == '08') ? 'selected' : ''; ?>>Agustus</option>
                            <option value="09" <?php echo (date('m') == '09') ? 'selected' : ''; ?>>September</option>
                            <option value="10" <?php echo (date('m') == '10') ? 'selected' : ''; ?>>Oktober</option>
                            <option value="11" <?php echo (date('m') == '11') ? 'selected' : ''; ?>>November</option>
                            <option value="12" <?php echo (date('m') == '12') ? 'selected' : ''; ?>>Desember</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Tahun :</label>
                        <select id="tahun" class="form-control" onchange="loadCalendar()">
                            <?php
                            $tahun_sekarang = date('Y');
                            for ($i = $tahun_sekarang - 2; $i <= $tahun_sekarang + 2; $i++) {
                                $selected = ($i == $tahun_sekarang) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Unit :</label>
                        <select id="unit" class="form-control select2" onchange="sub_unit()">
                            <option value="">Semua Unit</option>
                            <?php foreach ($unit as $value): ?>
                                <option value="<?php echo $value->nama_unit; ?>"><?php echo $value->nama_unit ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Sub Unit :</label>
                        <select id="sub_unit" class="form-control select2" onchange="loadCalendar()">
                            <option value="">Semua Sub Unit</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Kalender Presensi</h4>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <span class="badge badge-danger mr-2"><i class="fa fa-circle"></i> Hari Libur</span>
                        <span class="badge badge-success mr-2"><i class="fa fa-circle"></i> Presensi</span>
                        <span class="badge badge-warning mr-2"><i class="fa fa-circle"></i> Cuti</span>
                        <span class="badge badge-info mr-2"><i class="fa fa-circle"></i> Kegiatan</span>
                    </div>
                </div>
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetailLabel">Detail Data Tanggal <span id="tanggalDetail"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Alert Hari Libur -->
                <div id="alertLibur" class="alert alert-danger" style="display:none;">
                    <i class="fa fa-exclamation-triangle"></i> <strong>Hari Libur:</strong> <span id="keteranganLibur"></span>
                </div>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="presensi-tab" data-toggle="tab" href="#presensi" role="tab">
                            <i class="fa fa-check-circle text-success"></i> Presensi <span class="badge badge-success" id="badgePresensi">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="cuti-tab" data-toggle="tab" href="#cuti" role="tab">
                            <i class="fa fa-calendar text-warning"></i> Cuti <span class="badge badge-warning" id="badgeCuti">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="kegiatan-tab" data-toggle="tab" href="#kegiatan" role="tab">
                            <i class="fa fa-briefcase text-info"></i> Kegiatan <span class="badge badge-info" id="badgeKegiatan">0</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="presensi" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Unit</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Jadwal</th>
                                        <th>Status</th>
                                        <th>Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody id="tablePresensi">
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cuti" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-warning text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Unit</th>
                                        <th>Jenis Cuti</th>
                                        <th>Periode</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="tableCuti">
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kegiatan" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-info text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Unit</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Periode</th>
                                        <th>Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableKegiatan">
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var calendar;

    $(document).ready(function() {
        initCalendar();
    });

    function initCalendar() {
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
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
                showDetail(info.dateStr);
            },
            height: 'auto'
        });
        calendar.render();
    }

    function loadCalendarData(successCallback, failureCallback) {
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        var unit = $('#unit').val();
        var sub_unit = $('#sub_unit').val();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>Dashboard/data_kalender",
            data: {
                bulan: bulan,
                tahun: tahun,
                unit: unit,
                sub_unit: sub_unit
            },
            dataType: "json",
            success: function(data) {
                successCallback(data);
            },
            error: function(e) {
                console.log("Error loading calendar data", e);
                failureCallback(e);
            }
        });
    }

    function loadCalendar() {
        if (calendar) {
            calendar.refetchEvents();
        }
    }

    function sub_unit() {
        var unit = $("#unit").val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url() ?>Laporan/sub_unit',
            data: {
                unit: unit
            },
            success: function(response) {
                $("#sub_unit").html(response);
                loadCalendar();
            }
        });
    }

    function showDetail(tanggal) {
        var unit = $('#unit').val();
        var sub_unit = $('#sub_unit').val();

        // Format tanggal untuk ditampilkan
        var date = new Date(tanggal);
        var options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        var tanggalFormat = date.toLocaleDateString('id-ID', options);
        $('#tanggalDetail').text(tanggalFormat);

        // Load data detail
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>Dashboard/detail_kalender",
            data: {
                tanggal: tanggal,
                unit: unit,
                sub_unit: sub_unit
            },
            dataType: "json",
            beforeSend: function() {
                $('#tablePresensi').html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
                $('#tableCuti').html('<tr><td colspan="7" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
                $('#tableKegiatan').html('<tr><td colspan="7" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
            },
            success: function(data) {
                // Tampilkan alert jika hari libur
                if (data.libur !== null) {
                    $('#keteranganLibur').text(data.libur.keterangan);
                    $('#alertLibur').show();
                } else {
                    $('#alertLibur').hide();
                }

                // Update badge count
                $('#badgePresensi').text(data.presensi.length);
                $('#badgeCuti').text(data.cuti.length);
                $('#badgeKegiatan').text(data.kegiatan.length);

                // Populate Presensi Table
                var htmlPresensi = '';
                if (data.presensi.length > 0) {
                    $.each(data.presensi, function(index, item) {
                        htmlPresensi += '<tr>';
                        htmlPresensi += '<td>' + (index + 1) + '</td>';
                        htmlPresensi += '<td>' + item.nip + '</td>';
                        htmlPresensi += '<td>' + item.nama + '</td>';
                        htmlPresensi += '<td>' + item.unit + '</td>';
                        htmlPresensi += '<td>' + item.waktu + '</td>';
                        htmlPresensi += '<td>' + item.jam_jadwal + '</td>';
                        htmlPresensi += '<td><span class="badge badge-' + item.badge + '">' + item.status + '</span></td>';
                        htmlPresensi += '<td><span class="badge badge-' + (item.lokasi == 'WFO' ? 'primary' : 'secondary') + '">' + item.lokasi + '</span></td>';
                        htmlPresensi += '</tr>';
                    });
                } else {
                    htmlPresensi = '<tr><td colspan="8" class="text-center">Tidak ada data presensi</td></tr>';
                }
                $('#tablePresensi').html(htmlPresensi);

                // Populate Cuti Table
                var htmlCuti = '';
                if (data.cuti.length > 0) {
                    $.each(data.cuti, function(index, item) {
                        htmlCuti += '<tr>';
                        htmlCuti += '<td>' + (index + 1) + '</td>';
                        htmlCuti += '<td>' + item.nip + '</td>';
                        htmlCuti += '<td>' + item.nama + '</td>';
                        htmlCuti += '<td>' + item.unit + '</td>';
                        htmlCuti += '<td>' + item.jenis + '</td>';
                        htmlCuti += '<td>' + item.tanggal_mulai + ' s/d ' + item.tanggal_selesai + '</td>';
                        htmlCuti += '<td>' + item.keterangan + '</td>';
                        htmlCuti += '</tr>';
                    });
                } else {
                    htmlCuti = '<tr><td colspan="7" class="text-center">Tidak ada data cuti</td></tr>';
                }
                $('#tableCuti').html(htmlCuti);

                // Populate Kegiatan Table
                var htmlKegiatan = '';
                if (data.kegiatan.length > 0) {
                    $.each(data.kegiatan, function(index, item) {
                        htmlKegiatan += '<tr>';
                        htmlKegiatan += '<td>' + (index + 1) + '</td>';
                        htmlKegiatan += '<td>' + item.nip + '</td>';
                        htmlKegiatan += '<td>' + item.nama + '</td>';
                        htmlKegiatan += '<td>' + item.unit + '</td>';
                        htmlKegiatan += '<td>' + item.nama_kegiatan + '</td>';
                        htmlKegiatan += '<td>' + item.tanggal_mulai + ' s/d ' + item.tanggal_selesai + '</td>';
                        htmlKegiatan += '<td>' + item.lokasi + '</td>';
                        htmlKegiatan += '</tr>';
                    });
                } else {
                    htmlKegiatan = '<tr><td colspan="7" class="text-center">Tidak ada data kegiatan</td></tr>';
                }
                $('#tableKegiatan').html(htmlKegiatan);

                // Show modal
                $('#modalDetail').modal('show');
            },
            error: function(e) {
                console.log("Error loading detail", e);
                alert("Gagal memuat detail data");
            }
        });
    }
</script>