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
      <th>Tepat Waktu</th>
      <th>Terlambat / Pulang awal</th>
      <th>Tidak Valid</th>
      <th>Jumlah Presensi</th>
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
      $total_jam = 0;
      $total_menit = 0;
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

        // Hitung status ketepatan jam masuk (selalu dihitung, meski tidak absen pulang)
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
          $terlambat += 1;
        }

        if (@$presensi_pulang['waktu'] != null) {
          $kerja_libur = false;
          if ($hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
            if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
              $kerja_libur = true;
            }
          } else {
            $kerja_libur = true;
          }

          if ($kerja_libur) {
            if ($value->jenis_tempat == 1) {
              $wfo += 1;
            } elseif ($value->jenis_tempat == 2) {
              $wfh += 1;
            } elseif ($value->jenis_tempat == 3) {
              $wfo += 1;
            }

            // Cek pulang awal
            $jam_jadwal_pulang = (!empty($jadwal_masuk) && isset($jadwal_masuk['jam_pulang'])) ? strtotime($jadwal_masuk['jam_pulang']) : 0;
            $pulang_time = strtotime(date("H:i:s", strtotime($presensi_pulang['waktu'])));
            $diff_pulang = $pulang_time - $jam_jadwal_pulang;

            if ($status_ketepatan == 1 && $diff_pulang >= 0) {
              $tepat += 1;
            } else {
              if ($diff_pulang < 0) {
                $pulang_awal += 1;
              }
              $tidak_valid += 1;
            }
          }

          $datang = date_create($value->waktu);
          $pulang = date_create($presensi_pulang['waktu']);
          $diff = date_diff($datang, $pulang);

          if ($hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
            if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
              $efektif_jam = $efektif_jam + $diff->h - 1;
              $efektif_menit = $efektif_menit + $diff->i;
            }
          } else {
            $efektif_jam = $efektif_jam + $diff->h - 1;
            $efektif_menit = $efektif_menit + $diff->i;
          }
          $total_jam = $total_jam + $diff->h - 1;
          $total_menit = $total_menit + $diff->i;
        } else {
          $tidak_valid += 1;
        }
      }
      $total_jam = $total_jam + floor($total_menit / 60);
      $total_menit = $total_menit % 60;
      $efektif_jam = $efektif_jam + floor($efektif_menit / 60);
      $efektif_menit = $efektif_menit % 60;
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
          $total_jam = 0;
          $total_menit = 0;
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

            // Hitung status ketepatan jam masuk (selalu dihitung, meski tidak absen pulang)
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
              $terlambat += 1;
            }

            if (@$presensi_pulang['waktu'] != null) {
              $kerja_libur = false;
              if ($hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
                if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
                  $kerja_libur = true;
                }
              } else {
                $kerja_libur = true;
              }

              if ($kerja_libur) {
                if ($value->jenis_tempat == 1) {
                  $wfo += 1;
                } elseif ($value->jenis_tempat == 2) {
                  $wfh += 1;
                } elseif ($value->jenis_tempat == 3) {
                  $wfo += 1;
                }

                // Cek pulang awal
                $jam_jadwal_pulang = (!empty($jadwal_masuk) && isset($jadwal_masuk['jam_pulang'])) ? strtotime($jadwal_masuk['jam_pulang']) : 0;
                $pulang_time = strtotime(date("H:i:s", strtotime($presensi_pulang['waktu'])));
                $diff_pulang = $pulang_time - $jam_jadwal_pulang;

                if ($status_ketepatan == 1 && $diff_pulang >= 0) {
                  $tepat += 1;
                } else {
                  if ($diff_pulang < 0) {
                    $pulang_awal += 1;
                  }
                  $tidak_valid += 1;
                }
              }

              $datang = date_create($value->waktu);
              $pulang = date_create($presensi_pulang['waktu']);
              $diff = date_diff($datang, $pulang);

              if ($hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
                if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
                  $efektif_jam = $efektif_jam + $diff->h - 1;
                  $efektif_menit = $efektif_menit + $diff->i;
                }
              } else {
                $efektif_jam = $efektif_jam + $diff->h - 1;
                $efektif_menit = $efektif_menit + $diff->i;
              }
              $total_jam = $total_jam + $diff->h - 1;
              $total_menit = $total_menit + $diff->i;
            } else {
              $tidak_valid += 1;
            }
          }
          $total_jam = $total_jam + floor($total_menit / 60);
          $total_menit = $total_menit % 60;
          $efektif_jam = $efektif_jam + floor($efektif_menit / 60);
          $efektif_menit = $efektif_menit % 60;
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
    <?php echo !empty($ttd) && isset($ttd['nama_pegawai']) ? $ttd['nama_pegawai'] : '-' ?>
    <br>
    <?php echo !empty($ttd) && isset($ttd['NIP']) ? $ttd['NIP'] : '-' ?>
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