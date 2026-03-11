
<script type="text/javascript">

$(document).ready(function(){
  <?php foreach ($luar_jam as $value): ?>
    getMaps("luar_jam<?php echo $value->idpresensi_lokasi ?>",<?php echo $value->latitude ?>, <?php echo $value->longtitude ?>);
  <?php endforeach; ?>
});
</script>
<div class="vtabs">
    <ul class="nav nav-tabs tabs-vertical" role="tablist">
        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#presensi" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Presensi</span> </a> </li>
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#luarjam" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Presensi Luar Jam</span></a> </li>
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#kegiatan" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Kegiatan</span></a> </li>
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#cuti" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Cuti</span></a> </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="presensi" role="tabpanel">
            <div class="p-20">
              <div class="table-responsive">
              <table class="display nowrap table table-hover table-striped table-bordered table-print">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Presensi Datang</th>
                    <th>Istirahat</th>
                    <th>Presensi Pulang</th>
                    <!-- <th>Jam Kerja<br>(dengan Jam Istirahat)</th>
                    <th>Lokasi</th> -->
                    <th>Status Tepat Waktu</th>
                    <th>Status Approval</th>
                    <th>Detail</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no=1;
                  $total_valid = 0;
                  $tidak_valid = 0;
                  $pulang_awal = 0;
                  $terlambat   = 0;
                  foreach ($presensi as $value):
                    $total_jam = 0;
                    $total_menit = 0;
                    $totalkerja = 0 ;
                    $validpresensi = 0;
                    $s_tepat = 0;
                    $s_terlambat = 0;
                    $keterangan_jam = "";
                    $absenpulang = $this->ModelRiwayat->Pulang($value->idabsensi)->row_array();
                    $istirahat        = $this->ModelRiwayat->get_Absensi_Istirahat($value->pegawai_uuid, date("Y-m-d", strtotime($value->waktu)));
                    $jam_istirahat = "Belum Melakukan Presensi Istirahat";
                    if ($istirahat->num_rows() > 0) {
                      $istirahat = $istirahat->row_array();
                      $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) ." - Selesai Istirahat Belum Presensi";
                      $selesaiIstirahat = $this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"]);
                      if ($selesaiIstirahat->num_rows() > 0) {
                        $selesaiIstirahat = $selesaiIstirahat->row_array();
                        $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) ." - ". date("H:i:s", strtotime($selesaiIstirahat['waktu']));
                      }
                    }
                    $status_absensi = "<span class='badge bg-danger'>Belum Di Setujui</span>";
                    $btn_approval = "";
                    if ($value->status_absensi == 1) {
                      $status_absensi = "<span class='badge bg-info'>Sudah Di Setujui</span>";
                    }
                    if (@$absenpulang['waktu'] == null) {
                      $total_jam = 0;
                      $total_menit = 0;
                      $tidak_valid += 1;
                    }else {
                      $datang = date_create($value->waktu);
                      $pulang = date_create($absenpulang['waktu']);
                      $diff = date_diff($datang, $pulang );
                      $total_jam = $total_jam+$diff->h - 1;
                      $total_menit = $total_menit+$diff->i;

                      $totalkerja = date("H:i:s", strtotime($diff->h.":".$diff->i.":00"));
                      $jadwal = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
                      $jam_masuk = date("Y-m-d H:i", strtotime(date("Y-m-d").$jadwal['jam_masuk']));
                      $jam_pulang= date("Y-m-d H:i", strtotime(date("Y-m-d").$jadwal['jam_pulang']));
                      if (strtotime($jam_pulang) < strtotime($jam_masuk)) {
                        $tgl_pulang = date("Y-m-d").$jadwal['jam_pulang'];
                        $jam_pulang= date("Y-m-d H:i", strtotime('+1 days', strtotime($tgl_pulang)));
                      }
                      $j_awal  = date_create($jam_masuk);
                      $j_akhir = date_create($jam_pulang); // waktu sekarang
                      $j_diff  = date_diff($j_awal, $j_akhir);
                      $j_totalkerja = date("H:i:s", strtotime($j_diff->h.":".$j_diff->i.":00"));

                      $jam_jadwal  = strtotime($value->jam_jadwal);
                      $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
                      $diff  = $masuk - $jam_jadwal;
                      if ($diff <= 0) {
                        // $tepat += 1;
                        $s_tepat = 1;
                      }else {
                        $jam_toleransi = $value->jam_toleransi;
                        if ($jam_toleransi == null || $jam_toleransi == "") {
                          $jam_toleransi = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array()['toleransi_kedatangan'];
                        }
                        $toleransi = strtotime(date("H:i:s", strtotime($jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                        if ($diff <= $toleransi) {
                          // $tepat += 1;
                          $s_tepat = 1;
                        }else {
                          // $terlambat += 1;
                          $s_terlambat = 1;
                        }
                      }
                      $jam_toleransi  = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
                      $jam_jadwal     = strtotime($jam_toleransi['jam_pulang']);
                      $pulang         = strtotime(date("H:i:s", strtotime($absenpulang['waktu'])));
                      $diff           = $pulang - $jam_jadwal;
                      if ($s_tepat == 1 && $diff >= 0) {
                        $validpresensi = 1;
                        $total_valid += 1;
                      }else {
                        if ($diff > 0 && $s_terlambat == 1) {
                          $tidak_valid += 1;
                        }else if ($s_terlambat == 1) {
                          $terlambat += 1;
                        }else if ($diff < 0) {
                           $pulang_awal += 1;
                        }
                      }
                    }
                    ?>
                    <tr class="<?php if(@$absenpulang['waktu'] == null || $validpresensi == 0) echo 'juicy-peach-gradient'; ?>">
                      <td><?php echo $no++ ?></td>
                      <td><?php echo date("d-m-Y", strtotime($value->waktu)) ?></td>
                      <td><?php echo date("H:i:s", strtotime($value->waktu)) ?></td>
                      <td><?php echo $jam_istirahat ?></td>
                      <td><?php if (@$absenpulang['waktu'] == null) {
                        echo "Belum Melakukan Presensi Pulang";
                      }else{
                        echo date("H:i:s", strtotime($absenpulang['waktu'] ));
                      }?></td>
                      <!-- <td>
                        <?php echo $total_jam." Jam ".$total_menit." Menit";?>
                      </td> -->
                      <!-- <td>
                        <?php if ($value->jenis_tempat == 1): ?>
                          WFO
                          <?php else: ?>
                            WFH
                        <?php endif; ?>
                      </td> -->
                      <td><?php
                        $jam_jadwal  = strtotime($value->jam_jadwal);
                        $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
                        $diff  = $masuk - $jam_jadwal;
                        if ($diff <= 0) {
                          echo '<span class="badge bg-success">Tepat Waktu</span>';
                          // $txtTW += 1;
                        }else {
                          $toleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                          if ($diff <= $toleransi) {
                            echo '<span class="badge bg-warning">Toleransi</span>';
                            // $txtTO += 1;
                          }else {
                            echo '<span class="badge bg-danger">Terlambat</span>';
                            // $txtTE += 1;
                          }
                        } ?></td>
                        <td>
                          <?php if ($value->status_absensi == 1): ?>
                              <span class='badge bg-info'>Sudah Di Setujui</span>
                              <br>
                              <a href="<?php echo base_url('Absensi/ditolak/'.$value->idabsensi.'/'.$value->pegawai_uuid) ?>" class="btn btn-sm peach-gradient">Tolak</a>
                            <?php else: ?>
                              <?php if ($value->status_absensi == 2): ?>
                                  <span class='badge bg-danger'>Ditolak</span>
                                <?php else: ?>
                                  <span class='badge bg-warning'>Belum Di Setujui</span>
                              <?php endif; ?>
                              <br>
                              <a href="<?php echo base_url('Absensi/approval/'.$value->idabsensi.'/'.$value->pegawai_uuid) ?>" class="btn btn-sm blue-gradient">Approval</a>
                          <?php endif; ?>
                        </td>
                        <td>
                          <a href="<?php echo base_url()?>Laporan/DetailLaporanPresensi/<?php echo $value->idabsensi;?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL"><i class="fas fa-info-circle"></i></a>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <table class="table table-hover table-striped">
                <tr>
                  <td>Presensi Valid</td>
                  <td><?=$total_valid?></td>
                </tr>
                <tr>
                  <td>Terlambat / Pulang Awal</td>
                  <td><?=$terlambat+$pulang_awal?></td>
                </tr>
                <tr>
                  <td>Tidak Valid</td>
                  <td><?=$tidak_valid?></td>
                </tr>
              </table>
            </div>
            </div>
        </div>
        <div class="tab-pane p-20" id="luarjam" role="tabpanel">
          <a class="float-left" >
            <button type="button" id="print_luarjam" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
          </a>
          <table class="display nowrap table table-hover table-striped table-bordered table-print">
            <thead>
              <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Foto</th>
                <th width="40%">Lokasi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach ($luar_jam as $value): ?>
                <tr>
                  <td><?php echo $no++ ?></td>
                  <td><?php echo date("d-m-Y", strtotime($value->waktu)) ?></td>
                  <td><?php echo date("H:i:s", strtotime($value->waktu)) ?></td>
                  <td> <img src="<?php echo base_url($value->foto) ?>" style="height:200px"> </td>
                  <td><div id="luar_jam<?php echo $value->idpresensi_lokasi ?>" style="width:100%; height:250px"></div></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="printableluarjam row" hidden>
            <table class="col-12" border="0">
              <tr>
                <td align="center"><h1>Laporan Diluar Jam Kerja</h1>
                </td>

              </tr>
            </table>
            <div class="col-6">
              <table width="100%" border="0">
                <tr>
                  <td>NIP</td>
                  <td>: <?php echo $pegawai['NIP'] ?></td>
                </tr>
                <tr>
                  <td>Email SSO</td>
                  <td>: <?php echo $pegawai['email'] ?></td>
                </tr>
                <tr>
                  <td>Nama Pegawai</td>
                  <td>: <?php echo $pegawai['nama_pegawai'] ?></td>
                </tr>
              </table>
            </div>
            <div class="col-6">
              <table width="100%" border="0">
                <tr>
                  <td>Tanggal Mulai</td>
                  <td>: <?php echo date("d-m-Y", strtotime($tgl_mulai)) ?></td>
                </tr>
                <tr>
                  <td>Tanggal Selesai</td>
                  <td>: <?php echo date("d-m-Y", strtotime($tgl_akhir)) ?></td>
                </tr>
              </table>
            </div>
            <div class="col-12">
              <br><br>
              <div class="table-responsive">
                <table class="display nowrap table table-hover table-striped table-bordered ">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Tanggal</th>
                      <th>Waktu</th>
                      <th>Foto</th>
                      <th width="40%">Lokasi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no=1; foreach ($luar_jam as $value): ?>
                      <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo date("d-m-Y", strtotime($value->waktu)) ?></td>
                        <td><?php echo date("H:i:s", strtotime($value->waktu)) ?></td>
                        <td> <img src="<?php echo base_url($value->foto) ?>" style="height:200px"> </td>
                        <td><div id="luar_jam<?php echo $value->idpresensi_lokasi ?>" style="width:100%; height:250px"></div></td>
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
                <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
               Penanggung Jawab Kegiatan
               <br>
               <br>
               <br>
               <br>
               <?php echo $ttd['nama_pegawai'] ?>
               <br>
               <?php echo $ttd['NIP'] ?>
              </div>
            </div>
        </div>
        <div class="tab-pane p-20" id="kegiatan" role="tabpanel">
          <table class="display nowrap table table-hover table-striped table-bordered table-print">
            <thead>
              <tr>
                <th>#</th>
                <th>Foto</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status Tepat Waktu</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach ($kegiatan as $value): ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td> <img src="<?php echo base_url().$value->foto ?>" width="100px"> </td>
                  <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></td>
                  <td><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
                  <td> <?php
                    $jam_jadwal  = strtotime($value->jam_mulai);
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
                  </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane p-20" id="cuti" role="tabpanel">
          <table class="display nowrap table table-hover table-striped table-bordered table-print">
            <thead>
              <tr>
                <th>#</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Akhir</th>
                <th>Jenis Cuti</th>
                <th>Alasan Cuti</th>
                <th>Status Cuti</th>
                <th>Download File</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach ($cuti as $value): ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td><?php echo date("d-m-Y", strtotime($value->tanggal_mulai)) ?></td>
                  <td><?php echo date("d-m-Y", strtotime($value->tanggal_akhir)) ?></td>
                  <td><?php echo $value->jenis_izin ?></td>
                  <td><?php echo $value->alasan ?></td>
                  <td> <?php if ($value->status == 1): ?>
                    <span class="badge bg-success">Approval</span>
                  <?php else: ?>
                    <span class="badge bg-warning">Di Tolak</span>
                  <?php endif; ?> </td>
                  <td><?php if ($value->file == ""): ?>
                    <span class="badge bg-primary">Tidak Ada File</span>
                  <?php else: ?>
                    <a href="<?php echo base_url().$value->file ?>" class="btn btn-info btn-sm"> <i class="fas fa-download"></i> Download File</a>
                  <?php endif; ?></td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
  $('.table-print').DataTable({
    dom: 'Bfrtip',
    buttons: ['excel'],
  });

  $("#print_luarjam").click(function() {
    var mode = 'iframe'; //popup
    var close = mode == "popup";
    var options = {
      mode: mode,
      popClose: close
    };
    $("div.printableluarjam").printArea(options);
  });
});
</script>
