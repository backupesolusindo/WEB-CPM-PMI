<div class="row">
  <div class="col-lg-4 col-md-4">
    <div class="card aqua-gradient">
      <div class="card-body">
        <div class="d-flex flex-row">
          <div class="round align-self-center bg-success"><i class="ti-wallet"></i></div>
          <div class="m-l-10 align-self-center">
            <h3 class="m-b-0 text-white txtTW"> 0</h3>
            <h5 class="m-b-0 text-white">Tepat Waktu</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4">
    <div class="card peach-gradient">
      <div class="card-body">
        <div class="d-flex flex-row">
          <div class="round align-self-center bg-warning"><i class="ti-wallet"></i></div>
          <div class="m-l-10 align-self-center">
            <h3 class="m-b-0 text-white txtTO"> 0</h3>
            <h5 class="m-b-0 text-white">Toleransi</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4">
    <div class="card warm-flame-gradient">
      <div class="card-body">
        <div class="d-flex flex-row">
          <div class="round align-self-center bg-danger"><i class="ti-wallet"></i></div>
          <div class="m-l-10 align-self-center">
            <h3 class="m-b-0 text-white txtTE"> 0</h3>
            <h5 class="m-b-0 text-white">Terlambat</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<a class="float-left">
  <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
</a>
<table id="table-print" class="display nowrap table table-hover table-striped table-bordered print-view">
  <thead>
    <tr>
      <th>NO</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Tanggal</th>
      <th>Waktu Datang</th>
      <th>Waktu Pulang</th>
      <th>Status Datang</th>
      <th>Lokasi Presensi</th>
      <th>Detail</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $txtTW = 0;
    $txtTO = 0;
    $txtTE = 0;
    if ($presensi->num_rows() > 0): ?>
      <?php $no = 1;
      foreach ($presensi->result() as $value): ?>
        <?php
        $jam_jadwal  = strtotime($value->jam_jadwal);
        $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
        $diff  = $masuk - $jam_jadwal;
        $status_presensi = 1;
        $text_status = "";
        if ($diff <= 0) {
          $text_status = '<span class="badge bg-success">Tepat Waktu</span>';
          $txtTW += 1;
          $status_presensi = 1;
        } else {
          $jam_toleransi = $value->jam_toleransi;
          if ($jam_toleransi == null || $jam_toleransi == "") {
            $jam_toleransi = @$this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array()['toleransi_kedatangan'];
          }
          $toleransi = strtotime(date("H:i:s", strtotime($jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
          if ($diff <= $toleransi) {
            $text_status = '<span class="badge bg-warning">Toleransi</span>';
            $txtTO += 1;
            $status_presensi = 2;
          } else {
            $text_status = '<span class="badge bg-danger">Terlambat</span>';
            $txtTE += 1;
            $status_presensi = 3;
          }
        } ?>
        <?php if ($status_filter == $status_presensi || $status_filter == ""): ?>
          <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $value->NIP; ?></td>
            <td><?php echo $value->nama_pegawai; ?></td>
            <td class="text-center"><?php echo date("d-m-Y", strtotime($value->waktu)); ?></td>
            <td class="text-center"><?php echo date("H:i:s", strtotime($value->waktu)) . "<br>"; ?></td>
            <td class="text-center">
              <?php
              $cek_pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi);
              if ($cek_pulang->num_rows() > 0): ?>
                <?php echo date("H:i:s", strtotime($cek_pulang->row_array()['waktu'])); ?>
              <?php else: ?>
                Belum Absen Pulang
              <?php endif; ?>
            </td>
            <td><?php echo $text_status ?></td>
            <td>
              <?php if ($value->jenis_tempat == 1): ?>
                Dalam Kantor
              <?php else: ?>
                Luar Kantor
              <?php endif; ?>
            </td>
            <td>
              <a href="<?php echo base_url() ?>Laporan/DetailLaporanPresensi/<?php echo $value->idabsensi; ?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL"><i class="fas fa-info-circle"></i></a>
              <a href="<?php echo base_url() ?>Laporan/RealtimeLocatioan/<?php echo $value->uuid; ?>" class="btn-floating btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Realtime Location"><i class="fas fa-location-arrow"></i></a>
            </td>
          </tr>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

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
    </table>
  </div>
  <div class="col-12">
    <br><br>
    <div class="table-responsive">
      <table class="display nowrap table table-hover table-striped table-bordered ">
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
          <?php
          if ($presensi->num_rows() > 0): ?>
            <?php $no = 1;
            foreach ($presensi->result() as $value): ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $value->NIP; ?></td>
                <td><?php echo $value->nama_pegawai; ?></td>
                <td class="text-center"><?php echo date("d-m-Y", strtotime($value->waktu)); ?></td>
                <td class="text-center"><?php echo date("H:i:s", strtotime($value->waktu)) . "<br>"; ?></td>
                <td><?php
                    $jam_jadwal  = strtotime($value->jam_jadwal);
                    $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
                    $diff  = $masuk - $jam_jadwal;
                    if ($diff <= 0) {
                      echo '<span class="badge bg-success">Tepat Waktu</span>';
                    } else {
                      $toleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                      if ($diff <= $toleransi) {
                        echo '<span class="badge bg-warning">Toleransi</span>';
                      } else {
                        echo '<span class="badge bg-danger">Terlambat</span>';
                      }
                    } ?></td>
                <td>
                  <?php if ($value->jenis_tempat == 1): ?>
                    Dalam Kantor
                  <?php else: ?>
                    Luar Kantor
                  <?php endif; ?>
                </td>
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
    $(".txtTW").html("<?php echo $txtTW; ?>");
    $(".txtTO").html("<?php echo $txtTO; ?>");
    $(".txtTE").html("<?php echo $txtTE; ?>");

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