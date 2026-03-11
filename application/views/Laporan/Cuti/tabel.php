<a class="float-left" >
  <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
</a>
<table id="table-print" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>NO</th>
      <th>NIP</th>
      <th>Nama</th>
      <th>Tanggal Mulai</th>
      <th>Tanggal Akhir</th>
      <th>Jenis Cuti</th>
      <th>Alasan Cuti</th>
      <th>Status Cuti</th>
      <th>Download File</th>
      <th>Hapus Cuti</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; foreach ($cuti->result() as $value): ?>
      <tr id="<?php echo $value->idizin ?>">
        <td><?php echo $no++ ?></td>
        <td><?php echo $value->NIP ?></td>
        <td><?php echo $value->nama_pegawai ?></td>
        <td><?php echo date("d-m-Y", strtotime($value->tanggal_mulai)) ?></td>
        <td><?php echo date("d-m-Y", strtotime($value->tanggal_akhir)) ?></td>
        <td><?php echo $value->jenis_izin ?></td>
        <td><?php echo $value->alasan ?></td>
        <td> <?php if ($value->status == 1): ?>
          <span class="badge bg-success">Approval</span>
        <?php else: ?>
          <span class="badge bg-warning">Di Tolak</span>
        <?php endif; ?> </td>
        <td><?php if ($value->file == ""): ?>
          <span class="badge bg-primary">Tidak Ada File</span>
        <?php else: ?>
          <a href="<?php echo base_url().$value->file ?>" class="btn btn-info btn-sm"> <i class="fas fa-download"></i> Download File</a>
        <?php endif; ?></td>
        <td>
          <a class="btn peach-gradient btn-sm text-white" onclick="hapus('<?php echo $value->idizin ?>')"> <i class="fas fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="printableArea row" hidden>
  <table class="col-12" border="0">
    <tr>
      <td align="center"><h1>Laporan Cuti</h1></td>
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
  <div class="table-responsive">
    <table class="display nowrap table table-hover table-striped table-bordered ">
      <thead>
        <tr>
          <th>NO</th>
          <th>NIP</th>
          <th>Nama</th>
          <th>Tanggal Mulai</th>
          <th>Tanggal Akhir</th>
          <th>Jenis Cuti</th>
          <th>Alasan Cuti</th>
          <th>Status Cuti</th>
        </tr>
      </thead>
      <tbody>
        <?php $no=1; foreach ($cuti->result() as $value): ?>
          <tr>
            <td><?php echo $no++ ?></td>
            <td><?php echo $value->NIP ?></td>
            <td><?php echo $value->nama_pegawai ?></td>
            <td><?php echo date("d-m-Y", strtotime($value->tanggal_mulai)) ?></td>
            <td><?php echo date("d-m-Y", strtotime($value->tanggal_akhir)) ?></td>
            <td><?php echo $value->jenis_izin ?></td>
            <td><?php echo $value->alasan ?></td>
            <td> <?php if ($value->status == 1): ?>
              <span class="badge bg-success">Approval</span>
            <?php else: ?>
              <span class="badge bg-warning">Di Tolak</span>
            <?php endif; ?> </td>
          </tr>
        <?php endforeach; ?>
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
