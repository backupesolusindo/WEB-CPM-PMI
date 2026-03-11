<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <h3 class="white-text mx-3">CUTI PEGAWAI</h3>

        <a href="<?php echo base_url(); ?>CutiPegawai/input" class="float-right">
          <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
            <i class="fas fa-plus mt-0"></i> Tambah Data
          </button>
        </a>
      </div>

      <div class="card-body row">

        <!-- FILTER TANGGAL -->
        <div class="col-md-4 mb-3">
          <label>Menurut Tanggal :</label>
          <div class="input-daterange input-group" id="date-range">
            <input type="text" class="form-control" name="start" id="start" value="<?php echo date('d-m-Y') ?>" readonly>

            <div class="input-group-append">
              <span class="input-group-text bg-info b-0 text-white">S/D</span>
            </div>

            <input type="text" class="form-control" name="end" id="end" value="<?php echo date('d-m-Y') ?>" readonly>
          </div>
        </div>

        <!-- FILTER STATUS -->
        <div class="col-md-3 mb-3">
          <label>Status Izin :</label>
          <select id="status" class="form-control select2 col-md-12" onchange="search()">
            <option value="">Semua Status</option>
            <option value="1">Disetujui</option>
            <option value="2">Ditolak</option>
            <option value="0">Menunggu</option>
          </select>
        </div>

        <!-- TOMBOL CARI & MASSAL -->
        <div class="col-md-2 mb-3">
          <br>
          <button type="button" class="btn btn-info btn-md" onclick="search()">
            <i class="fa fa-search"></i> Cari
          </button>
          <!-- Tombol Massal akan muncul di sini secara dinamis -->
          <div id="massalButtonContainer" style="display: none; margin-left: 5px;">
            <button type="button" id="hapusMassalBtn" class="btn btn-success btn-md" onclick="hapusMassal()">
              <i class="fas fa-trash-alt"></i> Hapus Terpilih
            </button>
            <!-- Tombol Aksi Massal akan muncul di sini -->
            <div id="aksiMassalContainer" style="display: inline-block; margin-left: 5px;"></div>
          </div>
        </div>

        <!-- HASIL SEARCH -->
        <div class="col-12">
          <div class="table-responsive hasilSearch">
            <!-- Table akan diganti AJAX -->
            <table id="myTable" class="table table-hover table-striped table-bordered">
              <thead>
                <tr>
                  <th>Pilih</th>
                  <th>NO</th>
                  <th>NIP</th>
                  <th>Nama</th>
                  <th>Tanggal Mulai</th>
                  <th>Tanggal Akhir</th>
                  <th>Jenis Izin</th>
                  <th>Alasan Izin</th>
                  <th>Status Izin</th>
                  <th>Approval/Denied</th>
                  <th>Download File</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($data as $value): ?>
                <tr>
                 <td>
  <button 
    class="pilih-btn" 
    data-id="<?= $value->idizin ?>"
    data-status="<?= $value->status ?>"
    style="
      width:18px;
      height:18px;
      border:2px solid #000;
      background:white;
      border-radius:3px;
      padding:0;
      display:flex;
      justify-content:center;
      align-items:center;
      cursor:pointer;
      transition: background-color 0.2s;
    "
  >
    <i class="fas fa-check" style="font-size:12px; color:#28a745; display:none;"></i>
  </button>
</td>
                  <td><?= $no++; ?></td>
                  <td><?= $value->NIP; ?></td>
                  <td><?= $value->nama_pegawai; ?></td>
                  <td><?= date("d-m-Y", strtotime($value->tanggal_mulai)); ?></td>
                  <td><?= date("d-m-Y", strtotime($value->tanggal_akhir)); ?></td>
                  <td><?= $value->jenis_izin; ?></td>
                  <td><?= $value->alasan; ?></td>
                  <td>
                    <?php 
                      if ($value->status == 1) echo '<span class="badge badge-success p-2">Disetujui</span>';
                      elseif ($value->status == 2) echo '<span class="badge badge-danger p-2">Ditolak</span>';
                      else echo '<span class="badge badge-warning p-2">Menunggu</span>';
                    ?>
                  </td>

                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <?php if ($value->status != 1): ?>
                        <button class="btn btn-success" onclick="updateStatus(<?= $value->idizin ?>, 1)">
                          <i class="fas fa-check"></i> Setujui
                        </button>
                      <?php endif; ?>

                      <?php if ($value->status != 2): ?>
                        <button class="btn btn-danger" onclick="updateStatus(<?= $value->idizin ?>, 2)">
                          <i class="fas fa-times"></i> Tolak
                        </button>
                      <?php endif; ?>

                      <?php if ($value->status == 1 || $value->status == 2): ?>
                        <button class="btn btn-warning" onclick="updateStatus(<?= $value->idizin ?>, 0)">
                          <i class="fas fa-undo"></i> Reset
                        </button>
                      <?php endif; ?>
                    </div>
                  </td>

                  <td>
                    <?php 
                      if (empty($value->file) || $value->file == "document/izin/"):
                        echo '<span class="badge badge-secondary">Tidak Ada File</span>';
                      else:
                        echo '<a href="'.base_url().$value->file.'" class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-download"></i> Download
                              </a>';
                      endif;
                    ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Array untuk menyimpan ID izin yang dipilih
