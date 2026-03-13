<?php echo form_open_multipart('Lembur/approve_batch'); ?>
<input type="hidden" name="idlembur" value="<?php echo $lembur['idlembur'] ?>">
<div class="row card card-cascade narrower z-depth-1">
    <div class="col-md-12">
        <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
            <div>
            </div>
            <h3 class="white-text mx-3"><?php echo $title ?></h3>
            <div>
            </div>
        </div>
        <div class="col-md-12 row">
            <div class="col-6">
                <table width="100%" border="0">
                    <tr>
                        <td>Keterangan Lembur</td>
                        <td>: <?php echo $lembur['keterangan_lembur'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table width="100%" border="0">
                    <tr>
                        <td>Tanggal Mulai</td>
                        <td>: <?php echo date("d-m-Y", strtotime($lembur['tgl_mulai'])) ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Selesai</td>
                        <td>: <?php echo date("d-m-Y", strtotime($lembur['tgl_selesai'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-sm-12">
            <br>
            <div class="table-responsive">
                <table class="table table-striped" id="myTable">
                    <thead>
                        <tr>
                            <th width="5%">NO</th>
                            <th>NIP</th>
                            <th>Nama Pegawai</th>
                            <th>Unit</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($presensi as $value):
                            $status_text = 'MENUNGGU';
                            $status_class = 'badge-warning';

                            if ($value->status_aproval == '2') {
                                $status_class = 'badge-danger';
                                $status_text = 'DITOLAK';
                            }
                            if ($value->status_aproval == '1') {
                                $status_text = 'DISETUJUI';
                                $status_class = 'badge-success';
                            }
                            $durasiMenit = strtotime($value->jam_presensi_selesai) - strtotime($value->jam_presensi);
                        ?>
                            <tr id="row-<?php echo $value->idabsen_lembur ?>">
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $value->status_aproval ?></td>
                                <td><?php echo $value->nama_pegawai ?></td>
                                <td><?php echo $value->unit ?></td>
                                <td>
                                    <?php echo date("H:i:s", strtotime($value->jam_presensi)) ?><br>
                                    <small><?php echo date("d-m-Y", strtotime($value->jam_presensi)) ?></small>
                                </td>
                                <td>
                                    <?php echo date("H:i:s", strtotime($value->jam_presensi_selesai)) ?><br>
                                    <small><?php echo date("d-m-Y", strtotime($value->jam_presensi_selesai)) ?></small>
                                </td>
                                <td>
                                    <?php
                                    $jam = floor($durasiMenit / 60);
                                    $menit = $durasiMenit % 60;
                                    echo $jam . " Jam " . $menit . " Menit"; ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo $status_class ?> status-badge-<?php echo $value->idabsen_lembur ?>">
                                        <?php echo $status_text ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($status_text == 'MENUNGGU' || $status_text == 'DITOLAK'): ?>
                                        <button type="button" onclick="approvePresensi(<?php echo $value->idabsen_lembur ?>)"
                                            class="btn btn-sm btn-success btn-approve-<?php echo $value->idabsen_lembur ?>"
                                            data-toggle="tooltip" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($status_text == 'MENUNGGU' || $status_text == 'DISETUJUI'): ?>
                                        <button type="button" onclick="rejectPresensi(<?php echo $value->idabsen_lembur ?>)"
                                            class="btn btn-sm btn-danger btn-reject-<?php echo $value->idabsen_lembur ?>"
                                            data-toggle="tooltip" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br>
    <div class="col-md-12">
        <button type="button" name="kembali" onclick="window.history.back()" class="btn btn-default btn-sm">
            <i class="fa fa-mail-reply"></i> Kembali
        </button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    function approvePresensi(id) {
        if (confirm('Apakah Anda yakin ingin menyetujui presensi ini?')) {
            $.ajax({
                url: '<?php echo base_url() ?>Lembur/approve_presensi',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        // Update badge status
                        $('.status-badge-' + id).removeClass('badge-warning badge-danger').addClass('badge-success').text('DISETUJUI');

                        // Hide approve button, show reject button
                        $('.btn-approve-' + id).hide();
                        $('.btn-reject-' + id).show();

                        // Show notification
                        $.toast({
                            heading: 'Berhasil',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000
                        });
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

    function rejectPresensi(id) {
        if (confirm('Apakah Anda yakin ingin menolak presensi ini?')) {
            $.ajax({
                url: '<?php echo base_url() ?>Lembur/reject_presensi',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        // Update badge status
                        $('.status-badge-' + id).removeClass('badge-warning badge-success').addClass('badge-danger').text('DITOLAK');

                        // Hide reject button, show approve button
                        $('.btn-reject-' + id).hide();
                        $('.btn-approve-' + id).show();

                        // Show notification
                        $.toast({
                            heading: 'Berhasil',
                            text: response.message,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'success',
                            hideAfter: 3000
                        });
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

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>