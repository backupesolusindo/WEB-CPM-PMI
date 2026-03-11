<a class="float-left" >
  <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
</a>
<table id="table-print" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>NO</th>
      <th>Bulan - Tahun</th>
      <?php if ($tipe_pegawai == "Pegawai Negeri Sipil (PNS)"): ?>
        <th>NIP</th>
      <?php elseif($tipe_pegawai == "Pegawai Luar Biasa (LB)" || $tipe_pegawai == "Pegawai Kontrak"): ?>
        <th>KTP / NIK</th>
      <?php else: ?>
        <th>NIP / KTP / NIK</th>
      <?php endif; ?>
      <th>Nama Lengkap</th>
      <th>Unit Saat Ini</th>
      <th>Jabatan Saat Ini</th>
      <th>Jml. Hari</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; foreach ($pegawai->result() as $data): ?>
      <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo date("m-Y", strtotime($tgl_mulai)) ?></td>
        <td>`<?php if ($data->tipe_pegawai == "Pegawai Negeri Sipil (PNS)") {
              echo $data->NIP;
            }else {
              echo $data->NIK;
            }?></td>
        <td><?php echo $data->nama_pegawai ?></td>
        <td><?php echo $data->jenis_unit." ".$data->unit ?></td>
        <td><?php echo $data->jab_struktur ?></td>
        <?php
        $total = 0;
        $diff = date_diff(date_create($tgl_mulai), date_create($tgl_akhir));
        for ($i=0; $i <= $diff->days; $i++) {
          $tgl2 = date('Y-m-d', strtotime('+'.$i.' days', strtotime($this->input->post("start")))); //operasi penjumlahan tanggal sebanyak 6 hari
          $hari_dinas = $this->ModelDinasLuar->cekDinasLuar($data->uuid, $tgl2)->num_rows();
          if ($hari_dinas > 0) {
            $total += 1;
          }
        }
        ?>
        <td><?php echo $total ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="printableArea row" hidden>
  <div class="col-6">
    <table width="100%" border="0">
      <tr>
        <td>Lampiran </td>
        <td>:</td>
      </tr>
      <tr>
        <td>Nomor </td>
        <td>:</td>
      </tr>
      <tr>
        <td>Unit </td>
        <td>: <?php echo $unit ?></td>
      </tr>
      <tr>
        <td>Tipe Pegawai : </td>
        <td>: <?php echo $tipe_pegawai = ($tipe_pegawai == "") ? "Semua" : $tipe_pegawai ; ?></td>
      </tr>
      <tr>
        <td>Tanggal </td>
        <td>: <?php echo date("d-m-Y") ?></td>
      </tr>
      <tr>
        <td>Kehadiran </td>
        <td>: <?php echo date("m-Y", strtotime($tgl_mulai)) ?></td>
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
    <table class="table table-hover table-striped" border="0" width="100%">
      <thead>
        <tr>
          <th>NO</th>
          <th>Bulan - Tahun</th>
          <th>KTP</th>
          <th>Nama Lengkap</th>
          <th width="3%">Unit Saat Ini</th>
          <th>Jabatan Saat Ini</th>
          <th>Jml. Hari</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; foreach ($pegawai->result() as $data): ?>
          <tr>
            <td><?php echo $no++ ?></td>
            <td><?php echo date("m-Y", strtotime($tgl_mulai)) ?></td>
            <td>`<?php if ($data->tipe_pegawai == "Pegawai Negeri Sipil (PNS)") {
                  echo $data->NIP;
                }else {
                  echo $data->NIK;
                }?></td>
            <td><?php echo $data->nama_pegawai ?></td>
            <td><?php echo $data->jenis_unit." ".$data->unit ?></td>
            <td><?php echo $data->jab_struktur ?></td>
            <?php
            $total = 0;
            $diff = date_diff(date_create($tgl_mulai), date_create($tgl_akhir));
            for ($i=0; $i <= $diff->days; $i++) {
              $tgl2 = date('Y-m-d', strtotime('+'.$i.' days', strtotime($this->input->post("start")))); //operasi penjumlahan tanggal sebanyak 6 hari
              $hari_dinas = $this->ModelDinasLuar->cekDinasLuar($data->uuid, $tgl2)->num_rows();
              if ($hari_dinas > 0) {
                $total += 1;
              }
            }
            ?>
            <td><?php echo $total ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
</table>
</div>
<br>
<br>
<div class="col-7">

</div>
<div class="col-5 text-center">
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
