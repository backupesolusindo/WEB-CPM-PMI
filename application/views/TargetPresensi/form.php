<?php
$nama_bulan = [
    1  => 'Januari',
    2  => 'Februari',
    3  => 'Maret',
    4  => 'April',
    5  => 'Mei',
    6  => 'Juni',
    7  => 'Juli',
    8  => 'Agustus',
    9  => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
];
$tahun_list = range(date('Y') - 2, date('Y') + 1);
$is_edit    = !empty($target);
?>

<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
            <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <a href="<?= base_url() ?>TargetPresensi/index/<?= $tahun ?>">
                        <button type="button" class="btn btn-sm btn-outline-white btn-rounded">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                    </a>
                </div>
                <h3 class="white-text mx-3">
                    <i class="fas fa-bullseye"></i>
                    <?= $is_edit ? 'Edit' : 'Set' ?> Target Presensi
                </h3>
                <div></div>
            </div>

            <div class="card-body">

                <!-- Info Pegawai -->
                <div class="alert alert-info mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-user"></i> Pegawai:</strong>
                            <?= htmlspecialchars($pegawai['nama_pegawai']) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>NIP:</strong> <?= htmlspecialchars($pegawai['NIP'] ?? '-') ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Unit:</strong> <?= htmlspecialchars($pegawai['unit'] ?? '-') ?>
                        </div>
                    </div>
                </div>

                <?php echo form_open('TargetPresensi/save', ['class' => 'form-target']); ?>
                <input type="hidden" name="pegawai_uuid" value="<?= $pegawai['uuid'] ?>">

                <!-- Pilih Tahun -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Tahun</strong></label>
                            <select name="tahun" id="tahun" class="form-control" required>
                                <?php foreach ($tahun_list as $t) : ?>
                                    <option value="<?= $t ?>" <?= ($t == $tahun) ? 'selected' : '' ?>>
                                        <?= $t ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-isi-semua">
                            <i class="fas fa-magic"></i> Isi Semua Sama
                        </button>
                    </div>
                </div>

                <!-- Input 12 Bulan -->
                <div class="row">
                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                        <?php
                        $key  = 'bulan_' . $i;
                        $val  = $target[$key] ?? '';
                        // Tandai bulan berjalan
                        $is_current = ($i == date('n') && $tahun == date('Y'));
                        ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card <?= $is_current ? 'border-primary shadow-sm' : '' ?>">
                                <div class="card-header py-2 <?= $is_current ? 'bg-primary text-white' : 'bg-light' ?>">
                                    <strong><?= $nama_bulan[$i] ?></strong>
                                    <?php if ($is_current) : ?>
                                        <span class="badge badge-light float-right">Bulan ini</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body py-2">
                                    <div class="input-group input-group-sm">
                                        <input
                                            type="number"
                                            class="form-control input-bulan"
                                            name="<?= $key ?>"
                                            id="<?= $key ?>"
                                            value="<?= htmlspecialchars((string)$val) ?>"
                                            min="0"
                                            max="31"
                                            placeholder="0"
                                            required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- Tombol -->
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Target
                        </button>
                        <a href="<?= base_url() ?>TargetPresensi/index/<?= $tahun ?>" class="btn btn-light">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Isi semua bulan dengan nilai yang sama
        $('#btn-isi-semua').on('click', function() {
            var nilai = prompt('Masukkan jumlah hari target untuk semua bulan:', '25');
            if (nilai !== null && nilai !== '' && !isNaN(nilai) && parseInt(nilai) >= 0) {
                $('.input-bulan').val(parseInt(nilai));
            }
        });

        // Validasi: hanya angka 0-31
        $('.input-bulan').on('input', function() {
            var val = parseInt($(this).val());
            if (isNaN(val) || val < 0) $(this).val(0);
            if (val > 31) $(this).val(31);
        });

        // Muat ulang data saat tahun berubah (redirect ke form tahun baru)
        $('#tahun').on('change', function() {
            var tahun = $(this).val();
            var uuid = $('input[name="pegawai_uuid"]').val();
            window.location.href = '<?= base_url() ?>TargetPresensi/form/' + uuid + '/' + tahun;
        });
    });
</script>