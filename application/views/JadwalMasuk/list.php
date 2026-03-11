<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Jadwal Masuk</h3>
        <div>
          <a href="<?php base_url(); ?>JadwalMasuk/input" class="float-right">
          <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
        </a>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
                  <div class="col-lg-12">
                      <div class="card">
                          <div class="card-body">
                            <div class="table-responsive">
                            <table id="myTable" class="table color-table table-hover table-striped ">
                              <thead>
                                  <tr>
                                      <th width="5%">#</th>
                                      <th width="5%">Jenis</th>
                                      <th>Jabatan</th>
                                      <th>Nama Jadwal</th>
                                      <th>Jam Masuk</th>
                                      <th>Jam Pulang</th>
                                      <th>Total Jam Kerja</th>
                                      <th>Waktu Istirahat Keluar</th>
                                      <th>Waktu Istirahat Masuk</th>
                                      <th>Toleransi Kedatangan</th>
                                      <th>Toleransi Kepulangan</th>
                                      <!-- <th>Jumlah WFH</th>
                                      <th>Jumlah WFO</th> -->
                                      <th>Opsi</th>
                                  </tr>
                              </thead>
                              <tbody>

                                <?php
                                $no = 1;
                                foreach ($jadwalmasuk as $value): ?>
                                  <tr>
                                    <td><?php echo $no ?></td>
                                    <td>
                                      <?php if ($value->jenis == 1): ?>
                                          <span class="badge blue-gradient">WFO</span>
                                        <?php else: ?>
                                          <span class="badge aqua-gradient">WFH</span>
                                      <?php endif; ?>
                                    </td>
                                    <td><?php echo $value->jabatan_idjabatan ?></td>
                                    <td><?php echo $value->nama ?></td>
                                    <td><?php echo $value->jam_masuk ?></td>
                                    <td><?php echo $value->jam_pulang ?></td>
                                    <td><?php echo $value->total_jamkerja ?></td>
                                    <td><?php echo $value->isti_keluar ?></td>
                                    <td><?php echo $value->isti_masuk ?></td>
                                    <td><?php echo $value->toleransi_kedatangan ?></td>
                                    <td><?php echo $value->toleransi_kepulangan ?></td>

                                    <td>
                                      <a href="<?php echo base_url()?>JadwalMasuk/edit/<?php echo $value->idjadwal_masuk;?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>
                                      <a href="<?php echo base_url()?>JadwalMasuk/hapus/<?php echo $value->idjadwal_masuk;?>" class="btn-floating btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a>
                                    </td>
                                  </tr>
                                  <?php $no++; endforeach; ?>
                                      </tbody>

                                  </table>
                            </div>
                          </div>
                      </div>
                  </div>
              </div>

      </div>
    </div>
  </div>
</div>
