
<div class="card">
<div class="card-body card-block">
  <div class="row form-group">
          <div class="col col-md-3">
            <label for="agama" class=" form-control-label">Group Role</label>
          </div>
          <div class="col-12 col-md-9">
                <select name="group" id="select" class="form-control select2 col-12" re>
                  <option>...Pilih Group Role...</option>
                  <?php foreach ($group as $value): ?>
                    <option value="<?php echo $value->idgroup_roles; ?>" <?php if ($value->idgroup_roles == @$role['group_roles_idgroup_roles']): ?>
                      <?php echo 'selected'; ?>
                    <?php endif; ?>><?php echo $value->nama_group ?></option>
                  <?php endforeach; ?>
                </select>
          </div>
  </div>

<div class="row form-group">
  <div class="col col-md-3">
    <label for="text-input" class=" form-control-label">Role</label>
  </div>
  <div class="col-12 col-md-9">
    <input type="text" name="role" id="role" class="form-control" placeholder="Role" value="<?php echo @$role['role']; ?>" required>
  </div>
</div>

</div>

    </div>
