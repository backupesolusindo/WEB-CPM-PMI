<a class="float-left" id="btn-print-rekap" href="#" target="_blank">
  <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
</a>
<script>
  (function() {
    var base  = "<?php echo base_url(); ?>";
    // Kirim format Y-m-d agar strtotime di controller pasti benar
    var start = "<?php echo $tgl_mulai; ?>";
    var end   = "<?php echo $tgl_akhir; ?>";
    var unit  = encodeURIComponent("<?php echo addslashes($unit == 'Semua Unit' ? '' : $unit); ?>");
    var sub_unit     = (typeof $ !== 'undefined' && $('#sub_unit').length)     ? encodeURIComponent($('#sub_unit').val())     : '';
    var tipe_pegawai = (typeof $ !== 'undefined' && $('#tipe_pegawai').length) ? encodeURIComponent($('#tipe_pegawai').val()) : '';
    var jabatan      = (typeof $ !== 'undefined' && $('#jabatan').length)      ? encodeURIComponent($('#jabatan').val())      : '';
    var url = base + 'Laporan/exportRekapitulasiPresensiPdf'
            + '?start=' + start + '&end=' + end
            + '&unit=' + unit
            + '&sub_unit=' + sub_unit
            + '&tipe_pegawai=' + tipe_pegawai
            + '&jabatan=' + jabatan;
    document.getElementById('btn-print-rekap').href = url;
  })();
</script>
<style>
  #table-print th {
    vertical-align: middle !important;
    text-align: center;
    font-size: 11px;
    padding: 6px 4px;
    white-space: nowrap;
  }
  #table-print td {
    vertical-align: middle !important;
    font-size: 11px;
    padding: 5px 4px;
    white-space: nowrap;
  }
