<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Jadwal Lokasi Kantor</h3>
        <div>
          <a href="<?php base_url(); ?>JadwalLokasi/input" class="float-right">
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
                        <th>Senin</th>
                        <th>Selasa</th>
                        <th>Rabu</th>
                        <th>Kamis</th>
                        <th>Jum'at</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $no = 1;
                      foreach ($Pegawai as $value) : ?>
                        <tr>
                          <td><?php echo $no ?></td>
                          <td><?php echo $value->nama_pegawai ?></td>
                          <td>
                            <?php foreach ($this->ModelJadwalLokasi->get_jadwalpegawai($value->uuid, "Mon")->result() as $val) : ?>
                              - <?= $val->nama_kampus ?> <a href="<?php echo base_url('JadwalLokasi/hapus/') . $val->idhari_lokasi ?>"><i class="fas fa-trash-alt"></i></a><br>
                            <?php endforeach; ?>
                          </td>
                          <td>
                            <?php foreach ($this->ModelJadwalLokasi->get_jadwalpegawai($value->uuid, "Tue")->result() as $val) : ?>
                              - <?= $val->nama_kampus ?> <a href="<?php echo base_url('JadwalLokasi/hapus/') . $val->idhari_lokasi ?>"><i class="fas fa-trash-alt"></i></a><br>
                            <?php endforeach; ?>
                          </td>
                          <td>
                            <?php foreach ($this->ModelJadwalLokasi->get_jadwalpegawai($value->uuid, "Wed")->result() as $val) : ?>
                              - <?= $val->nama_kampus ?> <a href="<?php echo base_url('JadwalLokasi/hapus/') . $val->idhari_lokasi ?>"><i class="fas fa-trash-alt"></i></a><br>
                            <?php endforeach; ?>
                          </td>
                          <td>
                            <?php foreach ($this->ModelJadwalLokasi->get_jadwalpegawai($value->uuid, "Thu")->result() as $val) : ?>
                              - <?= $val->nama_kampus ?> <a href="<?php echo base_url('JadwalLokasi/hapus/') . $val->idhari_lokasi ?>"><i class="fas fa-trash-alt"></i></a><br>
                            <?php endforeach; ?>
                          </td>
                          <td>
                            <?php foreach ($this->ModelJadwalLokasi->get_jadwalpegawai($value->uuid, "Fri")->result() as $val) : ?>
                              - <?= $val->nama_kampus ?> <a href="<?php echo base_url('JadwalLokasi/hapus/') . $val->idhari_lokasi ?>"><i class="fas fa-trash-alt"></i></a><br>
                            <?php endforeach; ?>
                          </td>
                        </tr>
                      <?php $no++;
                      endforeach; ?>
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