<?php echo form_open('pdp/insert_peserta'); ?>
<input type="hidden" name="id_pdp" value="<?php echo $pdp->id_pdp; ?>">

<div class="row card card-cascade narrower z-depth-1">
  <div class="col-md-12">

    <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
      <div></div>
      <h3 class="white-text mx-3">FORM PESERTA PDP</h3>
      <div></div>
    </div>

    <!-- Info PDP -->
    <div class="col-md-12 row">
      <div class="col-6">
        <table width="100%" border="0">
          <tr>
            <td>Judul Pelatihan</td>
            <td>: <?php echo $pdp->judul_pelatihan; ?></td>
          </tr>
          <tr>
            <td>Jenis Kegiatan</td>
            <td>: <?php echo $pdp->jenis_kegiatan; ?></td>
          </tr>
          <tr>
            <td>KPI</td>
            <td>: <?php echo $pdp->keterkaitan_kpi ? $pdp->keterkaitan_kpi : '-'; ?></td>
          </tr>
          <tr>
            <td>Status</td>
            <td>: <?php echo ucfirst($pdp->status); ?></td>
          </tr>
        </table>
      </div>
      <div class="col-6">
        <table width="100%" border="0">
          <tr>
            <td>Tanggal Mulai</td>
            <td>: <?php echo date('d-m-Y', strtotime($pdp->tanggal_mulai)); ?></td>
          </tr>
          <tr>
            <td>Tanggal Selesai</td>
            <td>: <?php echo date('d-m-Y', strtotime($pdp->tanggal_selesai)); ?></td>
          </tr>
          <tr>
            <td>Bukti Dokumen</td>
            <td>:
              <?php if (!empty($pdp->bukti_dokumen)): ?>
                <a href="<?php echo base_url('uploads/pdp/' . $pdp->bukti_dokumen); ?>" target="_blank">
                  <i class="fas fa-file"></i> Lihat
                </a>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <br>

    <!-- Tombol Tambah Peserta -->
    <div class="col-sm-12">
      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".M_peserta">
        <i class="fa fa-plus"></i> Tambah Peserta
      </button>

      <!-- Modal List Pegawai -->
      <div class="modal fade M_peserta" tabindex="-1" role="dialog" aria-hidden="true">
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
                      <th>Nama Peserta</th>
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
                      </td>
                      <td><?php echo $p->unit; ?></td>
                      <td>
                        <a href="#"
                           onclick='pilih("<?php echo $p->uuid; ?>","<?php echo $p->NIP; ?>","<?php echo $p->unit; ?>")'
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

      <!-- Tabel Peserta Terpilih -->
      <br>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>NIP</th>
              <th>Nama Peserta</th>
              <th>Unit</th>
              <th>Opsi</th>
            </tr>
          </thead>
          <tbody id="tbl_peserta">
            <?php foreach ($peserta as $p): ?>
            <tr id="<?php echo $p->uuid; ?>">
              <td>
                <input type="hidden" name="uuid[]" class="uuid" value="<?php echo $p->uuid; ?>">
                <?php echo $p->NIP; ?>
              </td>
              <td><?php echo $p->nama_pegawai; ?></td>
              <td><?php echo $p->unit; ?></td>
              <td>
                <button type="button" onclick='hapus("<?php echo $p->uuid; ?>")'
                        class="btn btn-floating btn-danger">
                  <i class="fas fa-user-slash"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3">TOTAL PESERTA:</th>
              <th><b class="txt_jml_total"><?php echo count($peserta); ?></b></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

  </div>
  <br><br>
  <div class="col-md-12">
    <button type="button" onclick="window.history.back()" class="btn btn-default btn-sm">
      <i class="fa fa-mail-reply"></i> Kembali
    </button>
    <button type="submit" class="btn btn-success pull-right btn-rounded">
      <i class="fa fa-save"></i> Simpan
    </button>
  </div>
  <br>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
var pilih_kode = [];

<?php foreach ($peserta as $p) { ?>
  pilih_kode.push("<?php echo $p->uuid; ?>");
<?php } ?>

function pilih(uuid, nip, unit) {
  var nama = $("#nama-" + uuid).val();
  if (pilih_kode.indexOf(uuid) < 0) {
    pilih_kode.push(uuid);
    var tbody = "<tr id='" + uuid + "'>" +
      "<td><input type='hidden' name='uuid[]' class='uuid' value='" + uuid + "'>" + nip + "</td>" +
      "<td>" + nama + "</td>" +
      "<td>" + unit + "</td>" +
      "<td><button type='button' onclick='hapus(\"" + uuid + "\")' class='btn btn-floating btn-danger'><i class='fas fa-user-slash'></i></button></td>" +
      "</tr>";
    $("#tbl_peserta").append(tbody);
  } else {
    alert("Peserta yang Anda pilih sudah ada!");
  }
  hitung();
}

function hapus(uuid) {
  pilih_kode.splice($.inArray(uuid, pilih_kode), 1);
  $("#" + uuid).remove();
  hitung();
}

function hitung() {
  var jml = $('input.uuid').length;
  $(".txt_jml_total").html(jml);
}
</script>