let selectedIds = [];
let selectedStatuses = [];

// ================== UPDATE STATUS ==================
function updateStatus(idIzin, status) {
    let message = 
        status === 1 ? "Approve izin ini?" :
        status === 2 ? "Tolak izin ini?" :
        "Reset status ke Pending?";

    if (confirm(message)) {
        $("button").prop("disabled", true);

        $.ajax({
            url: "<?= base_url(); ?>CutiPegawai/updateStatus",
            type: "POST",
            data: { id_izin: idIzin, status: status },
            dataType: "json",
            success: function(response){
                $("button").prop("disabled", false);
                alert(response.success ? "Sukses: " + response.message : "Error: " + response.message);
                if (response.success) window.location.href = "<?= base_url('CutiPegawai'); ?>";
            },
            error: function(){
                $("button").prop("disabled", false);
                alert("Terjadi kesalahan sistem.");
            }
        });
    }
}

// ================== TOGGLE SELECT ==================
function toggleSelect(button, idIzin, status) {
    const $btn = $(button);
    const $icon = $btn.find('i');

    const index = selectedIds.indexOf(idIzin);
    if (index > -1) {
        selectedIds.splice(index, 1);
        selectedStatuses.splice(index, 1);
        $icon.hide();
        $btn.css('background-color', 'white');
    } else {
        selectedIds.push(idIzin);
        selectedStatuses.push(status);
        $icon.show();
        $btn.css('background-color', '#d4edda');
    }

    updateMassalButton();
}

// ================== UPDATE MASSAL BUTTON VISIBILITY ==================
function updateMassalButton() {
    if (selectedIds.length > 0) {
        $('#massalButtonContainer').show();
        buildAksiMassalButtons();
    } else {
        $('#massalButtonContainer').hide();
        $('#aksiMassalContainer').html('');
    }
}

// ================== BUILD AKSI MASSAL BUTTONS ==================
function buildAksiMassalButtons() {
    const uniqueStatuses = [...new Set(selectedStatuses)];

    $('#aksiMassalContainer').html('');

    if (uniqueStatuses.length === 1) {
        const status = uniqueStatuses[0];

        if (status === 1) {
            $('#aksiMassalContainer').append(`
                <button type="button" class="btn btn-danger btn-md" onclick="updateStatusMassal(2)">
                    <i class="fas fa-times"></i> Tolak Semua
                </button>
                <button type="button" class="btn btn-warning btn-md" onclick="updateStatusMassal(0)">
                    <i class="fas fa-undo"></i> Reset Semua
                </button>
            `);
        } else if (status === 2) {
            $('#aksiMassalContainer').append(`
                <button type="button" class="btn btn-success btn-md" onclick="updateStatusMassal(1)">
                    <i class="fas fa-check"></i> Setujui Semua
                </button>
                <button type="button" class="btn btn-warning btn-md" onclick="updateStatusMassal(0)">
                    <i class="fas fa-undo"></i> Reset Semua
                </button>
            `);
        } else if (status === 0) {
            $('#aksiMassalContainer').append(`
                <button type="button" class="btn btn-success btn-md" onclick="updateStatusMassal(1)">
                    <i class="fas fa-check"></i> Setujui Semua
                </button>
                <button type="button" class="btn btn-danger btn-md" onclick="updateStatusMassal(2)">
                    <i class="fas fa-times"></i> Tolak Semua
                </button>
            `);
        }
    }
}

