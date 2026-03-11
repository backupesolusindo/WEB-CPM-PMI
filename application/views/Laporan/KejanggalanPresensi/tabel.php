<table id="table-print" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <?php if ($tipe_pegawai == "Pegawai Negeri Sipil (PNS)"): ?>
        <th>NIP</th>
      <?php elseif($tipe_pegawai == "Pegawai Luar Biasa (LB)" || $tipe_pegawai == "Pegawai Kontrak"): ?>
        <th>KTP / NIK</th>
      <?php else: ?>
        <th>NIP / KTP / NIK</th>
      <?php endif; ?>
      <th>Nama</th>
      <th>Tanggal</th>
      <th>Presensi Datang</th>
      <th>Istirahat</th>
      <th>Presensi Pulang</th>
      <th>Jam Kerja<br>(dengan Jam Istirahat)</th>
      <th>Lokasi</th>
      <th>Status Tepat Waktu</th>
      <th>Status Approval</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; foreach ($pegawai as $val): ?>
      <?php foreach ($this->ModelRiwayat->RiwayatHarian($val->uuid, null, $tgl_mulai, $tgl_akhir)->result() as $value):
        $absenpulang = $this->ModelRiwayat->Pulang($value->idabsensi)->row_array();
        if(@$absenpulang['waktu'] == null) {
        $total_jam = 0;
        $total_menit = 0;
        $istirahat        = $this->ModelRiwayat->get_Absensi_Istirahat(date("Y-m-d", strtotime($value->waktu)));
        $jam_istirahat = "Belum Melakukan Presensi Istirahat";
        if ($istirahat->num_rows() > 0) {
          $istirahat = $istirahat->row_array();
          $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) ." - Selesai Istirahat Belum Presensi";
          $selesaiIstirahat = $this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"]);
          if ($selesaiIstirahat->num_rows() > 0) {
            $selesaiIstirahat = $selesaiIstirahat->row_array();
            $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) ." - ". date("H:i:s", strtotime($selesaiIstirahat['waktu']));
          }
        }
        $status_absensi = "<span class='badge bg-danger'>Belum Di Setujui</span>";
        $btn_approval = "";
        if ($value->status_absensi == 1) {
          $status_absensi = "<span class='badge bg-info'>Sudah Di Setujui</span>";
        }
        if (@$absenpulang['waktu'] == null) {
          $total_jam = 0;
          $total_menit = 0;
        }else {
          $datang = date_create($value->waktu);
          $pulang = date_create($absenpulang['waktu']);
          $diff = date_diff($datang, $pulang );
          $total_jam = $total_jam+$diff->h - 1;
          $total_menit = $total_menit+$diff->i;
        }
        ?>
        <tr>
          <td><?php echo $no++ ?></td>
          <td>`<?php if ($val->tipe_pegawai == "Pegawai Negeri Sipil (PNS)") {
                echo $val->NIP;
              }else {
                echo $val->NIK;
              }?></td>
          <td><?php echo $val->nama_pegawai?></td>
          <td><?php echo date("d-m-Y", strtotime($value->waktu)) ?></td>
          <td><?php echo date("H:i:s", strtotime($value->waktu)) ?></td>
          <td><?php echo $jam_istirahat ?></td>
          <td><?php if (@$absenpulang['waktu'] == null) {
            echo "Belum Melakukan Presensi Pulang";
          }else{
            echo date("H:i:s", strtotime($absenpulang['waktu'] ));
          }?></td>
          <td><?php echo $total_jam." Jam ".$total_menit." Menit";?></td>
          <td>
            <?php if ($value->jenis_tempat == 1): ?>
              WFO
            <?php else: ?>
              WFH
            <?php endif; ?>
          </td>
          <td><?php
            $jam_jadwal  = strtotime($value->jam_jadwal);
            $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
            $diff  = $masuk - $jam_jadwal;
            if ($diff <= 0) {
              echo '<span class="badge bg-success">Tepat Waktu</span>';
              // $txtTW += 1;
            }else {
              $toleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
              if ($diff <= $toleransi) {
                echo '<span class="badge bg-warning">Toleransi</span>';
                // $txtTO += 1;
              }else {
                echo '<span class="badge bg-danger">Terlambat</span>';
                // $txtTE += 1;
              }
            } ?></td>
            <td>
              <?php if ($value->status_absensi == 1): ?>
                <span class='badge bg-info'>Sudah Di Setujui</span>
                <br>
                <a href="<?php echo base_url('Absensi/ditolak/'.$value->idabsensi.'/'.$value->pegawai_uuid) ?>" class="btn btn-sm peach-gradient">Tolak</a>
              <?php else: ?>
                <?php if ($value->status_absensi == 2): ?>
                  <span class='badge bg-danger'>Ditolak</span>
                <?php else: ?>
                  <span class='badge bg-warning'>Belum Di Setujui</span>
                <?php endif; ?>
                <br>
                <a href="<?php echo base_url('Absensi/approval/'.$value->idabsensi.'/'.$value->pegawai_uuid) ?>" class="btn btn-sm blue-gradient">Approval</a>
              <?php endif; ?>
            </td>
          </tr>

        <?php }
       endforeach; ?>
      <?php endforeach; ?>

    </tbody>
  </table>

  <div class="printableArea row" hidden>
    <table class="col-12" border="0">
      <tr>
        <td align="center"><h1>Rekapitulasi Presensi Pegawai</h1></td>
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
        <th>WFH</th>
        <th>WFO</th>
        <th>Tepat Waktu</th>
        <th>Terlambat</th>
        <th>Jumlah Presensi</th>
        <th>Jam Efektif</th>
        <th>Kegiatan</th>
        <th>Cuti</th>
      </tr>
    </thead>
    <tbody>
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
  $(document).ready(function(){
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
