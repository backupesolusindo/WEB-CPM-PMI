<?php

/**
 * Widget KPI Pegawai
 * Bisa digunakan di dashboard utama atau halaman profil pegawai
 * 
 * Usage:
 * $this->load->view('kpi/widget_kpi_pegawai', ['pegawai_id' => $user_id]);
 */

// Load model jika belum
if (!isset($this->Kpi_model)) {
    $this->load->model('Kpi_model');
}

// Ambil data KPI terbaru
$bulan_sekarang = date('n');
$tahun_sekarang = date('Y');

$kpi_data = $this->Kpi_model->get_kpi_pegawai($pegawai_id, $bulan_sekarang, $tahun_sekarang);
$kpi_terbaru = !empty($kpi_data) ? $kpi_data[0] : null;

// Ambil 6 bulan terakhir untuk trend
$kpi_history = $this->Kpi_model->get_kpi_pegawai($pegawai_id);
$kpi_history = array_slice($kpi_history, 0, 6);
?>

<div class="box box-widget widget-user-2">
    <div class="widget-user-header bg-aqua">
        <h3 class="widget-user-username">KPI Saya</h3>
        <h5 class="widget-user-desc">Periode: <?= date('F Y') ?></h5>
    </div>
    <div class="box-footer no-padding">
        <?php if ($kpi_terbaru): ?>
            <!-- Nilai KPI Final -->
            <div class="text-center" style="padding: 20px;">
                <h1 style="font-size: 48px; margin: 0;">
                    <strong><?= number_format($kpi_terbaru['nilai_kpi_final'], 2) ?></strong>
                </h1>
                <p style="margin: 5px 0;">
                    <?php
                    $badge_class = '';
                    if ($kpi_terbaru['nilai_kpi_final'] >= 90) $badge_class = 'bg-green';
                    elseif ($kpi_terbaru['nilai_kpi_final'] >= 80) $badge_class = 'bg-light-blue';
                    elseif ($kpi_terbaru['nilai_kpi_final'] >= 70) $badge_class = 'bg-yellow';
                    elseif ($kpi_terbaru['nilai_kpi_final'] >= 60) $badge_class = 'bg-orange';
                    else $badge_class = 'bg-red';
                    ?>
                    <span class="badge <?= $badge_class ?>" style="font-size: 14px;">
                        <?= $kpi_terbaru['kategori_kinerja'] ?>
                    </span>
                </p>
            </div>

            <!-- Detail Komponen -->
            <ul class="nav nav-stacked">
                <li>
                    <a href="#">
                        Presensi
                        <span class="pull-right">
                            <strong><?= number_format($kpi_terbaru['nilai_presensi'], 2) ?></strong>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        Kegiatan
                        <span class="pull-right">
                            <strong><?= number_format($kpi_terbaru['nilai_kegiatan'], 2) ?></strong>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        Cuti
                        <span class="pull-right">
                            <strong><?= number_format($kpi_terbaru['nilai_cuti'], 2) ?></strong>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        Pekerjaan
                        <span class="pull-right">
                            <strong><?= number_format($kpi_terbaru['nilai_pekerjaan'], 2) ?></strong>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        Dinas Luar
                        <span class="pull-right">
                            <strong><?= number_format($kpi_terbaru['nilai_dinas_luar'], 2) ?></strong>
                        </span>
                    </a>
                </li>
            </ul>

            <!-- Mini Chart Trend -->
            <?php if (count($kpi_history) > 1): ?>
                <div style="padding: 15px;">
                    <canvas id="miniChartKPI_<?= $pegawai_id ?>" height="80"></canvas>
                </div>

                <script>
                    $(document).ready(function() {
                        const ctx = document.getElementById('miniChartKPI_<?= $pegawai_id ?>').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: <?= json_encode(array_map(function ($kpi) {
                                            return date('M', mktime(0, 0, 0, $kpi['periode_bulan'], 1));
                                        }, array_reverse($kpi_history))) ?>,
                                datasets: [{
                                    label: 'KPI',
                                    data: <?= json_encode(array_map(function ($kpi) {
                                                return floatval($kpi['nilai_kpi_final']);
                                            }, array_reverse($kpi_history))) ?>,
                                    borderColor: '#00a65a',
                                    backgroundColor: 'rgba(0, 166, 90, 0.1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100
                                    }
                                }
                            }
                        });
                    });
                </script>
            <?php endif; ?>

            <!-- Link Detail -->
            <div class="box-footer text-center">
                <a href="<?= base_url('kpi/detail/' . $pegawai_id) ?>" class="btn btn-sm btn-primary">
                    <i class="fa fa-eye"></i> Lihat Detail & History
                </a>
            </div>

        <?php else: ?>
            <!-- Belum Ada Data -->
            <div class="text-center" style="padding: 30px;">
                <i class="fa fa-info-circle fa-3x text-muted"></i>
                <p style="margin-top: 15px;">
                    Belum ada data KPI untuk periode ini.<br>
                    <small class="text-muted">Hubungi admin untuk menghitung KPI Anda.</small>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>