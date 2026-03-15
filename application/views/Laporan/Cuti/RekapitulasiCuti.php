<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Filter Rekapitulasi Cuti</h4>
                <div class="row">
                    <div class="col-md-3">
                        <label>Tanggal Mulai :</label>
                        <input type="date" id="tgl_mulai" class="form-control" value="<?php echo date('Y-m-01'); ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Tanggal Akhir :</label>
                        <input type="date" id="tgl_akhir" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-2">
                        <label>Unit :</label>
                        <select id="unit" class="form-control select2" onchange="sub_unit()">
                            <option value="">Semua Unit</option>
                            <?php foreach ($unit as $value): ?>
                                <option value="<?php echo $value->nama_unit; ?>"><?php echo $value->nama_unit ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Sub Unit :</label>
                        <select id="sub_unit" class="form-control select2">
                            <option value="">Semua Sub Unit</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Status :</label>
                        <select id="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="0">Pending</option>
                            <option value="1">Disetujui</option>
                            <option value="2">Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button class="btn btn-primary" onclick="loadRekapitulasi()"><i class="fa fa-search"></i> Tampilkan</button>
                        <button class="btn btn-success" onclick="exportExcel()"><i class="fa fa-file-excel"></i> Export Excel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Rekapitulasi Cuti Pegawai</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableRekapitulasi">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Unit</th>
                                <th class="bg-primary text-white">Cuti Tahunan</th>
                                <th class="bg-success text-white">Disetujui</th>
                                <th class="bg-warning text-white">Pending</th>
                                <th class="bg-danger text-white">Ditolak</th>
                                <th class="bg-primary text-white">Total Cuti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyRekapitulasi">
                            <tr>
                                <td colspan="10" class="text-center">Silakan pilih filter dan klik Tampilkan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Cuti -->
<div class="modal fade" id="modalDetailCuti" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detail Cuti - <span id="namaPegawai"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Cuti</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="bodyDetailCuti">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tableRekapitulasi').DataTable();
        // Load data saat halaman pertama kali dibuka
        loadRekapitulasi();
    });

    function sub_unit() {
        var unit = $("#unit").val();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url() ?>Laporan/sub_unit',
            data: {
                unit: unit
            },
            success: function(response) {
                $("#sub_unit").html(response);
            }
        });
    }

    function loadRekapitulasi() {
        var tgl_mulai = $('#tgl_mulai').val();
        var tgl_akhir = $('#tgl_akhir').val();
        var unit = $('#unit').val();
        var sub_unit = $('#sub_unit').val();
        var status = $('#status').val();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>Laporan/data_rekapitulasi_cuti",
            data: {
                start: tgl_mulai,
                end: tgl_akhir,
                unit: unit,
                sub_unit: sub_unit,
                status: status
            },
            dataType: "json",
            beforeSend: function() {
                // $('#tableRekapitulasi').DataTable().destroy();
                $('#bodyRekapitulasi').html('<tr><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
            },
            success: function(data) {
                var html = '';

                if (data.length > 0) {
                    $.each(data, function(index, item) {
                        html += '<tr>';
                        html += '<td>' + (index + 1) + '</td>';
                        html += '<td>' + item.nip + '</td>';
                        html += '<td>' + item.nama + '</td>';
                        html += '<td>' + item.unit + '</td>';
                        html += '<td class="text-center">' + item.cuti_tahunan + '</td>';
                        html += '<td class="text-center">' + item.cuti_disetujui + '</td>';
                        html += '<td class="text-center">' + item.cuti_pending + '</td>';
                        html += '<td class="text-center">' + item.cuti_ditolak + '</td>';
                        html += '<td class="text-center">' + item.total_cuti + '</td>';
                        html += '<td class="text-center">';
                        html += '<button class="btn btn-sm btn-info btn-rounded" onclick=\'showDetail(' + JSON.stringify(item) + ')\'><i class="fa fa-eye"></i> Detail</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="10" class="text-center">Tidak ada data cuti pada periode yang dipilih</td></tr>';
                }
                $('#bodyRekapitulasi').html(html);

                // Inisialisasi DataTable pada elemen table, bukan tbody
                $('#tableRekapitulasi').DataTable().destroy();
                $('#tableRekapitulasi').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    },
                    "pageLength": 25,
                    "order": [
                        [1, 'asc']
                    ]
                });
            },
            error: function(e) {
                console.log("Error loading data", e);
                $('#bodyRekapitulasi').html('<tr><td colspan="10" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    }

    function showDetail(item) {
        $('#namaPegawai').text(item.nama + ' (' + item.nip + ')');

        var html = '';
        if (item.detail && item.detail.length > 0) {
            $.each(item.detail, function(index, detail) {
                var statusBadge = '';
                if (detail.status == '1') {
                    statusBadge = '<span class="badge badge-success">Disetujui</span>';
                } else if (detail.status == '0') {
                    statusBadge = '<span class="badge badge-warning">Pending</span>';
                } else if (detail.status == '2') {
                    statusBadge = '<span class="badge badge-danger">Ditolak</span>';
                }

                html += '<tr>';
                html += '<td>' + (index + 1) + '</td>';
                html += '<td>' + detail.jenis + '</td>';
                html += '<td>' + detail.tanggal_mulai + '</td>';
                html += '<td>' + detail.tanggal_selesai + '</td>';
                html += '<td>' + detail.durasi + '</td>';
                html += '<td>' + statusBadge + '</td>';
                html += '<td>' + detail.keterangan + '</td>';
                html += '</tr>';
            });
        } else {
            html = '<tr><td colspan="7" class="text-center">Tidak ada detail</td></tr>';
        }

        $('#bodyDetailCuti').html(html);
        $('#modalDetailCuti').modal('show');
    }

    function exportExcel() {
        var tgl_mulai = $('#tgl_mulai').val();
        var tgl_akhir = $('#tgl_akhir').val();
        var unit = $('#unit').val();
        var sub_unit = $('#sub_unit').val();
        var status = $('#status').val();

        var url = "<?php echo base_url(); ?>Dashboard/export_rekapitulasi_cuti?";
        url += "start=" + tgl_mulai;
        url += "&end=" + tgl_akhir;
        url += "&unit=" + unit;
        url += "&sub_unit=" + sub_unit;
        url += "&status=" + status;

        window.open(url, '_blank');
    }
</script>