<script type="text/javascript">
  $(document).ready(function() {
    <?php foreach ($luar_jam as $value): ?>
      getMaps("luar_jam<?php echo $value->idpresensi_lokasi ?>", <?php echo $value->latitude ?>, <?php echo $value->longtitude ?>);
    <?php endforeach; ?>

    // Initialize iCheck for checkboxes
    if ($.fn.iCheck) {
      $('.check').iCheck({
        checkboxClass: 'icheckbox_flat-red'
      });

      // Event handler untuk check-all
      $(document).on('ifChecked ifUnchecked', '#check-all', function(e) {
        var checked = (e.type === 'ifChecked');
        if ($.fn.iCheck) {
          checked ?
            $('.row-check').iCheck('check') :
            $('.row-check').iCheck('uncheck');
        }
        updateSelectedCount();
      });

      // Event handler untuk row-check
      $(document).on('ifChecked ifUnchecked', '.row-check', function() {
        syncCheckAll();
        updateSelectedCount();
      });
    }

    // Fallback untuk browser tanpa iCheck
    $(document).on('change', '#check-all', function() {
      var checked = $(this).is(':checked');
      $('.row-check').prop('checked', checked);
      updateSelectedCount();
    });

    $(document).on('change', '.row-check', function() {
      syncCheckAll();
      updateSelectedCount();
    });

    // Initial count update
    updateSelectedCount();
  });

  function syncCheckAll() {
    var total = $('.row-check').length;
    var checked = $('.row-check:checked').length;
    var isAll = total > 0 && checked === total;

    if ($.fn.iCheck) {
      if (isAll) {
        $('#check-all').iCheck('check');
      } else {
        $('#check-all').iCheck('uncheck');
      }
    } else {
      $('#check-all').prop('checked', isAll);
    }
  }

  function updateSelectedCount() {
    var count = $('.row-check:checked').length;
    if (count > 0) {
      $('#btn-approve-selected').prop('disabled', false).removeClass('disabled');
      $('#btn-approve-all').prop('disabled', false).removeClass('disabled');
    } else {
      $('#btn-approve-selected').prop('disabled', true).addClass('disabled');
      // Approve all tetap bisa diclick walau tidak ada yang dicheck
      var totalAvailable = $('.row-check').length;
      if (totalAvailable === 0) {
        $('#btn-approve-all').prop('disabled', true).addClass('disabled');
      }
    }
  }
