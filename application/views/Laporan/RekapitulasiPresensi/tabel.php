<a class="float-left">
  <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
</a>
<table id="table-print" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>NO</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Jabatan</th>
      <th>Kegiatan</th>
      <th>Cuti</th>
      <th>Tepat Waktu</th>
      <th>Terlambat / Pulang awal</th>
      <th>Jumlah Presensi</th>
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
      $terlambat = 0;
      $tepat = 0;
      $total_detik = 0; // Total waktu kerja dalam detik
      $total_detik_jadwal = 0; // Total waktu jadwal dalam detik
      $efektif_jam = 0;
      $efektif_menit = 0;
      $pulang_awal = 0;
      $tidak_valid = 0;

      foreach ($this->ModelLaporan->rekapPresensi($data->uuid, $tgl_mulai, $tgl_akhir)->result() as $value) {
        $s_terlambat = 0;
        $s_tepat = 0;
        $hari = date("D", strtotime($value->waktu));
        $presensi_pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi)->row_array();
        $hari_libur = $this->ModelLibur->getDataLibur(date("d-m-Y", strtotime($value->waktu)))->num_rows();
        $jadwal_masuk = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();

        if (@$presensi_pulang['waktu'] != null && $value->status_absensi != 2) {
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
            // Hitung WFO/WFH
            if ($value->jenis_tempat == 1) {
              $wfo += 1;
            } elseif ($value->jenis_tempat == 2) {
              $wfh += 1;
            }

            // Cek keterlambatan
            $jam_jadwal  = strtotime($value->jam_jadwal);
            $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
            $diff  = $masuk - $jam_jadwal;

            if ($diff <= 0) {
              $s_tepat = 1;
            } else {
              $jam_toleransi = $value->jam_toleransi;
              if ($jam_toleransi == null || $jam_toleransi == "") {
                $jam_toleransi = $jadwal_masuk['toleransi_kedatangan'];
              }
              $toleransi = strtotime(date("H:i:s", strtotime($jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
              if ($diff <= $toleransi) {
                $s_tepat = 1;
              } else {
                $s_terlambat = 1;
              }
            }

            // Cek pulang awal
            $jam_jadwal_pulang = strtotime($jadwal_masuk['jam_pulang']);
            $pulang = strtotime(date("H:i:s", strtotime($presensi_pulang['waktu'])));
            $diff_pulang = $pulang - $jam_jadwal_pulang;

            if ($s_tepat == 1 && $diff_pulang >= 0) {
              $tepat += 1;
            } else {
              if ($diff_pulang > 0 && $s_terlambat == 1) {
                $tidak_valid += 1;
              } else if ($s_terlambat == 1) {
                $terlambat += 1;
              } else if ($diff_pulang < 0) {
                $pulang_awal += 1;
              }
            }

            // Hitung total jam kerja aktual (masuk - pulang) dengan istirahat 1 jam
            $waktu_masuk = strtotime($value->waktu);
            $waktu_pulang = strtotime($presensi_pulang['waktu']);
            $durasi_kerja_detik = $waktu_pulang - $waktu_masuk;

            // Kurangi 1 jam istirahat (3600 detik)
            $durasi_kerja_detik = max(0, $durasi_kerja_detik - 3600);
            $total_detik += $durasi_kerja_detik;

            // Hitung jam kerja sesuai jadwal (untuk perhitungan overtime)
            $jam_jadwal_masuk_time = strtotime($jadwal_masuk['jam_masuk']);
            $jam_jadwal_pulang_time = strtotime($jadwal_masuk['jam_pulang']);
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

      $jumlah_presensi = $wfo + $wfh;
      ?>
      <?php if ($jumlah_presensi > 0 || $tidak_valid > 0): ?>
        <tr>
          <td><?php echo $no++ ?></td>
          <td><?php echo $data->NIP ?></td>
          <td><?php echo $data->nama_pegawai ?></td>
          <td><?php echo $data->jab_struktur ?></td>
          <td><?php echo $this->ModelLaporan->rekapKegiatan($data->uuid, $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
          <td><?php echo $this->ModelPerizinan->get_riwayat($data->uuid, "1", $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
          <td><?php echo $tepat ?></td>
          <td><?php echo $terlambat + $pulang_awal ?></td>
          <td><?php echo $jumlah_presensi ?></td>
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
      <?php endif; ?>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="printableArea row" hidden>
  <table class="col-12" border="0">
    <tr>
      <td align="center">
        <h1>Rekapitulasi Presensi Pegawai</h1>
      </td>
    </tr>
  </table>
  <div class="col-6">
    <table width="100%" border="0">
      <tr>
        <td>Unit </td>
        <td>: <?php echo $unit ?></td>
      </tr>
      <tr>
        <td>Tanggal </td>
        <td>: <?php echo date("d-m-Y", strtotime($tgl_mulai)) ?></td>
      </tr>
      <tr>
        <td>Sampai Tanggal</td>
        <td>: <?php echo date("d-m-Y", strtotime($tgl_akhir)) ?></td>
      </tr>
    </table>
  </div>
  <div class="col-3">
    <!-- <table width="100%" border="0">
      <tr>
        <td>Tepat Waktu</td>
        <td>: <b class="txtTW"></b></td>
      </tr>
      <tr>
        <td>Toleransi</td>
        <td>: <b class="txtTO"></b></td>
      </tr>
      <tr>
        <td>Terlambat</td>
        <td>: <b class="txtTE"></b></td>
      </tr>
    </table> -->
  </div>
  <div class="col-12">
    <br><br>
    <table id="table-print" class="display nowrap table table-hover table-striped table-bordered">
      <thead>
        <tr>
          <th>NO</th>
          <th>NIP</th>
          <th>Nama</th>
          <th>Jabatan</th>
          <th>Tepat Waktu</th>
          <th>Terlambat / Pulang awal</th>
          <th>Tidak Valid</th>
          <th>Jumlah Presensi</th>
          <th>Total Jam</th>
          <th>Kegiatan</th>
          <th>Cuti</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1;
        foreach ($pegawai->result() as $data): ?>
          <?php
          $total = 0;
          $wfh = 0;
          $wfo = 0;
          $terlambat = 0;
          $tepat = 0;
          $total_detik = 0;
          $pulang_awal = 0;
          $tidak_valid = 0;

          foreach ($this->ModelLaporan->rekapPresensi($data->uuid, $tgl_mulai, $tgl_akhir)->result() as $value) {
            $s_terlambat = 0;
            $s_tepat = 0;
            $hari = date("D", strtotime($value->waktu));
            $presensi_pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi)->row_array();
            $hari_libur = $this->ModelLibur->getDataLibur(date("d-m-Y", strtotime($value->waktu)))->num_rows();
            $jadwal_masuk = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();

            if (@$presensi_pulang['waktu'] != null) {
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
                // Hitung WFO/WFH
                if ($value->jenis_tempat == 1) {
                  $wfo += 1;
                } elseif ($value->jenis_tempat == 2) {
                  $wfh += 1;
                }

                // Cek keterlambatan
                $jam_jadwal  = strtotime($value->jam_jadwal);
                $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
                $diff  = $masuk - $jam_jadwal;

                if ($diff <= 0) {
                  $s_tepat = 1;
                } else {
                  $jam_toleransi = $value->jam_toleransi;
                  if ($jam_toleransi == null || $jam_toleransi == "") {
                    $jam_toleransi = $jadwal_masuk['toleransi_kedatangan'];
                  }
                  $toleransi = strtotime(date("H:i:s", strtotime($jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                  if ($diff <= $toleransi) {
                    $s_tepat = 1;
                  } else {
                    $s_terlambat = 1;
                  }
                }

                // Cek pulang awal
                $jam_jadwal_pulang = strtotime($jadwal_masuk['jam_pulang']);
                $pulang = strtotime(date("H:i:s", strtotime($presensi_pulang['waktu'])));
                $diff_pulang = $pulang - $jam_jadwal_pulang;

                if ($s_tepat == 1 && $diff_pulang >= 0) {
                  $tepat += 1;
                } else {
                  if ($diff_pulang > 0 && $s_terlambat == 1) {
                    $tidak_valid += 1;
                  } else if ($s_terlambat == 1) {
                    $terlambat += 1;
                  } else if ($diff_pulang < 0) {
                    $pulang_awal += 1;
                  }
                }

                // Hitung total jam kerja aktual dengan istirahat 1 jam
                $waktu_masuk = strtotime($value->waktu);
                $waktu_pulang = strtotime($presensi_pulang['waktu']);
                $durasi_kerja_detik = $waktu_pulang - $waktu_masuk;

                // Kurangi 1 jam istirahat (3600 detik)
                $durasi_kerja_detik = max(0, $durasi_kerja_detik - 3600);
                $total_detik += $durasi_kerja_detik;
              }
            } else {
              $tidak_valid += 1;
            }
          }

          // Konversi total detik ke jam dan menit
          $total_jam = floor($total_detik / 3600);
          $total_menit = floor(($total_detik % 3600) / 60);

          $jumlah_presensi = $wfo + $wfh + $tidak_valid;
          ?>
          <?php if ($jumlah_presensi > 0): ?>
            <tr>
              <td><?php echo $no++ ?></td>
              <td><?php echo $data->NIP ?></td>
              <td><?php echo $data->nama_pegawai ?></td>
              <td><?php echo $data->jab_struktur ?></td>
              <td><?php echo $tepat ?></td>
              <td><?php echo $terlambat + $pulang_awal ?></td>
              <td><?php echo $tidak_valid ?></td>
              <td><?php echo $jumlah_presensi ?></td>
              <td><?php echo $total_jam . " Jam " . $total_menit . " Menit"; ?></td>
              <td><?php echo $this->ModelLaporan->rekapKegiatan($data->uuid, $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
              <td><?php echo $this->ModelPerizinan->get_riwayat($data->uuid, "1", $tgl_mulai, $tgl_akhir)->num_rows() ?></td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="col-8">

  </div>
  <div class="col-4 text-center">
    <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
    Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA
    <br>
    <br>
    <br>
    <br>
    <?php echo $ttd['nama_pegawai'] ?>
    <br>
    <?php echo $ttd['NIP'] ?>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#print").click(function() {
      var mode = 'iframe'; //popup
      var close = mode == "popup";
      var options = {
        mode: mode,
        popClose: close
      };
      $("div.printableArea").printArea(options);
    });
  });
</script>