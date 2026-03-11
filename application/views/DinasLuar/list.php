<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Jadwal Dinas Luar</h3>
        <div>
          <a href="<?php base_url(); ?>DinasLuar/input" class="float-right">
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
                                      <th width="10%">#</th>
                                      <th>No.Surat</th>
                                      <th>Kegiatan</th>
                                      <th>Lokasi</th>
                                      <th>Tanggal Mulai</th>
                                      <th>Tanggal Selesai</th>
                                      <th>Opsi</th>
                                  </tr>
                              </thead>
                              <tbody>

                                <?php
                                $no = 1;
                                foreach ($DinasLuar as $value): ?>
                                  <tr>
                                    <td><?php echo $no ?></td>
                                    <td><?php echo $value->no_surat ?></td>
                                    <td><?php echo $value->nama_surat ?></td>
                                    <td><?php echo $value->keterangan ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($value->tanggal_mulai)) ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($value->tanggal_selesai)) ?></td>
                                    <td>
                                      <a href="<?php echo base_url()?>DinasLuar/peserta/<?php echo $value->iddinas_luar;?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="PESERTA"><i class="fas fa-users"></i></a>
                                      <a href="<?php echo base_url()?>DinasLuar/edit/<?php echo $value->iddinas_luar;?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>
                                      <!-- <a href="<?php echo base_url()?>DinasLuar/hapus/<?php echo $value->iddinas_luar;?>" class="btn-floating btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a> -->
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
