<?php echo form_open_multipart('DinasLuar/insert_peserta');?>
<input type="hidden" name="iddinas_luar" value="<?php echo $dinasluar['iddinas_luar'] ?>">
<div class="row card card-cascade narrower z-depth-1">
  <div class="col-md-12">
    <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
      <div>
      </div>
      <h3 class="white-text mx-3"><?php echo $title ?></h3>
      <div>
      </div>
    </div>
    <div class="col-md-12 row">
      <div class="col-6">
        <table width="100%" border="0">
          <tr>
            <td>No.Surat</td>
            <td>: <?php echo $dinasluar['no_surat'] ?></td>
          </tr>
          <tr>
            <td>Kegiatan</td>
            <td>: <?php echo $dinasluar['nama_surat'] ?></td>
          </tr>
          <tr>
            <td>Lokasi</td>
            <td>: <?php echo $dinasluar['keterangan'] ?></td>
          </tr>
        </table>
      </div>
      <div class="col-6">
        <table width="100%" border="0">
          <tr>
            <td>Tanggal Mulai Kegiatan</td>
            <td>: <?php echo date("d-m-Y", strtotime($dinasluar['tanggal_mulai'])) ?></td>
          </tr>
          <tr>
            <td>Tanggal Selesai Kegiatan</td>
            <td>: <?php echo date("d-m-Y", strtotime($dinasluar['tanggal_selesai'])) ?></td>
          </tr>
        </table>
      </div>
    </div>

    <div class="col-sm-12">
      <br>
      <button type="button" name="kembali" class="btn btn-info btn-sm" data-toggle="modal" data-target=".M_barang"> <i class="fa fa-plus"></i> Tambah Peserta</button>
      <div class="modal fade M_barang" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title" id="myLargeModalLabel">LIST PEGAWAI</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>

                  <div class="modal-body">
                    <div class="table-responsive">
                                                <table class="table product-overview" id="myTable">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">NO</th>
                                                            <th>NIP</th>
                                                            <th>Nama Peserta</th>
                                                            <th>Unit</th>
                                                            <th>Pilih Peserta</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                      <?php $no = 0; foreach ($pegawai as $value): ?>
                                                        <tr>
                                                            <td><?php echo ++$no; ?></td>
                                                            <td><?php echo $value->NIP;?></td>
                                                            <td><?php echo $value->nama_pegawai;?></td>
                                                            <td><?php echo $value->unit;?></td>
                                                            <td>
                                                              <a href="#" onclick="pilih('<?php echo $value->uuid ?>','<?php echo $value->NIP;?>','<?php echo $value->nama_pegawai;?>','<?php echo $value->unit;?>')" class="text-inverse p-r-10 btn btn-circle btn-sm btn-primary" data-toggle="tooltip" title="Pilih Pegawai"><i class="fas fa-user-plus"></i></a>
                                                            </td>
                                                        </tr>
                                                      <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                  </div>
              </div>
              <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <div class="table-responsive">
                                  <table class="table">
                                      <thead>
                                          <tr>
                                              <th>NIP</th>
                                              <th>Nama Peserta</th>
                                              <th>Unit</th>
                                              <th>Status Presensi Harian</th>
                                              <th>Hapus</th>
                                          </tr>
                                      </thead>
                                      <tbody id="tbl_peserta">
                                        <?php foreach ($peserta as $value): ?>
                                          <tr id="<?php echo $value->uuid ?>">
                                            <td><input type='text' name='uuid[]' class='uuid' value='<?php echo $value->uuid ?>' hidden><?php echo $value->NIP ?></td>
                                            <td><?php echo $value->nama_pegawai ?></td>
                                            <td><?php echo $value->unit ?></td>
                                            <td>
                                              <?php if ($value->status_pres_wfo == 1): ?>
                                                  <span class="badge bg-primary">Dizinkan Presensi WFO / WFH</span>
                                                  <a href="<?php echo base_url() ?>DinasLuar/IzinPresensi/<?php echo $dinasluar['iddinas_luar'] ?>/<?php echo $value->idpegawai_dinasluar ?>/0">
                                                    <button type='button' class='btn btn-floating btn-secondary'><i class="fas fa-lock-open"></i></button>
                                                  </a>
                                                <?php else: ?>
                                                  <span class="badge bg-warning">Tidak Boleh Presensi WFO / WFH</span>
                                                  <a href="<?php echo base_url() ?>DinasLuar/IzinPresensi/<?php echo $dinasluar['iddinas_luar'] ?>/<?php echo $value->idpegawai_dinasluar ?>/1">
                                                    <button type='button' class='btn btn-floating btn-warning'><i class="fas fa-user-lock"></i></button>
                                                  </a>
                                              <?php endif; ?>
                                            </td>
                                            <td><button type='button' onclick='hapus("<?php echo $value->uuid ?>")' class='btn btn-floating btn-danger'><i class='fas fa-user-slash'></i></button></td>
                                          </tr>
                                        <?php endforeach; ?>
                                      </tbody>
                                      <tfoot>
                                          <th colspan="4" class="text-right">TOTAL PESERTA:</th>
                                          <th><b class="txt_jml_total"><?php echo sizeof($peserta) ?></b> </th>
                                      </tfoot>
                                  </table>
                              </div>
    </div>
  </div>
    <br><br>
    <div class="col-md-12">
      <button type="button" name="kembali" onclick="window.history.back()" class="btn btn-default btn-sm"> <i class="fa fa-mail-reply"></i> Kembali</button>
      <button type="submit" name="submit" class="btn btn-success pull-right btn-rounded"> <i class="fa fa-save"></i> Simpan</button>
    </div>
</div>
<?php echo form_close(); ?>


<script type="text/javascript">
var pilih_kode = []; //array buat nampung data biar tampil

<?php foreach ($peserta as $value) { ?>
  pilih_kode.push("<?php echo $value->uuid ?>");
<?php } ?>

function pilih(kode, nip, nama, unit) {
  // alert(pilih_kode.indexOf(kode));
  if (pilih_kode.indexOf(kode) < 0) {
    pilih_kode.push(kode);
    // alert(kode);
    var tbody = "<tr id='"+kode+"'><td><input type='text' name='uuid[]' class='uuid' value='"+kode+"' hidden>"+nip+"</td>"+
              "<td>"+nama+"</td>"+
              "<td>"+unit+"</td>"+
              "<td><span class=\"badge bg-warning\">Tidak Boleh Presensi WFO / WFH</span></td>"+
              "<td><button type='button' onclick='hapus(\""+kode+"\")' class='btn btn-floating btn-danger'><i class='fas fa-user-slash'></i></button></td></tr>";
    $("#tbl_peserta").append(tbody);
  }else{
    alert("Maaf Peserta yang Anda Pilih Sudah Ada !");
  }
  hitung();
  // alert(pilih_kode);
}

function hapus(kode) {
  // alert(kode);
  pilih_kode.splice( $.inArray(kode,pilih_kode) ,1 );
  $("#"+kode).remove();
  hitung();
  // alert(pilih_kode);
}

function hitung() {
  var jml = 0;
  $('input.uuid').each(function() {
    jml = jml + 1;
  });
  // alert(jml);
  $(".txt_jml_total").html(jml);
}

</script>
