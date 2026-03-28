<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">

      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div></div>
        <h3 class="white-text mx-3">PERSONAL DEVELOPMENT PLAN</h3>
        <div>
          <a href="<?php echo base_url(); ?>pdp/tambah" class="float-right">
            <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2"
                    data-toggle="tooltip" data-placement="top" title="Tambah Data Baru">
              <i class="fas fa-pencil-alt mt-0"></i>
            </button>
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table color-table table-hover table-striped" id="myTable">
            <thead>
              <tr>
                <th width="5%">#</th>
                <th>Judul Pelatihan</th>
                <th>Jenis Kegiatan</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>KPI</th>
                <th>Status</th>
                <th>Opsi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($pdp as $row) { ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row->judul_pelatihan; ?></td>
                <td><?php echo $row->jenis_kegiatan; ?></td>
                <td><?php echo date('d-m-Y', strtotime($row->tanggal_mulai)); ?></td>
                <td><?php echo date('d-m-Y', strtotime($row->tanggal_selesai)); ?></td>
                <td><?php echo $row->keterkaitan_kpi ? $row->keterkaitan_kpi : '-'; ?></td>
                <td>
                  <?php
                  $badge = 'secondary';
                  if ($row->status == 'pending')  $badge = 'warning';
                  if ($row->status == 'approved') $badge = 'success';
                  if ($row->status == 'rejected') $badge = 'danger';
                  ?>
                  <span class="badge badge-<?php echo $badge; ?>"><?php echo ucfirst($row->status); ?></span>
                </td>
                <td>
                  <a href="<?php echo base_url(); ?>pdp/peserta/<?php echo $row->id_pdp; ?>"
                     class="btn-floating btn-sm btn-primary"
                     data-toggle="tooltip" data-placement="top" title="Peserta">
                    <i class="fas fa-users"></i>
                  </a>
                  <a href="<?php echo base_url(); ?>pdp/edit/<?php echo $row->id_pdp; ?>"
                     class="btn-floating btn-sm btn-warning"
                     data-toggle="tooltip" data-placement="top" title="Edit">
                    <i class="fas fa-pen"></i>
                  </a>
                  <a href="<?php echo base_url(); ?>pdp/hapus/<?php echo $row->id_pdp; ?>"
                     onclick="return confirm('Yakin hapus data ini?')"
                     class="btn-floating btn-sm btn-danger"
                     data-toggle="tooltip" data-placement="top" title="Hapus">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>