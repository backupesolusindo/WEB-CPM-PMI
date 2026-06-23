<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Kantor</h3>
        <div>
          <a href="<?php echo base_url(); ?>Kampus/input" class="float-right">
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
                        <th>Nama Kantor</th>
                        <th>Latitude</th>
                        <th>Longtitude</th>
                        <th>Radius</th>
                        <th>Status</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $no = 1;
                      foreach ($kampus as $value): ?>
                        <tr>
                          <td><?php echo $no ?></td>
                          <td><?php echo $value->nama_kampus ?></td>
                          <td><?php echo $value->latitude ?></td>
                          <td><?php echo $value->longtitude ?></td>
                          <td><?php echo $value->radius ?> Meter</td>
                          <td>
                            <?php $status = isset($value->status) ? $value->status : 'aktif'; ?>
                            <?php if ($status === 'aktif'): ?>
                              <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                              <span class="badge bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php $status = isset($value->status) ? $value->status : 'aktif'; ?>
                            <a href="<?php echo base_url() ?>Kampus/toggle_status/<?php echo $value->idkampus; ?>"
                              class="btn-floating btn-sm <?php echo $status === 'aktif' ? 'btn-info' : 'btn-success'; ?>"
                              data-toggle="tooltip" data-placement="top"
                              title="<?php echo $status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan'; ?>"
                              onclick="return confirm('<?php echo $status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan'; ?> kantor ini?')">
                              <i class="fas <?php echo $status === 'aktif' ? 'fa-toggle-on' : 'fa-toggle-off'; ?>"></i>
                            </a>
                            <a href="<?php echo base_url() ?>Kampus/edit/<?php echo $value->idkampus; ?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fas fa-pen"></i></a>
                            <a href="<?php echo base_url() ?>Kampus/hapus/<?php echo $value->idkampus; ?>" class="btn-floating btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="return confirm('Hapus kantor ini?')"><i class="fas fa-trash"></i></a>
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