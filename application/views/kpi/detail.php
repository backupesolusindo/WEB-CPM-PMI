<?php
// Ambil KPI terbaru untuk ditampilkan di hero card
$kpi_latest = !empty($kpi_history) ? $kpi_history[0] : null;
$nilai_final = $kpi_latest ? floatval($kpi_latest['nilai_kpi_final']) : 0;

// Tentukan warna & ikon berdasarkan nilai
if ($nilai_final >= 90)      { $color = '#00a65a'; $badge_class = 'bg-green';      $icon = 'fa-trophy';      $label = 'Sangat Baik'; }
elseif ($nilai_final >= 80)  { $color = '#00c0ef'; $badge_class = 'bg-light-blue'; $icon = 'fa-thumbs-up';   $label = 'Baik'; }
elseif ($nilai_final >= 70)  { $color = '#f39c12'; $badge_class = 'bg-yellow';     $icon = 'fa-minus-circle';$label = 'Cukup'; }
elseif ($nilai_final >= 60)  { $color = '#ff851b'; $badge_class = 'bg-orange';     $icon = 'fa-exclamation'; $label = 'Kurang'; }
else                         { $color = '#dd4b39'; $badge_class = 'bg-red';        $icon = 'fa-times-circle';$label = 'Sangat Kurang'; }

// Ambil unit & jabatan
$unit    = $this->db->get_where('unit',    ['idunit'    => $pegawai['unit']])->row_array();
$jabatan = $this->db->get_where('jabatan', ['idjabatan' => $pegawai['jab_struktur']])->row_array();
?>

<style>
.kpi-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 24px;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.kpi-hero::before {
    content: '';
    position: absolute;
    top: -50px; right: -50px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}
.kpi-hero::after {
    content: '';
    position: absolute;
    bottom: -80px; left: -30px;
    width: 250px; height: 250px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
}
.kpi-hero .avatar {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px;
    border: 3px solid rgba(255,255,255,0.3);
    flex-shrink: 0;
}
.kpi-hero .pegawai-name { font-size: 22px; font-weight: 700; margin: 0 0 4px; }
.kpi-hero .pegawai-meta { font-size: 13px; opacity: 0.75; }
.kpi-score-circle {
    width: 110px; height: 110px;
    border-radius: 50%;
    border: 6px solid;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    background: rgba(255,255,255,0.08);
    flex-shrink: 0;
}
.kpi-score-circle .score-num { font-size: 28px; font-weight: 800; line-height: 1; }
.kpi-score-circle .score-lbl { font-size: 11px; opacity: 0.8; margin-top: 2px; }

