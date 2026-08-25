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

<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
            <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
                <div></div>
                <h3 class="white-text mx-3"><i class="fas fa-bullseye"></i> Target Presensi Pegawai</h3>
                <div></div>
            </div>

            <div class="card-body">

                <!-- Filter Tahun + Tombol Massal -->
                <div class="row mb-3 align-items-end">
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <select id="filter-tahun" class="form-control form-control-lg">
                                <?php foreach ($tahun_list as $t) : ?>
                                    <option value="<?= $t ?>" <?= ($t == $tahun) ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-sm" id="btn-filter" type="button">
                                    <i class="fas fa-filter"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 ml-auto text-right">
                        <a href="<?= base_url() ?>TargetPresensi/bulk/<?= $tahun ?>" class="btn btn-warning">
                            <i class="fas fa-users"></i> Input Massal
                        </a>
                    </div>
                </div>

                <!-- Tabel -->
                <div id="container-tabel">
                    <?php $this->load->view('TargetPresensi/tabel', ['tahun' => $tahun, 'pegawai' => $pegawai]); ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Filter tahun
        $('#btn-filter').on('click', function() {
            var tahun = $('#filter-tahun').val();
            $.ajax({
                url: '<?= base_url() ?>TargetPresensi/tabel',
                type: 'POST',
                data: {
                    tahun: tahun
                },
                success: function(html) {
                    $('#container-tabel').html(html);
                    // Re-init DataTable setelah konten diganti
                    if ($.fn.DataTable.isDataTable('#tabel-target')) {
                        $('#tabel-target').DataTable().destroy();
                    }
                    initDataTable();
                }
            });
        });

        function initDataTable() {
            $('#tabel-target').DataTable({
                scrollX: true,
                dom: 'Bfrtip',
                buttons: ['excel'],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ – _END_ dari _TOTAL_ pegawai',
                    paginate: {
                        previous: 'Prev',
                        next: 'Next'
                    }
                }
            });
        }

        initDataTable();
    });
</script>