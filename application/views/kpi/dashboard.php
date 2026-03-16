<div class="card">
    <div class="card-body">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Data KPI</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Bulan</label>
                            <select class="form-control" id="filterBulan" name="bulan">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($i == $bulan_selected) ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Tahun</label>
                            <select class="form-control" id="filterTahun" name="tahun">
                                <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                    <option value="<?= $i ?>" <?= ($i == $tahun_selected) ? 'selected' : '' ?>>
                                        <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label>Departemen</label>
                            <select class="form-control" id="filterUnit" name="unit_id">
                                <option value="">Semua Departemen</option>
                                <?php foreach ($unit_list as $unit): ?>
                                    <option value="<?= $unit['id'] ?>"><?= $unit['nama_unit'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-primary" onclick="loadData()">
                                    <i class="fa fa-search"></i> Tampilkan
                                </button>
                                <button type="button" class="btn btn-success" id="btnHitungKPI" onclick="hitungKPI()">
                                    <i class="fa fa-calculator"></i> Hitung KPI
                                </button>
                                <a href="<?= base_url('kpi/pengaturan_bobot') ?>" class="btn btn-warning">
                                    <i class="fa fa-cog"></i> Pengaturan Bobot
                                </a>
                                <button type="button" class="btn btn-info" onclick="exportExcel()">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row" id="statistikCards">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pegawai</span>
                        <span class="info-box-number" id="statTotalPegawai">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Rata-rata KPI</span>
                        <span class="info-box-number" id="statRataKPI">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">KPI Tertinggi</span>
                        <span class="info-box-number" id="statKPITertinggi">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-arrow-down"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">KPI Terendah</span>
                        <span class="info-box-number" id="statKPITerendah">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-5">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-pie-chart"></i> Distribusi Kategori Kinerja</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="chartKategori" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Top 10 Pegawai Terbaik</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="chartRanking" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data KPI -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-table"></i> Data KPI Pegawai</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tableKPI" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Presensi</th>
                                <th>Kegiatan</th>
                                <th>Cuti</th>
                                <th>Pekerjaan</th>
                                <th>Dinas Luar</th>
                                <th>KPI Final</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr>
                                <td colspan="12" class="text-center">
                                    <i class="fa fa-spinner fa-spin"></i> Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Progress Hitung KPI -->
        <div class="modal fade" id="modalProgressKPI" tabindex="-1" role="dialog" aria-labelledby="modalProgressKPILabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h4 class="modal-title text-white" id="modalProgressKPILabel">
                            <i class="fa fa-calculator"></i> Menghitung KPI
                        </h4>
                    </div>
                    <div class="modal-body">
                        <p id="kpiProgressInfo" class="text-muted">Memulai...</p>
                        <div class="progress active" style="height:22px;">
                            <div id="kpiProgressBar"
                                class="progress-bar progress-bar-success progress-bar-striped active"
                                role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                style="width:0%; min-width:2em;">0%</div>
                        </div>
                        <div style="max-height:200px; overflow-y:auto; margin-top:10px;">
                            <ul id="kpiProgressLog" class="list-unstyled small"></ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnTutupProgress" type="button" class="btn btn-default"
                            style="display:none;" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let chartKategori, chartRanking;

    $(document).ready(function() {
        loadData();
        $("#tableKPI").DataTable();
    });

    function loadData() {
        const bulan = $('#filterBulan').val();
        const tahun = $('#filterTahun').val();
        const unit_id = $('#filterUnit').val();

        loadStatistik(bulan, tahun);
        loadTabel(bulan, tahun, unit_id);
        loadChartRanking(bulan, tahun);
    }

    function loadStatistik(bulan, tahun) {
        $.ajax({
            url: '<?= base_url('kpi/ajax_get_statistik') ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                bulan,
                tahun
            },
            success: function(response) {
                if (!response.status || !response.data) return;
                const d = response.data;
                $('#statTotalPegawai').text(d.total_pegawai || 0);
                $('#statRataKPI').text(parseFloat(d.rata_rata_kpi || 0).toFixed(2));
                $('#statKPITertinggi').text(parseFloat(d.kpi_tertinggi || 0).toFixed(2));
                $('#statKPITerendah').text(parseFloat(d.kpi_terendah || 0).toFixed(2));

                // Pie chart kategori
                const ctx = document.getElementById('chartKategori').getContext('2d');
                if (chartKategori) chartKategori.destroy();
                chartKategori = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'],
                        datasets: [{
                            data: [
                                d.sangat_baik || 0,
                                d.baik || 0,
                                d.cukup || 0,
                                d.kurang || 0,
                                d.sangat_kurang || 0
                            ],
                            backgroundColor: ['#00a65a', '#00c0ef', '#f39c12', '#dd4b39', '#d73925']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            },
            error: function(xhr) {
                console.error('Statistik error:', xhr.responseText);
            }
        });
    }

    function loadTabel(bulan, tahun, unit_id) {
        $('#tableBody').html('<tr><td colspan="12" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data...</td></tr>');
        $.ajax({
            url: '<?= base_url('kpi/ajax_get_data') ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                bulan,
                tahun,
                unit_id
            },
            success: function(response) {
                if (!response.status || !response.data || !response.data.length) {
                    $('#tableBody').html('<tr><td colspan="12" class="text-center">Tidak ada data untuk periode ini</td></tr>');
                    return;
                }
                let html = '';
                response.data.forEach(function(item, index) {
                    const badge = getBadgeClass(item.nilai_kpi_final);
                    html += `<tr>
                        <td>${index + 1}</td>
                        <td>${item.nip || '-'}</td>
                        <td>${item.nama_pegawai || '-'}</td>
                        <td>${item.departemen || '-'}</td>
                        <td>${parseFloat(item.nilai_presensi   || 0).toFixed(2)}</td>
                        <td>${parseFloat(item.nilai_kegiatan   || 0).toFixed(2)}</td>
                        <td>${parseFloat(item.nilai_cuti       || 0).toFixed(2)}</td>
                        <td>${parseFloat(item.nilai_pekerjaan  || 0).toFixed(2)}</td>
                        <td>${parseFloat(item.nilai_dinas_luar || 0).toFixed(2)}</td>
                        <td><strong>${parseFloat(item.nilai_kpi_final || 0).toFixed(2)}</strong></td>
                        <td><span class="badge ${badge}">${item.kategori_kinerja || '-'}</span></td>
                        <td>
                            <a href="<?= base_url('kpi/detail/') ?>${item.pegawai_id}"
                               class="btn btn-xs btn-info" title="Detail">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>`;
                });
                $('#tableBody').html(html);
            },
            error: function(xhr) {
                console.error('Tabel error:', xhr.responseText);
                $('#tableBody').html('<tr><td colspan="12" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    }

    function loadChartRanking(bulan, tahun) {
        $.ajax({
            url: '<?= base_url('kpi/ajax_get_ranking') ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                bulan,
                tahun,
                limit: 10
            },
            success: function(response) {
                if (!response.status || !response.data || !response.data.length) return;
                const labels = response.data.map(function(i) {
                    return i.nama_pegawai;
                });
                const values = response.data.map(function(i) {
                    return parseFloat(i.nilai_kpi_final);
                });
                const ctx = document.getElementById('chartRanking').getContext('2d');
                if (chartRanking) chartRanking.destroy();
                chartRanking = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Nilai KPI',
                            data: values,
                            backgroundColor: '#00a65a'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            },
            error: function(xhr) {
                console.error('Chart ranking error:', xhr.responseText);
            }
        });
    }

    function getBadgeClass(nilai) {
        if (nilai >= 90) return 'bg-green';
        if (nilai >= 80) return 'bg-light-blue';
        if (nilai >= 70) return 'bg-yellow';
        if (nilai >= 60) return 'bg-orange';
        return 'bg-red';
    }

    async function hitungKPI() {
        const bulan = $('#filterBulan').val();
        const tahun = $('#filterTahun').val();
        const unit_id = $('#filterUnit').val();

        if (!confirm('Hitung KPI untuk periode ini? Proses akan berjalan satu per satu.')) return;

        let pegawaiList = [],
            total = 0;
        try {
            const res = await $.ajax({
                url: '<?= base_url('kpi/ajax_get_pegawai_list') ?>',
                type: 'POST',
                data: {
                    bulan,
                    tahun,
                    unit_id
                }
            });
            if (!res.status || !res.data.length) {
                alert('Tidak ada pegawai ditemukan.');
                return;
            }
            pegawaiList = res.data;
            total = res.total;
        } catch (e) {
            alert('Gagal mengambil data pegawai.');
            return;
        }

        $('#modalProgressKPI').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#kpiProgressBar').css('width', '0%').attr('aria-valuenow', 0).text('0%');
        $('#kpiProgressInfo').text('Memulai...');
        $('#kpiProgressLog').empty();
        $('#btnTutupProgress').hide();
        $('#btnHitungKPI').prop('disabled', true);

        let sukses = 0,
            gagal = 0;

        for (let i = 0; i < pegawaiList.length; i++) {
            const p = pegawaiList[i];
            const pct = Math.round(((i + 1) / total) * 100);

            $('#kpiProgressInfo').text(`(${i + 1}/${total}) ${p.nama_pegawai}`);
            $('#kpiProgressBar').css('width', pct + '%').attr('aria-valuenow', pct).text(pct + '%');

            try {
                const r = await $.ajax({
                    url: '<?= base_url('kpi/ajax_hitung_single') ?>',
                    type: 'POST',
                    data: {
                        pegawai_id: p.uuid,
                        bulan,
                        tahun
                    }
                });
                if (r.status) {
                    sukses++;
                    $('#kpiProgressLog').prepend(`<li class="text-success"><i class="fa fa-check"></i> ${p.nama_pegawai}</li>`);
                } else {
                    gagal++;
                    $('#kpiProgressLog').prepend(`<li class="text-danger"><i class="fa fa-times"></i> ${p.nama_pegawai}: ${r.message}</li>`);
                }
            } catch (e) {
                gagal++;
                $('#kpiProgressLog').prepend(`<li class="text-danger"><i class="fa fa-times"></i> ${p.nama_pegawai}: request error</li>`);
            }
        }

        $('#kpiProgressInfo').text(`Selesai: ${sukses} berhasil, ${gagal} gagal`);
        $('#kpiProgressBar').removeClass('active').css('width', '100%').text('100%');
        $('#btnTutupProgress').show();
        $('#btnHitungKPI').prop('disabled', false);
        loadData();
    }

    function exportExcel() {
        const bulan = $('#filterBulan').val();
        const tahun = $('#filterTahun').val();
        const unit_id = $('#filterUnit').val();
        window.location.href = `<?= base_url('kpi/export') ?>?bulan=${bulan}&tahun=${tahun}&unit_id=${unit_id}`;
    }
</script>