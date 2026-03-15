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
<div class="card">
    <div class="card-body row">
        <!-- Filter Section -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter Data KPI</h3>
            </div>
            <div class="box-body">
                <form id="formFilter" class="form-inline">
                    <div class="form-group">
                        <label>Bulan:</label>
                        <select class="form-control" id="filterBulan" name="bulan">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= ($i == $bulan_selected) ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tahun:</label>
                        <select class="form-control" id="filterTahun" name="tahun">
                            <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                <option value="<?= $i ?>" <?= ($i == $tahun_selected) ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Departemen:</label>
                        <select class="form-control" id="filterUnit" name="unit_id">
                            <option value="">Semua Departemen</option>
                            <?php foreach ($unit_list as $unit): ?>
                                <option value="<?= $unit['id'] ?>"><?= $unit['nama_unit'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="loadData()">
                        <i class="fa fa-search"></i> Tampilkan
                    </button>

                    <button type="button" class="btn btn-success" onclick="hitungKPI()">
                        <i class="fa fa-calculator"></i> Hitung KPI
                    </button>
                    <a href="<?= base_url('kpi/pengaturan_bobot') ?>" class="btn btn-warning">
                        <i class="fa fa-cog"></i> Pengaturan Bobot
                    </a>

                    <button type="button" class="btn btn-info" onclick="exportExcel()">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </button>
                </form>
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

        <!-- Chart Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-pie-chart"></i> Distribusi Kategori Kinerja</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="chartKategori" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Top 10 Pegawai Terbaik</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="chartRanking" height="200"></canvas>
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
                    <table id="tableKPI" class="table table-bordered table-striped">
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
    </div>
</div>
<script>
    let chartKategori, chartRanking;

    $(document).ready(function() {
        loadData();
    });

    function loadData() {
        const bulan = $('#filterBulan').val();
        const tahun = $('#filterTahun').val();
        const unit_id = $('#filterUnit').val();

        // Load statistik
        loadStatistik(bulan, tahun);

        // Load tabel
        loadTabel(bulan, tahun, unit_id);

        // Load chart
        loadChart(bulan, tahun);
    }

    function loadStatistik(bulan, tahun) {
        $.ajax({
            url: '<?= base_url('kpi/ajax_get_statistik') ?>',
            type: 'GET',
            data: {
                bulan,
                tahun
            },
            success: function(response) {
                if (response.status) {
                    const data = response.data;
                    $('#statTotalPegawai').text(data.total_pegawai || 0);
                    $('#statRataKPI').text(parseFloat(data.rata_rata_kpi || 0).toFixed(2));
                    $('#statKPITertinggi').text(parseFloat(data.kpi_tertinggi || 0).toFixed(2));
                    $('#statKPITerendah').text(parseFloat(data.kpi_terendah || 0).toFixed(2));
                }
            }
        });
    }

    function loadTabel(bulan, tahun, unit_id) {
        $.ajax({
            url: '<?= base_url('kpi/ajax_get_data') ?>',
            type: 'GET',
            data: {
                bulan,
                tahun,
                unit_id
            },
            success: function(response) {
                if (response.status) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="12" class="text-center">Tidak ada data</td></tr>';
                    } else {
                        response.data.forEach((item, index) => {
                            const badgeClass = getBadgeClass(item.nilai_kpi_final);
                            html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nip}</td>
                                <td>${item.nama_lengkap}</td>
                                <td>${item.departemen || '-'}</td>
                                <td>${parseFloat(item.nilai_presensi).toFixed(2)}</td>
                                <td>${parseFloat(item.nilai_kegiatan).toFixed(2)}</td>
                                <td>${parseFloat(item.nilai_cuti).toFixed(2)}</td>
                                <td>${parseFloat(item.nilai_pekerjaan).toFixed(2)}</td>
                                <td>${parseFloat(item.nilai_dinas_luar).toFixed(2)}</td>
                                <td><strong>${parseFloat(item.nilai_kpi_final).toFixed(2)}</strong></td>
                                <td><span class="badge ${badgeClass}">${item.kategori_kinerja}</span></td>
                                <td>
                                    <a href="<?= base_url('kpi/detail/') ?>${item.pegawai_id}" 
                                       class="btn btn-xs btn-info" title="Detail">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                        });
                    }
                    $('#tableBody').html(html);
                }
            }
        });
    }

    function loadChart(bulan, tahun) {
        // Load data statistik untuk chart
        $.ajax({
            url: '<?= base_url('kpi/ajax_get_statistik') ?>',
            type: 'GET',
            data: {
                bulan,
                tahun
            },
            success: function(response) {
                if (response.status) {
                    const data = response.data;

                    // Chart Kategori (Pie Chart)
                    const ctxKategori = document.getElementById('chartKategori').getContext('2d');
                    if (chartKategori) chartKategori.destroy();

                    chartKategori = new Chart(ctxKategori, {
                        type: 'pie',
                        data: {
                            labels: ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'],
                            datasets: [{
                                data: [
                                    data.sangat_baik || 0,
                                    data.baik || 0,
                                    data.cukup || 0,
                                    data.kurang || 0,
                                    data.sangat_kurang || 0
                                ],
                                backgroundColor: ['#00a65a', '#00c0ef', '#f39c12', '#dd4b39', '#d73925']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            }
        });

        // Load ranking untuk chart
        $.ajax({
            url: '<?= base_url('kpi/ajax_get_ranking') ?>',
            type: 'GET',
            data: {
                bulan,
                tahun,
                limit: 10
            },
            success: function(response) {
                if (response.status) {
                    const labels = response.data.map(item => item.nama_lengkap);
                    const values = response.data.map(item => parseFloat(item.nilai_kpi_final));

                    const ctxRanking = document.getElementById('chartRanking').getContext('2d');
                    if (chartRanking) chartRanking.destroy();

                    chartRanking = new Chart(ctxRanking, {
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
                }
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

    function hitungKPI() {
        const bulan = $('#filterBulan').val();
        const tahun = $('#filterTahun').val();
        const unit_id = $('#filterUnit').val();

        if (!confirm('Hitung KPI untuk periode ini? Proses ini mungkin memakan waktu.')) {
            return;
        }

        $.ajax({
            url: '<?= base_url('kpi/proses_hitung') ?>',
            type: 'POST',
            data: {
                bulan,
                tahun,
                unit_id
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Menghitung KPI...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'KPI berhasil dihitung'
                });
                loadData();
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menghitung KPI'
                });
            }
        });
    }

    function exportExcel() {
        const bulan = $('#filterBulan').val();
        const tahun = $('#filterTahun').val();
        const unit_id = $('#filterUnit').val();

        window.location.href = `<?= base_url('kpi/export') ?>?bulan=${bulan}&tahun=${tahun}&unit_id=${unit_id}`;
    }
</script>
<style>
    select {
        display: block !important;
    }
</style>