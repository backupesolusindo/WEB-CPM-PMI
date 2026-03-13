<div class="table-responsive">
    <table class="display nowrap table table-hover table-striped table-bordered" id="table-lembur">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Kegiatan Lembur</th>
                <th class="text-center">Status Approval</th>
                <th class="text-center">Foto</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th class="text-center">Durasi</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $totalDurasi = 0;
            $totalDisetujui = 0;
            $totalDitolak = 0;
            $totalMenunggu = 0;

            foreach ($lembur as $value):
                $durasiMenit = 0;
                if ($value->jam_presensi_selesai) {
                    $durasiMenit = (strtotime($value->jam_presensi_selesai) - strtotime($value->jam_presensi)) / 60;
                }

                // Hitung total berdasarkan status
                if ($value->status_aproval == 'DISETUJUI' || $value->status_aproval == '1') {
                    $totalDurasi += $durasiMenit;
                    $totalDisetujui++;
                } elseif ($value->status_aproval == 'DITOLAK' || $value->status_aproval == '2') {
                    $totalDitolak++;
                } else {
                    $totalMenunggu++;
                }

                // Ambil info kegiatan lembur
                $lembur_info = $this->db->where('idlembur', $value->lembur_idlembur)->get('lembur')->row_array();
            ?>
                <tr>
                    <td class="text-center"><?php echo $no++; ?></td>
                    <td>
                        <strong><?php echo $lembur_info['keterangan_lembur'] ?? '-' ?></strong><br>
                        <small class="text-muted">
                            <?php echo date("d-m-Y", strtotime($lembur_info['tgl_mulai'])) ?> s/d
                            <?php echo date("d-m-Y", strtotime($lembur_info['tgl_selesai'])) ?>
                        </small>
                    </td>
                    <td class="text-center">
                        <?php if ($value->status_aproval == 'DISETUJUI' || $value->status_aproval == '1'): ?>
                            <span class="badge badge-success">Disetujui</span>
                        <?php elseif ($value->status_aproval == 'DITOLAK' || $value->status_aproval == '2'): ?>
                            <span class="badge badge-danger">Ditolak</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Menunggu Approval</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if (!empty($value->foto)): ?>
                            <img src="<?php echo base_url() . $value->foto ?>" width="80px" class="img-thumbnail"
                                onclick="showImageModal('<?php echo base_url() . $value->foto ?>')">
                        <?php else: ?>
                            <span class="badge badge-secondary">Tidak ada foto</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo date("H:i:s", strtotime($value->jam_presensi)) ?><br>
                        <small class="text-muted"><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></small>
                    </td>
                    <td>
                        <?php if ($value->jam_presensi_selesai): ?>
                            <?php echo date("H:i:s", strtotime($value->jam_presensi_selesai)) ?><br>
                            <small class="text-muted"><?php echo date("d-m-Y", strtotime($value->jam_presensi_selesai)) ?></small>
                        <?php else: ?>
                            <span class="badge badge-warning">Belum Selesai</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($value->jam_presensi_selesai): ?>
                            <?php
                            $jam = floor($durasiMenit / 60);
                            $menit = $durasiMenit % 60;
                            ?>
                            <strong><?php echo $jam ?> Jam <?php echo $menit ?> Menit</strong>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($value->status_aproval != 'DISETUJUI' && $value->status_aproval != '1'): ?>
                            <button type="button"
                                onclick="approveLembur(<?php echo $value->idabsen_lembur ?>)"
                                class="btn btn-sm btn-success"
                                data-toggle="tooltip"
                                title="Setujui">
                                <i class="fas fa-check"></i>
                            </button>
                        <?php endif; ?>

                        <?php if ($value->status_aproval != 'DITOLAK' && $value->status_aproval != '2'): ?>
                            <button type="button"
                                onclick="rejectLembur(<?php echo $value->idabsen_lembur ?>)"
                                class="btn btn-sm btn-danger"
                                data-toggle="tooltip"
                                title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="bg-light">
                <th colspan="2" class="text-right">TOTAL:</th>
                <th class="text-center">
                    <span class="badge badge-success"><?php echo $totalDisetujui ?></span>
                    <span class="badge badge-danger"><?php echo $totalDitolak ?></span>
                    <span class="badge badge-warning"><?php echo $totalMenunggu ?></span>
                </th>
                <th colspan="3"></th>
                <th class="text-center">
                    <strong>
                        <?php
                        $totalJam = floor($totalDurasi / 60);
                        $totalMenit = $totalDurasi % 60;
                        echo $totalJam . " Jam " . $totalMenit . " Menit";
                        ?>
                    </strong>
                </th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Disetujui</h5>
                    <h2><?php echo $totalDisetujui ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Ditolak</h5>
                    <h2><?php echo $totalDitolak ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Menunggu</h5>
                    <h2><?php echo $totalMenunggu ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Durasi</h5>
                    <h2><?php echo $totalJam ?> Jam</h2>
                    <small><?php echo $totalMenit ?> Menit</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Presensi Lembur</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#table-lembur').DataTable({
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'print'],
            order: [
                [4, 'desc']
            ]
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    function showImageModal(imageUrl) {
        $('#modalImage').attr('src', imageUrl);
        $('#imageModal').modal('show');
    }

    function approveLembur(id) {
        if (confirm('Apakah Anda yakin ingin menyetujui lembur ini?')) {
            $.ajax({
                url: '<?php echo base_url() ?>Lembur/approve_presensi',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        $.toast({
                            heading: 'Berhasil',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000
                        });
                        search(); // Reload data
                    } else {
                        $.toast({
                            heading: 'Gagal',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 3000
                        });
                    }
                },
                error: function() {
                    $.toast({
                        heading: 'Error',
                        text: 'Terjadi kesalahan sistem',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000
                    });
                }
            });
        }
    }

    function rejectLembur(id) {
        if (confirm('Apakah Anda yakin ingin menolak lembur ini?')) {
            $.ajax({
                url: '<?php echo base_url() ?>Lembur/reject_presensi',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        $.toast({
                            heading: 'Berhasil',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000
                        });
                        search(); // Reload data
                    } else {
                        $.toast({
                            heading: 'Gagal',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 3000
                        });
                    }
                },
                error: function() {
                    $.toast({
                        heading: 'Error',
                        text: 'Terjadi kesalahan sistem',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000
                    });
                }
            });
        }
    }
</script>