</script>
</script>
<div class="vtabs">
  <ul class="nav nav-tabs tabs-vertical" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#presensi" role="tab">
        <span class="hidden-sm-up"><i class="ti-home"></i></span>
        <span class="hidden-xs-down">Presensi</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#luarjam" role="tab">
        <span class="hidden-sm-up"><i class="ti-user"></i></span>
        <span class="hidden-xs-down">Presensi Luar Jam</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#kegiatan" role="tab">
        <span class="hidden-sm-up"><i class="ti-user"></i></span>
        <span class="hidden-xs-down">Kegiatan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#cuti" role="tab">
        <span class="hidden-sm-up"><i class="ti-email"></i></span>
        <span class="hidden-xs-down">Cuti</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#lembur" role="tab">
        <span class="hidden-sm-up"><i class="ti-time"></i></span>
        <span class="hidden-xs-down">Lembur</span>
      </a>
    </li>
  </ul>
  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="presensi" role="tabpanel">
      <div class="p-20">
        <div class="mb-3">
          <button type="button" id="btn-approve-selected" class="btn btn-success btn-sm" onclick="approveSelected()">
            <i class="fas fa-check-double"></i> Approve Terpilih
          </button>
          <button type="button" id="btn-approve-all" class="btn btn-info btn-sm" onclick="approveAll()">
            <i class="fas fa-check-circle"></i> Approve Semua
          </button>
        </div>
        <div class="table-responsive">
          <table class="display nowrap table table-hover table-striped table-bordered table-print">
            <thead>
              <tr>
                <th class="text-center" width="5%">
                  <input type="checkbox" class="check" id="check-all" data-checkbox="icheckbox_flat-red">
                  <label for="check-all">#</label>
                </th>
                <th>Tanggal</th>
                <th>Presensi Datang</th>
                <th>Istirahat</th>
                <th>Presensi Pulang</th>
                <th>Status Tepat Waktu</th>
                <th>Status Approval</th>
                <th class="text-center">Detail</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $total_valid = 0;
              $tidak_valid = 0;
              $pulang_awal = 0;
              $terlambat   = 0;
              foreach ($presensi as $value):
                $total_jam = 0;
                $total_menit = 0;
                $totalkerja = 0;
                $validpresensi = 0;
                $s_tepat = 0;
                $s_terlambat = 0;
                $keterangan_jam = "";
                $absenpulang = $this->ModelRiwayat->Pulang($value->idabsensi)->row_array();
                $istirahat        = $this->ModelRiwayat->get_Absensi_Istirahat($value->pegawai_uuid, date("Y-m-d", strtotime($value->waktu)));
                $jam_istirahat = "<span class='badge bg-warning'>Kosong</span>";
                if ($istirahat->num_rows() > 0) {
                  $istirahat = $istirahat->row_array();
                  $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) . " - Selesai Istirahat Belum Presensi";
                  $selesaiIstirahat = $this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"]);
                  if ($selesaiIstirahat->num_rows() > 0) {
                    $selesaiIstirahat = $selesaiIstirahat->row_array();
                    $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) . " - " . date("H:i:s", strtotime($selesaiIstirahat['waktu']));
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
                } else {
                  $datang = date_create($value->waktu);
                  $pulang = date_create($absenpulang['waktu']);
                  $diff = date_diff($datang, $pulang);
                  $total_jam = $total_jam + $diff->h - 1;
                  $total_menit = $total_menit + $diff->i;

                  $totalkerja = date("H:i:s", strtotime($diff->h . ":" . $diff->i . ":00"));
                  $jadwal = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
                  $jam_masuk = date("Y-m-d H:i", strtotime(date("Y-m-d") . $jadwal['jam_masuk']));
                  $jam_pulang = date("Y-m-d H:i", strtotime(date("Y-m-d") . $jadwal['jam_pulang']));
                  if (strtotime($jam_pulang) < strtotime($jam_masuk)) {
                    $tgl_pulang = date("Y-m-d") . $jadwal['jam_pulang'];
                    $jam_pulang = date("Y-m-d H:i", strtotime('+1 days', strtotime($tgl_pulang)));
                  }
                  $j_awal  = date_create($jam_masuk);
                  $j_akhir = date_create($jam_pulang); // waktu sekarang
                  $j_diff  = date_diff($j_awal, $j_akhir);
                  $j_totalkerja = date("H:i:s", strtotime($j_diff->h . ":" . $j_diff->i . ":00"));

                  $jam_jadwal  = strtotime($value->jam_jadwal);
                  $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
                  $diff  = $masuk - $jam_jadwal;
                  if ($diff <= 0) {
                    // $tepat += 1;
                    $s_tepat = 1;
                  } else {
                    $jam_toleransi = $value->jam_toleransi;
                    if ($jam_toleransi == null || $jam_toleransi == "") {
                      $jam_toleransi = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array()['toleransi_kedatangan'];
                    }
                    $toleransi = strtotime(date("H:i:s", strtotime($jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                    if ($diff <= $toleransi) {
                      // $tepat += 1;
                      $s_tepat = 1;
                    } else {
                      // $terlambat += 1;
                      $s_terlambat = 1;
                    }
                  }
                  $jam_toleransi  = $this->ModelJadwalMasuk->get_edit($value->idjadwal)->row_array();
                  $jam_jadwal     = strtotime($jam_toleransi['jam_pulang']);
                  $pulang         = strtotime(date("H:i:s", strtotime($absenpulang['waktu'])));
                  $diff           = $pulang - $jam_jadwal;

                  if ($value->status_absensi == 2) {
                    $tidak_valid += 1;
                  } else if ($s_tepat == 1 && $diff >= 0) {
                    $validpresensi = 1;
                    $total_valid += 1;
                  } else {
                    if ($diff > 0 && $s_terlambat == 1) {
                      $tidak_valid += 1;
                    } else if ($s_terlambat == 1) {
                      $terlambat += 1;
                    } else if ($diff < 0) {
                      $pulang_awal += 1;
                    }
                  }
                }
              ?>
                <tr class="<?php if (@$absenpulang['waktu'] == null || $validpresensi == 0) echo 'juicy-peach-gradient'; ?>">
                  <td class="text-center">
                    <?php if ($value->status_absensi != 1): ?>
                      <input type="checkbox" class="check row-check" value="<?php echo $value->idabsensi; ?>" data-uuid="<?php echo $value->pegawai_uuid; ?>" data-checkbox="icheckbox_flat-red">
                      <label for="row-check-<?php echo $value->idabsensi; ?>"><?php echo $no++ ?></label>
                    <?php endif; ?>
                  </td>
                  <td><?php echo date("d-m-Y", strtotime($value->waktu)) ?></td>
                  <td><?php echo date("H:i:s", strtotime($value->waktu)) ?></td>
                  <td><?php echo $jam_istirahat ?></td>
                  <td>
                    <?php if (@$absenpulang['waktu'] == null) {
                      echo "-";
                    } else {
                      echo date("H:i:s", strtotime($absenpulang['waktu']));
                    } ?>
                  </td>
                  <td class="text-center"><?php
                                          $jam_jadwal = strtotime($value->jam_jadwal);
                                          $masuk = strtotime(date("H:i:s", strtotime($value->waktu)));
                                          $diff = $masuk - $jam_jadwal;

                                          if ($diff <= 0) {
                                            echo '<span class="badge bg-success">Tepat Waktu</span>';
                                          } else {
                                            $toleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                                            if ($diff <= $toleransi) {
                                              echo '<span class="badge bg-warning">Toleransi</span>';
                                            } else {
                                              echo '<span class="badge bg-danger">Terlambat</span>';
                                            }
                                          }
                                          ?>
                  </td>
                  <td class="text-center">
                    <?php if ($value->status_absensi == 1): ?>
                      <span class='badge bg-info'>Sudah Di Setujui</span>
                      <?php if (!empty($value->keterangan_approval)): ?>
                        <div class="mt-1"><small class="text-muted"><i>Keterangan: <?php echo $value->keterangan_approval; ?></i></small></div>
                      <?php endif; ?>
                      <div class="mt-2">
                        <button class="btn btn-sm btn-danger btn-rounded" onclick="showModalTolak(<?php echo $value->idabsensi; ?>, '<?php echo $value->pegawai_uuid; ?>')">
                          <i class="fas fa-times"></i> Tolak</button>
                      </div>
                    <?php else: ?>
                      <?php if ($value->status_absensi == 2): ?>
                        <span class='badge bg-danger'>Ditolak</span>
                        <?php if (!empty($value->keterangan_approval)): ?>
                          <div class="mt-1"><small class="text-muted"><i>Alasan: <?php echo $value->keterangan_approval; ?></i></small></div>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class='badge bg-warning'>Belum Di Setujui</span>
                      <?php endif; ?>
                      <div class="mt-2">
                        <button class="btn btn-sm btn-info btn-rounded" onclick="showModalApproval(<?php echo $value->idabsensi; ?>, '<?php echo $value->pegawai_uuid; ?>')">
                          <i class="fas fa-check"></i> Approval</button>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a href="<?php echo base_url() ?>Laporan/DetailLaporanPresensi/<?php echo $value->idabsensi; ?>" class="btn btn-sm btn-primary btn-rounded" data-toggle="tooltip" data-placement="top" title="Detail">
                      <i class="fas fa-info-circle"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <div class="mt-4">
            <h5 class="mb-3">Ringkasan Presensi</h5>
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td width="70%">Presensi Valid</td>
                  <td class="text-center"><strong><?= $total_valid ?></strong></td>
                </tr>
                <tr>
                  <td>Terlambat / Pulang Awal</td>
                  <td class="text-center"><strong><?= $terlambat + $pulang_awal ?></strong></td>
                </tr>
                <tr>
                  <td>Tidak Valid</td>
                  <td class="text-center"><strong><?= $tidak_valid ?></strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane p-20" id="luarjam" role="tabpanel">
      <div class="mb-3">
        <button type="button" id="print_luarjam" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak Laporan">
          <i class="fas fa-print"></i> Print
        </button>
      </div>

      <div class="table-responsive">
        <table class="display nowrap table table-hover table-striped table-bordered table-print">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th>Tanggal</th>
              <th>Waktu</th>
              <th class="text-center">Foto</th>
              <th width="40%">Lokasi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            foreach ($luar_jam as $value): ?>
              <tr>
                <td class="text-center"><?php echo $no++ ?></td>
                <td><?php echo date("d-m-Y", strtotime($value->waktu)) ?></td>
                <td><?php echo date("H:i:s", strtotime($value->waktu)) ?></td>
                <td class="text-center">
                  <img src="<?php echo base_url($value->foto) ?>" style="height:200px; max-width:100%;" class="img-thumbnail">
                </td>
                <td>
                  <div id="luar_jam<?php echo $value->idpresensi_lokasi ?>" style="width:100%; height:250px"></div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="printableluarjam row" hidden>
        <table class="col-12" border="0">
          <tr>
            <td align="center">
              <h1>Laporan Diluar Jam Kerja</h1>
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
                <?php $no = 1;
                foreach ($luar_jam as $value): ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo date("d-m-Y", strtotime($value->waktu)) ?></td>
                    <td><?php echo date("H:i:s", strtotime($value->waktu)) ?></td>
                    <td> <img src="<?php echo base_url($value->foto) ?>" style="height:200px"> </td>
                    <td>
                      <div id="luar_jam<?php echo $value->idpresensi_lokasi ?>" style="width:100%; height:250px"></div>
                    </td>
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

    <!-- Printable Area Kegiatan -->
    <div class="printablekegiatan row" hidden>
      <table class="col-12" border="0">
        <tr>
          <td align="center">
            <h1>Laporan Kegiatan Pegawai</h1>
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
        <table class="display nowrap table table-hover table-striped table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Foto</th>
              <th>Tanggal</th>
              <th>Waktu</th>
              <th>Status Tepat Waktu</th>
              <th>Status Approval</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            foreach ($kegiatan as $value): ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td> <img src="<?php echo base_url() . $value->foto ?>" width="80px"> </td>
                <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></td>
                <td><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
                <td> <?php
                      $jam_jadwal  = strtotime($value->jam_mulai);
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
                <td class="text-center">
                  <?php if ($value->status_aproval == '1'): ?>
                    Disetujui
                  <?php elseif ($value->status_aproval == '2'): ?>
                    Ditolak
                  <?php else: ?>
                    Menunggu Approval
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="col-8">

      </div>
      <div class="col-4 text-center">
        <br><br>
        <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
        Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA
        <br>
        <br>
        <br>
        <br>
        <?php echo $ttd['nama_pegawai'] ?>
        <br>
        <?php echo $ttd['NIP'] ?>
      </div>
    </div>

    <div class="tab-pane p-20" id="kegiatan" role="tabpanel">
      <div class="mb-3">
        <button type="button" id="print_kegiatan" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak Laporan">
          <i class="fas fa-print"></i> Print
        </button>
      </div>

      <table class="display nowrap table table-hover table-striped table-bordered table-print">
        <thead>
          <tr>
            <th>#</th>
            <th>Foto</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Status Tepat Waktu</th>
            <th>Status Approval</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          foreach ($kegiatan as $value): ?>
            <tr>
              <td><?php echo $no++; ?></td>
              <td> <img src="<?php echo base_url() . $value->foto ?>" width="100px"> </td>
              <td><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></td>
              <td><?php echo date("H:i:s", strtotime($value->jam_presensi)); ?></td>
              <td> <?php
                    $jam_jadwal  = strtotime($value->jam_mulai);
                    $masuk       = strtotime(date("H:i:s", strtotime($value->jam_presensi)));
                    $diff  = $masuk - $jam_jadwal;
                    if ($diff <= 0) {
                      echo '<h5><span class="badge bg-success">Tepat Waktu</span></h5>';
                    } else {
                      $toleransi = strtotime(date("H:i:s", strtotime("00:30:00"))) - strtotime(date("H:i:s", strtotime("00:00:00")));
                      if ($diff <= $toleransi) {
                        echo '<h5><span class="badge bg-warning">Toleransi</span></h5>';
                      } else {
                        echo '<h5><span class="badge bg-danger">Terlambat</span></h5>';
                      }
                    } ?> </td>
              <td class="text-center">
                <?php if ($value->status_aproval == '1'): ?>
                  <span class="badge bg-success">Disetujui</span>
                <?php elseif ($value->status_aproval == '2'): ?>
                  <span class="badge bg-danger">Ditolak</span>
                <?php else: ?>
                  <span class="badge bg-warning">Menunggu Approval</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="tab-pane p-20" id="cuti" role="tabpanel">
      <div class="mb-3">
        <button type="button" id="print_cuti" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak Laporan">
          <i class="fas fa-print"></i> Print
        </button>
      </div>

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
          <?php $no = 1;
          foreach ($cuti as $value): ?>
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
                <?php endif; ?>
              </td>
              <td><?php if ($value->file == ""): ?>
                  <span class="badge bg-primary">Tidak Ada File</span>
                <?php else: ?>
                  <a href="<?php echo base_url() . $value->file ?>" class="btn btn-info btn-sm"> <i class="fas fa-download"></i> Download File</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Printable Area Cuti -->
    <div class="printablecuti row" hidden>
      <table class="col-12" border="0">
        <tr>
          <td align="center">
            <h1>Laporan Cuti/Perizinan Pegawai</h1>
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
        <table class="display nowrap table table-hover table-striped table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Akhir</th>
              <th>Jenis Cuti</th>
              <th>Alasan Cuti</th>
              <th>Status Cuti</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            foreach ($cuti as $value): ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo date("d-m-Y", strtotime($value->tanggal_mulai)) ?></td>
                <td><?php echo date("d-m-Y", strtotime($value->tanggal_akhir)) ?></td>
                <td><?php echo $value->jenis_izin ?></td>
                <td><?php echo $value->alasan ?></td>
                <td> <?php if ($value->status == 1): ?>
                    Disetujui
                  <?php else: ?>
                    Di Tolak
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="col-8">

      </div>
      <div class="col-4 text-center">
        <br><br>
        <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
        Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA
        <br>
        <br>
        <br>
        <br>
        <?php echo $ttd['nama_pegawai'] ?>
        <br>
        <?php echo $ttd['NIP'] ?>
      </div>
    </div>

    <div class="tab-pane p-20" id="lembur" role="tabpanel">
      <div class="mb-3">
        <button type="button" id="print_lembur" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Cetak Laporan">
          <i class="fas fa-print"></i> Print
        </button>
      </div>

      <div class="table-responsive">
        <table class="display nowrap table table-hover table-striped table-bordered table-print">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Status Approval</th>
              <th class="text-center">Foto</th>
              <th>Jam Mulai</th>
              <th>Jam Selesai</th>
              <th>Durasi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            $totalDurasi = 0;
            foreach ($lembur as $value):
              $durasiMenit = strtotime($value->jam_presensi_selesai) - strtotime($value->jam_presensi);
              if ($value->status_aproval == '1') {
                $totalDurasi = $totalDurasi + $durasiMenit;
              }
            ?>
              <tr>
                <td class="text-center"><?php echo $no++; ?></td>
                <td class="text-center">
                  <?php if ($value->status_aproval == '1'): ?>
                    <span class="badge bg-success">Disetujui</span>
                  <?php elseif ($value->status_aproval == '2'): ?>
                    <span class="badge bg-danger">Ditolak</span>
                  <?php else: ?>
                    <span class="badge bg-warning">Menunggu Approval</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <img src="<?php echo base_url() . $value->foto ?>" width="100px" class="img-thumbnail">
                </td>
                <td>
                  <?php echo date("H:i:s", strtotime($value->jam_presensi)) ?><br>
                  <small><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></small>
                </td>
                <td>
                  <?php echo date("H:i:s", strtotime($value->jam_presensi_selesai)) ?><br>
                  <small><?php echo date("d-m-Y", strtotime($value->jam_presensi_selesai)) ?></small>
                </td>
                <td>
                  <?php
                  $jam = floor($durasiMenit / 60);
                  $menit = $durasiMenit % 60;
                  echo $jam . " Jam " . $menit . " Menit"; ?>

                </td>

              </tr>
            <?php endforeach; ?>
            <tr>
              <td colspan="3" class="text-center">Total Lembur</td>
              <td colspan="3" class="text-center">
                <?php
                $jam = floor($totalDurasi / 60);
                $menit = $totalDurasi % 60;
                echo $jam . " Jam " . $menit . " Menit"; ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Printable Area Lembur -->
    <div class="printablelembur row" hidden>
      <table class="col-12" border="0">
        <tr>
          <td align="center">
            <h1>Laporan Lembur Pegawai</h1>
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
        <table class="display nowrap table table-hover table-striped table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Status Approval</th>
              <th>Foto</th>
              <th>Jam Mulai</th>
              <th>Jam Selesai</th>
              <th>Durasi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            $totalDurasi_print = 0;
            foreach ($lembur as $value):
              $durasiMenit_print = strtotime($value->jam_presensi_selesai) - strtotime($value->jam_presensi);
              if ($value->status_aproval == '1') {
                $totalDurasi_print = $totalDurasi_print + $durasiMenit_print;
              }
            ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td class="text-center">
                  <?php if ($value->status_aproval == '1'): ?>
                    Disetujui
                  <?php elseif ($value->status_aproval == '2'): ?>
                    Ditolak
                  <?php else: ?>
                    Menunggu Approval
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <img src="<?php echo base_url() . $value->foto ?>" width="80px">
                </td>
                <td>
                  <?php echo date("H:i:s", strtotime($value->jam_presensi)) ?><br>
                  <small><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></small>
                </td>
                <td>
                  <?php echo date("H:i:s", strtotime($value->jam_presensi_selesai)) ?><br>
                  <small><?php echo date("d-m-Y", strtotime($value->jam_presensi_selesai)) ?></small>
                </td>
                <td>
                  <?php
                  $jam_p = floor($durasiMenit_print / 60);
                  $menit_p = $durasiMenit_print % 60;
                  echo $jam_p . " Jam " . $menit_p . " Menit"; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <tr>
              <td colspan="3" class="text-center"><strong>Total Lembur (Disetujui)</strong></td>
              <td colspan="3" class="text-center">
                <strong><?php
                        $jam_total = floor($totalDurasi_print / 60);
                        $menit_total = $totalDurasi_print % 60;
                        echo $jam_total . " Jam " . $menit_total . " Menit"; ?></strong>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-8">

      </div>
      <div class="col-4 text-center">
        <br><br>
        <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
        Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA
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
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.table-print').DataTable({
      dom: 'Bfrtip',
    });

    $("#print_luarjam").click(function() {
      var mode = 'iframe';
      var close = mode == "popup";
      var options = {
        mode: mode,
        popClose: close
      };
      $("div.printableluarjam").printArea(options);
    });

    $("#print_kegiatan").click(function() {
      var mode = 'iframe';
      var close = mode == "popup";
      var options = {
        mode: mode,
        popClose: close
      };
      $("div.printablekegiatan").printArea(options);
    });

    $("#print_cuti").click(function() {
      var mode = 'iframe';
      var close = mode == "popup";
      var options = {
        mode: mode,
        popClose: close
      };
      $("div.printablecuti").printArea(options);
    });

    $("#print_lembur").click(function() {
      var mode = 'iframe';
      var close = mode == "popup";
      var options = {
        mode: mode,
        popClose: close
      };
      $("div.printablelembur").printArea(options);
    });
  });

  // Toggle Check All - sudah tidak diperlukan karena menggunakan iCheck
  // function toggleCheckAll() sudah digantikan dengan event handler iCheck di atas

  // Approve Selected
  function approveSelected() {
    var checkboxes = $('.row-check:checked');
    if (checkboxes.length === 0) {
      alert('Silahkan pilih data yang akan di-approve terlebih dahulu!');
      return;
    }

    if (!confirm('Apakah Anda yakin ingin approve ' + checkboxes.length + ' data presensi yang dipilih?')) {
      return;
    }

    var approvalData = [];
    checkboxes.each(function() {
      approvalData.push({
        idabsensi: $(this).val(),
        uuid: $(this).attr('data-uuid')
      });
    });

    // Disable button
    $('#btn-approve-selected').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

    // Kirim data ke server
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Absensi/approve_multiple",
      data: {
        data: JSON.stringify(approvalData)
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result.status == 200) {
          alert('Berhasil approve ' + result.count + ' data presensi!');
          location.reload();
        } else {
          alert('Gagal melakukan approval: ' + result.message);
          $('#btn-approve-selected').prop('disabled', false).html('<i class="fas fa-check-double"></i> Approve Terpilih');
        }
      },
      error: function(e) {
        alert('Terjadi kesalahan saat melakukan approval');
        $('#btn-approve-selected').prop('disabled', false).html('<i class="fas fa-check-double"></i> Approve Terpilih');
      }
    });
  }

  // Approve All
  function approveAll() {
    var checkboxes = $('.row-check');
    if (checkboxes.length === 0) {
      alert('Tidak ada data presensi yang perlu di-approve!');
      return;
    }

    if (!confirm('Apakah Anda yakin ingin approve SEMUA (' + checkboxes.length + ') data presensi yang belum di-approve?')) {
      return;
    }

    var approvalData = [];
    checkboxes.each(function() {
      approvalData.push({
        idabsensi: $(this).val(),
        uuid: $(this).attr('data-uuid')
      });
    });

    // Disable button
    $('#btn-approve-all').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

    // Kirim data ke server
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>Absensi/approve_multiple",
      data: {
        data: JSON.stringify(approvalData)
      },
      success: function(response) {
        var result = JSON.parse(response);
        if (result.status == 200) {
          alert('Berhasil approve semua (' + result.count + ') data presensi!');
          location.reload();
        } else {
          alert('Gagal melakukan approval: ' + result.message);
          $('#btn-approve-all').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Approve Semua');
        }
      },
      error: function(e) {
        alert('Terjadi kesalahan saat melakukan approval');
        $('#btn-approve-all').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Approve Semua');
      }
    });
  }
