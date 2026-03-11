<table id="tblLibur" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>NO</th>
      <th>Tanggal</th>
      <th>Keterangan</th>
      <th>Hapus</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; foreach ($libur as $value): ?>
      <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo date("d-m-Y", strtotime($value->tanggal)) ?></td>
        <td><?php echo $value->keterangan ?></td>
        <td>
          <a href="<?php echo base_url('Libur/delete/'.$value->idtanggal_libur) ?>" class="btn-floating btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hapus"><i class="fas fa-trash"></i></a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
  $("#tblLibur").DataTable();
});
</script>
