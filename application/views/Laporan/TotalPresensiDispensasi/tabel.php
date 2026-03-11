<a class="float-left" >
  <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
</a>
<table id="table-print" class="display nowrap table table-hover table-striped table-bordered">
  <thead>
    <tr>
      <th>NO</th>
      <!-- <th>Bulan - Tahun</th> -->
      <?php if ($tipe_pegawai == "Pegawai Negeri Sipil (PNS)"): ?>
        <th>NIP</th>
      <?php elseif($tipe_pegawai == "Pegawai Luar Biasa (LB)" || $tipe_pegawai == "Pegawai Kontrak"): ?>
        <th>KTP / NIK</th>
      <?php else: ?>
        <th>NIP / KTP / NIK</th>
      <?php endif; ?>
      <th>Nama Lengkap</th>
      <th>Unit Saat Ini</th>
      <th>Jabatan Saat Ini</th>
      <th>Jml. Presensi</th>
      <!-- <th>Jam Presensi</th> -->
      <th>Detail</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; foreach ($pegawai->result() as $data): ?>
      <tr>
        <td><?php echo $no++ ?></td>
        <!-- <td><?php echo date("m-Y", strtotime($tgl_mulai)) ?></td> -->
        <td>`<?php if ($data->tipe_pegawai == "Pegawai Negeri Sipil (PNS)") {
          echo $data->NIP;
        }else {
          echo $data->NIK;
        }?></td>
        <td><?php echo $data->nama_pegawai ?></td>
        <td><?php echo $data->jenis_unit." ".$data->unit ?></td>
        <td><?php echo $data->jab_struktur ?></td>
        <?php
        $total = 0;
        $wfh=0; $wfo=0;
        $terlambat = 0;
        $tepat = 0;
        $efektif_jam = 0;
        $efektif_menit = 0;
        $hitung_presensi = $this->ModelLaporan->rekapPresensiDouble($data->uuid,$tgl_mulai,$tgl_akhir)->result();
        if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
          $hitung_presensi = $this->ModelLaporan->rekapPresensiDouble($data->uuid,$tgl_mulai,$tgl_akhir)->result();
        }
        foreach ($hitung_presensi as $value) {
          $hari = date("D", strtotime($value->waktu));
          $hari_libur = $this->ModelLibur->getDataLibur(date("d-m-Y", strtotime($value->waktu)))->num_rows();
          $hari_izin = $this->ModelPerizinan->cek_izin($data->uuid, date("Y-m-d", strtotime($value->waktu)))->num_rows();
          $hari_dinas = $this->ModelDinasLuar->cekDinasLuar($data->uuid, date("Y-m-d", strtotime($value->waktu)))->num_rows();
          $presensi_pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi)->row_array();
          if ($hari_dinas < 1) {
            if ($hari_dinas > 0 || $hari_izin > 0 || $hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
              if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
                if ($value->jenis_tempat == 1) {
                  $wfo += 1;
                }elseif ($value->jenis_tempat == 2) {
                  $wfh += 1;
                }
              }
            }else {
              if ($value->jenis_tempat == 1) {
                $wfo += 1;
              }elseif ($value->jenis_tempat == 2) {
                $wfh += 1;
              }
            }


            $datang = date_create($value->waktu);
            if (@$presensi_pulang['waktu'] != null) {
              $pulang = date_create($presensi_pulang['waktu']);
              $diff = date_diff($datang, $pulang );

              $hari = date("D", strtotime($value->waktu));
              if ($hari_dinas > 0 || $hari_izin > 0 || $hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
                if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
                  $efektif_jam = $efektif_jam+$diff->h - 1;
                  $efektif_menit = $efektif_menit+$diff->i;
                }
              }else {
                $efektif_jam = $efektif_jam+$diff->h - 1;
                $efektif_menit = $efektif_menit+$diff->i;
              }
            }
          }
        }
        $efektif_jam = $efektif_jam + floor($efektif_menit/60);
        $efektif_menit = $efektif_menit % 60;
        ?>
        <td><?php echo $wfo+$wfh ?></td>
        <!-- <td><?php echo $efektif_jam." Jam ".$efektif_menit." Menit";?></td> -->
        <td>
          <a href="<?php echo base_url()?>Laporan/DetailRekap/<?php echo $data->uuid;?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="DETAIL"><i class="fas fa-info-circle"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="printableArea row" hidden>
  <div class="col-12">
    <table width="100%" border="0">
      <tr>
        <td width="15%">Lampiran </td>
        <td id="txt_lampiran"></td>
      </tr>
      <tr>
        <td>Nomor </td>
        <td id="txt_nomor"></td>
      </tr>
      <tr>
        <td>Tanggal </td>
        <td id="txt_tgl_surat"></td>
        <!-- <td>: <?php echo date("d-m-Y") ?></td> -->
      </tr>
      <tr>
        <td>Bulan </td>
        <td id="txt_bulan_surat"></td>
        <!-- <td>: <?php echo date("m-Y", strtotime($tgl_mulai)) ?></td> -->
      </tr>
      <tr>
        <td>Jenis Pegawai : </td>
        <td id="txt_jenis_pegawai"></td>
        <!-- <td>: <?php echo $tipe_pegawai = ($tipe_pegawai == "") ? "Semua" : $tipe_pegawai ; ?></td> -->
      </tr>
    </table>
  </div>
  <div class="col-3">
    <!-- <table width="100%" border="0">
      <tr>
        <td>Tepat Waktu</td>
        <td>: <b class="txtTW"></b></td>
      </tr>
      <tr>
        <td>Toleransi</td>
        <td>: <b class="txtTO"></b></td>
      </tr>
      <tr>
        <td>Terlambat</td>
        <td>: <b class="txtTE"></b></td>
      </tr>
    </table> -->
  </div>
  <div class="col-12">
    <br><br>
    <table class="table table-hover table-striped" border="0" width="100%">
      <thead>
        <tr>
          <th>NO</th>
          <!-- <th>Bulan - Tahun</th> -->
          <?php if ($tipe_pegawai == "Pegawai Negeri Sipil (PNS)"): ?>
            <th>NIP</th>
          <?php elseif($tipe_pegawai == "Pegawai Luar Biasa (LB)" || $tipe_pegawai == "Pegawai Kontrak"): ?>
            <th>KTP / NIK</th>
          <?php else: ?>
            <th>NIP / KTP / NIK</th>
          <?php endif; ?>
          <th>Nama Lengkap</th>
          <th width="3%">Unit Saat Ini</th>
          <th>Jabatan Saat Ini</th>
          <th>Jml. Presensi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total_jumlah = 0;
        $no=1; foreach ($pegawai->result() as $data): ?>
        <td><?php echo $no++ ?></td>
        <td><?php if ($data->tipe_pegawai == "Pegawai Negeri Sipil (PNS)") {
          echo $data->NIP;
        }else {
          echo $data->NIK;
        }?></td>
        <td><?php echo $data->nama_pegawai ?></td>
        <td><?php echo $data->jenis_unit." ".$data->unit ?></td>
        <td><?php echo $data->jab_struktur ?></td>
        <?php
        $total = 0;
        $wfh=0; $wfo=0;
        $terlambat = 0;
        $tepat = 0;
        $efektif_jam = 0;
        $efektif_menit = 0;
        $hitung_presensi = $this->ModelLaporan->rekapPresensiDouble($data->uuid,$tgl_mulai,$tgl_akhir)->result();
        if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
          $hitung_presensi = $this->ModelLaporan->rekapPresensiDouble($data->uuid,$tgl_mulai,$tgl_akhir)->result();
        }
        foreach ($hitung_presensi as $value) {
          $hari = date("D", strtotime($value->waktu));
          $hari_libur = $this->ModelLibur->getDataLibur(date("d-m-Y", strtotime($value->waktu)))->num_rows();
          $hari_izin = $this->ModelPerizinan->cek_izin($data->uuid, date("Y-m-d", strtotime($value->waktu)))->num_rows();
          $hari_dinas = $this->ModelDinasLuar->cekDinasLuar($data->uuid, date("Y-m-d", strtotime($value->waktu)))->num_rows();
          $presensi_pulang = $this->ModelAbsensi->get_AbsensiPulang($value->idabsensi)->row_array();
          if ($hari_dinas < 1) {
            if ($hari_dinas > 0 || $hari_izin > 0 || $hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
              if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
                if ($value->jenis_tempat == 1) {
                  $wfo += 1;
                }elseif ($value->jenis_tempat == 2) {
                  $wfh += 1;
                }
              }
            }else {
              if ($value->jenis_tempat == 1) {
                $wfo += 1;
              }elseif ($value->jenis_tempat == 2) {
                $wfh += 1;
              }
            }


            $datang = date_create($value->waktu);
            if (@$presensi_pulang['waktu'] != null) {
              $pulang = date_create($presensi_pulang['waktu']);
              $diff = date_diff($datang, $pulang );

              $hari = date("D", strtotime($value->waktu));
              if ($hari_dinas > 0 || $hari_izin > 0 || $hari_libur > 0 || $hari == "Sat" || $hari == "Sun") {
                if ($data->jab_struktur == "Anggota Satpam" || $data->jab_struktur == "Waker" || $data->jab_struktur == "Parkir") {
                  $efektif_jam = $efektif_jam+$diff->h - 1;
                  $efektif_menit = $efektif_menit+$diff->i;
                }
              }else {
                $efektif_jam = $efektif_jam+$diff->h - 1;
                $efektif_menit = $efektif_menit+$diff->i;
              }
            }
          }
        }
        $efektif_jam = $efektif_jam + floor($efektif_menit/60);
        $efektif_menit = $efektif_menit % 60;
        $total_jumlah = ($wfo+$wfh) + $total_jumlah;
        ?>
        <td><?php echo $wfo+$wfh ?></td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <th colspan="5">Jumlah</th>
      <th><?php echo $total_jumlah ?></th>
      <th></th>
    </tr>
  </tbody>
</table>
</div>
<br>
<br>
<div class="col-7">

</div>
<div class="col-5 text-center">
  <?php $ttd = $this->ModelPegawai->get_direktur()->row_array(); ?>
  Direktur Politeknik Negeri Jember
  <br>
  <br>
  <br>
  <br>
  <?php echo $ttd['nama_pegawai'] ?>
  <br>
  <?php echo $ttd['NIP'] ?>
</div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#print").click(function() {
      var lampiran  = $('#lampiran').val();
      var nomor  = $('#nomor').val();
      var tgl_surat  = $('#tgl_surat').val();
      var bulan_surat  = $('#bulan_surat').val();
      var jenis_pegawai  = $('#jenis_pegawai').val();
      $("#txt_lampiran").html(": "+lampiran);
      $("#txt_nomor").html(": "+nomor);
      $("#txt_tgl_surat").html(": "+tgl_surat);
      $("#txt_bulan_surat").html(": "+bulan_surat);
      $("#txt_jenis_pegawai").html(": "+jenis_pegawai);

      var mode = 'iframe'; //popup
      var close = mode == "popup";
      var options = {
        mode: mode,
        popClose: close
      };
      $("div.printableArea").printArea(options);
    });
  });
</script>
