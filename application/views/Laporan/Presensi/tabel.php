<?php
// Hitung jumlah libur pegawai yang tidak hadir (tidak ada presensi)
$txtTW = 0;
$txtTO = 0;
$txtTE = 0;
$txtLP = 0;
$no    = 1;

if (!empty($libur_pegawai_list)) {
  foreach ($libur_pegawai_list as $key => $lp) {
    if (empty($hadir_set[$lp->pegawai_uuid . '_' . $lp->tanggal])) {
      $txtLP++;
    }
  }
}
?>

<div class="row">
  <div class="col-lg-3 col-md-6">
    <div class="card aqua-gradient">
      <div class="card-body">
        <div class="d-flex flex-row">
          <div class="round align-self-center bg-success"><i class="ti-wallet"></i></div>
          <div class="m-l-10 align-self-center">
            <h3 class="m-b-0 text-white txtTW">0</h3>
            <h5 class="m-b-0 text-white">Tepat Waktu</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="card peach-gradient">
      <div class="card-body">
        <div class="d-flex flex-row">
          <div class="round align-self-center bg-warning"><i class="ti-wallet"></i></div>
          <div class="m-l-10 align-self-center">
            <h3 class="m-b-0 text-white txtTO">0</h3>
            <h5 class="m-b-0 text-white">Toleransi</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="card warm-flame-gradient">
      <div class="card-body">
        <div class="d-flex flex-row">
          <div class="round align-self-center bg-danger"><i class="ti-wallet"></i></div>
          <div class="m-l-10 align-self-center">
            <h3 class="m-b-0 text-white txtTE">0</h3>
            <h5 class="m-b-0 text-white">Terlambat</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="card" style="background: linear-gradient(40deg, #fd7e14, #ff5722);">
      <div class="card-body">
        <div class="d-flex flex-row">
          <div class="round align-self-center" style="background-color:#c0520a;"><i class="ti-na"></i></div>
          <div class="m-l-10 align-self-center">
            <h3 class="m-b-0 text-white txtLP">0</h3>
            <h5 class="m-b-0 text-white">Libur Pegawai</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<a class="float-left">
  <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan">
    <i class="fas fa-print"></i> PRINT
  </button>
</a>

<table id="table-print" class="display nowrap table table-hover table-striped table-bordered print-view">
  <thead>
    <tr>
      <th>NO</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Tanggal</th>
      <th>Waktu Datang / Toleransi</th>
      <th>Waktu Pulang</th>
      <th>Status Datang</th>
      <th>Lokasi Presensi</th>
      <th>Detail</th>
    </tr>
  </thead>
  <tbody>

    <?php foreach ($presensi->result() as $value): ?>
      <?php
      $data_jadwal   = @$this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
      $jam_jadwal    = strtotime($value->jam_jadwal);
      $masuk         = strtotime(date("H:i:s", strtotime($value->waktu)));
      $diff          = $masuk - $jam_jadwal;
      $status_presensi = 1;
      $text_status   = "";
      $jam_toleransi = $value->jam_toleransi ?? null;
      if ($jam_toleransi == null || $jam_toleransi == "") {
        $jam_toleransi = ($data_jadwal != null) ? @$data_jadwal['toleransi_kedatangan'] : null;
      }
      if ($diff <= 0) {
        $text_status = '<span class="badge bg-success">Tepat Waktu</span>';
        $txtTW++;
        $status_presensi = 1;
      } elseif ($jam_toleransi != null && $jam_toleransi != "" && $masuk <= strtotime($jam_toleransi)) {
        $text_status = '<span class="badge bg-warning">Toleransi</span>';
        $txtTO++;
        $status_presensi = 2;
      } else {
        $text_status = '<span class="badge bg-danger">Terlambat</span>';
        $txtTE++;
        $status_presensi = 3;
      }
      ?>
      <?php if ($status_filter == $status_presensi || $status_filter == ""): ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td><?php echo $value->NIP; ?></td>
          <td><?php echo $value->nama_pegawai; ?></td>
          <td class="text-center">
            <?php echo date("d-m-Y", strtotime($value->waktu)); ?>
            <br><small><?php echo $data_jadwal['nama'] ?? "Jadwal Kosong"; ?></small>
          </td>
          <td class="text-center">
            <?php echo date("H:i:s", strtotime($value->waktu)); ?>
            <small>/<?php echo $jam_toleransi ? date("H:i", strtotime($jam_toleransi)) : "-"; ?></small>
          </td>
          <td class="text-center">
            <?php
            $cek_pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi);
            if ($cek_pulang->num_rows() > 0): ?>
              <?php echo date("H:i:s", strtotime($cek_pulang->row_array()['waktu'])); ?>
            <?php else: ?>
              Belum Absen Pulang
            <?php endif; ?>
          </td>
          <td><?php echo $text_status; ?></td>
          <td>
            <?php
            if ($value->jenis_tempat == 1) echo 'Dalam Kantor';
            elseif ($value->jenis_tempat == 2) echo 'Luar Kantor';
            else echo 'Mobile Unit';
            ?>
          </td>
          <td>
            <a href="<?php echo base_url(); ?>Laporan/DetailLaporanPresensi/<?php echo $value->idabsensi; ?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL">
              <i class="fas fa-info-circle"></i>
            </a>
            <a href="<?php echo base_url(); ?>Laporan/RealtimeLocatioan/<?php echo $value->uuid; ?>" class="btn-floating btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Realtime Location">
              <i class="fas fa-location-arrow"></i>
            </a>
          </td>
        </tr>
      <?php endif; ?>
    <?php endforeach; ?>

    <?php
    // Baris pegawai libur yang tidak hadir presensi
    if (!empty($libur_pegawai_list) && ($status_filter == "" || $status_filter == "4")):
      foreach ($libur_pegawai_list as $key => $lp):
        if (!empty($hadir_set[$lp->pegawai_uuid . '_' . $lp->tanggal])) continue;
    ?>
        <tr style="background-color:#fff3e0;">
          <td><?php echo $no++; ?></td>
          <td><?php echo $lp->NIP ?? '-'; ?></td>
          <td><?php echo $lp->nama_pegawai; ?></td>
          <td class="text-center">
            <?php echo date("d-m-Y", strtotime($lp->tanggal)); ?>
            <br><small>-</small>
          </td>
          <td class="text-center">-</td>
          <td class="text-center">-</td>
          <td>
            <span class="badge" style="background-color:#fd7e14;color:#fff;">
              <i class="fa fa-calendar-times mr-1"></i>Libur Pegawai
            </span>
            <?php if (!empty($lp->keterangan)): ?>
              <br><small class="text-muted"><?php echo $lp->keterangan; ?></small>
            <?php endif; ?>
          </td>
          <td>-</td>
          <td>-</td>
        </tr>
    <?php
      endforeach;
    endif;
    ?>

  </tbody>
