<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
            <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
                <div>
                </div>
                <h3 class="white-text mx-3">Jadwal Dinas Luar</h3>
                <div>
                    <a href="<?php base_url(); ?>DinasLuar/input" class="float-right">
                        <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter Tanggal -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Tanggal Mulai:</label>
                        <input type="date" id="tgl_mulai" class="form-control" value="<?php echo $tgl_mulai; ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Tanggal Akhir:</label>
                        <input type="date" id="tgl_akhir" class="form-control" value="<?php echo $tgl_akhir; ?>">
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label><br>
                        <button type="button" class="btn btn-primary" onclick="filterData()">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetFilter()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="myTable" class="table color-table table-hover table-striped ">
                                        <thead>
                                            <tr>
                                                <th width="10%">#</th>
                                                <th>No.Surat</th>
                                                <th>Kegiatan</th>
                                                <th>Lokasi</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Status Approval</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $no = 1;
                                            foreach ($DinasLuar as $value):
                                                $status_approval = isset($value->status_approval) ? $value->status_approval : 'MENUNGGU';
                                                $badge_class = 'badge-warning';
                                                if ($status_approval == 'DISETUJUI') {
                                                    $badge_class = 'badge-success';
                                                } elseif ($status_approval == 'DITOLAK') {
                                                    $badge_class = 'badge-danger';
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $no ?></td>
                                                    <td><?php echo $value->no_surat ?></td>
                                                    <td><?php echo $value->nama_surat ?></td>
                                                    <td><?php echo $value->keterangan ?></td>
                                                    <td><?php echo date("d-m-Y", strtotime($value->tanggal_mulai)) ?></td>
                                                    <td><?php echo date("d-m-Y", strtotime($value->tanggal_selesai)) ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $status_approval; ?></span>
                                                        <?php if (isset($value->keterangan_approval) && !empty($value->keterangan_approval)): ?>
                                                            <br><small class="text-muted" style="cursor: pointer;" data-toggle="tooltip" title="<?php echo htmlspecialchars($value->keterangan_approval); ?>">
                                                                <i class="fas fa-info-circle"></i> Lihat Keterangan
                                                            </small>
                                                        <?php endif; ?>
                                                        <?php if (isset($value->approval_by) && !empty($value->approval_by)): ?>
                                                            <br><small class="text-muted">oleh: <?php echo $value->approval_by; ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo base_url() ?>DinasLuar/peserta/<?php echo $value->iddinas_luar; ?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="PESERTA"><i class="fas fa-users"></i></a>
                                                        <a href="<?php echo base_url() ?>DinasLuar/edit/<?php echo $value->iddinas_luar; ?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>

                                                        <?php if ($status_approval == 'MENUNGGU'): ?>
                                                            <a href="javascript:void(0)" onclick="showApprovalModal(<?php echo $value->iddinas_luar; ?>, 'DISETUJUI')" class="btn-floating btn-sm btn-success" data-toggle="tooltip" data-placement="top" data-original-title="SETUJUI"><i class="fas fa-check"></i></a>
                                                            <a href="javascript:void(0)" onclick="showApprovalModal(<?php echo $value->iddinas_luar; ?>, 'DITOLAK')" class="btn-floating btn-sm btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="TOLAK"><i class="fas fa-times"></i></a>
                                                        <?php endif; ?>
                                                        <!-- <a href="<?php echo base_url() ?>DinasLuar/hapus/<?php echo $value->iddinas_luar; ?>" class="btn-floating btn-sm btn-danger"  data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a> -->
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

<!-- Modal Approval -->
<div class="modal fade" id="modalApproval" tabindex="-1" role="dialog" aria-labelledby="modalApprovalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formApproval" method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalApprovalLabel">Konfirmasi Approval</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keterangan_approval">Keterangan <span id="statusText"></span>:</label>
                        <textarea class="form-control" id="keterangan_approval" name="keterangan_approval" rows="4" placeholder="Masukkan keterangan approval (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitApproval">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function filterData() {
        var tgl_mulai = document.getElementById('tgl_mulai').value;
        var tgl_akhir = document.getElementById('tgl_akhir').value;

        if (tgl_mulai && tgl_akhir) {
            window.location.href = '<?php echo base_url(); ?>DinasLuar?tgl_mulai=' + tgl_mulai + '&tgl_akhir=' + tgl_akhir;
        } else {
            alert('Silakan pilih tanggal mulai dan tanggal akhir');
        }
    }

    function resetFilter() {
        window.location.href = '<?php echo base_url(); ?>DinasLuar';
    }

    function showApprovalModal(id, status) {
        var statusText = status === 'DISETUJUI' ? 'Persetujuan' : 'Penolakan';
        document.getElementById('statusText').innerText = statusText;
        document.getElementById('formApproval').action = '<?php echo base_url(); ?>DinasLuar/approval/' + id + '/' + status;

        var btnSubmit = document.getElementById('btnSubmitApproval');
        btnSubmit.className = status === 'DISETUJUI' ? 'btn btn-success' : 'btn btn-danger';
        btnSubmit.innerText = status === 'DISETUJUI' ? 'Setujui' : 'Tolak';

        $('#modalApproval').modal('show');
    }
</script>