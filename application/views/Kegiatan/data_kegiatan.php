<?php
$no = 1;
foreach ($kegiatan as $value): ?>
  <tr>
    <td><?php echo $no ?></td>
    <td><?php echo $value->idkegiatan ?></td>
    <td><?php echo $value->nama_kegiatan ?></td>
    <td><?php
        if ($value->tanggal == $value->tanggal_selesai) {
          echo date("d-m-Y", strtotime($value->tanggal));
        }else {
          echo date("d-m-Y", strtotime($value->tanggal)) ." s/d ". date("d-m-Y", strtotime($value->tanggal_selesai));
        }
     ?></td>
    <td><?php echo $value->jam_mulai ?></td>
    <td><?php echo $value->nama_unit ?></td>
    <td><?php echo $value->nama_pegawai ?></td>
    <td>
      <a href="<?php echo base_url()?>Kegiatan/peserta/<?php echo $this->core->encrypt_url($value->idkegiatan);?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="PESERTA"><i class="fas fa-users"></i></a>
      <a href="<?php echo base_url()?>Kegiatan/edit/<?php echo $this->core->encrypt_url($value->idkegiatan);?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>
      <!-- <a href="<?php echo base_url()?>Kegiatan/hapus/<?php echo $value->idkegiatan;?>" class="btn-floating btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a> -->
    </td>
  </tr>
  <?php $no++; endforeach; ?>