// ================== UPDATE STATUS MASSAL ==================
function updateStatusMassal(newStatus) {
    if (selectedIds.length === 0) {
        alert("Belum ada data yang dipilih untuk diubah statusnya.");
        return;
    }

    let actionText = '';
    if (newStatus === 1) actionText = "Setujui";
    else if (newStatus === 2) actionText = "Tolak";
    else if (newStatus === 0) actionText = "Reset";

    if (!confirm(`Anda yakin ingin ${actionText} ${selectedIds.length} data izin yang dipilih?`)) {
        return;
    }

    $("button").prop("disabled", true);

    $.ajax({
        url: "<?= base_url(); ?>CutiPegawai/massAction",
        type: "POST",
        data: { action: newStatus === 1 ? 'approve' : (newStatus === 2 ? 'reject' : 'reset'), ids: selectedIds },
        dataType: "json",
        success: function(response){
            $("button").prop("disabled", false);
            alert(response.success ? "Sukses: " + response.message : "Error: " + response.message);
            if (response.success) {
                selectedIds = [];
                selectedStatuses = [];
                updateMassalButton();
                window.location.href = "<?= base_url('CutiPegawai'); ?>";
            }
        },
        error: function(){
            $("button").prop("disabled", false);
            alert("Terjadi kesalahan sistem.");
        }
    });
}

// ================== HAPUS MASSAL ==================
function hapusMassal() {
    if (selectedIds.length === 0) {
        alert("Belum ada data yang dipilih untuk dihapus.");
        return;
    }

    if (!confirm(`Anda yakin ingin menghapus ${selectedIds.length} data izin yang dipilih?`)) {
        return;
    }

    $("button").prop("disabled", true);

    $.ajax({
        url: "<?= base_url(); ?>CutiPegawai/massAction",
        type: "POST",
        data: { action: 'delete', ids: selectedIds },
        dataType: "json",
        success: function(response){
            $("button").prop("disabled", false);
            alert(response.success ? "Sukses: " + response.message : "Error: " + response.message);
            if (response.success) {
                selectedIds = [];
                selectedStatuses = [];
                updateMassalButton();
                window.location.href = "<?= base_url('CutiPegawai'); ?>";
            }
        },
        error: function(){
            $("button").prop("disabled", false);
            alert("Terjadi kesalahan sistem.");
        }
    });
}

// ================== HAPUS (SINGLE) ==================
function hapus(idIzin){
    if (confirm("Hapus izin ini?")){
        $("button").prop("disabled", true);

        $.ajax({
            url: "<?= base_url(); ?>CutiPegawai/hapus",
            type: "POST",
            data: { id_izin: idIzin },
            dataType: "json",
            success: function(response){
                $("button").prop("disabled", false);
                alert(response.success ? "Sukses: " + response.message : "Error: " + response.message);
                if(response.success) search();
            },
            error: function(){
                $("button").prop("disabled", false);
                alert("Terjadi kesalahan sistem.");
            }
        });
    }
}

// ================== SEARCH ==================
function search() {
    var start  = $("#start").val();
    var end    = $("#end").val();
    var status = $("#status").val();

    $.ajax({
        type: "POST",
        url: "<?= base_url('CutiPegawai/tabelIzinFiltered'); ?>",
        data: { start: start, end: end, status: status },
        dataType: "html",
        beforeSend: function() {
            $(".hasilSearch").html('<div class="text-center">Memuat...</div>');
        },
        success: function(html){
            $(".hasilSearch").html(html);

            // Destroy old datatable
            if ($.fn.DataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable().destroy();
            }

            // Init datatable baru
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel']
            });

            // RE-BIND EVENT HANDLER UNTUK TOMBOL PILIH (UNTUK DATA BARU)
            bindPilihButtons();
        },
        error: function(){
            alert("Terjadi kesalahan sistem.");
        }
    });
}

// ================== BIND EVENT HANDLER UNTUK TOMBOL PILIH ==================
function bindPilihButtons() {
    $('.hasilSearch').off('click.pilih').on('click.pilih', '.pilih-btn', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let status = $(this).data('status');
        toggleSelect(this, id, status);
    });
}

// ================== INISIALISASI EVENT HANDLER SAAT HALAMAN PERTAMA DIMUAT ==================
$(document).ready(function() {
    // Aktifkan datepicker
    $('#date-range').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy"
    });

    // Inisialisasi DataTable awal
    if ($.fn.DataTable.isDataTable('#myTable')) {
        $('#myTable').DataTable().destroy();
    }

    $('#myTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel']
    });

    // DAFTARKAN EVENT HANDLER UNTUK TOMBOL PILIH DI HALAMAN AWAL
    bindPilihButtons();
});

</script>