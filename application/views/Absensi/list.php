<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Presensi</h3>
        <div>
          <a href="<?php base_url(); ?>Absensi/presensi_datang" class="float-right">
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
                                      <th>Nama Pegawai</th>
                                      <th>Waktu</th>
                                      <th>Waktu Pulang</th>
                                      <th>Jenis Absen</th>
                                      <th>Jenis Tempat</th>
                                  </tr>
                              </thead>
                              <tbody>

                                <?php
                                $no = 1;
                                foreach ($absensi as $value): ?>
                                  <tr>
                                    <td><?php echo $no ?></td>
                                    <td><?php echo $value->nama_pegawai ?></td>
                                    <td><?php echo date("d-m-Y H:i:s", strtotime($value->waktu)); ?></td>
                                    <td><?php
                                    $pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi);
                                    if ($pulang->num_rows() < 1) { ?>
                                      <a href="<?php echo base_url() ?>Absensi/presensi_pulang/<?php echo $value->idabsensi ?>">
                                        <button type="submit" class="btn btn-primary btn-sm"> <i class="fa fa-clock"></i> Presensi Pulang</button>
                                      </a>
                                    <?php }else{
                                      echo date("d-m-Y H:i:s", strtotime($pulang->row_array()['waktu']));
                                    }
                                    ?></td>
                                    <td>
                                      <?php if ($value->jenis_absen==1): ?>
                                        <span class="badge bg-info">Absen Harian</span>
                                      <?php elseif($value->jenis_absen==2): ?>
                                        <span class="badge bg-warning">Absen Istirahat</span>
                                      <?php elseif($value->jenis_absen==3): ?>
                                        <span class="badge bg-success">Absen Kegiatan</span>
                                      <?php endif; ?>
                                    </td>
                                    <td>
                                      <?php if ($value->jenis_tempat==1): ?>
                                        <span>Dalam Kampus</span>
                                      <?php elseif($value->jenis_tempat==2): ?>
                                        <span>Luar Kampus</span>
                                      <?php endif; ?>
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
