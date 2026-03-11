<table id="myTable" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>NO</th>
      <th>Kode Kegiatan</th>
      <th>Nama Kegiatan</th>
      <th>Tanggal</th>
      <th>Unit</th>
      <th>Lokasi</th>
      <th>Jumlah Peserta</th>
      <th>Detail</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; foreach ($kegiatan->result() as $value): ?>
      <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $value->idkegiatan ?></td>
        <td><?php echo $value->nama_kegiatan ?></td>
        <td><?php echo date("d-m-Y", strtotime($value->tanggal)) ?></td>
        <td><?php echo $value->nama_unit ?></td>
        <td><?php echo $value->nama_gedung.", ".$value->nama_kampus ?></td>
        <td><?php echo $this->ModelKegiatan->getPesertaKegiatan($value->idkegiatan)->num_rows() ?> Peserta</td>
        <td>
          <a href="<?php echo base_url()?>Laporan/detailKegiatan/<?php echo $this->core->encrypt_url($value->idkegiatan);?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL"><i class="fas fa-info-circle"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