</script>

<!-- Modal Approval -->
<div class="modal fade" id="modalApproval" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Approval Presensi</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formApproval" method="POST" action="<?php echo base_url('Absensi/approval_with_note'); ?>">
        <div class="modal-body">
          <input type="hidden" name="idabsensi" id="approval_idabsensi">
          <input type="hidden" name="uuid" id="approval_uuid">
          <div class="form-group">
            <label>Keterangan Approval <small class="text-muted">(Opsional)</small></label>
            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan approval (opsional)"></textarea>
            <small class="form-text text-muted">Contoh: Presensi sudah sesuai, Sudah dikonfirmasi dengan atasan, dll.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-check"></i> Setujui
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Tolak Presensi</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formTolak" method="POST" action="<?php echo base_url('Absensi/ditolak_with_note'); ?>">
        <div class="modal-body">
          <input type="hidden" name="idabsensi" id="tolak_idabsensi">
          <input type="hidden" name="uuid" id="tolak_uuid">
          <div class="form-group">
            <label>Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan alasan penolakan" required></textarea>
            <small class="form-text text-muted">Contoh: Presensi tidak sesuai lokasi, Waktu tidak valid, dll.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-times"></i> Tolak
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  function showModalApproval(idabsensi, uuid) {
    $('#approval_idabsensi').val(idabsensi);
    $('#approval_uuid').val(uuid);
    $('#modalApproval').modal('show');
  }

  function showModalTolak(idabsensi, uuid) {
    $('#tolak_idabsensi').val(idabsensi);
    $('#tolak_uuid').val(uuid);
    $('#modalTolak').modal('show');
  }
</script>