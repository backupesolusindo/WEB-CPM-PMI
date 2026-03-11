
<table id="table-print" class="table" border="1">
  <thead>
    <tr>
      <th>UNIT</th>
      <th>SUB UNIT</th>
      <th>Kepala Unit/Sub Unit</th>
      <th>NIP</th>
      <th>Nama Pegawai</th>
      <th>Email</th>
      <th>Struktur Jabatan</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    $this->db->group_start();
    $this->db->where("nama_unit",$unit);
    if ($unit == "KANTOR POLIJE") {
      $this->db->or_where("parent_unit",$unit);
    }
    $this->db->group_end();
    $this->db->where("level > 0");
    $this->db->where("status = 1");
    $parent_unit = $this->db->get("unit")->result();
    foreach ($parent_unit as $value): ?>
    <tr>
      <td>
        <?php
        @$kep_unit = @$this->ModelPegawai->get_jabatunit($value->idunit)->row_array();
        echo $value->nama_unit;
        $pegawai = $this->ModelPegawai->get_UnitPegawai($value->nama_unit, $value->nama_unit)->result(); ?>
      </td>
      <td>-</td>
      <td><?php echo @$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai']; ?></td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
    </tr>
    <?php foreach ($pegawai as $val_peg): ?>
      <tr>
        <td><?php echo $value->nama_unit ?></td>
        <td>-</td>
        <td><?php echo @$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai']; ?></td>
        <td><?php echo "'".$val_peg->NIP ?></td>
        <td><?php echo $val_peg->nama_pegawai ?></td>
        <td><?php echo $val_peg->email ?></td>
        <td><?php echo $val_peg->jab_struktur ?></td>
      </tr>
    <?php endforeach; ?>
    <?php
    $unit = $this->db->get_where("unit",array('parent_unit' => $value->nama_unit, 'status' => '1'))->result();
    foreach ($unit as $val_unit): ?>
    <tr>
      <td><?php echo $value->nama_unit ?></td>
      <td><?php
        @$kep_unit = @$this->ModelPegawai->get_jabatunit($val_unit->idunit)->row_array();
        echo $val_unit->nama_unit;
        $pegawai = $this->ModelPegawai->get_UnitPegawai($val_unit->nama_unit, $val_unit->nama_unit)->result(); ?></td>
        <td><?php echo @$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai']; ?></td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
        <td>-</td>
      </tr>
      <?php foreach ($pegawai as $val_peg): ?>
        <tr>
          <td><?php echo $value->nama_unit ?></td>
          <td><?php echo $val_unit->nama_unit ?></td>
          <td><?php echo @$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai']; ?></td>
          <td><?php echo "'".$val_peg->NIP ?></td>
          <td><?php echo $val_peg->nama_pegawai ?></td>
          <td><?php echo $val_peg->email ?></td>
          <td><?php echo $val_peg->jab_struktur ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endforeach; ?>
    <?php $no++; endforeach; ?>
  </tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
  $('#table-print').DataTable({
    dom: 'Bfrtip',
    buttons: ['excel'],
  });
});
</script>
