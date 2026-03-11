
<table id="table-print" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>NO</th>
      <!-- <th>Bulan - Tahun</th> -->
      <?php if ($tipe_pegawai == "Pegawai Negeri Sipil (PNS)"): ?>
        <th>NIP</th>
      <?php elseif($tipe_pegawai == "Pegawai Luar Biasa (LB)" || $tipe_pegawai == "Pegawai Kontrak"): ?>
        <th>KTP / NIK</th>
      <?php else: ?>
        <th>NIP / KTP / NIK</th>
      <?php endif; ?>
      <th>Nama Lengkap</th>
      <th>Unit</th>
      <th>Jabatan</th>
      <th>Jml. Presensi Diluar Jam Kerja</th>
      <th>Detail</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; foreach ($pegawai as $data): ?>
      <?php
        $jam = $this->ModelLaporan->rekap_kerjadiluarjam($data->uuid,$tgl_mulai,$tgl_akhir)->num_rows();
        if ($jam > 0) { ?>
          <tr>
            <td><?php echo $no++ ?></td>
            <!-- <td><?php echo date("m-Y", strtotime($tgl_mulai)) ?></td> -->
            <td>`<?php if ($data->tipe_pegawai == "Pegawai Negeri Sipil (PNS)") {
              echo $data->NIP;
            }else {
              echo $data->NIK;
            }?></td>
            <td><?php echo $data->nama_pegawai ?></td>
            <td><?php echo $data->jenis_unit." ".$data->unit ?></td>
            <td><?php echo $data->jab_struktur ?></td>
            <td>
              <?php echo $jam ?> Hari
            </td>
            <td>
              <a href="<?php echo base_url()?>Laporan/DetailRekap/<?php echo $data->uuid;?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL"><i class="fas fa-info-circle"></i></a>
            </td>
          </tr>
        <?php } ?>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="printableArea row" hidden>
  <div class="col-12">
    <table width="100%" border="0">
      <tr>
        <td width="15%">Lampiran </td>
        <td id="txt_lampiran"></td>
      </tr>
      <tr>
        <td>Nomor </td>
        <td id="txt_nomor"></td>
      </tr>
      <tr>
        <td>Tanggal </td>
        <td id="txt_tgl_surat"></td>
      </tr>
      <tr>
        <td>Bulan </td>
        <td id="txt_bulan_surat"></td>
      </tr>
      <tr>
        <td>Jenis Pegawai : </td>
        <td id="txt_jenis_pegawai"></td>
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
          <!-- <th>Bulan - Tahun</th> -->
          <?php if ($tipe_pegawai == "Pegawai Negeri Sipil (PNS)"): ?>
            <th>NIP</th>
          <?php elseif($tipe_pegawai == "Pegawai Luar Biasa (LB)" || $tipe_pegawai == "Pegawai Kontrak"): ?>
            <th>KTP / NIK</th>
          <?php else: ?>
            <th>NIP / KTP / NIK</th>
          <?php endif; ?>
          <th>Nama Lengkap</th>
          <th>Unit</th>
          <th>Jabatan</th>
          <th>Jml. Presensi Diluar Jam Kerja</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; foreach ($pegawai as $data): ?>
          <?php
            $jam = $this->ModelLaporan->rekap_kerjadiluarjam($data->uuid,$tgl_mulai,$tgl_akhir)->num_rows();
            if ($jam > 0) { ?>
              <tr>
                <td><?php echo $no++ ?></td>
                <!-- <td><?php echo date("m-Y", strtotime($tgl_mulai)) ?></td> -->
                <td>`<?php if ($data->tipe_pegawai == "Pegawai Negeri Sipil (PNS)") {
                  echo $data->NIP;
                }else {
                  echo $data->NIK;
                }?></td>
                <td><?php echo $data->nama_pegawai ?></td>
                <td><?php echo $data->jenis_unit." ".$data->unit ?></td>
                <td><?php echo $data->jab_struktur ?></td>
                <td>
                  <?php echo $jam ?> Hari
                </td>
              </tr>
            <?php } ?>
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
      var lampiran  = $('#lampiran').val();
      var nomor  = $('#nomor').val();
      var tgl_surat  = $('#tgl_surat').val();
      var bulan_surat  = $('#bulan_surat').val();
      var jenis_pegawai  = $('#jenis_pegawai').val();
      $("#txt_lampiran").html(": "+lampiran);
      $("#txt_nomor").html(": "+nomor);
      $("#txt_tgl_surat").html(": "+tgl_surat);
      $("#txt_bulan_surat").html(": "+bulan_surat);
      $("#txt_jenis_pegawai").html(": "+jenis_pegawai);

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
