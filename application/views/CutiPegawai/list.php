<style>
  /* ===== SUMMARY CARDS ===== */
  .summary-card {
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
    margin-bottom: 4px;
  }

  .summary-card .sc-icon {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }

  .summary-card .sc-label {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 2px;
  }

  .summary-card .sc-value {
    font-size: 22px;
    font-weight: 700;
    line-height: 1;
  }

  /* ===== FILTER AREA ===== */
  .filter-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 16px 20px 8px;
    margin-bottom: 16px;
  }

  .filter-card label {
    font-size: 12px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 4px;
  }

  /* ===== BULK ACTION BAR ===== */
  #bulkActionBar {
    display: none;
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 10px 16px;
    margin-bottom: 12px;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  #bulkActionBar .bulk-label {
    font-weight: 600;
    font-size: 13px;
    color: #856404;
    flex: 1;
  }

  /* ===== TABLE ===== */
  #myTable thead th {
    background: #1976d2;
    color: #fff;
    font-size: 12px;
    white-space: nowrap;
    vertical-align: middle;
  }

  #myTable tbody tr:hover {
    background-color: #f0f7ff !important;
  }

  #myTable td {
    vertical-align: middle;
    font-size: 13px;
  }

  /* ===== PILIH CHECKBOX ===== */
  .pilih-btn {
    width: 22px;
    height: 22px;
    border: 2px solid #ced4da;
    background: white;
    border-radius: 4px;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s;
  }

  .pilih-btn:hover {
    border-color: #1976d2;
  }

  .pilih-btn.selected {
    background-color: #1976d2;
    border-color: #1976d2;
  }

  .pilih-btn.selected i {
    color: #fff !important;
  }

  /* ===== BADGE STATUS ===== */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
  }

  .status-badge.approved {
    background: #d4edda;
    color: #155724;
  }

  .status-badge.rejected {
    background: #f8d7da;
    color: #721c24;
  }

  .status-badge.pending {
    background: #fff3cd;
    color: #856404;
  }

  .status-badge.no-file {
    background: #e2e3e5;
    color: #383d41;
  }

  /* ===== ACTION BUTTONS ===== */
  .action-wrap {
    display: flex;
    gap: 4px;
    flex-wrap: nowrap;
  }

  .action-wrap .btn {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 4px;
    white-space: nowrap;
  }

  /* ===== LOADING OVERLAY ===== */
  #tableLoader {
    text-align: center;
    padding: 40px;
    display: none;
  }
</style>

