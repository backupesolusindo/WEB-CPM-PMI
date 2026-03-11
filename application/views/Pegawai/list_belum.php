<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Pegawai Belum Mendaftar E-Presensi</h3>
        <div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
                  <div class="col-lg-12">
                      <div class="card">
                          <div class="card-body">
                            <div class="table-responsive">
                            <table id="table-print" class="table color-table table-hover table-striped ">
                              <thead>
                                  <tr>
                                      <th width="10%">#</th>
                                      <th>UUID</th>
                                      <th>NIP</th>
                                      <th>NIK</th>
                                      <th>Nama Lengkap</th>
                                      <th>E-Mail</th>
                                      <th>Unit</th>
                                      <th>Jenis Unit</th>
                                      <th>Struktur Jabatan</th>
                                      <th>Status</th>
                                  </tr>
                              </thead>
                              <tbody>

                                <?php
                                $no = 1;
                                foreach ($Pegawai as $value): ?>
                                    <?php if ($this->ModelPegawai->edit($value->uuid)->num_rows() < 1):
                                        $status = "Belum Terdaftar";
                                        $this->db->where("nama_asli", $value->nama_lengkap);
                                        if ($this->db->get("pegawai")->num_rows() > 0) {
                                          $status = "ID Pegawai Tidak Sinkron";
                                        }
                                      ?>
                                      <tr>
                                        <td><?php echo $no ?></td>
                                        <td><?php echo $value->uuid ?></td>
                                        <td><?php echo $value->nip ?></td>
                                        <td><?php echo $value->no_ktp ?></td>
                                        <td><?php echo $value->nama_gelar ?></td>
                                        <td><?php echo $value->email ?></td>
                                        <td><?php echo $value->unit ?></td>
                                        <td><?php echo $value->jenis_unit ?></td>
                                        <td><?php echo $value->jab_struktur ?></td>
                                        <td><?php echo $status ?></td>
                                      </tr>
                                    <?php $no++; endif; ?>
                                  <?php endforeach; ?>
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

<script type="text/javascript">
$(document).ready(function(){
  $('#table-print').DataTable({
    dom: 'Bfrtip',
    buttons: ['excel'],
  });
});
</script>
