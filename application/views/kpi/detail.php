<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Detail KPI Pegawai
            <small><?= $pegawai['nama_lengkap'] ?></small>
        </h1>
    </section>

    <section class="content">
        <!-- Info Pegawai -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> Informasi Pegawai</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">NIP:</th>
                                <td><?= $pegawai['nip'] ?></td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap:</th>
                                <td><?= $pegawai['nama_lengkap'] ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?= $pegawai['email'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">No. HP:</th>
                                <td><?= $pegawai['no_hp'] ?></td>
                            </tr>
                            <tr>
                                <th>Departemen:</th>
                                <td>
                                    <?php
                                    $unit = $this->db->get_where('unit', ['id' => $pegawai['unit_id']])->row_array();
                                    echo $unit['nama_unit'] ?? '-';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Jabatan:</th>
                                <td>
                                    <?php
                                    $jabatan = $this->db->get_where('jabatan', ['id' => $pegawai['jabatan_id']])->row_array();
                                    echo $jabatan['nama_jabatan'] ?? '-';
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- History KPI -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history"></i> Riwayat KPI</h3>
            </div>
            <div class="box-body">
                <?php if (empty($kpi_history)): ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Belum ada data KPI untuk pegawai ini.
                    </div>
                <?php else: ?>
                    <!-- Chart Trend KPI -->
                    <div class="row">
                        <div class="col-md-12">
                            <canvas id="chartTrendKPI" height="80"></canvas>
                        </div>
                    </div>

                    <hr>

                    <!-- Tabel Detail -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Presensi</th>
                                    <th>Kegiatan</th>
                                    <th>Cuti</th>
                                    <th>Pekerjaan</th>
                                    <th>Dinas Luar</th>
                                    <th>KPI Final</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Hitung</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kpi_history as $kpi): ?>
                                    <?php
                                    $periode = date('F Y', mktime(0, 0, 0, $kpi['periode_bulan'], 1, $kpi['periode_tahun']));
                                    $badgeClass = '';
                                    if ($kpi['nilai_kpi_final'] >= 90) $badgeClass = 'bg-green';
                                    elseif ($kpi['nilai_kpi_final'] >= 80) $badgeClass = 'bg-light-blue';
                                    elseif ($kpi['nilai_kpi_final'] >= 70) $badgeClass = 'bg-yellow';
                                    elseif ($kpi['nilai_kpi_final'] >= 60) $badgeClass = 'bg-orange';
                                    else $badgeClass = 'bg-red';
                                    ?>
                                    <tr>
                                        <td><?= $periode ?></td>
                                        <td><?= number_format($kpi['nilai_presensi'], 2) ?></td>
                                        <td><?= number_format($kpi['nilai_kegiatan'], 2) ?></td>
                                        <td><?= number_format($kpi['nilai_cuti'], 2) ?></td>
                                        <td><?= number_format($kpi['nilai_pekerjaan'], 2) ?></td>
                                        <td><?= number_format($kpi['nilai_dinas_luar'], 2) ?></td>
                                        <td><strong><?= number_format($kpi['nilai_kpi_final'], 2) ?></strong></td>
                                        <td>
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= $kpi['kategori_kinerja'] ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($kpi['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <a href="<?= base_url('kpi') ?>" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </section>
</div>

<?php if (!empty($kpi_history)): ?>
    <script>
        $(document).ready(function() {
            // Data untuk chart
            const labels = <?= json_encode(array_map(function ($kpi) {
                                return date('M Y', mktime(0, 0, 0, $kpi['periode_bulan'], 1, $kpi['periode_tahun']));
                            }, array_reverse($kpi_history))) ?>;

            const dataKPI = <?= json_encode(array_map(function ($kpi) {
                                return floatval($kpi['nilai_kpi_final']);
                            }, array_reverse($kpi_history))) ?>;

            const dataPresensi = <?= json_encode(array_map(function ($kpi) {
                                        return floatval($kpi['nilai_presensi']);
                                    }, array_reverse($kpi_history))) ?>;

            const dataKegiatan = <?= json_encode(array_map(function ($kpi) {
                                        return floatval($kpi['nilai_kegiatan']);
                                    }, array_reverse($kpi_history))) ?>;

            const dataCuti = <?= json_encode(array_map(function ($kpi) {
                                    return floatval($kpi['nilai_cuti']);
                                }, array_reverse($kpi_history))) ?>;

            const dataPekerjaan = <?= json_encode(array_map(function ($kpi) {
                                        return floatval($kpi['nilai_pekerjaan']);
                                    }, array_reverse($kpi_history))) ?>;

            const dataDinasLuar = <?= json_encode(array_map(function ($kpi) {
                                        return floatval($kpi['nilai_dinas_luar']);
                                    }, array_reverse($kpi_history))) ?>;

            // Create chart
            const ctx = document.getElementById('chartTrendKPI').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'KPI Final',
                            data: dataKPI,
                            borderColor: '#00a65a',
                            backgroundColor: 'rgba(0, 166, 90, 0.1)',
                            borderWidth: 3,
                            tension: 0.4
                        },
                        {
                            label: 'Presensi',
                            data: dataPresensi,
                            borderColor: '#3c8dbc',
                            borderWidth: 2,
                            tension: 0.4,
                            hidden: true
                        },
                        {
                            label: 'Kegiatan',
                            data: dataKegiatan,
                            borderColor: '#f39c12',
                            borderWidth: 2,
                            tension: 0.4,
                            hidden: true
                        },
                        {
                            label: 'Cuti',
                            data: dataCuti,
                            borderColor: '#dd4b39',
                            borderWidth: 2,
                            tension: 0.4,
                            hidden: true
                        },
                        {
                            label: 'Pekerjaan',
                            data: dataPekerjaan,
                            borderColor: '#605ca8',
                            borderWidth: 2,
                            tension: 0.4,
                            hidden: true
                        },
                        {
                            label: 'Dinas Luar',
                            data: dataDinasLuar,
                            borderColor: '#00c0ef',
                            borderWidth: 2,
                            tension: 0.4,
                            hidden: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
<?php endif; ?>