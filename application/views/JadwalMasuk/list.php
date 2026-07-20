<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div></div>
        <h3 class="white-text mx-3">Jadwal Masuk</h3>
        <div>
          <a href="<?php echo base_url(); ?>JadwalMasuk/input" class="float-right">
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
          </a>
        </div>
      </div>

      <div class="card-body">

        <!-- Filter Jabatan & Pegawai -->
        <div class="row mb-3">
          <div class="col-md-4">
            <label>Filter Jabatan :</label>
            <select id="filter_jabatan" class="form-control select2">
              <option value="">-- Semua Jabatan --</option>
              <?php foreach ($jabatan as $jab): ?>
                <?php if ($jab->idjabatan != "adminr"): ?>
                  <option value="<?php echo $jab->idjabatan; ?>"><?php echo $jab->namajabatan; ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4" id="wrap_filter_pegawai" style="display:none;">
            <label>Filter Pegawai :</label>
            <select id="filter_pegawai" class="form-control select2">
              <option value="">-- Semua Pegawai (Jabatan Ini) --</option>
            </select>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button id="btn_reset_filter" class="btn btn-secondary btn-sm" style="display:none;">
              <i class="fas fa-times"></i> Reset
            </button>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="myTable" class="table color-table table-hover table-striped">
                    <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th width="5%">Jenis</th>
                        <th>Jabatan</th>
                        <th>Pegawai</th>
                        <th>Nama Jadwal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Total Jam Kerja</th>
                        <th>Waktu Istirahat Keluar</th>
                        <th>Waktu Istirahat Masuk</th>
                        <th>Toleransi Kedatangan</th>
                        <th>Toleransi Kepulangan</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody id="tbody_jadwal">
                      <?php
                      $no = 1;
                      foreach ($jadwalmasuk as $value): ?>
                        <tr data-jabatan="<?php echo $value->jabatan_idjabatan; ?>">
                          <td><?php echo $no ?></td>
                          <td>
                            <?php if ($value->jenis == 1): ?>
                              <span class="badge blue-gradient">WFO</span>
                            <?php elseif ($value->jenis == 2): ?>
                              <span class="badge aqua-gradient">WFH</span>
                            <?php else: ?>
                              <span class="badge purple-gradient">Mobile Unit</span>
                            <?php endif; ?>
                          </td>
                          <td><?php echo $value->namajabatan ?? $value->jabatan_idjabatan ?></td>
                          <td>
                            <?php if (!empty($value->pegawai_assigned)): ?>
                              <?php foreach ($value->pegawai_assigned as $peg): ?>
                                <span class="badge badge-info mr-1"><?php echo $peg->nama_pegawai ?></span>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <span class="text-muted"><i>Semua Pegawai</i></span>
                            <?php endif; ?>
                          </td>
                          <td><?php echo $value->nama ?></td>
                          <td><?php echo $value->jam_masuk ?></td>
                          <td><?php echo $value->jam_pulang ?></td>
                          <td><?php echo $value->total_jamkerja ?></td>
                          <td><?php echo $value->isti_keluar ?></td>
                          <td><?php echo $value->isti_masuk ?></td>
                          <td><?php echo $value->toleransi_kedatangan ?></td>
                          <td><?php echo $value->toleransi_kepulangan ?></td>
                          <td>
                            <a href="<?php echo base_url() ?>JadwalMasuk/edit/<?php echo $value->idjadwal_masuk; ?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>
                            <a href="<?php echo base_url() ?>JadwalMasuk/hapus/<?php echo $value->idjadwal_masuk; ?>" class="btn-floating btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a>
                          </td>
                        </tr>
                      <?php $no++;
                      endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var table = $('#myTable').DataTable();

    // Saat jabatan dipilih
    $('#filter_jabatan').on('change', function() {
      var idjabatan = $(this).val();

      if (!idjabatan) {
        // Reset semua filter
        $('#wrap_filter_pegawai').hide();
        $('#btn_reset_filter').hide();
        $('#filter_pegawai').val('').trigger('change');
        table.column(2).search('').draw();
        return;
      }

      // Filter tabel berdasarkan jabatan (column index 2)
      var jabatanText = $('#filter_jabatan option:selected').text();
      table.column(2).search(jabatanText).draw();

      $('#btn_reset_filter').show();

      // Load pegawai berdasarkan jabatan via AJAX
      $.ajax({
        url: '<?php echo base_url(); ?>JadwalMasuk/get_pegawai_by_jabatan',
        type: 'POST',
        data: {
          idjabatan: idjabatan
        },
        dataType: 'json',
        success: function(data) {
          var options = '<option value="">-- Semua Pegawai (Jabatan Ini) --</option>';
          if (data.length > 0) {
            $.each(data, function(i, pegawai) {
              options += '<option value="' + pegawai.uuid + '" data-nama="' + pegawai.nama_pegawai + '">' + pegawai.nama_pegawai + '</option>';
            });
            $('#wrap_filter_pegawai').show();
          } else {
            $('#wrap_filter_pegawai').hide();
          }
          $('#filter_pegawai').html(options);
          if ($('#filter_pegawai').hasClass('select2-hidden-accessible')) {
            $('#filter_pegawai').select2('destroy');
          }
          $('#filter_pegawai').select2();
        }
      });
    });

    // Saat pegawai dipilih — filter tabel kolom Pegawai (column index 3)
    $('#filter_pegawai').on('change', function() {
      var nama = $('#filter_pegawai option:selected').data('nama') || '';
      if ($(this).val() === '') {
        // Kembali ke filter jabatan saja
        var jabatanText = $('#filter_jabatan option:selected').text();
        table.column(2).search(jabatanText).column(3).search('').draw();
      } else {
        table.column(3).search(nama).draw();
      }
    });

    // Reset filter
    $('#btn_reset_filter').on('click', function() {
      table.column(2).search('').column(3).search('').draw();
      $('#filter_jabatan').val('').trigger('change');
      $('#filter_jabatan, #filter_pegawai').val(null).trigger('change.select2');
      $('#wrap_filter_pegawai').hide();
      $('#btn_reset_filter').hide();
    });
  });
</script>