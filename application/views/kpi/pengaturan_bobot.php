<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-balance-scale"></i> Bobot Komponen KPI</h3>
                    </div>

                    <form action="<?= base_url('kpi/update_bobot') ?>" method="POST" id="formBobot">
                        <div class="box-body">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                Total bobot harus sama dengan <strong>100%</strong>.
                                Saat ini: <strong id="totalBobot"><?= $total_bobot ?></strong>%
                            </div>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="30%">Komponen</th>
                                        <th width="50%">Deskripsi</th>
                                        <th width="20%">Bobot (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bobot_list as $bobot): ?>
                                        <tr>
                                            <td>
                                                <strong><?= ucfirst(str_replace('_', ' ', $bobot['komponen'])) ?></strong>
                                            </td>
                                            <td>
                                                <small><?= $bobot['deskripsi'] ?></small>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number"
                                                        class="form-control bobot-input"
                                                        name="bobot[<?= $bobot['id'] ?>]"
                                                        value="<?= $bobot['bobot'] ?>"
                                                        min="0"
                                                        max="100"
                                                        step="0.01"
                                                        required>
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray">
                                        <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                        <td>
                                            <strong id="totalBobotFooter"><?= $total_bobot ?></strong>%
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="btnSimpan">
                                <i class="fa fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="<?= base_url('kpi') ?>" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi</h3>
                    </div>
                    <div class="box-body">
                        <h4>Cara Kerja Bobot KPI:</h4>
                        <ol>
                            <li>Setiap komponen dinilai dengan skala 0-100</li>
                            <li>Nilai setiap komponen dikalikan dengan bobotnya</li>
                            <li>Hasil perkalian dijumlahkan untuk mendapat KPI final</li>
                        </ol>

                        <h4>Contoh Perhitungan:</h4>
                        <div class="well well-sm">
                            <small>
                                Presensi: 90 × 30% = 27<br>
                                Kegiatan: 80 × 25% = 20<br>
                                Cuti: 100 × 10% = 10<br>
                                Pekerjaan: 85 × 25% = 21.25<br>
                                Dinas Luar: 95 × 10% = 9.5<br>
                                <hr>
                                <strong>KPI Final = 87.75</strong>
                            </small>
                        </div>

                        <h4>Kategori Kinerja:</h4>
                        <ul class="list-unstyled">
                            <li><span class="badge bg-green">90-100</span> Sangat Baik</li>
                            <li><span class="badge bg-light-blue">80-89</span> Baik</li>
                            <li><span class="badge bg-yellow">70-79</span> Cukup</li>
                            <li><span class="badge bg-orange">60-69</span> Kurang</li>
                            <li><span class="badge bg-red">0-59</span> Sangat Kurang</li>
                        </ul>
                    </div>
                </div>

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-warning"></i> Perhatian</h3>
                    </div>
                    <div class="box-body">
                        <p>
                            <i class="fa fa-exclamation-triangle"></i>
                            Perubahan bobot akan mempengaruhi perhitungan KPI selanjutnya.
                        </p>
                        <p>
                            <i class="fa fa-info-circle"></i>
                            Data KPI yang sudah dihitung sebelumnya tidak akan berubah.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Hitung total bobot saat input berubah
        $('.bobot-input').on('input', function() {
            hitungTotalBobot();
        });

        // Validasi sebelum submit
        $('#formBobot').on('submit', function(e) {
            const total = hitungTotalBobot();

            if (total != 100) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Total Bobot Tidak Valid',
                    text: `Total bobot harus 100%. Saat ini: ${total}%`,
                    confirmButtonText: 'OK'
                });
                return false;
            }

            return confirm('Simpan perubahan bobot KPI?');
        });
    });

    function hitungTotalBobot() {
        let total = 0;

        $('.bobot-input').each(function() {
            const nilai = parseFloat($(this).val()) || 0;
            total += nilai;
        });

        total = Math.round(total * 100) / 100; // Round to 2 decimal places

        $('#totalBobot').text(total);
        $('#totalBobotFooter').text(total);

        // Ubah warna berdasarkan validitas
        if (total == 100) {
            $('#totalBobot, #totalBobotFooter').removeClass('text-danger').addClass('text-success');
            $('#btnSimpan').prop('disabled', false);
        } else {
            $('#totalBobot, #totalBobotFooter').removeClass('text-success').addClass('text-danger');
            $('#btnSimpan').prop('disabled', true);
        }

        return total;
    }
</script>