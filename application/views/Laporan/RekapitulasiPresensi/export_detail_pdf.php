<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Detail Rekapitulasi Presensi - <?php echo $pegawai['nama_pegawai']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
            color: #333;
        }

        h3,
        h4 {
            margin: 0;
        }

        .header-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: top;
            padding: 2px 5px;
        }

        .section-title {
            background: #2d6a9f;
            color: #fff;
            padding: 5px 8px;
            margin: 15px 0 5px;
            font-size: 12px;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.data th {
            background: #2d6a9f;
            color: #fff;
            padding: 5px;
            border: 1px solid #aaa;
            text-align: center;
            font-size: 10px;
        }

        table.data td {
            padding: 4px 6px;
            border: 1px solid #ccc;
            font-size: 10px;
        }

        table.data tr:nth-child(even) {
            background: #f2f7fc;
        }

        .badge-success {
            background: #28a745;
            color: #fff;
            padding: 1px 6px;
            border-radius: 3px;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
            padding: 1px 6px;
            border-radius: 3px;
        }

        .badge-danger {
            background: #dc3545;
            color: #fff;
            padding: 1px 6px;
            border-radius: 3px;
        }

        .badge-info {
            background: #17a2b8;
            color: #fff;
            padding: 1px 6px;
            border-radius: 3px;
        }

        .summary-table {
            width: 40%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .summary-table td {
            padding: 4px 8px;
            border: 1px solid #ccc;
            font-size: 10px;
        }

        .summary-table td:first-child {
            width: 70%;
        }

        .ttd-section {
            margin-top: 40px;
            text-align: right;
            padding-right: 60px;
        }

        .page-break {
            page-break-before: always;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .kop {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="kop">
        <h3>REKAPITULASI DETAIL PRESENSI PEGAWAI</h3>
        <h4>PMI</h4>
    </div>

    <!-- Info Pegawai -->
    <table class="header-table">
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="35%">NIP</td>
                        <td>: <strong><?php echo $pegawai['NIP']; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Nama Pegawai</td>
                        <td>: <strong><?php echo $pegawai['nama_pegawai']; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Email SSO</td>
                        <td>: <?php echo $pegawai['email']; ?></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td width="40%">Unit</td>
                        <td>: <?php echo $pegawai['unit']; ?></td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>: <?php echo date("d-m-Y", strtotime($tgl_mulai)); ?> s/d <?php echo date("d-m-Y", strtotime($tgl_akhir)); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Tabel Presensi -->
    <div class="section-title">DATA PRESENSI</div>
    <table class="data">
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="12%">Tanggal</th>
                <th width="12%">Jam Masuk</th>
                <th width="17%">Istirahat</th>
                <th width="12%">Jam Pulang</th>
                <th width="14%">Status Waktu</th>
                <th width="14%">Status Approval</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_valid = 0;
            $tidak_valid = 0;
            $pulang_awal_total = 0;
            $terlambat_total = 0;
            foreach ($presensi as $value):
                $s_tepat = 0;
                $s_terlambat = 0;
                $validpresensi = 0;
                $absenpulang = $this->ModelRiwayat->Pulang($value->idabsensi)->row_array();

                // Istirahat
                $istirahat = $this->ModelRiwayat->get_Absensi_Istirahat($value->pegawai_uuid, date("Y-m-d", strtotime($value->waktu)));
                $jam_istirahat = "Kosong";
                if ($istirahat->num_rows() > 0) {
                    $ist = $istirahat->row_array();
                    $jam_istirahat = date("H:i:s", strtotime($ist['waktu']));
                    $selesaiIst = $this->ModelRiwayat->get_Selesai_Istirahat($ist["idabsensi"]);
                    if ($selesaiIst->num_rows() > 0) {
                        $sIst = $selesaiIst->row_array();
                        $jam_istirahat = date("H:i:s", strtotime($ist['waktu'])) . " - " . date("H:i:s", strtotime($sIst['waktu']));
                    } else {
                        $jam_istirahat .= " (blm selesai)";
                    }
                }

                // Status keterlambatan
                $status_waktu_label = "-";
                $jam_jadwal_ts = strtotime($value->jam_jadwal);
                $masuk_ts = strtotime(date("H:i:s", strtotime($value->waktu)));
                $diff_masuk = $masuk_ts - $jam_jadwal_ts;
                if ($diff_masuk <= 0) {
                    $s_tepat = 1;
                    $status_waktu_label = '<span class="badge-success">Tepat Waktu</span>';
                } else {
                    $jam_toleransi = $value->jam_toleransi;
                    if (!$jam_toleransi) {
                        $jadwal_row = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
                        $jam_toleransi = $jadwal_row['toleransi_kedatangan'] ?? null;
                    }
                    if ($jam_toleransi && $masuk_ts <= strtotime($jam_toleransi)) {
                        $s_tepat = 1;
                        $status_waktu_label = '<span class="badge-warning">Toleransi</span>';
                    } else {
                        $s_terlambat = 1;
                        $status_waktu_label = '<span class="badge-danger">Terlambat</span>';
                    }
                }

                // Hitung valid/tidak valid
                if (@$absenpulang['waktu'] == null) {
                    $tidak_valid++;
                } else {
                    $jadwal_masuk_row = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
                    $jam_pulang_jadwal = strtotime($jadwal_masuk_row['jam_pulang'] ?? '17:00:00');
                    $pulang_ts = strtotime(date("H:i:s", strtotime($absenpulang['waktu'])));
                    $diff_pulang = $pulang_ts - $jam_pulang_jadwal;
                    if ($value->status_absensi == 2) {
                        $tidak_valid++;
                    } elseif ($s_tepat == 1 && $diff_pulang >= 0) {
                        $validpresensi = 1;
                        $total_valid++;
                    } else {
                        if ($diff_pulang > 0 && $s_terlambat == 1) $tidak_valid++;
                        elseif ($s_terlambat == 1) $terlambat_total++;
                        elseif ($diff_pulang < 0) $pulang_awal_total++;
                    }
                }

                // Status approval
                if ($value->status_absensi == 1) {
                    $status_approval = '<span class="badge-info">Disetujui</span>';
                } elseif ($value->status_absensi == 2) {
                    $status_approval = '<span class="badge-danger">Ditolak</span>';
                } else {
                    $status_approval = '<span class="badge-warning">Belum Disetujui</span>';
                }
            ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><?php echo date("d-m-Y", strtotime($value->waktu)); ?></td>
                    <td class="text-center"><?php echo date("H:i:s", strtotime($value->waktu)); ?></td>
                    <td class="text-center"><?php echo $jam_istirahat; ?></td>
                    <td class="text-center"><?php echo @$absenpulang['waktu'] ? date("H:i:s", strtotime($absenpulang['waktu'])) : '-'; ?></td>
                    <td class="text-center"><?php echo $status_waktu_label; ?></td>
                    <td class="text-center"><?php echo $status_approval; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Ringkasan -->
    <table class="summary-table">
        <tr>
            <td>Presensi Valid</td>
            <td class="text-center"><strong><?php echo $total_valid; ?></strong></td>
        </tr>
        <tr>
            <td>Terlambat / Pulang Awal</td>
            <td class="text-center"><strong><?php echo $terlambat_total + $pulang_awal_total; ?></strong></td>
        </tr>
        <tr>
            <td>Tidak Valid</td>
            <td class="text-center"><strong><?php echo $tidak_valid; ?></strong></td>
        </tr>
    </table>

    <!-- Tabel Kegiatan -->
    <div class="section-title">DATA KEGIATAN</div>
    <table class="data">
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="15%">Tanggal</th>
                <th width="12%">Waktu</th>
                <th width="14%">Status Waktu</th>
                <th width="15%">Status Approval</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($kegiatan as $value): ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)); ?></td>
                    <td class="text-center"><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
                    <td class="text-center">
                        <?php
                        $diff = strtotime(date("H:i:s", strtotime($value->jam_presensi))) - strtotime($value->jam_mulai);
                        if ($diff <= 0) echo '<span class="badge-success">Tepat Waktu</span>';
                        elseif ($diff <= 1800) echo '<span class="badge-warning">Toleransi</span>';
                        else echo '<span class="badge-danger">Terlambat</span>';
                        ?>
                    </td>
                    <td class="text-center">
                        <?php
                        if ($value->status_aproval == '1') echo '<span class="badge-success">Disetujui</span>';
                        elseif ($value->status_aproval == '2') echo '<span class="badge-danger">Ditolak</span>';
                        else echo '<span class="badge-warning">Menunggu</span>';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Tabel Cuti -->
    <div class="section-title">DATA CUTI / PERIZINAN</div>
    <table class="data">
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="14%">Tgl Mulai</th>
                <th width="14%">Tgl Akhir</th>
                <th width="20%">Jenis Cuti</th>
                <th>Alasan</th>
                <th width="12%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($cuti as $value): ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><?php echo date("d-m-Y", strtotime($value->tanggal_mulai)); ?></td>
                    <td><?php echo date("d-m-Y", strtotime($value->tanggal_akhir)); ?></td>
                    <td><?php echo $value->jenis_izin; ?></td>
                    <td><?php echo $value->alasan; ?></td>
                    <td class="text-center">
                        <?php echo ($value->status == 1) ? '<span class="badge-success">Disetujui</span>' : '<span class="badge-warning">Ditolak</span>'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Tabel Lembur -->
    <div class="section-title">DATA LEMBUR</div>
    <table class="data">
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="18%">Jam Mulai</th>
                <th width="18%">Jam Selesai</th>
                <th width="15%">Durasi</th>
                <th width="14%">Status Approval</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_durasi_lembur = 0;
            foreach ($lembur as $value):
                $durasi_detik = strtotime($value->jam_presensi_selesai) - strtotime($value->jam_presensi);
                if ($value->status_aproval == '1') $total_durasi_lembur += $durasi_detik;
                $jam_l = floor($durasi_detik / 3600);
                $menit_l = floor(($durasi_detik % 3600) / 60);
            ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td><?php echo date("d-m-Y H:i:s", strtotime($value->jam_presensi)); ?></td>
                    <td><?php echo date("d-m-Y H:i:s", strtotime($value->jam_presensi_selesai)); ?></td>
                    <td><?php echo $jam_l . " Jam " . $menit_l . " Menit"; ?></td>
                    <td class="text-center">
                        <?php
                        if ($value->status_aproval == '1') echo '<span class="badge-success">Disetujui</span>';
                        elseif ($value->status_aproval == '2') echo '<span class="badge-danger">Ditolak</span>';
                        else echo '<span class="badge-warning">Menunggu</span>';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-center"><strong>Total Lembur (Disetujui)</strong></td>
                <td colspan="2"><strong><?php echo floor($total_durasi_lembur / 3600) . " Jam " . floor(($total_durasi_lembur % 3600) / 60) . " Menit"; ?></strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="ttd-section">
        <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
        <p>Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA</p>
        <br><br><br><br>
        <p><strong><?php echo isset($ttd['nama_pegawai']) ? $ttd['nama_pegawai'] : '-'; ?></strong></p>
        <p><?php echo isset($ttd['NIP']) ? $ttd['NIP'] : '-'; ?></p>
    </div>

</body>
<script>
    $(document).ready(function() {
        window.print();
    });
</script>

</html>