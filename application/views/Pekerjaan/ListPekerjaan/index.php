<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
        <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div></div>
                <h3 class="white-text mx-3">List Pekerjaan</h3>
                <div>
                    <a href="<?php echo base_url(); ?>Pekerjaan/input" class="float-right">
                        <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru">
                            <i class="fas fa-pencil-alt mt-0"></i>
                        </button>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="myTable" class="table color-table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>Jabatan</th>
                                                <th>Nama Pekerjaan</th>
                                                <th>Point</th>
                                                <th>Tipe Pekerjaan</th> 
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            if (!empty($listpekerjaan)) {
                                                foreach ($listpekerjaan as $pekerjaan): ?>
                                                    <tr>
                                                        <td><?php echo $no ?></td>
                                                        <td><?php echo isset($pekerjaan['jabatan_idjabatan']) ? $pekerjaan['jabatan_idjabatan'] : '-'; ?></td>
                                                        <td><?php echo isset($pekerjaan['nama_pekerjaan']) ? $pekerjaan['nama_pekerjaan'] : '-'; ?></td>
                                                        <td><?php echo isset($pekerjaan['point']) ? number_format($pekerjaan['point'], 0, ',', '.') : '-'; ?></td>
                                                        <td>
                                                            <?php 
                                                            if (isset($pekerjaan['tipe_pekerjaan'])) {
                                                                echo $pekerjaan['tipe_pekerjaan'] == 0 ? 'Fleksibel' : 'Harian';
                                                            } else {
                                                                echo '-';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                        <a href="<?php echo base_url()?>Pekerjaan/edit/<?php echo $pekerjaan['id_pekerjaan'];?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>
                                                        <a href="<?php echo base_url()?>Pekerjaan/delete/<?php echo $pekerjaan['id_pekerjaan'];?>" class="btn-floating btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php 
                                                $no++; 
                                                endforeach;
                                            } else { ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                                </tr>
                                            <?php } ?>
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
document.getElementById('point').addEventListener('input', function (e) {
    let value = this.value.replace(/\./g, '');
    if (!isNaN(value) && value !== '') {
        this.value = parseInt(value).toLocaleString('id-ID');
    } else {
        this.value = '';
    }
});
</script>
