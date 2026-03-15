<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan KPI - <?= $periode ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }

        .badge-green {
            background-color: #00a65a;
        }

        .badge-blue {
            background-color: #00c0ef;
        }

        .badge-yellow {
            background-color: #f39c12;
            color: #333;
        }

        .badge-orange {
            background-color: #ff851b;
        }

        .badge-red {
            background-color: #dd4b39;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f0f0f0;
            border-left: 4px solid #4CAF50;
        }

        .summary-item {
            display: inline-block;
            margin-right: 30px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN KPI (KEY PERFORMANCE INDICATOR)</h1>
        <p>Periode: <?= $periode ?></p>
        <p>Tanggal Cetak: <?= date('d F Y H:i') ?></p>
    </div>

    <?php if (!empty($kpi_data)): ?>
        <!-- Summary -->
        <div class="summary">
            <strong>Ringkasan:</strong><br>
            <div class="summary-item">
                Total Pegawai: <strong><?= count($kpi_data) ?></strong>
            </div>
            <div class="summary-item">
                Rata-rata KPI: <strong><?= number_format(array_sum(array_column($kpi_data, 'nilai_kpi_final')) / count($kpi_data), 2) ?></strong>
            </div>
            <div class="summary-item">
                KPI Tertinggi: <strong><?= number_format(max(array_column($kpi_data, 'nilai_kpi_final')), 2) ?></strong>
            </div>
            <div class="summary-item">
                KPI Terendah: <strong><?= number_format(min(array_column($kpi_data, 'nilai_kpi_final')), 2) ?></strong>
            </div>
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">NIP</th>
                    <th style="width: 20%;">Nama</th>
                    <th style="width: 15%;">Departemen</th>
                    <th style="width: 8%;">Presensi</th>
                    <th style="width: 8%;">Kegiatan</th>
                    <th style="width: 8%;">Cuti</th>
                    <th style="width: 8%;">Pekerjaan</th>
                    <th style="width: 8%;">Dinas</th>
                    <th style="width: 8%;">KPI</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($kpi_data as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nip'] ?></td>
                        <td><?= $row['nama_lengkap'] ?></td>
                        <td><?= $row['departemen'] ?? '-' ?></td>
                        <td><?= number_format($row['nilai_presensi'], 1) ?></td>
                        <td><?= number_format($row['nilai_kegiatan'], 1) ?></td>
                        <td><?= number_format($row['nilai_cuti'], 1) ?></td>
                        <td><?= number_format($row['nilai_pekerjaan'], 1) ?></td>
                        <td><?= number_format($row['nilai_dinas_luar'], 1) ?></td>
                        <td>
                            <strong><?= number_format($row['nilai_kpi_final'], 2) ?></strong>
                            <?php
                            $badge_class = 'badge-red';
                            if ($row['nilai_kpi_final'] >= 90) $badge_class = 'badge-green';
                            elseif ($row['nilai_kpi_final'] >= 80) $badge_class = 'badge-blue';
                            elseif ($row['nilai_kpi_final'] >= 70) $badge_class = 'badge-yellow';
                            elseif ($row['nilai_kpi_final'] >= 60) $badge_class = 'badge-orange';
                            ?>
                            <br><span class="badge <?= $badge_class ?>"><?= $row['kategori_kinerja'] ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Kategori -->
        <div style="margin-top: 30px;">
            <strong>Keterangan Kategori:</strong>
            <ul style="list-style: none; padding: 0;">
                <li><span class="badge badge-green">Sangat Baik</span> : 90 - 100</li>
                <li><span class="badge badge-blue">Baik</span> : 80 - 89</li>
                <li><span class="badge badge-yellow">Cukup</span> : 70 - 79</li>
                <li><span class="badge badge-orange">Kurang</span> : 60 - 69</li>
                <li><span class="badge badge-red">Sangat Kurang</span> : 0 - 59</li>
            </ul>
        </div>

    <?php else: ?>
        <p style="text-align: center; padding: 50px; color: #999;">
            Tidak ada data KPI untuk periode ini.
        </p>
    <?php endif; ?>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Sistem KPI</p>
        <p>&copy; <?= date('Y') ?> - All Rights Reserved</p>
    </div>
</body>

</html>