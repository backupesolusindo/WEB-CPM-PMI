<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Detail Laporan Kegiatan</h3>
        <div>
          <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
            <a href="<?php echo base_url(); ?>Absensi/input_kegiatan/<?php echo $this->core->encrypt_url($kegiatan['idkegiatan']); ?>" class="float-right">
              <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
            </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="text-center">
        <br>
        <h3><?php echo $kegiatan['nama_kegiatan'] ?></h3>
      </div>
      <div class="card-body row">
        <div class="col-6">
          <table width="100%" border="0">
            <tr>
              <td>Kode Kegiatan</td>
              <td>: <?php echo $kegiatan['idkegiatan'] ?></td>
            </tr>
            <tr>
              <td>Lokasi</td>
              <td>: <?php echo $kegiatan['nama_gedung'] . ", " . $kegiatan['nama_kampus'] ?></td>
            </tr>
            <tr>
              <td>PIC</td>
              <td>: <?php echo $kegiatan['nama_pegawai'] ?></td>
            </tr>
            <tr>
              <td>Unit Pelaksana</td>
              <td>: <?php echo $kegiatan['nama_unit'] ?></td>
            </tr>
          </table>
        </div>
        <div class="col-6">
          <table width="100%" border="0">
            <tr>
              <td>Tanggal Mulai Kegiatan</td>
              <td>: <?php echo date("d-m-Y", strtotime($kegiatan['tanggal'])) ?></td>
            </tr>
            <tr>
              <td>Tanggal Selesai Kegiatan</td>
              <td>: <?php echo date("d-m-Y", strtotime($kegiatan['tanggal_selesai'])) ?></td>
            </tr>
            <tr>
              <td>Waktu Mulai Kegiatan</td>
              <td>: <?php echo date("H:i:s", strtotime($kegiatan['jam_mulai'])) ?></td>
            </tr>
            <tr>
              <td>Waktu Selesai Kegiatan</td>
              <td>: <?php echo date("H:i:s", strtotime($kegiatan['jam_selesai'])) ?></td>
            </tr>

          </table>
        </div>
        <div class="col-12">
          <br>

          <!-- Notulensi -->
          <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
              <span><i class="fas fa-file-alt mr-1"></i> Notulensi Kegiatan</span>
              <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                <button class="btn btn-sm btn-primary btn-rounded" id="btn-edit-notulensi">
                  <i class="fas fa-pencil-alt mr-1"></i> Edit
                </button>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <div id="notulensi-display">
                <?php if (!empty($kegiatan['notulensi'])): ?>
                  <div><?php echo $kegiatan['notulensi']; ?></div>
                <?php else: ?>
                  <p class="text-muted"><i>Belum ada notulensi.</i></p>
                <?php endif; ?>
              </div>
              <div id="notulensi-form" style="display:none;">
                <textarea id="input-notulensi" row="5" height="100px"><?php echo $kegiatan['notulensi'] ?? ''; ?></textarea>
                <div class="mt-2">
                  <button class="btn btn-success btn-sm btn-rounded" id="btn-simpan-notulensi">
                    <i class="fas fa-save mr-1"></i> Simpan
                  </button>
                  <button class="btn btn-secondary btn-sm btn-rounded ml-1" id="btn-batal-notulensi">Batal</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Foto Kegiatan -->
          <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
              <span><i class="fas fa-images mr-1"></i> Foto Kegiatan</span>
              <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                <button class="btn btn-sm btn-primary btn-rounded" id="btn-upload-foto" data-toggle="modal" data-target="#modalUploadFoto">
                  <i class="fas fa-upload mr-1"></i> Upload Foto
                </button>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <div class="row" id="galeri-foto">
                <?php if (empty($foto_kegiatan)): ?>
                  <div class="col-12 text-muted" id="no-foto-msg"><i>Belum ada foto kegiatan.</i></div>
                <?php else: ?>
                  <?php foreach ($foto_kegiatan as $foto): ?>
                    <div class="col-md-3 col-sm-4 col-6 mb-3 foto-item" id="foto-<?php echo $foto->id; ?>">
                      <div class="card">
                        <a href="<?php echo base_url() . $foto->foto; ?>" target="_blank">
                          <img src="<?php echo base_url() . $foto->foto; ?>" class="card-img-top" style="height:150px;object-fit:cover;">
                        </a>
                        <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                          <div class="card-footer p-1 text-center">
                            <button class="btn btn-danger btn-sm btn-hapus-foto btn-rounded" data-id="<?php echo $foto->id; ?>">
                              <i class="fas fa-trash"></i>
                            </button>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php
          // Flatten semua hadir & tidak hadir dari semua tanggal
          $semua_hadir_flat = [];
          $semua_tidak_hadir_flat = [];
          foreach ($tanggal_list as $tgl) {
            foreach ($hadir_per_tgl[$tgl] ?? [] as $p) {
              $semua_hadir_flat[] = $p;
            }
            foreach ($tidak_hadir_per_tgl[$tgl] ?? [] as $p) {
              $semua_tidak_hadir_flat[] = ['tgl' => $tgl, 'data' => $p];
            }
          }
          $total_hadir       = count($semua_hadir_flat);
          $total_tidak_hadir = count($semua_tidak_hadir_flat);
          $total_undangan    = count($tidak_hadir_per_tgl[$tanggal_list[0]] ?? []) + $total_hadir;
          ?>

          <div class="d-flex mb-3" style="gap:10px;">
            <span class="badge badge-pill badge-success p-2" style="font-size:14px;">
              <i class="fas fa-user-check mr-1"></i> Hadir: <?php echo $total_hadir; ?>
            </span>
            <span class="badge badge-pill badge-danger p-2" style="font-size:14px;">
              <i class="fas fa-user-times mr-1"></i> Tidak Hadir (unik per hari): <?php echo $total_tidak_hadir; ?>
            </span>
          </div>

          <ul class="nav nav-tabs" id="tabPeserta" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#panel-hadir" role="tab">
                <i class="fas fa-user-check mr-1"></i> Hadir
                <span class="badge badge-success ml-1"><?php echo $total_hadir; ?></span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#panel-tidak-hadir" role="tab">
                <i class="fas fa-user-times mr-1"></i> Tidak Hadir
                <span class="badge badge-danger ml-1"><?php echo $total_tidak_hadir; ?></span>
              </a>
            </li>
          </ul>

          <div class="tab-content border border-top-0 p-3">

            <!-- Tab Hadir -->
            <div class="tab-pane fade show active" id="panel-hadir" role="tabpanel">
              <div class="mb-2">
                <button type="button" id="print" class="btn btn-info btn-sm">
                  <i class="fas fa-print"></i> PRINT
                </button>
              </div>
              <div class="table-responsive">
                <table id="table-print" class="display nowrap table table-hover table-striped table-bordered print-excel">
                  <thead>
                    <tr>
                      <th>NO</th>
                      <th>Foto</th>
                      <th>Nama Pegawai</th>
                      <th>Tanggal</th>
                      <th>Waktu Datang</th>
                      <th>Lokasi</th>
                      <th>Status Kedatangan</th>
                      <th>Approval</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($semua_hadir_flat)): ?>
                      <tr>
                        <td colspan="8" class="text-center">Belum ada peserta yang hadir</td>
                      </tr>
                    <?php else: ?>
                      <?php $no = 1;
                      $prev_tgl = null;
                      foreach ($semua_hadir_flat as $value): ?>
                        <?php $tgl_row = date('Y-m-d', strtotime($value->jam_presensi)); ?>
                        <?php if ($tgl_row !== $prev_tgl): ?>
                          <tr class="table-primary">
                            <td colspan="8"><strong><i class="fas fa-calendar-day mr-1"></i><?php echo date('d-m-Y', strtotime($tgl_row)); ?></strong></td>
                          </tr>
                          <?php $prev_tgl = $tgl_row;
                          $no = 1; ?>
                        <?php endif; ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td><img src="<?php echo base_url() . $value->foto ?>" width="70px"></td>
                          <td><?php echo $value->nama_pegawai; ?></td>
                          <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)); ?></td>
                          <td><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
                          <td><?php echo $value->status_lokasi == 1 ? "Di Lokasi" : "Dilakukan Secara Online"; ?></td>
                          <td><?php
                              $jam_jadwal = strtotime($kegiatan['jam_mulai']);
                              $masuk      = strtotime(date("H:i:s", strtotime($value->jam_presensi)));
                              $diff       = $masuk - $jam_jadwal;
                              if ($diff <= 0) {
                                echo '<span class="badge bg-success">Tepat Waktu</span>';
                              } else {
                                $toleransi = strtotime("00:30:00") - strtotime("00:00:00");
                                echo $diff <= $toleransi
                                  ? '<span class="badge bg-warning">Toleransi</span>'
                                  : '<span class="badge bg-danger">Terlambat</span>';
                              }
                              ?></td>
                          <td id="approval-<?php echo $value->idabsen_kegiatan; ?>">
                            <?php if ($value->status_aproval == 1): ?>
                              <span class="badge bg-success">Disetujui</span>
                              <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                                <button class="btn btn-danger btn-sm btn-approval mt-1 btn-rounded" data-id="<?php echo $value->idabsen_kegiatan; ?>" data-status="2"><i class='fa fa-ban'></i></button>
                              <?php endif; ?>
                            <?php elseif ($value->status_aproval == 2): ?>
                              <span class="badge bg-danger">Ditolak</span>
                              <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                                <button class="btn btn-success btn-sm btn-approval mt-1 btn-rounded" data-id="<?php echo $value->idabsen_kegiatan; ?>" data-status="1"><i class='fa fa-check'></i></button>
                              <?php endif; ?>
                            <?php else: ?>
                              <span class="badge bg-warning">Menunggu</span>
                              <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                                <button class="btn btn-success btn-sm btn-approval mr-1 btn-rounded" data-id="<?php echo $value->idabsen_kegiatan; ?>" data-status="1"><i class='fa fa-check'></i></button>
                                <button class="btn btn-danger btn-sm btn-approval btn-rounded" data-id="<?php echo $value->idabsen_kegiatan; ?>" data-status="2"><i class='fa fa-ban'></i></button>
                              <?php endif; ?>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Tab Tidak Hadir -->
            <div class="tab-pane fade" id="panel-tidak-hadir" role="tabpanel">
              <div class="table-responsive">
                <table class="display nowrap table table-hover table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>NO</th>
                      <th>NIP</th>
                      <th>Nama Pegawai</th>
                      <th>Tanggal</th>
                      <th>Unit</th>
                      <th>Jabatan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($semua_tidak_hadir_flat)): ?>
                      <tr>
                        <td colspan="7" class="text-center">Semua peserta undangan telah hadir di semua hari</td>
                      </tr>
                    <?php else: ?>
                      <?php $no = 1;
                      $prev_tgl = null;
                      foreach ($semua_tidak_hadir_flat as $item):
                        $value = $item['data'];
                        $tgl_row = $item['tgl']; ?>
                        <?php if ($tgl_row !== $prev_tgl): ?>
                          <tr class="table-danger">
                            <td colspan="7"><strong><i class="fas fa-calendar-day mr-1"></i><?php echo date('d-m-Y', strtotime($tgl_row)); ?></strong></td>
                          </tr>
                          <?php $prev_tgl = $tgl_row;
                          $no = 1; ?>
                        <?php endif; ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td><?php echo $value->NIP; ?></td>
                          <td><?php echo $value->nama_pegawai; ?></td>
                          <td><?php echo date('d-m-Y', strtotime($tgl_row)); ?></td>
                          <td><?php echo isset($value->unit) ? $value->unit : '-'; ?></td>
                          <td><?php echo isset($value->jab_struktur) ? $value->jab_struktur : '-'; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="printableArea row" hidden>
    <table class="col-12" border="0">
      <tr>
        <td align="center">
          <h1>Laporan Presensi Kegiatan</h1>
          <br>
          <br>
          <h3><?php echo $kegiatan['nama_kegiatan'] ?></h3>
        </td>

      </tr>
    </table>
    <div class="col-6">
      <table width="100%" border="0">
        <tr>
          <td>Kode Kegiatan</td>
          <td>: <?php echo $kegiatan['idkegiatan'] ?></td>
        </tr>
        <tr>
          <td>Lokasi</td>
          <td>: <?php echo $kegiatan['nama_gedung'] . ", " . $kegiatan['nama_kampus'] ?></td>
        </tr>
        <tr>
          <td>PIC</td>
          <td>: <?php echo $kegiatan['nama_pegawai'] ?></td>
        </tr>
        <tr>
          <td>Unit Pelaksana</td>
          <td>: <?php echo $kegiatan['nama_unit'] ?></td>
        </tr>
      </table>
    </div>
    <div class="col-6">
      <table width="100%" border="0">
        <tr>
          <td>Tanggal Mulai Kegiatan</td>
          <td>: <?php echo date("d-m-Y", strtotime($kegiatan['tanggal'])) ?></td>
        </tr>
        <tr>
          <td>Tanggal Selesai Kegiatan</td>
          <td>: <?php echo date("d-m-Y", strtotime($kegiatan['tanggal_selesai'])) ?></td>
        </tr>
        <tr>
          <td>Waktu Mulai Kegiatan</td>
          <td>: <?php echo date("H:i:s", strtotime($kegiatan['jam_mulai'])) ?></td>
        </tr>
        <tr>
          <td>Waktu Selesai Kegiatan</td>
          <td>: <?php echo date("H:i:s", strtotime($kegiatan['jam_selesai'])) ?></td>
        </tr>

      </table>
    </div>
    <div class="col-12">
      <br><br>
      <div class="table-responsive">
        <table class="display nowrap table table-hover table-striped table-bordered ">
          <thead>
            <tr>
              <th>NO</th>
              <th>Foto</th>
              <th>Nama Pegawai</th>
              <th>Tanggal</th>
              <th>Waktu Datang</th>
              <th>Lokasi</th>
              <th>Status Kedatangan</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            foreach ($peserta->result() as $value): ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td> <img src="<?php echo base_url() . $value->foto ?>" width="70px"> </td>
                <td><?php echo $value->nama_pegawai; ?></td>
                <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></td>
                <td><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
                <td><?php if ($value->status_lokasi == 1) {
                      echo "Di Lokasi";
                    } else {
                      echo "Dilakukan Secara Online";
                    } ?></td>
                <td> <?php
                      $jam_jadwal  = strtotime($kegiatan['jam_mulai']);
                      $masuk       = strtotime(date("H:i:s", strtotime($value->jam_presensi)));
                      $diff  = $masuk - $jam_jadwal;
                      if ($diff <= 0) {
                        echo 'Tepat Waktu';
                      } else {
                        $toleransi = strtotime(date("H:i:s", strtotime("00:30:00"))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                        if ($diff <= $toleransi) {
                          echo 'Toleransi';
                        } else {
                          echo 'Terlambat';
                        }
                      } ?> </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-8">

    </div>
    <div class="col-4 text-center">
      <br><br>
      Penanggung Jawab Kegiatan
      <br>
      <br>
      <br>
      <br>
      <?php echo $kegiatan['nama_pegawai'] ?>
      <br>
      <?php echo $kegiatan['NIP'] ?>
    </div>
  </div>

  <!-- Modal Upload Foto -->
  <div class="modal fade" id="modalUploadFoto" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-upload mr-1"></i> Upload Foto Kegiatan</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Pilih Foto (JPG/PNG, maks 10MB)</label>
            <input type="file" id="input-foto-kegiatan" class="form-control-file" accept="image/*" multiple>
          </div>
          <div id="preview-foto" class="row mt-2"></div>
          <div id="upload-progress" style="display:none;" class="mt-2">
            <div class="progress">
              <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
            </div>
            <small class="text-muted">Mengupload...</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-rounded" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary btn-rounded" id="btn-do-upload">
            <i class="fas fa-upload mr-1"></i> Upload
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url() ?>/desain/dist/js/pages/jquery.PrintArea.js" type="text/JavaScript"></script>
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
  <script type="text/javascript">
    var idkegiatan = "<?php echo $kegiatan['idkegiatan']; ?>";
    var ckEditor = null;

    $(document).ready(function() {
      // Print
      $("#print").click(function() {
        var mode = 'iframe';
        var close = mode == "popup";
        $("div.printableArea").printArea({
          mode: mode,
          popClose: close
        });
      });

      // Approval
      $(document).on("click", ".btn-approval", function() {
        var id = $(this).data("id");
        var status = $(this).data("status");
        var label = status == 1 ? "menyetujui" : "menolak";
        if (!confirm("Yakin ingin " + label + " presensi ini?")) return;
        $.ajax({
          url: "<?php echo base_url(); ?>Laporan/approval_kegiatan",
          type: "POST",
          data: {
            idabsen_kegiatan: id,
            status: status
          },
          success: function(res) {
            var r = JSON.parse(res);
            if (r.status == 200) {
              location.reload();
            } else {
              alert("Gagal melakukan approval");
            }
          }
        });
      });

      // Notulensi - toggle edit
      $("#btn-edit-notulensi").click(function() {
        $("#notulensi-display").hide();
        $("#notulensi-form").show();
        if (!ckEditor) {
          ClassicEditor.create(document.querySelector('#input-notulensi'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'underline', '|',
              'bulletedList', 'numberedList', '|',
              'alignment', '|', 'link', 'blockQuote', '|',
              'undo', 'redo'
            ]
          }).then(function(editor) {
            ckEditor = editor;
            var existing = $("#notulensi-display").find('div').html() || '';
            ckEditor.setData(existing);
          }).catch(console.error);
        }
      });
      $("#btn-batal-notulensi").click(function() {
        $("#notulensi-form").hide();
        $("#notulensi-display").show();
      });
      $("#btn-simpan-notulensi").click(function() {
        if (!ckEditor) return;
        var notulensi = ckEditor.getData();
        $.ajax({
          url: "<?php echo base_url(); ?>Laporan/simpan_notulensi",
          type: "POST",
          data: {
            idkegiatan: idkegiatan,
            notulensi: notulensi
          },
          success: function(res) {
            var r = JSON.parse(res);
            if (r.status == 200) {
              $("#notulensi-display").html('<div>' + notulensi + '</div>');
              $("#notulensi-form").hide();
              $("#notulensi-display").show();
            } else {
              alert("Gagal menyimpan notulensi");
            }
          }
        });
      });

      // Preview foto sebelum upload
      $("#input-foto-kegiatan").change(function() {
        $("#preview-foto").html("");
        $.each(this.files, function(i, file) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $("#preview-foto").append(
              '<div class="col-4 mb-2"><img src="' + e.target.result + '" class="img-fluid rounded" style="height:80px;object-fit:cover;"></div>'
            );
          };
          reader.readAsDataURL(file);
        });
      });

      // Upload foto satu per satu
      $("#btn-do-upload").click(function() {
        var files = $("#input-foto-kegiatan")[0].files;
        if (files.length === 0) {
          alert("Pilih foto terlebih dahulu");
          return;
        }
        $("#upload-progress").show();
        $("#btn-do-upload").prop("disabled", true);

        var uploaded = 0;
        $.each(files, function(i, file) {
          var fd = new FormData();
          fd.append("idkegiatan", idkegiatan);
          fd.append("foto_kegiatan", file);
          $.ajax({
            url: "<?php echo base_url(); ?>Laporan/upload_foto_kegiatan",
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,
            success: function(res) {
              var r = JSON.parse(res);
              uploaded++;
              if (r.status == 200) {
                $("#no-foto-msg").remove();
                $("#galeri-foto").append(
                  '<div class="col-md-3 col-sm-4 col-6 mb-3 foto-item" id="foto-' + r.id + '">' +
                  '<div class="card">' +
                  '<a href="' + r.foto + '" target="_blank">' +
                  '<img src="' + r.foto + '" class="card-img-top" style="height:150px;object-fit:cover;">' +
                  '</a>' +
                  '<div class="card-footer p-1 text-center">' +
                  '<button class="btn btn-danger btn-sm btn-hapus-foto btn-rounded" data-id="' + r.id + '">' +
                  '<i class="fas fa-trash"></i>' +
                  '</button>' +
                  '</div>' +
                  '</div>' +
                  '</div>'
                );
              } else {
                alert("Gagal upload: " + r.message);
              }
              if (uploaded === files.length) {
                $("#upload-progress").hide();
                $("#btn-do-upload").prop("disabled", false);
                $("#input-foto-kegiatan").val("");
                $("#preview-foto").html("");
                $("#modalUploadFoto").modal("hide");
              }
            }
          });
        });
      });

      // Hapus foto
      $(document).on("click", ".btn-hapus-foto", function() {
        var id = $(this).data("id");
        if (!confirm("Yakin ingin menghapus foto ini?")) return;
        $.ajax({
          url: "<?php echo base_url(); ?>Laporan/hapus_foto_kegiatan",
          type: "POST",
          data: {
            id: id
          },
          success: function(res) {
            var r = JSON.parse(res);
            if (r.status == 200) {
              $("#foto-" + id).remove();
            } else {
              alert("Gagal menghapus foto");
            }
          }
        });
      });
    });
  </script>