<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="row">

        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <strong>JABATAN</strong>
              <small> Form Input</small>
            </div>
            <div class="card-body card-block">
              <?php echo form_open_multipart('Jabatan/insert');?>
              <?php echo @$error;?>

              <div class="card">
              <div class="card-body card-block">


              <div class="row form-group">
                <div class="col col-md-3">
                  <label for="text-input" class=" form-control-label">Kode Jabatan</label>
                </div>
                <div class="col-12 col-md-9">
                  <input type="text" name="kode" id="kode" class="form-control" placeholder="Kode Jabatan" value="<?php echo @$idjabatan; ?>" required >
                </div>
              </div>

              <div class="row form-group">
                <div class="col col-md-3">
                  <label for="text-input" class=" form-control-label">Nama Jabatan</label>
                </div>
                <div class="col-12 col-md-9">
                  <input type="text" name="namajabatan" id="namajabatan" class="form-control" placeholder="Nama Jabatan" value="<?php echo @$namajabatan; ?>" required>
                </div>
              </div>


              <div class="row form-group">
                <div class="col col-md-3">
                  <label for="text-input" class=" form-control-label">Roles</label>
                </div>
                <div class="col-12 col-md-9">
                  <select multiple id="optgroup" name="roles[]">
                    <?php foreach ($group as $g): ?>
                      <optgroup label="<?php echo $g->nama_group ?>">
                        <?php foreach ($this->ModelUsers->get_roles($g->idgroup_roles)->result() as $r): ?>
                          <option value="<?php echo $r->roles ?>"><?php echo $r->roles ?></option>
                        <?php endforeach; ?>
                      </optgroup>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              </div>

                  </div>
              <button type="submit" class="btn btn-primary btn-sm">SIMPAN</button>
              <?php echo form_close(); ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
