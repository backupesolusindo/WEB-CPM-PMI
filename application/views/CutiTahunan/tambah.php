<?php echo form_open('CutiTahunan/simpan'); ?>

<div class="row card card-cascade narrower z-depth-1">
  <div class="col-md-12">

    <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
      <div></div>
      <h3 class="white-text mx-3">TAMBAH CUTI TAHUNAN</h3>
      <div></div>
    </div>

    <div class="col-sm-12">

      <!-- Tahun Cuti (berlaku untuk semua pegawai yang dipilih) -->
      <div class="row mb-3">
        <div class="col-md-3">
          <label><b>Tahun Cuti</b> <span class="text-danger">*</span></label>
          <input type="number" name="tahun_cuti" class="form-control" required
                 value="<?php echo date('Y'); ?>" min="2020" max="2099">
          <small class="text-muted">Tahun ini berlaku untuk semua pegawai yang dipilih</small>
        </div>
      </div>

      <!-- Tombol Tambah Pegawai -->
      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".M_pegawai">
        <i class="fa fa-plus"></i> Tambah Pegawai
      </button>

      <!-- Modal List Semua Pegawai -->
      <div class="modal fade M_pegawai" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">LIST PEGAWAI</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table class="table table-hover table-striped" id="tbl_modal_pegawai">
                  <thead>
                    <tr>
                      <th width="5%">NO</th>
                      <th>NIP</th>
                      <th>Nama Pegawai</th>
                      <th>Unit</th>
                      <th>Pilih</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 0; foreach ($pegawai as $p): ?>
                    <tr>
                      <td><?php echo ++$no; ?></td>
                      <td><?php echo $p->NIP; ?></td>
                      <td>
                        <?php echo $p->nama_pegawai; ?>
                        <input type="hidden" id="nama-<?php echo $p->uuid; ?>" value="<?php echo $p->nama_pegawai; ?>">
                        <input type="hidden" id="nip-<?php echo $p->uuid; ?>"  value="<?php echo $p->NIP; ?>">
                        <input type="hidden" id="unit-<?php echo $p->uuid; ?>" value="<?php echo $p->unit; ?>">
                      </td>
                      <td><?php echo $p->unit; ?></td>
                      <td>
                        <a href="#" onclick='pilih("<?php echo $p->uuid; ?>")'
                           class="btn btn-circle btn-sm btn-primary"
                           data-toggle="tooltip" title="Pilih Pegawai">
                          <i class="fas fa-user-plus"></i>
                        </a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal -->

      <!-- Tabel Pegawai Terpilih -->
      <br>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>NIP</th>
              <th>Nama Pegawai</th>
              <th>Unit</th>
              <th width="160">Total Cuti (Hari)</th>
              <th>Opsi</th>
            </tr>
          </thead>
          <tbody id="tbl_pilihan">
            <!-- diisi via JS -->
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4">TOTAL DIPILIH:</th>
              <th><b class="txt_jml_total">0</b></th>
            </tr>
          </tfoot>
        </table>
      </div>

    </div>
  </div>

  <br><br>
  <div class="col-md-12 mb-3">
    <button type="button" onclick="window.history.back()" class="btn btn-default btn-sm">
      <i class="fa fa-mail-reply"></i> Kembali
    </button>
    <button type="submit" class="btn btn-success pull-right btn-rounded">
      <i class="fa fa-save"></i> Simpan
    </button>
  </div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
var pilih_kode = [];

function pilih(uuid) {
  if (pilih_kode.indexOf(uuid) >= 0) {
    alert("Pegawai ini sudah dipilih!");
    return;
  }

  var nama = $("#nama-" + uuid).val();
  var nip  = $("#nip-"  + uuid).val();
  var unit = $("#unit-" + uuid).val();

  pilih_kode.push(uuid);

  var row = "<tr id='row-" + uuid + "'>" +
    "<td><input type='hidden' name='uuid[]' class='uuid_input' value='" + uuid + "'>" + nip + "</td>" +
    "<td>" + nama + "</td>" +
    "<td>" + unit + "</td>" +
    "<td><input type='number' name='total_cuti[]' class='form-control form-control-sm' min='1' max='365' placeholder='Contoh: 12' required></td>" +
    "<td><button type='button' onclick='hapus(\"" + uuid + "\")' class='btn btn-floating btn-danger'><i class='fas fa-user-slash'></i></button></td>" +
    "</tr>";

  $("#tbl_pilihan").append(row);
  hitung();
}

function hapus(uuid) {
  pilih_kode.splice($.inArray(uuid, pilih_kode), 1);
  $("#row-" + uuid).remove();
  hitung();
}

function hitung() {
  $(".txt_jml_total").html(pilih_kode.length);
}
</script>