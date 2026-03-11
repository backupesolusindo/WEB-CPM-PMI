<table id="myTable" class="table color-table table-hover table-striped">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th>Nama Pegawai</th>
            <th>Total Poin</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($total_poin_pegawai)): ?>
        <?php $no = 1; foreach ($total_poin_pegawai as $row): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['nama_pegawai']); ?></td>
                <td><?php echo isset($row['total_point']) ? number_format($row['total_point'], 0, ',', '.') : '0'; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">Tidak ada data ditemukan</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    // Handler untuk tombol refresh per pegawai
    $('.btn-refresh-single').click(function() {
        var pegawai_id = $(this).data('id');
        refreshData(pegawai_id);
    });
    
    // Inisialisasi DataTable jika belum
    if (!$.fn.DataTable.isDataTable('#myTable')) {
        $('#myTable').DataTable({
            "pageLength": 10,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    } else {
        $('#myTable').DataTable().draw();
    }
});
</script>