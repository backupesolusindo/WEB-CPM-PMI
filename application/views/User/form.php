
<div class="card">
<div class="card-body card-block">
  <div class="row form-group">
          <div class="col col-md-3">
            <label for="agama" class=" form-control-label">Nama Pegawai</label>
          </div>
          <div class="col-12 col-md-9">
                <select name="nik" id="select" class="form-control select2" re>
                  <option>...Pilih Pegawai...</option>
                  <?php foreach ($pegawai as $data_pegawai): ?>
                    <option value="<?php echo $data_pegawai->uuid; ?>" <?php if ($data_pegawai->uuid == @$user['pegawai_NIK']): ?>
                      <?php echo 'selected'; ?>
                    <?php endif; ?>><?php echo $data_pegawai->NIP ?>
                    <?php if ($data_pegawai->email != null || $data_pegawai->email != "") {
                      echo " | ".$data_pegawai->email." | ";
                    } ?> <?php echo $data_pegawai->nama_pegawai ?></option>
                  <?php endforeach; ?>
                </select>
          </div>
  </div>

<div class="row form-group">
  <div class="col col-md-3">
    <label for="text-input" class=" form-control-label">Username (NIP / Email Pegawai)</label>
  </div>
  <div class="col-12 col-md-9">
    <input type="text" name="username" id="username" class="form-control" placeholder="Username (Diisi NIP / Email Pegawai yg dipilih)" value="<?php echo @$user['username']; ?>" required>
  </div>
</div>

<div class="row form-group">
  <div class="col col-md-3">
    <label for="text-input" class=" form-control-label">Password</label>
  </div>
  <div class="col-5">
      <input type="password" name="password" id="password" class="form-control" placeholder="password" value="<?php
      echo "lkm"; ?>" required>
  </div>
</div>

<div class="row form-group">
  <div class="col col-md-3">
    <label for="text-input" class=" form-control-label">Re Password</label>
  </div>
  <div class="col-5 ">
      <input type="password" name="repassword" id="repassword" class="form-control" placeholder="re-password" value="<?php
      echo "lkm";?>" required>
  </div>
  <div class="col-4">
    <?php echo @$_SESSION['error']; ?>
  </div>
</div>

<div class="row form-group">
  <div class="col col-md-3">
    <label for="text-input" class=" form-control-label">Jabatan :</label>
  </div>
  <div class="col-12 col-md-9">
    <select name="jabatan" id="jabatan" class="form-control select2" required onchange="selected()">
      <option>...Pilih Jabatan...</option>
      <?php foreach ($jabatan as $value): ?>
          <option value="<?php echo $value->idjabatan; ?>"
            <?php if ($value->idjabatan == @$user['jabatan']): ?>
              selected
            <?php endif; ?>
            ><?php echo $value->namajabatan; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<div class="row form-group">
  <div class="col col-md-3">
    <label for="text-input" class=" form-control-label">Unit :</label>
  </div>
  <div class="col-12 col-md-9">
    <select name="unit" id="unit" class="form-control select2" required onchange="selected()">
      <option>...Pilih Unit...</option>
      <?php foreach ($unit as $value): ?>
          <option value="<?php echo $value->nama_unit; ?>"
            <?php if ($value->nama_unit == @$user['unit_user']): ?>
              selected
            <?php endif; ?>
            ><?php echo $value->nama_unit; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<div class="row form-group">
  <div class="col col-md-3">
    <label for="text-input" class=" form-control-label">Roles</label>
  </div>
  <div class="col-12 col-md-9">
    <div class="opt_roles">
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

    </div>

    <script type="text/javascript">
      function selected() {
        var jab = $('#jabatan').val();
        $.ajax({
          type: 'POST',
          url: '<?php echo base_url();?>Jabatan/filter_roles/' + jab,
          data: { filter_jab: jab },
          success: function(response) {
            $('.opt_roles').html(response);
            $('#optgroup').multiSelect({
                selectableOptgroup: true
            });
          }
        });
      }
    </script>