<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">

      <!-- HEADER -->
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <h3 class="white-text mx-3 mb-0"><i class="fas fa-calendar-check me-2"></i> Cuti Pegawai</h3>
        <a href="<?php echo base_url(); ?>CutiPegawai/input">
          <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-3">
            <i class="fas fa-plus"></i> Tambah Data
          </button>
        </a>
      </div>

      <div class="card-body">

        <!-- SUMMARY CARDS -->
        <div class="row mb-4" id="summaryCards">
          <div class="col-6 col-md-3 mb-2">
            <div class="summary-card" style="background:#e3f2fd;">
              <div class="sc-icon" style="background:#1976d2; color:#fff;"><i class="fas fa-list"></i></div>
              <div>
                <div class="sc-label">Total Pengajuan</div>
                <div class="sc-value text-primary" id="sc-total">-</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-2">
            <div class="summary-card" style="background:#fff3cd;">
              <div class="sc-icon" style="background:#ffc107; color:#fff;"><i class="fas fa-clock"></i></div>
              <div>
                <div class="sc-label">Menunggu</div>
                <div class="sc-value text-warning" id="sc-pending">-</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-2">
            <div class="summary-card" style="background:#d4edda;">
              <div class="sc-icon" style="background:#28a745; color:#fff;"><i class="fas fa-check-circle"></i></div>
              <div>
                <div class="sc-label">Disetujui</div>
                <div class="sc-value text-success" id="sc-approved">-</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-2">
            <div class="summary-card" style="background:#f8d7da;">
              <div class="sc-icon" style="background:#dc3545; color:#fff;"><i class="fas fa-times-circle"></i></div>
              <div>
                <div class="sc-label">Ditolak</div>
                <div class="sc-value text-danger" id="sc-rejected">-</div>
              </div>
            </div>
          </div>
        </div>

        <!-- FILTER AREA -->
        <div class="filter-card">
          <div class="row align-items-end">
            <div class="col-md-4 mb-2">
              <label><i class="fas fa-calendar-alt me-1"></i> Rentang Tanggal</label>
              <div class="input-daterange input-group" id="date-range">
                <input type="text" class="form-control form-control-sm" name="start" id="start" value="<?php echo date('01-m-Y') ?>" readonly>
                <div class="input-group-append">
                  <span class="input-group-text bg-info text-white" style="font-size:12px;">S/D</span>
                </div>
                <input type="text" class="form-control form-control-sm" name="end" id="end" value="<?php echo date('d-m-Y') ?>" readonly>
              </div>
            </div>
            <div class="col-md-3 mb-2">
              <label><i class="fas fa-filter me-1"></i> Status Izin</label>
              <select id="status" class="form-control form-control-sm">
                <option value="">Semua Status</option>
                <option value="1">Disetujui</option>
                <option value="2">Ditolak</option>
                <option value="0">Menunggu</option>
              </select>
            </div>
            <div class="col-md-5 mb-2 d-flex align-items-end gap-2">
              <button type="button" class="btn btn-info btn-sm" onclick="search()">
                <i class="fas fa-search"></i> Cari
              </button>
              <button type="button" class="btn btn-secondary btn-sm" onclick="resetFilter()">
                <i class="fas fa-sync-alt"></i> Reset
              </button>
              <button type="button" class="btn btn-success btn-sm" onclick="exportExcel()">
                <i class="fas fa-file-excel"></i> Export Excel
              </button>
            </div>
          </div>
        </div>

        <!-- BULK ACTION BAR -->
        <div id="bulkActionBar">
          <div class="bulk-label">
            <i class="fas fa-check-square me-1"></i>
            <span id="bulkCount">0</span> data dipilih
          </div>
          <div id="aksiMassalContainer" class="d-flex gap-2 flex-wrap"></div>
          <button type="button" class="btn btn-danger btn-sm" onclick="hapusMassal()">
            <i class="fas fa-trash-alt"></i> Hapus
          </button>
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
            <i class="fas fa-times"></i> Batal
          </button>
        </div>

        <!-- TABLE -->
        <div id="tableLoader">
          <div class="spinner-border text-primary" role="status"></div>
          <div class="mt-2 text-muted" style="font-size:13px;">Memuat data...</div>
        </div>

        <div class="table-responsive hasilSearch">
          <table id="myTable" class="table table-hover table-striped table-bordered" width="100%">
            <thead>
              <tr>
                <th width="40px" class="text-center">
                  <button class="pilih-btn" id="selectAllBtn" title="Pilih Semua">
                    <i class="fas fa-check" style="font-size:11px; color:#fff; display:none;"></i>
                  </button>
                </th>
                <th width="40px">No</th>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Tgl Mulai</th>
                <th>Tgl Akhir</th>
                <th>Jenis Izin</th>
                <th>Alasan</th>
                <th width="100px" class="text-center">Status</th>
                <th width="180px" class="text-center">Aksi</th>
                <th width="100px" class="text-center">File</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($data)): $no = 1;
                foreach ($data as $value): ?>
                  <tr>
                    <td class="text-center">
                      <button class="pilih-btn" data-id="<?= $value->idizin ?>" data-status="<?= $value->status ?>">
                        <i class="fas fa-check" style="font-size:11px; color:#fff; display:none;"></i>
                      </button>
                    </td>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($value->NIP); ?></td>
                    <td><?= htmlspecialchars($value->nama_pegawai); ?></td>
                    <td><?= date("d-m-Y", strtotime($value->tanggal_mulai)); ?></td>
                    <td><?= date("d-m-Y", strtotime($value->tanggal_akhir)); ?></td>
                    <td><?= htmlspecialchars($value->jenis_izin); ?></td>
                    <td style="max-width:160px; white-space:normal; word-break:break-word;"><?= htmlspecialchars($value->alasan); ?></td>
                    <td class="text-center">
                      <?php if ($value->status == 1): ?>
                        <span class="status-badge approved"><i class="fas fa-check-circle"></i> Disetujui</span>
                      <?php elseif ($value->status == 2): ?>
                        <span class="status-badge rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                      <?php else: ?>
                        <span class="status-badge pending"><i class="fas fa-clock"></i> Menunggu</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="action-wrap">
                        <?php if ($value->status != 1): ?>
                          <button class="btn btn-success" onclick="updateStatus(<?= $value->idizin ?>, 1)" title="Setujui">
                            <i class="fas fa-check"></i> Setujui
                          </button>
                        <?php endif; ?>
                        <?php if ($value->status != 2): ?>
                          <button class="btn btn-danger" onclick="updateStatus(<?= $value->idizin ?>, 2)" title="Tolak">
                            <i class="fas fa-times"></i> Tolak
                          </button>
                        <?php endif; ?>
                        <?php if ($value->status == 1 || $value->status == 2): ?>
                          <button class="btn btn-warning" onclick="updateStatus(<?= $value->idizin ?>, 0)" title="Reset">
                            <i class="fas fa-undo"></i>
                          </button>
                        <?php endif; ?>
                      </div>
                    </td>
                    <td class="text-center">
                      <?php if (empty($value->file) || $value->file == "document/izin/"): ?>
                        <span class="status-badge no-file"><i class="fas fa-file-slash"></i> Tidak Ada</span>
                      <?php else: ?>
                        <a href="<?= base_url() . $value->file ?>" class="btn btn-info btn-sm" target="_blank">
                          <i class="fas fa-download"></i>
                        </a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach;
              else: ?>
                <tr>
                  <td colspan="11" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i> Tidak ada data
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>


