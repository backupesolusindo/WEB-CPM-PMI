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

        <!-- Filter -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Filter</h3>
            </div>
            <div class="box-body">
                <form method="GET" action="<?= base_url('kpi/daftar_pegawai') ?>" class="form-inline">
                    <div class="form-group m-r-10">
                        <label class="m-r-5">Bulan:</label>
                        <select class="form-control" name="bulan">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= ($i == $bulan_selected) ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group m-r-10">
                        <label class="m-r-5">Tahun:</label>
                        <select class="form-control" name="tahun">
                            <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                                <option value="<?= $i ?>" <?= ($i == $tahun_selected) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group m-r-10">
                        <label class="m-r-5">Departemen:</label>
                        <select class="form-control" name="unit_id">
                            <option value="">Semua</option>
                            <?php foreach ($unit_list as $unit): ?>
                                <option value="<?= $unit['id'] ?>" <?= ($unit['id'] == $unit_id_selected) ? 'selected' : '' ?>>
                                    <?= $unit['nama_unit'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Tampilkan
                    </button>
                    <a href="<?= base_url('kpi') ?>" class="btn btn-default m-l-5">
                        <i class="fa fa-tachometer"></i> Dashboard KPI
                    </a>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <?php
            $total = count($pegawai_kpi);
            $sudah_hitung = array_filter($pegawai_kpi, fn($p) => $p['nilai_kpi_final'] !== null);
            $belum_hitung = $total - count($sudah_hitung);
            $rata = count($sudah_hitung) > 0
                ? array_sum(array_column(array_filter($pegawai_kpi, fn($p) => $p['nilai_kpi_final'] !== null), 'nilai_kpi_final')) / count($sudah_hitung)
                : 0;
        ?>
        <div class="row m-b-20">
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pegawai</span>
                        <span class="info-box-number"><?= $total ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sudah Dihitung</span>
                        <span class="info-box-number"><?= count($sudah_hitung) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Belum Dihitung</span>
                        <span class="info-box-number"><?= $belum_hitung ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-light-blue">
                    <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Rata-rata KPI</span>
                        <span class="info-box-number"><?= number_format($rata, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Pegawai -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-table"></i>
                    Daftar Pegawai &amp; KPI &mdash;
                    <?= date('F', mktime(0, 0, 0, $bulan_selected, 1)) ?> <?= $tahun_selected ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="tablePegawaiKPI" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Departemen</th>
                                <th>Presensi</th>
                                <th>Kegiatan</th>
                                <th>Cuti</th>
                                <th>Pekerjaan</th>
                                <th>Dinas Luar</th>
                                <th>KPI Final</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pegawai_kpi)): ?>
                                <tr>
                                    <td colspan="12" class="text-center">Tidak ada data pegawai.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pegawai_kpi as $i => $p): ?>
                                    <?php
                                        $sudah = $p['nilai_kpi_final'] !== null;
                                        $nilai = floatval($p['nilai_kpi_final']);
                                        if ($nilai >= 90)      $badge = 'bg-green';
                                        elseif ($nilai >= 80)  $badge = 'bg-light-blue';
                                        elseif ($nilai >= 70)  $badge = 'bg-yellow';
                                        elseif ($nilai >= 60)  $badge = 'bg-orange';
                                        else                   $badge = 'bg-red';
                                    ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($p['nip'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($p['nama_pegawai'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($p['departemen'] ?? '-') ?></td>
                                        <?php if ($sudah): ?>
                                            <td><?= number_format($p['nilai_presensi'], 2) ?></td>
                                            <td><?= number_format($p['nilai_kegiatan'], 2) ?></td>
                                            <td><?= number_format($p['nilai_cuti'], 2) ?></td>
                                            <td><?= number_format($p['nilai_pekerjaan'], 2) ?></td>
                                            <td><?= number_format($p['nilai_dinas_luar'], 2) ?></td>
                                            <td><strong><?= number_format($nilai, 2) ?></strong></td>
                                            <td><span class="badge <?= $badge ?>"><?= $p['kategori_kinerja'] ?></span></td>
                                        <?php else: ?>
                                            <td colspan="6" class="text-center text-muted">
                                                <em>Belum dihitung</em>
                                            </td>
                                            <td><span class="badge bg-default">-</span></td>
                                        <?php endif; ?>
                                        <td>
                                            <a href="<?= base_url('kpi/detail/' . $p['uuid']) ?>"
                                               class="btn btn-xs btn-info" title="Detail KPI">
                                                <i class="fa fa-eye"></i> Detail KPI
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tablePegawaiKPI').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            columnDefs: [
                { orderable: false, targets: [11] }
            ]
        });
    });
</script>
<style>
    select { display: block !important; }
    .m-r-5  { margin-right: 5px; }
    .m-r-10 { margin-right: 10px; }
    .m-l-5  { margin-left: 5px; }
    .m-b-20 { margin-bottom: 20px; }
</style>
