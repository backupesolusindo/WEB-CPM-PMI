<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Detail Laporan Kegiatan</h3>
        <div>
          <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
            <a href="<?php echo base_url(); ?>Absensi/input_kegiatan/<?php echo $this->core->encrypt_url($kegiatan['idkegiatan']);?>" class="float-right">
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
              <td>: <?php echo $kegiatan['nama_gedung'].", ".$kegiatan['nama_kampus'] ?></td>
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
            <a class="float-left" >
              <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
            </a>
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
                <?php $no=1; foreach ($peserta->result() as $value): ?>
                  <tr>
                    <td><?php echo $no++; ?></td>
                    <td> <img src="<?php echo base_url().$value->foto ?>" width="100px"> </td>
                    <td><?php echo $value->nama_pegawai; ?></td>
                    <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></td>
                    <td><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
                    <td><?php if ($value->status_lokasi == 1) {
                        echo "Di Lokasi";
                      }else {
                        echo "Dilakukan Secara Online";
                      } ?></td>
                    <td> <?php
                      $jam_jadwal  = strtotime($kegiatan['jam_mulai']);
                      $masuk       = strtotime(date("H:i:s", strtotime($value->jam_presensi)));
                      $diff  = $masuk - $jam_jadwal;
                      if ($diff <= 0) {
                        echo '<h5><span class="badge bg-success">Tepat Waktu</span></h5>';
                      }else {
                        $toleransi = strtotime(date("H:i:s", strtotime("00:30:00"))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                        if ($diff <= $toleransi) {
                          echo '<h5><span class="badge bg-warning">Toleransi</span></h5>';
                        }else {
                          echo '<h5><span class="badge bg-danger">Terlambat</span></h5>';
                        }
                      } ?> </td>
                    <td id="approval-<?php echo $value->idabsen_kegiatan; ?>">
                      <?php if ($value->status_aproval == 1): ?>
                        <h5><span class="badge bg-success">Disetujui</span></h5>
                        <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                          <button class="btn btn-danger btn-sm btn-approval mt-1 btn-rounded" data-id="<?php echo $value->idabsen_kegiatan; ?>" data-status="2"><i class='fa fa-ban'></i></button>
                        <?php endif; ?>
                      <?php elseif ($value->status_aproval == 2): ?>
                        <h5><span class="badge bg-danger">Ditolak</span></h5>
                        <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                          <button class="btn btn-success btn-sm btn-approval mt-1 btn-rounded" data-id="<?php echo $value->idabsen_kegiatan; ?>" data-status="1"><i class='fa fa-check'></i></button>
                        <?php endif; ?>
                      <?php else: ?>
                        <h5><span class="badge bg-warning">Menunggu</span></h5>
                        <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
                          <button class="btn btn-success btn-sm btn-approval mr-1 btn-rounded" data-id="<?php echo $value->idabsen_kegiatan; ?>" data-status="1"><i class='fa fa-check'></i></button>
                          <button class="btn btn-danger btn-sm btn-approval btn-rounded" data-id="<?php echo $value->idabsen_kegiatan; ?>" data-status="2"><i class='fa fa-ban'></i></button>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="printableArea row" hidden>
    <table class="col-12" border="0">
      <tr>
        <td align="center"><h1>Laporan Presensi Kegiatan</h1>
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
          <td>: <?php echo $kegiatan['nama_gedung'].", ".$kegiatan['nama_kampus'] ?></td>
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
            <?php $no=1; foreach ($peserta->result() as $value): ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td> <img src="<?php echo base_url().$value->foto ?>" width="70px"> </td>
                <td><?php echo $value->nama_pegawai; ?></td>
                <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></td>
                <td><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
                <td><?php if ($value->status_lokasi == 1) {
                    echo "Di Lokasi";
                  }else {
                    echo "Dilakukan Secara Online";
                  } ?></td>
                <td> <?php
                  $jam_jadwal  = strtotime($kegiatan['jam_mulai']);
                  $masuk       = strtotime(date("H:i:s", strtotime($value->jam_presensi)));
                  $diff  = $masuk - $jam_jadwal;
                  if ($diff <= 0) {
                    echo 'Tepat Waktu';
                  }else {
                    $toleransi = strtotime(date("H:i:s", strtotime("00:30:00"))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                    if ($diff <= $toleransi) {
                      echo 'Toleransi';
                    }else {
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

    <script src="<?php echo base_url() ?>/desain/dist/js/pages/jquery.PrintArea.js" type="text/JavaScript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $("#print").click(function() {
          var mode = 'iframe'; //popup
          var close = mode == "popup";
          var options = {
            mode: mode,
            popClose: close
          };
          $("div.printableArea").printArea(options);
        });

        $(document).on("click", ".btn-approval", function() {
          var id     = $(this).data("id");
          var status = $(this).data("status");
          var label  = status == 1 ? "menyetujui" : "menolak";
          if (!confirm("Yakin ingin " + label + " presensi ini?")) return;
          $.ajax({
            url: "<?php echo base_url(); ?>Laporan/approval_kegiatan",
            type: "POST",
            data: { idabsen_kegiatan: id, status: status },
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
      });
    </script>
