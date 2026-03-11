      <a class="float-left" >
        <button type="button" id="print" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Laporan"><i class="fas fa-print"></i> PRINT</button>
      </a>
      <table id="table-print" class="display nowrap table table-hover table-striped table-bordered print-view">
        <thead>
          <tr>
            <th>NO</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; foreach ($pegawai as $value): ?>
            <?php if ($this->ModelAbsensi->cek_Absensi($value->uuid, $tanggal)->num_rows() < 1): ?>
              <tr>
                <td><?=$no++?></td>
                <td><?=$value->NIK?></td>
                <td><?=$value->nama_pegawai?></td>
                <td><?php echo date("d-m-Y", strtotime($tanggal)) ?></td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
      </tbody>
    </table>

    <div class="printableArea row" hidden>
      <table class="col-12" border="0">
        <tr>
          <td align="center"><h1>Laporan Tidak Presensi</h1></td>
        </tr>
      </table>
      <div class="col-6">
        <table width="100%" border="0">
          <tr>
            <td>Tanggal </td>
            <td>: <?php echo date("d-m-Y", strtotime($tanggal)) ?></td>
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
                <th>NIP</th>
                <th>Nama</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; foreach ($pegawai as $value): ?>
                <?php if ($this->ModelAbsensi->cek_Absensi($value->uuid, $tanggal)->num_rows() < 1): ?>
                  <tr>
                    <td><?=$no++?></td>
                    <td><?=$value->NIK?></td>
                    <td><?=$value->nama_pegawai?></td>
                    <td><?php echo date("d-m-Y", strtotime($tanggal)) ?></td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- <div class="col-4 text-center">
        <?php $ttd = $this->ModelPegawai->get_kepala_kepegawaian()->row_array(); ?>
        Kepala SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA
        <br>
        <br>
        <br>
        <br>
        <?php echo $ttd['nama_pegawai'] ?>
        <br>
        <?php echo $ttd['NIP'] ?>
      </div> -->
    </div>

    <script type="text/javascript">
      $(document).ready(function(){
        // $(".txtTW").html("<?php echo $txtTW; ?>");
        // $(".txtTO").html("<?php echo $txtTO; ?>");
        // $(".txtTE").html("<?php echo $txtTE; ?>");

        $("#print").click(function() {
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
