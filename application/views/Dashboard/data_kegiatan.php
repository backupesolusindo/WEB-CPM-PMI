<?php
$no = 1;
foreach ($kegiatan as $value): ?>
  <tr>
    <td><?php echo $no ?></td>
    <td><?php echo $value->nama_kegiatan ?></td>
    <td><?php echo date("d-m-Y", strtotime($value->tanggal)) ?></td>
    <td><?php echo $value->jam_mulai ?></td>
    <td><?php echo $value->nama_unit ?></td>
    <td><?php echo $value->nama_pegawai ?></td>
    <td>
      <a href="<?php echo base_url()?>Laporan/detailKegiatan/<?php echo $value->idkegiatan;?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL"><i class="fas fa-info-circle"></i></a>
    </td>
  </tr>
  <?php $no++; endforeach; ?>
