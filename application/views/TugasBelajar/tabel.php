<h4>Total Jumlah Pegawai : <?php echo sizeof($data) ?></h4>
<table id="table-print" class="table color-table table-hover table-striped ">
  <thead>
    <tr>
      <th width="10%">#</th>
      <th>NIP/NIK/No.KTP</th>
      <th>Nama</th>
      <th>Kampus</th>
      <th>Keterangan</th>
      <th>Tanggal Mulai</th>
      <th>Tanggal Selesai</th>
      <th>Opsi</th>
    </tr>
  </thead>
  <tbody>

    <?php
    $no = 1;
    foreach ($data as $value):
      $pegawai = $this->ModelPegawai->edit($value->pegawai_uuid)->row_array(); ?>
    <tr>
      <td><?php echo $no ?></td>
      <td><?php echo $pegawai['NIK'].$pegawai['NIP']?></td>
      <td><?php echo $pegawai['nama_pegawai'] ?></td>
      <td><?php echo $value->nama_kampus ?></td>
      <td><?php echo $value->keterangan ?></td>
      <td><?php echo $value->tahun ?></td>
      <td><?php echo $value->tahun_selesai ?></td>
      <td>
        <a href="<?php echo base_url()?>TugasBelajar/edit/<?php echo $value->idtugas_belajar;?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>
      </td>
    </tr>
    <?php $no++; endforeach; ?>
  </tbody>
</table>
