<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">

      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div></div>
        <h3 class="white-text mx-3">DATA CUTI TAHUNAN</h3>
        <div>
          <a href="<?php echo base_url(); ?>CutiTahunan/tambah" class="float-right">
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2"
              data-toggle="tooltip" title="Tambah Data">
              <i class="fas fa-pencil-alt mt-0"></i>
            </button>
          </a>
        </div>
      </div>

      <div class="card-body">

        <!-- Filter Tahun -->
        <div class="row mb-3">
          <div class="col-md-3">
            <label>Filter Tahun:</label>
            <select class="form-control"
              onchange="window.location='<?php echo base_url(); ?>CutiTahunan?tahun='+this.value">
              <option value="" <?php if ($tahun_aktif == '') echo 'selected'; ?>>-- Semua Tahun --</option>
              <?php foreach ($tahun_list as $t): ?>
                <option value="<?php echo $t->tahun_cuti; ?>"
                  <?php if ($tahun_aktif == $t->tahun_cuti) echo 'selected'; ?>>
                  <?php echo $t->tahun_cuti; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table color-table table-hover table-striped" id="myTable">
            <thead>
              <tr>
                <th width="5%">#</th>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Bagian</th>
                <th>Jabatan</th>
                <th>Total Cuti</th>
                <th>Tahun</th>
                <th>Opsi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              foreach ($cuti as $row): ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td><?php echo $row->NIP; ?></td>
                  <td><?php echo $row->nama_pegawai; ?></td>
                  <td><?php echo $row->unit; ?></td>
                  <td><?php echo $row->jab_struktur ? $row->jab_struktur : '-'; ?></td>
                  <td><b><?php echo $row->total_cuti; ?></b> hari</td>
                  <td><?php echo $row->tahun_cuti; ?></td>
                  <td>
                    <a href="<?php echo base_url(); ?>CutiTahunan/edit/<?php echo $row->idcuti_tahunan; ?>"
                      class="btn-floating btn-sm btn-warning"
                      data-toggle="tooltip" title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <a href="<?php echo base_url(); ?>CutiTahunan/hapus/<?php echo $row->idcuti_tahunan; ?>"
                      onclick="return confirm('Yakin hapus data ini?')"
                      class="btn-floating btn-sm btn-danger"
                      data-toggle="tooltip" title="Hapus">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($cuti)): ?>
                <tr>
                  <td colspan="8" class="text-center text-muted">Belum ada data.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>