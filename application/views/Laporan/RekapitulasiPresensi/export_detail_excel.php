<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>

    <!-- Header Info Pegawai -->
    <table border="0" cellpadding="3">
        <tr>
            <td colspan="4"><strong>DETAIL REKAPITULASI PRESENSI PEGAWAI</strong></td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td width="120">NIP</td>
            <td>: <?php echo $pegawai['NIP']; ?></td>
            <td width="120">Unit</td>
            <td>: <?php echo $pegawai['unit']; ?></td>
        </tr>
        <tr>
            <td>Nama Pegawai</td>
            <td>: <?php echo $pegawai['nama_pegawai']; ?></td>
            <td>Periode</td>
            <td>: <?php echo date("d-m-Y", strtotime($tgl_mulai)); ?> s/d <?php echo date("d-m-Y", strtotime($tgl_akhir)); ?></td>
        </tr>
        <tr>
            <td>Email SSO</td>
            <td>: <?php echo $pegawai['email']; ?></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <br>

    <!-- ==================== TABEL PRESENSI ==================== -->
    <table border="0" cellpadding="3">
        <tr>
            <td><strong>DATA PRESENSI</strong></td>
        </tr>
    </table>
    <table border="1" cellpadding="4" cellspacing="0">
        <thead>
            <tr style="background-color:#2d6a9f; color:#ffffff;">
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Istirahat</th>
                <th>Jam Pulang</th>
                <th>Status Ketepatan Waktu</th>
                <th>Status Approval</th>
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
                $istirahat_data = $this->ModelRiwayat->get_Absensi_Istirahat($value->pegawai_uuid, date("Y-m-d", strtotime($value->waktu)));
                $jam_istirahat_str = "Kosong";
                if ($istirahat_data->num_rows() > 0) {
                    $ist = $istirahat_data->row_array();
                    $jam_istirahat_str = date("H:i:s", strtotime($ist['waktu']));
                    $selesaiIst = $this->ModelRiwayat->get_Selesai_Istirahat($ist["idabsensi"]);
                    if ($selesaiIst->num_rows() > 0) {
                        $sIst = $selesaiIst->row_array();
                        $jam_istirahat_str .= " - " . date("H:i:s", strtotime($sIst['waktu']));
                    } else {
                        $jam_istirahat_str .= " (belum selesai)";
                    }
                }

                // Status keterlambatan
                $status_waktu_str = "-";
                $jam_jadwal_ts = strtotime($value->jam_jadwal);
                $masuk_ts = strtotime(date("H:i:s", strtotime($value->waktu)));
                $diff_masuk = $masuk_ts - $jam_jadwal_ts;
                if ($diff_masuk <= 0) {
                    $s_tepat = 1;
                    $status_waktu_str = "Tepat Waktu";
                } else {
                    $jam_toleransi = $value->jam_toleransi;
                    if (!$jam_toleransi) {
                        $jadwal_row = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
                        $jam_toleransi = $jadwal_row['toleransi_kedatangan'] ?? null;
                    }
                    if ($jam_toleransi && $masuk_ts <= strtotime($jam_toleransi)) {
                        $s_tepat = 1;
                        $status_waktu_str = "Toleransi";
                    } else {
                        $s_terlambat = 1;
                        $status_waktu_str = "Terlambat";
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
                if ($value->status_absensi == 1) $status_approval_str = "Sudah Disetujui";
                elseif ($value->status_absensi == 2) $status_approval_str = "Ditolak";
                else $status_approval_str = "Belum Disetujui";
            ?>
                <tr>
                    <td align="center"><?php echo $no++; ?></td>
                    <td><?php echo date("d-m-Y", strtotime($value->waktu)); ?></td>
                    <td align="center"><?php echo date("H:i:s", strtotime($value->waktu)); ?></td>
                    <td align="center"><?php echo $jam_istirahat_str; ?></td>
                    <td align="center"><?php echo @$absenpulang['waktu'] ? date("H:i:s", strtotime($absenpulang['waktu'])) : '-'; ?></td>
                    <td align="center"><?php echo $status_waktu_str; ?></td>
                    <td align="center"><?php echo $status_approval_str; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <!-- Ringkasan Presensi -->
    <table border="1" cellpadding="4" cellspacing="0" width="350">
        <tr style="background-color:#ddeeff;">
            <td width="70%"><strong>Presensi Valid</strong></td>
            <td align="center"><strong><?php echo $total_valid; ?></strong></td>
        </tr>
        <tr>
            <td>Terlambat / Pulang Awal</td>
            <td align="center"><?php echo $terlambat_total + $pulang_awal_total; ?></td>
        </tr>
        <tr>
            <td>Tidak Valid</td>
            <td align="center"><?php echo $tidak_valid; ?></td>
        </tr>
    </table>

    <br>

    <!-- ==================== TABEL KEGIATAN ==================== -->
    <?php if (count($kegiatan) > 0): ?>
        <table border="0" cellpadding="3">
            <tr>
                <td><strong>DATA KEGIATAN</strong></td>
            </tr>
        </table>
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr style="background-color:#2d6a9f; color:#ffffff;">
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Waktu Presensi</th>
                    <th>Status Ketepatan</th>
                    <th>Status Approval</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($kegiatan as $value): ?>
                    <tr>
                        <td align="center"><?php echo $no++; ?></td>
                        <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)); ?></td>
                        <td align="center"><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
                        <td align="center">
                            <?php
                            $diff = strtotime(date("H:i:s", strtotime($value->jam_presensi))) - strtotime($value->jam_mulai);
                            if ($diff <= 0) echo "Tepat Waktu";
                            elseif ($diff <= 1800) echo "Toleransi";
                            else echo "Terlambat";
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            if ($value->status_aproval == '1') echo "Disetujui";
                            elseif ($value->status_aproval == '2') echo "Ditolak";
                            else echo "Menunggu Approval";
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
    <?php endif; ?>

    <!-- ==================== TABEL CUTI ==================== -->
    <?php if (count($cuti) > 0): ?>
        <table border="0" cellpadding="3">
            <tr>
                <td><strong>DATA CUTI / PERIZINAN</strong></td>
            </tr>
        </table>
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr style="background-color:#2d6a9f; color:#ffffff;">
                    <th>No</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Akhir</th>
                    <th>Jenis Cuti</th>
                    <th>Alasan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($cuti as $value): ?>
                    <tr>
                        <td align="center"><?php echo $no++; ?></td>
                        <td><?php echo date("d-m-Y", strtotime($value->tanggal_mulai)); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($value->tanggal_akhir)); ?></td>
                        <td><?php echo $value->jenis_izin; ?></td>
                        <td><?php echo $value->alasan; ?></td>
                        <td align="center"><?php echo ($value->status == 1) ? "Disetujui" : "Ditolak"; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
    <?php endif; ?>

    <!-- ==================== TABEL LEMBUR ==================== -->
    <?php if (count($lembur) > 0): ?>
        <table border="0" cellpadding="3">
            <tr>
                <td><strong>DATA LEMBUR</strong></td>
            </tr>
        </table>
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr style="background-color:#2d6a9f; color:#ffffff;">
                    <th>No</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Durasi</th>
                    <th>Status Approval</th>
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
                        <td align="center"><?php echo $no++; ?></td>
                        <td><?php echo date("d-m-Y H:i:s", strtotime($value->jam_presensi)); ?></td>
                        <td><?php echo date("d-m-Y H:i:s", strtotime($value->jam_presensi_selesai)); ?></td>
                        <td><?php echo $jam_l . " Jam " . $menit_l . " Menit"; ?></td>
                        <td align="center">
                            <?php
                            if ($value->status_aproval == '1') echo "Disetujui";
                            elseif ($value->status_aproval == '2') echo "Ditolak";
                            else echo "Menunggu Approval";
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" align="center"><strong>Total Lembur (Disetujui)</strong></td>
                    <td colspan="2"><strong><?php echo floor($total_durasi_lembur / 3600) . " Jam " . floor(($total_durasi_lembur % 3600) / 60) . " Menit"; ?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Tanda Tangan -->
    <br>
    <table border="0" cellpadding="3" width="100%">
        <tr>
            <td width="65%"></td>
            <td align="center">
                <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
                Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA<br>
                <br><br><br><br>
                <strong><?php echo isset($ttd['nama_pegawai']) ? $ttd['nama_pegawai'] : '-'; ?></strong><br>
                <?php echo isset($ttd['NIP']) ? $ttd['NIP'] : '-'; ?>
            </td>
        </tr>
    </table>

</body>

</html>