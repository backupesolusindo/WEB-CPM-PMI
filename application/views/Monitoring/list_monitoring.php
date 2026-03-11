<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Pegawai Akses Monitoring</h3>
        <div>
          <!-- <a href="<?php base_url(); ?>JadwalMasuk/input" class="float-right">
          <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
        </a> -->
      </div>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-6">
            <table width="100%" border="0">
              <tr>
                <td>NIP</td>
                <td>: <?php echo $pimpinan['NIP'] ?></td>
              </tr>
              <tr>
                <td>Nama Pimpinan</td>
                <td>: <?php echo $pimpinan['nama_pegawai'] ?></td>
              </tr>
            </table>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table id="myTable" class="table color-table table-hover table-striped ">
                  <thead>
                    <tr>
                      <th width="10%">#</th>
                      <th>NIP</th>
                      <th>Nama Lengkap</th>
                      <th>E-Mail</th>
                      <th>Unit</th>
                      <th>Jenis Unit</th>
                      <th>Struktur Jabatan</th>
                      <!-- <th>Opsi</th> -->
                    </tr>
                  </thead>
                  <tbody>

                    <?php $no=1;
                    $data_kepala = array();
                     foreach ($Kepala as $value): ?>
                      <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo $value->NIP ?></td>
                        <td><?php echo $value->nama_pegawai ?></td>
                        <td><?php echo $value->email ?></td>
                        <td><?php echo $value->unit ?></td>
                        <td><?php echo $value->jenis_unit ?></td>
                        <td><?php echo $value->jab_struktur ?></td>
                      </tr>
                    <?php array_push($data_kepala, $value->uuid); $no++; endforeach; ?>
                    <?php foreach ($this->ModelPegawai->get_anggotamonitoring($uuid, $data_kepala)->result() as $value): ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo $value->NIP ?></td>
                      <td><?php echo $value->nama_pegawai ?></td>
                      <td><?php echo $value->email ?></td>
                      <td><?php echo $value->unit ?></td>
                      <td><?php echo $value->jenis_unit ?></td>
                      <td><?php echo $value->jab_struktur ?></td>
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
