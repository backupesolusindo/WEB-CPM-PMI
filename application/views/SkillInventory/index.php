<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">

      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <h3 class="white-text mx-3">MASTER SKILL (DM Skill)</h3>
        <div>
          <a href="<?php echo base_url(); ?>SkillInventory/skill_karyawan" class="btn btn-outline-white btn-rounded btn-sm px-2 mr-2">
            <i class="fas fa-users"></i> Data Skill Karyawan
          </a>
          <a href="<?php echo base_url(); ?>SkillInventory/tambah" class="btn btn-outline-white btn-rounded btn-sm px-2">
            <i class="fas fa-plus"></i> Tambah Skill
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="myTable">
            <thead class="blue lighten-4">
              <tr>
                <th>No</th>
                <th>Nama Skill</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th width="120">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($skill as $row) { ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row->nama_skill; ?></td>
                <td><?php echo $row->kategori ? $row->kategori : '<span class="text-muted">-</span>'; ?></td>
                <td><?php echo $row->deskripsi ? $row->deskripsi : '<span class="text-muted">-</span>'; ?></td>
                <td>
                  <a href="<?php echo base_url(); ?>SkillInventory/edit/<?php echo $row->id_skill; ?>" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="<?php echo base_url(); ?>SkillInventory/hapus/<?php echo $row->id_skill; ?>"
                     onclick="return confirm('Yakin hapus skill ini?')"
                     class="btn btn-sm btn-danger">
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