.komponen-card {
    border-radius: 10px;
    padding: 16px 18px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    margin-bottom: 16px;
    border-left: 4px solid;
    transition: transform .15s;
}
.komponen-card:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(0,0,0,0.1); }
.komponen-card .k-label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: .5px; }
.komponen-card .k-value { font-size: 26px; font-weight: 700; line-height: 1.1; }
.komponen-card .k-bobot { font-size: 11px; color: #aaa; }
.komponen-card .progress { height: 6px; border-radius: 3px; margin-top: 8px; background: #f0f0f0; }
.komponen-card .progress-bar { border-radius: 3px; }

.timeline-kpi { position: relative; padding-left: 20px; }
.timeline-kpi::before {
    content: ''; position: absolute; left: 7px; top: 0; bottom: 0;
    width: 2px; background: #e9ecef;
}
.timeline-item { position: relative; margin-bottom: 20px; }
.timeline-dot {
    position: absolute; left: -20px; top: 4px;
    width: 14px; height: 14px; border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
}
.timeline-card {
    background: #fff;
    border-radius: 8px;
    padding: 14px 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    border: 1px solid #f0f0f0;
}
.timeline-card .tc-periode { font-weight: 600; font-size: 14px; }
.timeline-card .tc-score { font-size: 22px; font-weight: 800; }
.timeline-card .tc-mini { font-size: 11px; color: #999; }
.mini-bar { display: flex; align-items: center; gap: 6px; margin-top: 4px; font-size: 11px; color: #666; }
.mini-bar .bar-wrap { flex: 1; height: 4px; background: #eee; border-radius: 2px; }
.mini-bar .bar-fill  { height: 4px; border-radius: 2px; }

.section-title {
    font-size: 15px; font-weight: 700; color: #333;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 8px; margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}
.section-title i { opacity: .6; }
</style>

<div class="card">
    <div class="card-body">


<!-- Hero Card -->
<div class="kpi-hero">
    <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap; position:relative; z-index:1;">
        <div class="avatar"><i class="fa fa-user"></i></div>
        <div style="flex:1; min-width:180px;">
            <div class="pegawai-name"><?= htmlspecialchars($pegawai['nama_pegawai'] ?? '-') ?></div>
            <div class="pegawai-meta">
                <i class="fa fa-id-card"></i> <?= htmlspecialchars($pegawai['nip'] ?? '-') ?>
                &nbsp;&bull;&nbsp;
                <i class="fa fa-building"></i> <?= htmlspecialchars($unit['nama_unit'] ?? '-') ?>
                &nbsp;&bull;&nbsp;
                <i class="fa fa-briefcase"></i> <?= htmlspecialchars($jabatan['nama_jabatan'] ?? '-') ?>
            </div>
            <?php if ($pegawai['email'] ?? ''): ?>
            <div class="pegawai-meta" style="margin-top:4px;">
                <i class="fa fa-envelope"></i> <?= htmlspecialchars($pegawai['email']) ?>
            </div>
            <?php endif; ?>
        </div>
        <?php if ($kpi_latest): ?>
        <div style="text-align:center;">
            <div class="kpi-score-circle" style="border-color:<?= $color ?>; color:#fff;">
                <div class="score-num"><?= number_format($nilai_final, 1) ?></div>
                <div class="score-lbl">KPI Final</div>
            </div>
            <div style="margin-top:8px;">
                <span class="badge <?= $badge_class ?>" style="font-size:12px; padding:4px 10px;">
                    <i class="fa <?= $icon ?>"></i> <?= $label ?>
                </span>
            </div>
            <div style="font-size:11px; opacity:.6; margin-top:4px;">
                <?= date('F Y', mktime(0,0,0,$kpi_latest['periode_bulan'],1,$kpi_latest['periode_tahun'])) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">

    <!-- Kolom Kiri: Komponen KPI terbaru + Chart -->
    <div class="col-md-8">

        <?php if ($kpi_latest): ?>
        <!-- Komponen KPI Terbaru -->
        <div class="box box-solid" style="border:none; box-shadow:none; background:transparent;">
            <div class="box-body" style="padding:0;">
                <div class="section-title"><i class="fa fa-sliders"></i> Komponen KPI — <?= date('F Y', mktime(0,0,0,$kpi_latest['periode_bulan'],1,$kpi_latest['periode_tahun'])) ?></div>
                <div class="row">
                    <?php
                    $komponen = [
                        ['label'=>'Presensi',   'key'=>'nilai_presensi',   'color'=>'#3c8dbc', 'icon'=>'fa-calendar-check-o'],
                        ['label'=>'Kegiatan',   'key'=>'nilai_kegiatan',   'color'=>'#f39c12', 'icon'=>'fa-tasks'],
                        ['label'=>'Cuti',       'key'=>'nilai_cuti',       'color'=>'#dd4b39', 'icon'=>'fa-plane'],
                        ['label'=>'Pekerjaan',  'key'=>'nilai_pekerjaan',  'color'=>'#605ca8', 'icon'=>'fa-briefcase'],
                        ['label'=>'Dinas Luar', 'key'=>'nilai_dinas_luar', 'color'=>'#00c0ef', 'icon'=>'fa-map-marker'],
                    ];
                    foreach ($komponen as $k):
                        $val = floatval($kpi_latest[$k['key']]);
                    ?>
                    <div class="col-sm-6 col-xs-12">
                        <div class="komponen-card" style="border-left-color:<?= $k['color'] ?>;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                                <div>
                                    <div class="k-label"><i class="fa <?= $k['icon'] ?>"></i> <?= $k['label'] ?></div>
                                    <div class="k-value" style="color:<?= $k['color'] ?>;"><?= number_format($val, 2) ?></div>
                                </div>
                                <div style="font-size:28px; opacity:.12; color:<?= $k['color'] ?>;"><i class="fa <?= $k['icon'] ?>"></i></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width:<?= min($val,100) ?>%; background:<?= $k['color'] ?>;"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Chart Trend -->
        <?php if (!empty($kpi_history)): ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-line-chart"></i> Tren KPI</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="chartTrendKPI" height="100"></canvas>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Kolom Kanan: Timeline Riwayat -->
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history"></i> Riwayat KPI</h3>
            </div>
            <div class="box-body" style="max-height:600px; overflow-y:auto;">
                <?php if (empty($kpi_history)): ?>
                    <div class="alert alert-info" style="margin:0;">
                        <i class="fa fa-info-circle"></i> Belum ada data KPI.
                    </div>
                <?php else: ?>
                <div class="timeline-kpi">
                    <?php foreach ($kpi_history as $kpi):
                        $nf = floatval($kpi['nilai_kpi_final']);
                        if ($nf >= 90)     { $tc = '#00a65a'; $tb = 'bg-green'; }
                        elseif ($nf >= 80) { $tc = '#00c0ef'; $tb = 'bg-light-blue'; }
                        elseif ($nf >= 70) { $tc = '#f39c12'; $tb = 'bg-yellow'; }
                        elseif ($nf >= 60) { $tc = '#ff851b'; $tb = 'bg-orange'; }
                        else               { $tc = '#dd4b39'; $tb = 'bg-red'; }
                    ?>
                    <div class="timeline-item">
                        <div class="timeline-dot" style="color:<?= $tc ?>; background:<?= $tc ?>;"></div>
                        <div class="timeline-card">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div class="tc-periode">
                                    <?= date('M Y', mktime(0,0,0,$kpi['periode_bulan'],1,$kpi['periode_tahun'])) ?>
                                </div>
                                <div class="tc-score" style="color:<?= $tc ?>;"><?= number_format($nf,1) ?></div>
                            </div>
                            <div style="margin-top:2px;">
                                <span class="badge <?= $tb ?>" style="font-size:10px;"><?= $kpi['kategori_kinerja'] ?></span>
                            </div>
                            <!-- Mini bars -->
                            <?php
                            $mini = [
                                ['Presensi',  $kpi['nilai_presensi'],   '#3c8dbc'],
                                ['Kegiatan',  $kpi['nilai_kegiatan'],   '#f39c12'],
                                ['Pekerjaan', $kpi['nilai_pekerjaan'],  '#605ca8'],
                            ];
                            foreach ($mini as [$ml, $mv, $mc]):
                            ?>
                            <div class="mini-bar">
                                <span style="width:58px;"><?= $ml ?></span>
                                <div class="bar-wrap"><div class="bar-fill" style="width:<?= min(floatval($mv),100) ?>%; background:<?= $mc ?>;"></div></div>
                                <span><?= number_format(floatval($mv),1) ?></span>
                            </div>
                            <?php endforeach; ?>
                            <div class="tc-mini" style="margin-top:6px;">
                                <i class="fa fa-clock-o"></i>
                                <?= isset($kpi['created_at']) ? date('d/m/Y', strtotime($kpi['created_at'])) : '-' ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <a href="<?= base_url('kpi/daftar_pegawai') ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Tabel Lengkap -->
<?php if (!empty($kpi_history)): ?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-table"></i> Tabel Riwayat Lengkap</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" style="font-size:13px;">
                <thead class="bg-gray">
                    <tr>
                        <th>Periode</th>
                        <th class="text-center">Presensi</th>
                        <th class="text-center">Kegiatan</th>
                        <th class="text-center">Cuti</th>
                        <th class="text-center">Pekerjaan</th>
                        <th class="text-center">Dinas Luar</th>
                        <th class="text-center">KPI Final</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Dihitung</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kpi_history as $kpi):
                        $nf = floatval($kpi['nilai_kpi_final']);
                        if ($nf >= 90)     $bc = 'bg-green';
                        elseif ($nf >= 80) $bc = 'bg-light-blue';
                        elseif ($nf >= 70) $bc = 'bg-yellow';
                        elseif ($nf >= 60) $bc = 'bg-orange';
                        else               $bc = 'bg-red';
                    ?>
                    <tr>
                        <td><strong><?= date('F Y', mktime(0,0,0,$kpi['periode_bulan'],1,$kpi['periode_tahun'])) ?></strong></td>
                        <td class="text-center"><?= number_format($kpi['nilai_presensi'],2) ?></td>
                        <td class="text-center"><?= number_format($kpi['nilai_kegiatan'],2) ?></td>
                        <td class="text-center"><?= number_format($kpi['nilai_cuti'],2) ?></td>
                        <td class="text-center"><?= number_format($kpi['nilai_pekerjaan'],2) ?></td>
                        <td class="text-center"><?= number_format($kpi['nilai_dinas_luar'],2) ?></td>
                        <td class="text-center"><strong style="font-size:15px;"><?= number_format($nf,2) ?></strong></td>
                        <td class="text-center"><span class="badge <?= $bc ?>"><?= $kpi['kategori_kinerja'] ?></span></td>
                        <td class="text-center text-muted" style="font-size:11px;">
                            <?= isset($kpi['created_at']) ? date('d/m/Y H:i', strtotime($kpi['created_at'])) : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>

</div>
<script>
$(document).ready(function () {
    const reversed = <?= json_encode(array_reverse($kpi_history)) ?>;
    const labels   = reversed.map(k => {
        const d = new Date(k.periode_tahun, k.periode_bulan - 1, 1);
        return d.toLocaleString('id-ID', { month: 'short', year: 'numeric' });
    });

    const datasets = [
        { label: 'KPI Final',   data: reversed.map(k => +k.nilai_kpi_final),   borderColor: '#00a65a', backgroundColor: 'rgba(0,166,90,.1)', borderWidth: 3, tension: .4, fill: true },
        { label: 'Presensi',    data: reversed.map(k => +k.nilai_presensi),    borderColor: '#3c8dbc', borderWidth: 2, tension: .4, hidden: true, fill: false },
        { label: 'Kegiatan',    data: reversed.map(k => +k.nilai_kegiatan),    borderColor: '#f39c12', borderWidth: 2, tension: .4, hidden: true, fill: false },
        { label: 'Cuti',        data: reversed.map(k => +k.nilai_cuti),        borderColor: '#dd4b39', borderWidth: 2, tension: .4, hidden: true, fill: false },
        { label: 'Pekerjaan',   data: reversed.map(k => +k.nilai_pekerjaan),   borderColor: '#605ca8', borderWidth: 2, tension: .4, hidden: true, fill: false },
        { label: 'Dinas Luar',  data: reversed.map(k => +k.nilai_dinas_luar),  borderColor: '#00c0ef', borderWidth: 2, tension: .4, hidden: true, fill: false },
    ];

    new Chart(document.getElementById('chartTrendKPI').getContext('2d'), {
        type: 'line',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { color: 'rgba(0,0,0,.05)' } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${parseFloat(ctx.raw).toFixed(2)}`
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>
