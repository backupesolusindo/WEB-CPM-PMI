<?php
$no = 1;
foreach ($data as $value): ?>
  <tr>
    <td><?php echo $no ?></td>
    <td><?php echo $value->keterangan_lembur ?></td>
    <td><?php echo date("d-m-Y", strtotime($value->tgl_mulai)); ?></td>
    <td><?php echo date("d-m-Y", strtotime($value->tgl_selesai)); ?></td>
    <td><?php echo $value->nama_unit ?></td>
    <td>
      <a href="<?php echo base_url()?>Lembur/peserta/<?php echo $value->idlembur;?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="PESERTA"><i class="fas fa-users"></i></a>
      <a href="<?php echo base_url()?>Lembur/edit/<?php echo $value->idlembur;?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>
    </td>
  </tr>
  <?php $no++; endforeach; ?>