</style>
<table id="table-print" class="display nowrap table table-hover table-striped table-bordered table-sm">
  <thead class="bg-light">
    <tr>
      <th rowspan="2" class="text-center align-middle">NO</th>
      <th rowspan="2" class="text-center align-middle">NIP</th>
      <th rowspan="2" class="align-middle">Nama</th>
      <th rowspan="2" class="align-middle">Jabatan</th>
      <th rowspan="2" class="text-center align-middle">Kegiatan</th>
      <th rowspan="2" class="text-center align-middle">Cuti</th>
      <th colspan="3" class="text-center bg-info text-white">Kehadiran</th>
      <th colspan="4" class="text-center bg-primary text-white">Ketepatan</th>
      <th colspan="5" class="text-center bg-secondary text-white">Akumulasi</th>
      <th colspan="2" class="text-center bg-dark text-white">Waktu Kerja</th>
      <th rowspan="2" class="text-center align-middle">Aksi</th>
    </tr>
    <tr>
      <th class="text-center">Target</th>
      <th class="text-center">Hadir</th>
      <th class="text-center">% Masuk</th>
      <th class="text-center">Tepat</th>
      <th class="text-center">Toleransi</th>
      <th class="text-center">Telat</th>
      <th class="text-center">% Telat</th>
      <th class="text-center">Plg Awal</th>
      <th class="text-center">Datang</th>
      <th class="text-center">Pulang</th>
      <th class="text-center">Libur</th>
      <th class="text-center">Tdk Valid</th>
      <th class="text-center">Total Jam</th>
      <th class="text-center">Lembur</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1;
    foreach ($pegawai->result() as $data): ?>
      <?php
      $total = 0;
      $wfh = 0;
      $wfo = 0;
      $jml_pulang = 0;
      $jml_datang = 0;
      $terlambat = 0;
      $toleransi = 0;
      $tepat = 0;
      $total_detik = 0; // Total waktu kerja dalam detik
      $total_detik_jadwal = 0; // Total waktu jadwal dalam detik
      $efektif_jam = 0;
      $efektif_menit = 0;
      $pulang_awal = 0;
      $tidak_valid = 0;

      $libur = $this->ModelLibur->getDataLiburPegawai($data->uuid, $tgl_mulai, $tgl_akhir)->num_rows();
      foreach ($this->ModelLaporan->rekapPresensi($data->uuid, $tgl_mulai, $tgl_akhir)->result() as $value) {
        $jml_datang += 1;
        $hari = date("D", strtotime($value->waktu));
        $presensi_pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi)->row_array();
        $hari_libur = $this->ModelLibur->getDataLibur(date("d-m-Y", strtotime($value->waktu)))->num_rows();
        $jadwal_masuk = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();

        // Hitung status ketepatan jam masuk (sama persis dengan logika badge di detail rekap)
        $jam_jadwal_rkp    = strtotime($value->jam_jadwal);
        $masuk_rkp         = strtotime(date("H:i:s", strtotime($value->waktu)));
        $diff_rkp          = $masuk_rkp - $jam_jadwal_rkp;
        $jam_toleransi_rkp = $value->jam_toleransi;
        if ($jam_toleransi_rkp == null || $jam_toleransi_rkp == "") {
          $jam_toleransi_rkp = ($jadwal_masuk != null) ? $jadwal_masuk['toleransi_kedatangan'] : null;
        }
        if ($diff_rkp <= 0) {
          $status_ketepatan = 1; // Tepat Waktu
        } elseif ($jam_toleransi_rkp != null && $jam_toleransi_rkp != "" && $masuk_rkp <= strtotime($jam_toleransi_rkp)) {
          $status_ketepatan = 2; // Toleransi
        } else {
          $status_ketepatan = 3; // Terlambat
        }

        // Hitung tepat waktu, toleransi, terlambat — sesuai detail rekap (tanpa filter status_absensi)
        if ($status_ketepatan == 1) {
          $tepat += 1;
        } elseif ($status_ketepatan == 2) {
          $toleransi += 1;
        } else {
          $terlambat += 1;
        }

        if (@$presensi_pulang['waktu'] != null && $value->status_absensi != 2) {
          $jml_pulang += 1;

          // Cek apakah pegawai bekerja di hari libur/weekend
          $kerja_libur = false;
          if ($hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
            if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
              $kerja_libur = true;
            }
          } else {
            $kerja_libur = true;
          }

          if ($kerja_libur) {
            // Hitung WFO/WFH/Mobile Unit
            if ($value->jenis_tempat == 1) {
              $wfo += 1;
            } elseif ($value->jenis_tempat == 2) {
              $wfh += 1;
            } elseif ($value->jenis_tempat == 3) {
              $wfo += 1; // Mobile Unit dihitung sebagai hadir
            }

            // Cek pulang awal
            $jam_jadwal_pulang = ($jadwal_masuk != null && $jadwal_masuk['jam_pulang'] != null) ? strtotime($jadwal_masuk['jam_pulang']) : 0;
            $pulang_time = strtotime(date("H:i:s", strtotime($presensi_pulang['waktu'])));
            $diff_pulang = $pulang_time - $jam_jadwal_pulang;

            if ($status_ketepatan == 1 && $diff_pulang >= 0) {
              // valid
            } else {
              if ($diff_pulang < 0) {
                $pulang_awal += 1;
              }
              $tidak_valid += 1;
            }

            // Hitung total jam kerja aktual (masuk - pulang) dengan istirahat 1 jam
            $waktu_masuk = strtotime($value->waktu);
            $waktu_pulang = strtotime($presensi_pulang['waktu']);
            $durasi_kerja_detik = $waktu_pulang - $waktu_masuk;

            // Kurangi 1 jam istirahat (3600 detik)
            $durasi_kerja_detik = max(0, $durasi_kerja_detik - 3600);
            $total_detik += $durasi_kerja_detik;

            // Hitung jam kerja sesuai jadwal (untuk perhitungan overtime)
            $jam_jadwal_masuk_time  = ($jadwal_masuk != null && $jadwal_masuk['jam_masuk'] != null) ? strtotime($jadwal_masuk['jam_masuk']) : 0;
            $jam_jadwal_pulang_time = ($jadwal_masuk != null && $jadwal_masuk['jam_pulang'] != null) ? strtotime($jadwal_masuk['jam_pulang']) : 0;
            $durasi_jadwal_detik = $jam_jadwal_pulang_time - $jam_jadwal_masuk_time;

            // Kurangi 1 jam istirahat
            $durasi_jadwal_detik = max(0, $durasi_jadwal_detik - 3600);
            $total_detik_jadwal += $durasi_jadwal_detik;
          }
        } else {
          $tidak_valid += 1;
        }
      }

      // Konversi total detik ke jam dan menit
      $total_jam = floor($total_detik / 3600);
      $total_menit = floor(($total_detik % 3600) / 60);

      // Hitung overtime (selisih antara jam kerja aktual dengan jam jadwal)
      $overtime_detik = max(0, $total_detik - $total_detik_jadwal);
      $total_jam_over = floor($overtime_detik / 3600);
      $total_menit_over = floor(($overtime_detik % 3600) / 60);

      // Jml Presensi = tepat waktu + toleransi + terlambat (sesuai detail rekap)
      $jumlah_presensi = $tepat + $toleransi + $terlambat;

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
      $persen_masuk = ($target_masuk > 0) ? round(($jumlah_presensi / $target_masuk) * 100, 1) . '%' : '-';

      // Persentase Keterlambatan dari Total Masuk
      $persen_terlambat = ($jumlah_presensi > 0) ? round(($terlambat / $jumlah_presensi) * 100, 1) . '%' : '0%';
      ?>
      <tr>
        <td class="text-center"><?php echo $no++ ?></td>
        <td class="text-center"><?php echo $data->NIP ?></td>
        <td><?php echo $data->nama_pegawai ?></td>
        <td><?php echo $data->namajabatan ?></td>
        <td class="text-center"><?php echo $this->ModelLaporan->rekapKegiatan($data->uuid, $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
        <td class="text-center"><?php echo $this->ModelPerizinan->get_riwayat($data->uuid, "1", $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
        <td class="text-center"><?php echo $target_masuk > 0 ? $target_masuk : '-' ?></td>
        <td class="text-center font-weight-bold"><?php echo $jumlah_presensi ?></td>
        <td class="text-center font-weight-bold text-primary"><?php echo $persen_masuk ?></td>
        <td class="text-center"><?php echo $tepat ?></td>
        <td class="text-center"><?php echo $toleransi ?></td>
        <td class="text-center <?php echo $terlambat > 0 ? 'text-danger font-weight-bold' : '' ?>"><?php echo $terlambat ?></td>
        <td class="text-center <?php echo $terlambat > 0 ? 'text-danger font-weight-bold' : '' ?>"><?php echo $persen_terlambat ?></td>
        <td class="text-center"><?php echo $pulang_awal ?></td>
        <td class="text-center"><?= $jml_datang ?></td>
        <td class="text-center"><?= $jml_pulang ?></td>
        <td class="text-center"><?php echo $libur ?></td>
        <td class="text-center"><?php echo $tidak_valid ?></td>
        <td class="text-center"><?php echo $total_jam . "j " . $total_menit . "m"; ?></td>
        <td class="text-center"><?php
            if ($total_jam_over > 0 || $total_menit_over > 0) {
              echo $total_jam_over . "j " . $total_menit_over . "m";
            } else {
              echo "-";
            }
            ?></td>
        <td class="text-center">
          <a href="<?php echo base_url() ?>Laporan/DetailRekap/<?php echo $data->uuid; ?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL"><i class="fas fa-info-circle"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>