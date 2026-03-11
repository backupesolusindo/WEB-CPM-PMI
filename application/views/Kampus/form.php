<h3 class="box-title"><b>Kantor</b></h3>
<br>
<div class="row">
  <div class="col-md-12">
      <div class="form-group">
          <label>Nama Kantor :</label>
          <input type="text" name="nama_kampus" id="nama_kampus" class="form-control" value="<?php echo @$kampus["nama_kampus"] ?>" required>
      </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
      <div class="form-group">
          <label>Latitude :</label>
          <input type="text" name="latitude" id="latitude" class="form-control" value="<?php echo @$kampus["latitude"] ?>" required>
      </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
      <div class="form-group">
          <label>Longtitude :</label>
          <input type="text" name="longtitude" id="longtitude" class="form-control" value="<?php echo @$kampus["longtitude"] ?>" required>
      </div>
  </div>
</div>
<div class="form-actions" >
    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
    <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
</div>
