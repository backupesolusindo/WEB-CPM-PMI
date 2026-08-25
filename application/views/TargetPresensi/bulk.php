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
$tahun_list = range(date('Y') - 2, date('Y') + 1);
?>

<style>
    .input-target {
        width: 52px !important;
        min-width: 52px;
        padding: 2px 4px;
        text-align: center;
        font-size: 12px;
    }

    .col-bulan {
        min-width: 60px;
    }

    #tabel-bulk thead th {
        white-space: nowrap;
        vertical-align: middle;
    }

    .row-belum td {
        background-color: #fff8e1 !important;
    }

    .row-sudah td {
        background-color: #f1f8e9 !important;
    }
</style>

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
                <h3 class="white-text mx-3"><i class="fas fa-users"></i> Input Target Presensi Massal</h3>
                <div></div>
            </div>

            <div class="card-body">

                <?php echo form_open('TargetPresensi/save_bulk', ['id' => 'form-bulk']); ?>

                <!-- Toolbar -->
                <div class="row align-items-end mb-3">
                    <div class="col-md-2">
                        <label class="mb-1"><strong>Tahun</strong></label>
                        <select name="tahun" id="sel-tahun" class="form-control form-control-lg">
                            <?php foreach ($tahun_list as $t) : ?>
                                <option value="<?= $t ?>" <?= ($t == $tahun) ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="mb-1"><strong>Isi semua kolom</strong></label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="val-isi-semua" class="form-control" placeholder="25" min="0" max="31" value="25">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="btn-isi-semua">
                                    <i class="fas fa-fill-drip"></i> Isi
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="mb-1"><strong>Isi per bulan</strong></label>
                        <div class="input-group input-group-sm">
                            <select id="sel-bulan-pilih" class="form-control form-control-lg">
                                <?php foreach ($nama_bulan as $nb => $label) : ?>
                                    <option value="<?= $nb ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" id="val-isi-bulan" class="form-control" placeholder="25" min="0" max="31" value="25">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-info" id="btn-isi-bulan">
                                    <i class="fas fa-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 ml-auto text-right">
                        <button type="submit" class="btn btn-success btn-sm" id="btn-simpan">
                            <i class="fas fa-save"></i> Simpan Semua
                        </button>
                    </div>
                </div>

                <!-- Keterangan warna -->
                <div class="mb-2 small">
                    <span class="badge bg-success">&#9632; Sudah ada target</span>
                    &nbsp;
                    <span class="badge bg-warning">&#9632; Belum ada target</span>
                </div>

                <!-- Tabel -->
                <div class="table-responsive">
                    <table id="tabel-bulk" class="table table-bordered table-sm" style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th class="align-middle text-center" style="width:35px">#</th>
                                <th class="align-middle" style="width:110px">NIP</th>
                                <th class="align-middle" style="min-width:160px">Nama Pegawai</th>
                                <th class="align-middle" style="min-width:120px">Unit</th>
                                <?php foreach ($nama_bulan as $nb => $label) : ?>
                                    <th class="text-center col-bulan"><?= $label ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pegawai)) : ?>
                                <tr>
                                    <td colspan="16" class="text-center text-muted py-4">
                                        Belum ada data pegawai.
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1;
                                foreach ($pegawai as $p) :
                                    $sudah_ada = isset($p->id_target) && $p->id_target;
                                    $row_class = $sudah_ada ? 'row-sudah' : 'row-belum';
                                ?>
                                    <tr class="<?= $row_class ?>">
                                        <td class="text-center"><?= $no ?></td>
                                        <td><?= htmlspecialchars($p->NIP ?? '-') ?></td>
                                        <td><?= htmlspecialchars($p->nama_pegawai) ?></td>
                                        <td><?= htmlspecialchars($p->unit ?? '-') ?></td>
                                        <?php for ($i = 1; $i <= 12; $i++) :
                                            $key = 'bulan_' . $i;
                                            $val = (isset($p->$key) && $p->$key !== null) ? (int)$p->$key : '';
                                        ?>
                                            <td class="p-1">
                                                <input
                                                    type="number"
                                                    class="form-control input-target input-bulan"
                                                    name="pegawai[<?= $p->uuid ?>][bulan_<?= $i ?>]"
                                                    value="<?= $val ?>"
                                                    min="0"
                                                    max="31"
                                                    placeholder="0"
                                                    data-bulan="<?= $i ?>">
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tombol bawah -->
                <div class="row mt-3">
                    <div class="col-12 text-right">
                        <a href="<?= base_url() ?>TargetPresensi/index/<?= $tahun ?>" class="btn btn-light btn-sm mr-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-save"></i> Simpan Semua
                        </button>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Ganti tahun → reload halaman bulk dengan tahun baru
        $('#sel-tahun').on('change', function() {
            window.location.href = '<?= base_url() ?>TargetPresensi/bulk/' + $(this).val();
        });

        // Isi SEMUA input dengan nilai yang sama
        $('#btn-isi-semua').on('click', function() {
            var val = parseInt($('#val-isi-semua').val());
            if (isNaN(val) || val < 0) {
                alert('Nilai tidak valid.');
                return;
            }
            if (val > 31) {
                alert('Maksimal 31 hari.');
                return;
            }
            $('.input-bulan').val(val);
        });

        // Isi satu bulan tertentu untuk semua pegawai
        $('#btn-isi-bulan').on('click', function() {
            var bulan = $('#sel-bulan-pilih').val();
            var val = parseInt($('#val-isi-bulan').val());
            if (isNaN(val) || val < 0) {
                alert('Nilai tidak valid.');
                return;
            }
            if (val > 31) {
                alert('Maksimal 31 hari.');
                return;
            }
            $('.input-bulan[data-bulan="' + bulan + '"]').val(val);
        });

        // Validasi input: angka 0–31 saja
        $(document).on('input', '.input-bulan', function() {
            var v = parseInt($(this).val());
            if (isNaN(v) || v < 0) $(this).val(0);
            if (v > 31) $(this).val(31);
        });

        // Navigasi keyboard: Tab masuk ke kolom berikutnya, Enter turun ke baris berikutnya
        $(document).on('keydown', '.input-bulan', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                var bulan = $(this).data('bulan');
                var $rows = $('#tabel-bulk tbody tr');
                var $cur = $(this).closest('tr');
                var idx = $rows.index($cur);
                var $next = $rows.eq(idx + 1).find('.input-bulan[data-bulan="' + bulan + '"]');
                if ($next.length) $next.focus().select();
            }
        });

        // Konfirmasi sebelum simpan
        $('#form-bulk').on('submit', function(e) {
            var total = $('.input-bulan').length / 12;
            if (!confirm('Simpan target presensi untuk ' + Math.round(total) + ' pegawai tahun ' + $('#sel-tahun').val() + '?')) {
                e.preventDefault();
            }
        });
    });
</script>