<script>
  let selectedIds = [];
  let selectedStatuses = [];

  // ===== INIT =====
  $(document).ready(function() {
    $('#date-range').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: 'dd-mm-yyyy'
    });

    initDataTable();
    bindPilihButtons();
    updateSummary();

    // Pilih semua
    $(document).on('click', '#selectAllBtn', function() {
      var allBtns = $('#myTable tbody .pilih-btn');
      var allSelected = allBtns.length > 0 &&
        allBtns.length === allBtns.filter('.selected').length;

      if (allSelected) {
        allBtns.each(function() {
          deselect(this);
        });
      } else {
        allBtns.each(function() {
          doSelect(this);
        });
      }
      syncSelectAll();
      updateBulkBar();
    });
  });

  function initDataTable() {
    if ($.fn.DataTable.isDataTable('#myTable')) {
      $('#myTable').DataTable().destroy();
    }
    $('#myTable').DataTable({
      dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
      buttons: [{
        extend: 'excel',
        text: 'Excel',
        className: 'd-none'
      }],
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
      },
      responsive: true,
      columnDefs: [{
        orderable: false,
        targets: [0, 9, 10]
      }]
    });
  }

  // ===== SELECT LOGIC =====
  function doSelect(btn) {
    var $btn = $(btn);
    var id = parseInt($btn.data('id'));
    var st = parseInt($btn.data('status'));
    if (!selectedIds.includes(id)) {
      selectedIds.push(id);
      selectedStatuses.push(st);
    }
    $btn.addClass('selected').find('i').show();
  }

  function deselect(btn) {
    var $btn = $(btn);
    var id = parseInt($btn.data('id'));
    var idx = selectedIds.indexOf(id);
    if (idx > -1) {
      selectedIds.splice(idx, 1);
      selectedStatuses.splice(idx, 1);
    }
    $btn.removeClass('selected').find('i').hide();
  }

  function clearSelection() {
    $('#myTable tbody .pilih-btn').each(function() {
      deselect(this);
    });
    syncSelectAll();
    updateBulkBar();
  }

  function syncSelectAll() {
    var allBtns = $('#myTable tbody .pilih-btn');
    var selBtns = allBtns.filter('.selected');
    var allSel = allBtns.length > 0 && allBtns.length === selBtns.length;
    var $btn = $('#selectAllBtn');
    $btn.toggleClass('selected', allSel).find('i').toggle(allSel);
  }

  function updateBulkBar() {
    var count = selectedIds.length;
    $('#bulkCount').text(count);

    if (count > 0) {
      $('#bulkActionBar').css('display', 'flex');
      buildAksiMassalButtons();
    } else {
      $('#bulkActionBar').hide();
      $('#aksiMassalContainer').html('');
    }
  }

  function buildAksiMassalButtons() {
    var unique = [...new Set(selectedStatuses)];
    var html = '';

    var canApprove = unique.some(function(s) {
      return s !== 1;
    });
    var canReject = unique.some(function(s) {
      return s !== 2;
    });
    var canReset = unique.some(function(s) {
      return s === 1 || s === 2;
    });

    if (canApprove) html += '<button type="button" class="btn btn-success btn-sm" onclick="updateStatusMassal(1)"><i class="fas fa-check"></i> Setujui</button>';
    if (canReject) html += '<button type="button" class="btn btn-danger btn-sm" onclick="updateStatusMassal(2)"><i class="fas fa-times"></i> Tolak</button>';
    if (canReset) html += '<button type="button" class="btn btn-warning btn-sm" onclick="updateStatusMassal(0)"><i class="fas fa-undo"></i> Reset</button>';

    $('#aksiMassalContainer').html(html);
  }

  function bindPilihButtons() {
    $('.hasilSearch').off('click.pilih').on('click.pilih', '.pilih-btn:not(#selectAllBtn)', function(e) {
      e.preventDefault();
      if ($(this).hasClass('selected')) {
        deselect(this);
      } else {
        doSelect(this);
      }
      syncSelectAll();
      updateBulkBar();
    });
  }

  // ===== SUMMARY CARDS =====
  function updateSummary() {
    var rows = $('#myTable tbody tr');
    var total = rows.length;
    var pending = 0,
      approved = 0,
      rejected = 0;
    rows.each(function() {
      var badge = $(this).find('.status-badge');
      if (badge.hasClass('pending')) pending++;
      if (badge.hasClass('approved')) approved++;
      if (badge.hasClass('rejected')) rejected++;
    });
    $('#sc-total').text(total);
    $('#sc-pending').text(pending);
    $('#sc-approved').text(approved);
    $('#sc-rejected').text(rejected);
  }

  // ===== SEARCH =====
  function search() {
    clearSelection();
    var start = $('#start').val();
    var end = $('#end').val();
    var status = $('#status').val();

    $('#tableLoader').show();
    $('.hasilSearch').hide();

    $.ajax({
      type: 'POST',
      url: '<?= base_url("CutiPegawai/tabelIzinFiltered"); ?>',
      data: {
        start: start,
        end: end,
        status: status
      },
      dataType: 'html',
      success: function(html) {
        $('#tableLoader').hide();
        $('.hasilSearch').html(html).show();
        initDataTable();
        bindPilihButtons();
        updateSummary();
      },
      error: function() {
        $('#tableLoader').hide();
        $('.hasilSearch').show();
        showToast('Terjadi kesalahan saat memuat data.', 'danger');
      }
    });
  }

  function resetFilter() {
    $('#start').val('<?= date("01-m-Y") ?>');
    $('#end').val('<?= date("d-m-Y") ?>');
    $('#status').val('');
    search();
  }

  // ===== UPDATE STATUS (SINGLE) =====
  function updateStatus(idIzin, status) {
    var label = status === 1 ? 'Setujui' : (status === 2 ? 'Tolak' : 'Reset ke Menunggu');
    if (!confirm(label + ' izin ini?')) return;

    $.ajax({
      url: '<?= base_url(); ?>CutiPegawai/updateStatus',
      type: 'POST',
      data: {
        id_izin: idIzin,
        status: status
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          showToast(response.message, 'success');
          search();
        } else {
          showToast(response.message, 'danger');
        }
      },
      error: function() {
        showToast('Terjadi kesalahan sistem.', 'danger');
      }
    });
  }

  // ===== UPDATE STATUS MASSAL =====
  function updateStatusMassal(newStatus) {
    if (selectedIds.length === 0) {
      showToast('Belum ada data dipilih.', 'warning');
      return;
    }
    var label = newStatus === 1 ? 'Setujui' : (newStatus === 2 ? 'Tolak' : 'Reset');
    if (!confirm(label + ' ' + selectedIds.length + ' data yang dipilih?')) return;

    var action = newStatus === 1 ? 'approve' : (newStatus === 2 ? 'reject' : 'reset');
    $.ajax({
      url: '<?= base_url(); ?>CutiPegawai/massAction',
      type: 'POST',
      data: {
        action: action,
        ids: selectedIds
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          showToast(response.message, 'success');
          clearSelection();
          search();
        } else {
          showToast(response.message, 'danger');
        }
      },
      error: function() {
        showToast('Terjadi kesalahan sistem.', 'danger');
      }
    });
  }

  // ===== HAPUS MASSAL =====
  function hapusMassal() {
    if (selectedIds.length === 0) {
      showToast('Belum ada data dipilih.', 'warning');
      return;
    }
    if (!confirm('Hapus ' + selectedIds.length + ' data yang dipilih?')) return;

    $.ajax({
      url: '<?= base_url(); ?>CutiPegawai/massAction',
      type: 'POST',
      data: {
        action: 'delete',
        ids: selectedIds
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          showToast(response.message, 'success');
          clearSelection();
          search();
        } else {
          showToast(response.message, 'danger');
        }
      },
      error: function() {
        showToast('Terjadi kesalahan sistem.', 'danger');
      }
    });
  }

  // ===== EXPORT EXCEL =====
  function exportExcel() {
    $('#myTable').DataTable().button('.buttons-excel').trigger();
  }

  // ===== TOAST NOTIFICATION =====
  function showToast(message, type) {
    type = type || 'info';
    var colors = {
      success: '#28a745',
      danger: '#dc3545',
      warning: '#ffc107',
      info: '#17a2b8'
    };
    var icons = {
      success: 'check-circle',
      danger: 'times-circle',
      warning: 'exclamation-triangle',
      info: 'info-circle'
    };

    var $toast = $('<div>')
      .css({
        position: 'fixed',
        top: '20px',
        right: '20px',
        zIndex: 99999,
        background: colors[type],
        color: type === 'warning' ? '#856404' : '#fff',
        padding: '12px 20px',
        borderRadius: '8px',
        boxShadow: '0 4px 12px rgba(0,0,0,.2)',
        fontSize: '13px',
        fontWeight: '500',
        display: 'flex',
        alignItems: 'center',
        gap: '8px',
        maxWidth: '320px'
      })
      .html('<i class="fas fa-' + icons[type] + '"></i> ' + message);

    $('body').append($toast);
    setTimeout(function() {
      $toast.fadeOut(400, function() {
        $(this).remove();
      });
    }, 3000);
  }
</script>