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
<table id="table-print" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>NO</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Jabatan</th>
      <th>Kegiatan</th>
      <th>Cuti</th>
      <th>Jml Presensi</th>
      <th>Tepat Waktu</th>
      <th>Toleransi</th>
      <th>Terlambat</th>
      <th>Pulang Awal</th>
      <th>Jml Datang</th>
      <th>Jml Pulang</th>
      <th>Jml Libur</th>
      <th>Tidak Valid</th>
      <th>Total Jam</th>
      <th>Over Time</th>
      <th>Detail</th>
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
      ?>
      <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $data->NIP ?></td>
        <td><?php echo $data->nama_pegawai ?></td>
        <td><?php echo $data->namajabatan ?></td>
        <td><?php echo $this->ModelLaporan->rekapKegiatan($data->uuid, $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
        <td><?php echo $this->ModelPerizinan->get_riwayat($data->uuid, "1", $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
        <td><?php echo $jumlah_presensi ?></td>
        <td><?php echo $tepat ?></td>
        <td><?php echo $toleransi ?></td>
        <td><?php echo $terlambat ?></td>
        <td><?php echo $pulang_awal ?></td>
        <td><?= $jml_datang ?></td>
        <td><?= $jml_pulang ?></td>
        <td><?php echo $libur ?></td>
        <td><?php echo $tidak_valid ?></td>
        <td><?php echo $total_jam . " Jam " . $total_menit . " Menit"; ?></td>
        <td><?php
            // Tampilkan overtime jika ada, jika tidak tampilkan 0
            if ($total_jam_over > 0 || $total_menit_over > 0) {
              echo $total_jam_over . " Jam " . $total_menit_over . " Menit";
            } else {
              echo "0 Jam 0 Menit";
            }
            ?></td>
        <td>
          <a href="<?php echo base_url() ?>Laporan/DetailRekap/<?php echo $data->uuid; ?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL"><i class="fas fa-info-circle"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>