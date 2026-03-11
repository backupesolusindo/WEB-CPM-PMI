<h3 class="box-title"><b>List Pekerjaan</b></h3> 
<form action="<?php echo base_url('pekerjaan/simpan'); ?>" method="POST"> 
    <div class="row form-group"> 
    <div class="col-12"> 
            <label>Jabatan :</label> 
            <select name="jabatan_idjabatan" id="select" class="form-control select2 col-md-12" required> 
                <option>...Pilih Jabatan...</option> 
                <?php foreach ($jabatan as $value): ?> 
                    <?php if ($value->jabatan_idjabatan != "adminr"): ?> 
                        <option value="<?php echo $value->idjabatan; ?>" <?php if ($value->idjabatan == @$detailpekerjaan['jabatan_idjabatan']): ?> 
                            <?php echo 'selected'; ?> 
                        <?php endif; ?>><?php echo $value->namajabatan ?></option> 
                    <?php endif; ?> 
                <?php endforeach; ?> 
            </select> 
            <br><br> 
        </div> 

        <div class="col-md-12"> 
            <div class="form-group"> 
                <label>Nama Pekerjaan :</label> 
                <input type="text" name="nama_pekerjaan" id="nama_pekerjaan" class="form-control"  
                    value="<?php echo isset($detailpekerjaan->nama_pekerjaan) ? htmlspecialchars($detailpekerjaan->nama_pekerjaan) : ''; ?>"  
                    required> 
            </div> 
        </div> 

        <div class="col-md-12"> 
    <div class="form-group"> 
        <label>Point :</label> 
        <input type="text" name="point" id="point" class="form-control"  
            value="<?php echo isset($detailpekerjaan->point) ? number_format($detailpekerjaan->point, 0, ',', '.') : ''; ?>"  
            required> 
    </div> 
</div> 

        <div class="col-md-4"> 
            <div class="col-sm-12 row"> 
                <label class="col-12">Pilih Salah Satu Tipe Pekerjaan... </label> 
                <div class="custom-control custom-radio col-3"> 
                    <input type="radio" class="custom-control-input" name="tipe_pekerjaan" id="iya" value="0" 
                        <?php echo (isset($jadwalmasuk['tipe_pekerjaan']) && $jadwalmasuk['tipe_pekerjaan'] == "0") ? 'checked' : ''; ?>> 
                    <label class="custom-control-label" for="iya">Fleksibel</label> 
                </div> 
                <div class="custom-control custom-radio col-3"> 
                    <input type="radio" class="custom-control-input" name="tipe_pekerjaan" id="tidak" value="1" 
                        <?php echo (isset($jadwalmasuk['tipe_pekerjaan']) && $jadwalmasuk['tipe_pekerjaan'] == "1") ? 'checked' : ''; ?>> 
                    <label class="custom-control-label" for="tidak">Harian</label> 
                </div> 
            </div> 

            <div class="form-actions mt-3"> 
                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button> 
                <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button> 
            </div> 
        </div> 
    </div> 
</form>