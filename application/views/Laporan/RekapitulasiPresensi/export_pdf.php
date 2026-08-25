<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekapitulasi Presensi Pegawai</title>
  <style>
    @page { size: A4 landscape; margin: 10mm; }
    * { box-sizing: border-box; }
    body {
      font-family: Arial, sans-serif;
      font-size: 10px;
      margin: 0;
      padding: 0;
      color: #000;
    }
    .header { text-align: center; margin-bottom: 6px; }
    .header h2 { margin: 0 0 2px 0; font-size: 14px; }
    .info-table { width: 50%; border: none; margin-bottom: 8px; font-size: 10px; }
    .info-table td { padding: 1px 4px; border: none; }
    table.rekap {
      width: 100%;
      border-collapse: collapse;
      font-size: 9px;
    }
    table.rekap th, table.rekap td {
      border: 1px solid #333;
      padding: 3px 4px;
      white-space: nowrap;
      text-align: center;
    }
    table.rekap th {
      background-color: #d0e4f7;
      font-weight: bold;
    }
    table.rekap td.text-left { text-align: left; }
    tr:nth-child(even) { background-color: #f5f5f5; }
    .ttd {
      margin-top: 30px;
      float: right;
      width: 260px;
      text-align: center;
      font-size: 10px;
    }
    .print-btn {
      position: fixed;
      top: 10px;
      right: 10px;
      padding: 6px 14px;
      background: #17a2b8;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 12px;
      cursor: pointer;
    }
    @media print {
      .print-btn { display: none; }
      @page { size: A4 landscape; margin: 10mm; }
    }
  </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">&#128424; Print / Simpan PDF</button>

<div class="header">
  <h2>Rekapitulasi Presensi Pegawai</h2>
</div>

<table class="info-table">
  <tr><td width="110">Unit</td><td>: <?php echo $unit ?></td></tr>
  <tr><td>Tanggal</td><td>: <?php echo date("d-m-Y", strtotime($tgl_mulai)) ?></td></tr>
  <tr><td>Sampai Tanggal</td><td>: <?php echo date("d-m-Y", strtotime($tgl_akhir)) ?></td></tr>
</table>

<table class="rekap">
  <thead>
    <tr>
      <th rowspan="2">NO</th>
      <th rowspan="2">NIP</th>
      <th rowspan="2">Nama</th>
      <th rowspan="2">Jabatan</th>
      <th rowspan="2">Kegiatan</th>
      <th rowspan="2">Cuti</th>
      <th colspan="3">Kehadiran</th>
      <th colspan="4">Ketepatan</th>
      <th colspan="5">Akumulasi</th>
      <th colspan="2">Waktu Kerja</th>
    </tr>
    <tr>
      <th>Target</th>
      <th>Hadir</th>
      <th>% Masuk</th>
      <th>Tepat</th>
      <th>Toleransi</th>
      <th>Telat</th>
      <th>% Telat</th>
      <th>Plg Awal</th>
      <th>Datang</th>
      <th>Pulang</th>
      <th>Libur</th>
      <th>Tdk Valid</th>
      <th>Total Jam</th>
      <th>Lembur</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1;
    foreach ($pegawai->result() as $data):
      $wfh = 0; $wfo = 0; $jml_pulang = 0; $jml_datang = 0;
      $terlambat = 0; $toleransi = 0; $tepat = 0;
      $total_detik = 0; $total_detik_jadwal = 0;
      $pulang_awal = 0; $tidak_valid = 0;

      $libur = $this->ModelLibur->getDataLiburPegawai($data->uuid, $tgl_mulai, $tgl_akhir)->num_rows();

      foreach ($this->ModelLaporan->rekapPresensi($data->uuid, $tgl_mulai, $tgl_akhir)->result() as $value):
        $jml_datang += 1;
        $hari = date("D", strtotime($value->waktu));
        $presensi_pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi)->row_array();
        $hari_libur = $this->ModelLibur->getDataLibur(date("d-m-Y", strtotime($value->waktu)))->num_rows();
        $jadwal_masuk = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();

        $jam_jadwal_rkp    = strtotime($value->jam_jadwal);
        $masuk_rkp         = strtotime(date("H:i:s", strtotime($value->waktu)));
        $diff_rkp          = $masuk_rkp - $jam_jadwal_rkp;
        $jam_toleransi_rkp = $value->jam_toleransi;
        if ($jam_toleransi_rkp == null || $jam_toleransi_rkp == "") {
          $jam_toleransi_rkp = ($jadwal_masuk != null) ? $jadwal_masuk['toleransi_kedatangan'] : null;
        }
        if ($diff_rkp <= 0) {
          $status_ketepatan = 1;
        } elseif ($jam_toleransi_rkp != null && $jam_toleransi_rkp != "" && $masuk_rkp <= strtotime($jam_toleransi_rkp)) {
          $status_ketepatan = 2;
        } else {
          $status_ketepatan = 3;
        }

        if ($status_ketepatan == 1) {
          $tepat += 1;
        } elseif ($status_ketepatan == 2) {
          $toleransi += 1;
        } else {
          $terlambat += 1;
        }

        if (@$presensi_pulang['waktu'] != null && $value->status_absensi != 2) {
          $jml_pulang += 1;
          $kerja_libur = false;
          if ($hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
            if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
              $kerja_libur = true;
            }
          } else {
            $kerja_libur = true;
          }

          if ($kerja_libur) {
            if ($value->jenis_tempat == 1)      $wfo += 1;
            elseif ($value->jenis_tempat == 2)  $wfh += 1;
            elseif ($value->jenis_tempat == 3)  $wfo += 1;

            $jam_jadwal_pulang = ($jadwal_masuk != null && $jadwal_masuk['jam_pulang'] != null) ? strtotime($jadwal_masuk['jam_pulang']) : 0;
            $pulang_time = strtotime(date("H:i:s", strtotime($presensi_pulang['waktu'])));
            $diff_pulang = $pulang_time - $jam_jadwal_pulang;

            if ($status_ketepatan == 1 && $diff_pulang >= 0) {
              // valid
            } else {
              if ($diff_pulang < 0) $pulang_awal += 1;
              $tidak_valid += 1;
            }

            $waktu_masuk = strtotime($value->waktu);
            $waktu_pulang = strtotime($presensi_pulang['waktu']);
            $total_detik += max(0, ($waktu_pulang - $waktu_masuk) - 3600);

            $jam_jadwal_masuk_time  = ($jadwal_masuk != null && $jadwal_masuk['jam_masuk'] != null) ? strtotime($jadwal_masuk['jam_masuk']) : 0;
            $jam_jadwal_pulang_time = ($jadwal_masuk != null && $jadwal_masuk['jam_pulang'] != null) ? strtotime($jadwal_masuk['jam_pulang']) : 0;
            $total_detik_jadwal += max(0, ($jam_jadwal_pulang_time - $jam_jadwal_masuk_time) - 3600);
          }
        } else {
          $tidak_valid += 1;
        }
      endforeach;

      $total_jam   = floor($total_detik / 3600);
      $total_menit = floor(($total_detik % 3600) / 60);
      $overtime_detik = max(0, $total_detik - $total_detik_jadwal);
      $jam_over    = floor($overtime_detik / 3600);
      $menit_over  = floor(($overtime_detik % 3600) / 60);
      $jml_presensi = $tepat + $toleransi + $terlambat;

      // Hitung Target Masuk Bulanan dari target_presensi
      $target_masuk = 0;
      $target_cache = [];
      $curr_target  = strtotime(date('Y-m-01', strtotime($tgl_mulai)));
      $last_target  = strtotime(date('Y-m-01', strtotime($tgl_akhir)));
      while ($curr_target <= $last_target) {
        $thn = date('Y', $curr_target);
        $bln = (int)date('n', $curr_target);
        if (!isset($target_cache[$thn])) {
          $target_cache[$thn] = $this->ModelTargetPresensi->get_by_pegawai_tahun($data->uuid, $thn);
        }
        if (!empty($target_cache[$thn])) {
          $key = 'bulan_' . $bln;
          $target_masuk += isset($target_cache[$thn][$key]) ? (int)$target_cache[$thn][$key] : 0;
        }
        $curr_target = strtotime('+1 month', $curr_target);
      }

      // Persentase Total Masuk dari Target Masuk Bulanan
      $persen_masuk = ($target_masuk > 0) ? round(($jml_presensi / $target_masuk) * 100, 2) . '%' : '-';

      // Persentase Keterlambatan dari Total Masuk
      $persen_terlambat = ($jml_presensi > 0) ? round(($terlambat / $jml_presensi) * 100, 2) . '%' : '0%';
    ?>
    <tr>
      <td><?php echo $no++ ?></td>
      <td><?php echo $data->NIP ?></td>
      <td class="text-left"><?php echo $data->nama_pegawai ?></td>
      <td class="text-left"><?php echo $data->namajabatan ?></td>
      <td><?php echo $this->ModelLaporan->rekapKegiatan($data->uuid, $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
      <td><?php echo $this->ModelPerizinan->get_riwayat($data->uuid, "1", $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
      <td><?php echo $target_masuk > 0 ? $target_masuk : '-' ?></td>
      <td><?php echo $jml_presensi ?></td>
      <td><?php echo $persen_masuk ?></td>
      <td><?php echo $tepat ?></td>
      <td><?php echo $toleransi ?></td>
      <td><?php echo $terlambat ?></td>
      <td><?php echo $persen_terlambat ?></td>
      <td><?php echo $pulang_awal ?></td>
      <td><?php echo $jml_datang ?></td>
      <td><?php echo $jml_pulang ?></td>
      <td><?php echo $libur ?></td>
      <td><?php echo $tidak_valid ?></td>
      <td><?php echo $total_jam . "j " . $total_menit . "m" ?></td>
      <td><?php echo ($jam_over > 0 || $menit_over > 0) ? $jam_over . "j " . $menit_over . "m" : "-" ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="ttd">
  <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
  Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA
  <br><br><br><br>
  <?php echo isset($ttd['nama_pegawai']) ? $ttd['nama_pegawai'] : '-' ?>
  <br>
  <?php echo isset($ttd['NIP']) ? $ttd['NIP'] : '-' ?>
</div>

</body>
</html>
