<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="row">

        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <strong>Roles</strong>
              <small> Form Input</small>
            </div>
            <div class="card-body card-block">
              <?php echo form_open_multipart('GroupRole/insert');?>
              <?php echo @$error;?>

              <div class="card">
              <div class="card-body card-block">
                <div class="row form-group">
                  <div class="col col-md-3">
                    <label for="text-input" class=" form-control-label">ID Group Role</label>
                  </div>
                  <div class="col-12 col-md-9">
                    <input type="text" name="id" id="id" class="form-control" placeholder="ID" required>
                  </div>
                </div>
              <div class="row form-group">
                <div class="col col-md-3">
                  <label for="text-input" class=" form-control-label">Group Role</label>
                </div>
                <div class="col-12 col-md-9">
                  <input type="text" name="group" id="group" class="form-control" placeholder="Group"  required>
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
