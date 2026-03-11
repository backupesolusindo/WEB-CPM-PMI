<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Unit</h3>
        <div>
          <!-- <a href="<?php base_url(); ?>Unit/sinkron" class="float-right">
            <button type="button" class="btn btn-outline-white btn-rounded " data-toggle="tooltip" data-placement="top" data-original-title="Sinkronisasi Data Unit"><i class="fas fa-cloud-download-alt"></i> Sinkronisasi Unit</button>
          </a> -->
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
                      <th>Nama Unit</th>
                      <th>Level Unit</th>
                      <th>Parent Unit</th>
                      <th>Status</th>
                      <!-- <th>Opsi</th> -->
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $no = 1;
                    foreach ($unit as $value): ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo $value->nama_unit ?></td>
                      <td><?php echo $value->level ?></td>
                      <td><?php echo $value->parent_unit ?></td>
                      <td class="status<?php echo $value->idunit ?>">
                        <?php if ($value->status == 1): ?>
                          <span class="badge bg-primary">Tampil</span>
                        <?php else: ?>
                          <span class="badge bg-danger">Disembunyikan</span>
                        <?php endif; ?>
                      </td>
                      <!-- <td>
                        <div class="opsi<?php echo $value->idunit ?>">
                          <?php if ($value->status == 1): ?>
                            <a class="btn-floating btn-sm btn-danger" onclick="sembunyikan('<?php echo $value->idunit ?>')"  data-toggle="tooltip" data-placement="top" title="Sembunyikan"><i class="fas fa-eye-slash"></i></a>
                          <?php else: ?>
                            <a class="btn-floating btn-sm btn-primary" onclick="tampilkan('<?php echo $value->idunit ?>')" data-toggle="tooltip" data-placement="top" title="Tampilkan"><i class="fas fa-eye"></i></a>
                          <?php endif; ?>
                        </div>
                      </td> -->
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

<script type="text/javascript">
  function sembunyikan(idunit) {
    $.ajax({
        type  : 'POST',
        url   : '<?php echo base_url() ?>Unit/sembunyikan',
        data  : {idunit:idunit},
        success : function(response){
          if (response == "berhasil") {
            $(".status"+idunit).html('<span class="badge bg-danger">Disembunyikan</span>');
            $(".opsi"+idunit).html('<a class="btn-floating btn-sm btn-primary" onclick="tampilkan('+idunit+')"  data-toggle="tooltip" data-placement="top" title="Tampilkan"><i class="fas fa-eye"></i></a>');
          }else {
            $(".opsi"+idunit).append('<span class="badge bg-danger">Proses Gagal</span>');
          }
        }
    });
  }
  function tampilkan(idunit) {
    $.ajax({
        type  : 'POST',
        url   : '<?php echo base_url() ?>Unit/tampilkan',
        data  : {idunit:idunit},
        success : function(response){
          if (response == "berhasil") {
            $(".status"+idunit).html('<span class="badge bg-primary">Tampil</span>');
            $(".opsi"+idunit).html('<a class="btn-floating btn-sm btn-danger" onclick="sembunyikan('+idunit+')"  data-toggle="tooltip" data-placement="top" title="Sembunyikan"><i class="fas fa-eye-slash"></i></a>');
          }else {
            $(".opsi"+idunit).append('<span class="badge bg-danger">Proses Gagal</span>');
          }
        }
    });
  }
</script>
