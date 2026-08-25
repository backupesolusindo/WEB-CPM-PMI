<?php
$nama_bulan = [
    1  => 'Jan',
    2  => 'Feb',
    3  => 'Mar',
    4  => 'Apr',
    5  => 'Mei',
    6  => 'Jun',
    7  => 'Jul',
    8  => 'Agt',
    9  => 'Sep',
    10 => 'Okt',
    11 => 'Nov',
    12 => 'Des',
];
?>
<div class="table-responsive">
    <table id="tabel-target" class="table table-hover table-striped table-sm" style="width:100%">
        <thead>
            <tr>
                <th rowspan="2" class="align-middle text-center">#</th>
                <th rowspan="2" class="align-middle">NIP</th>
                <th rowspan="2" class="align-middle">Nama Pegawai</th>
                <th rowspan="2" class="align-middle">Unit</th>
                <th colspan="12" class="text-center">Target Presensi <?= $tahun ?> (hari)</th>
                <th rowspan="2" class="align-middle text-center">Aksi</th>
            </tr>
            <tr>
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <th class="text-center"><?= $nama_bulan[$i] ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pegawai)) : ?>
                <tr>
                    <td colspan="17" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Belum ada data pegawai.
                    </td>
                </tr>
            <?php else : ?>
                <?php $no = 1;
                foreach ($pegawai as $p) : ?>
                    <tr>
                        <td class="text-center"><?= $no ?></td>
                        <td><?= htmlspecialchars($p->NIP ?? '-') ?></td>
                        <td><?= htmlspecialchars($p->nama_pegawai) ?></td>
                        <td><?= htmlspecialchars($p->unit ?? '-') ?></td>
                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                            <?php $key = 'bulan_' . $i; ?>
                            <td class="text-center">
                                <?php if (isset($p->$key) && $p->$key !== null) : ?>
                                    <span class="badge badge-success"><?= (int)$p->$key ?></span>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td class="text-center">
                            <a href="<?= base_url() ?>TargetPresensi/form/<?= $p->uuid ?>/<?= $tahun ?>"
                                class="btn btn-sm btn-warning btn-rounded"
                                data-toggle="tooltip" title="Set Target Presensi">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if (isset($p->id_target) && $p->id_target) : ?>
                                <a href="<?= base_url() ?>TargetPresensi/hapus/<?= $p->id_target ?>/<?= $tahun ?>"
                                    class="btn btn-sm btn-danger btn-rounded btn-hapus"
                                    data-toggle="tooltip" title="Hapus Target"
                                    data-nama="<?= htmlspecialchars($p->nama_pegawai) ?>">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php $no++;
                endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Konfirmasi hapus
    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var nama = $(this).data('nama');
        if (confirm('Hapus target presensi pegawai "' + nama + '"?\nData yang sudah dihapus tidak bisa dikembalikan.')) {
            window.location.href = url;
        }
    });
</script>