</table>

<!-- ===== PRINTABLE AREA ===== -->
<div class="printableArea row" hidden>
  <table class="col-12" border="0">
    <tr>
      <td align="center">
        <h1>Laporan Presensi</h1>
      </td>
    </tr>
  </table>
  <div class="col-6">
    <table width="100%" border="0">
      <tr>
        <td>Tanggal</td>
        <td>: <?php echo date("d-m-Y", strtotime($tgl_mulai)); ?></td>
      </tr>
      <tr>
        <td>Sampai Tanggal</td>
        <td>: <?php echo date("d-m-Y", strtotime($tgl_akhir)); ?></td>
      </tr>
    </table>
  </div>
  <div class="col-3">
    <table width="100%" border="0">
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
      <tr>
        <td>Libur Pegawai</td>
        <td>: <b class="txtLP"></b></td>
      </tr>
    </table>
  </div>
  <div class="col-12">
    <br><br>
    <div class="table-responsive">
      <table class="display nowrap table table-hover table-striped table-bordered">
        <thead>
          <tr>
            <th>NO</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Waktu Datang</th>
            <th>Status Datang</th>
            <th>Lokasi Presensi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no_print = 1; ?>
          <?php foreach ($presensi->result() as $value): ?>
            <tr>
              <td><?php echo $no_print++; ?></td>
              <td><?php echo $value->NIP; ?></td>
              <td><?php echo $value->nama_pegawai; ?></td>
              <td class="text-center"><?php echo date("d-m-Y", strtotime($value->waktu)); ?></td>
              <td class="text-center"><?php echo date("H:i:s", strtotime($value->waktu)); ?></td>
              <td><?php
                  $jam_jadwal_print = strtotime($value->jam_jadwal);
                  $masuk_print      = strtotime(date("H:i:s", strtotime($value->waktu)));
                  $diff_print       = $masuk_print - $jam_jadwal_print;
                  $jam_tol_print    = $value->jam_toleransi ?? null;
                  if ($jam_tol_print == null || $jam_tol_print == "") {
                    $jadwal_print  = @$this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
                    $jam_tol_print = ($jadwal_print != null) ? @$jadwal_print['toleransi_kedatangan'] : null;
                  }
                  if ($diff_print <= 0) {
                    echo '<span class="badge bg-success">Tepat Waktu</span>';
                  } elseif ($jam_tol_print != null && $jam_tol_print != "" && $masuk_print <= strtotime($jam_tol_print)) {
                    echo '<span class="badge bg-warning">Toleransi</span>';
                  } else {
                    echo '<span class="badge bg-danger">Terlambat</span>';
                  }
                  ?></td>
              <td><?php
                  if ($value->jenis_tempat == 1) echo 'Dalam Kantor';
                  elseif ($value->jenis_tempat == 2) echo 'Luar Kantor';
                  else echo 'Mobile Unit';
                  ?></td>
            </tr>
          <?php endforeach; ?>

          <?php if (!empty($libur_pegawai_list)): ?>
            <?php foreach ($libur_pegawai_list as $key => $lp): ?>
              <?php if (!empty($hadir_set[$lp->pegawai_uuid . '_' . $lp->tanggal])) continue; ?>
              <tr style="background-color:#fff3e0;">
                <td><?php echo $no_print++; ?></td>
                <td><?php echo $lp->NIP ?? '-'; ?></td>
                <td><?php echo $lp->nama_pegawai; ?></td>
                <td class="text-center"><?php echo date("d-m-Y", strtotime($lp->tanggal)); ?></td>
                <td class="text-center">-</td>
                <td>
                  <span class="badge" style="background-color:#fd7e14;color:#fff;">Libur Pegawai</span>
                  <?php if (!empty($lp->keterangan)): ?>
                    <br><small><?php echo $lp->keterangan; ?></small>
                  <?php endif; ?>
                </td>
                <td>-</td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-4 text-center">
    <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
    Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA
    <br><br><br><br>
    <?php echo $ttd['nama_pegawai']; ?>
    <br>
    <?php echo $ttd['NIP']; ?>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $(".txtTW").html("<?php echo $txtTW; ?>");
    $(".txtTO").html("<?php echo $txtTO; ?>");
    $(".txtTE").html("<?php echo $txtTE; ?>");
    $(".txtLP").html("<?php echo $txtLP; ?>");

    $("#print").click(function() {
      var mode = 'iframe';
      var close = mode == "popup";
      var options = {
        mode: mode,
        popClose: close
      };
      $("div.printableArea").printArea(options);
    });
  });
</script>