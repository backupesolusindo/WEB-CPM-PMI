<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" integrity="sha512-C7hOmCgGzihKXzyPU/z4nv97W0d9bv4ALuuEbSf6hm93myico9qa0hv4dODThvCsqQUmKmLcJmlpRmCaApr83g==" crossorigin="anonymous" />
<div class="row">
  <!-- Filter Section -->
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body row">
        <div class="col-md-4">
          <label>Berdasarkan Tanggal:</label>
          <div class="input-daterange input-group" id="date-range">
            <input type="text" class="form-control" name="start" id="start" value="<?php echo '01'.date("-m-Y") ?>" readonly/>
            <div class="input-group-append">
              <span class="input-group-text bg-info b-0 text-white">S/D</span>
            </div>
            <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly/>
          </div>
          <br>
        </div>
        <div class="col-md-3">
          <label>Jabatan:</label>
          <select id="jabatan" class="form-control select2 col-md-12" required>
            <option value="">Semua Jabatan</option>
            <?php foreach ($jabatan as $value): ?>
              <option value="<?php echo $value->namajabatan; ?>"><?php echo $value->namajabatan ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4 me-3">
          <label>Nama Pegawai :</label>
          <select id="pegawai" class="form-control select2" required>
              <option value="">Semua Pegawai</option>
              <?php foreach ($pegawai as $value): ?>
                  <option value="<?php echo $value->uuid ?>">
                      <?php echo $value->nama_pegawai ?>
                  </option>
              <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <br>
          <button type="button" class="btn btn-info btn-md" onclick="loadData()"> <i class="fa fa-search"></i> Cari</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Stat Cards -->
  <div class="col-lg-6">
    <div class="row">
      <div class="col-md-6">
        <div class="card bg-primary z-depth-2" style="min-height:100px;">
          <div class="row mt-2">
            <div class="col-md-5 col-5 text-left pl-3">
              <a type="button" href="#" class="btn-floating btn-lg primary-color accent-2 ml-3"><i class="fas fa-clipboard-list"></i></a>
            </div>
            <div class="col-md-7 col-7 text-right pr-4">
              <h3 class="ml-3 mt-3 mb-1 font-weight-bold white-text txtTotalPekerjaan">0</h3>
              <p class="font-small white-text font-weight-bold">Total Pekerjaan</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card bg-warning z-depth-2" style="min-height:100px;">
          <div class="row mt-2">
            <div class="col-md-5 col-5 text-left pl-3">
              <a type="button" href="#" class="btn-floating btn-lg warning-color ml-3"><i class="fas fa-spinner"></i></a>
            </div>
            <div class="col-md-7 col-7 text-right pr-4">
              <h3 class="ml-3 mt-3 mb-1 font-weight-bold white-text txtSedangDikerjakan">0</h3>
              <p class="font-small white-text font-weight-bold">Sedang Dikerjakan</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row mt-3">
      <div class="col-md-6">
        <div class="card bg-success z-depth-2" style="min-height:100px;">
          <div class="row mt-2">
            <div class="col-md-5 col-5 text-left pl-3">
              <a type="button" href="#" class="btn-floating btn-lg success-color ml-3"><i class="fas fa-check-circle"></i></a>
            </div>
            <div class="col-md-7 col-7 text-right pr-4">
              <h3 class="ml-3 mt-3 mb-1 font-weight-bold white-text txtMenungguPersetujuan">0</h3>
              <p class="font-small white-text font-weight-bold">Menunggu Persetujuan</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card bg-danger z-depth-2" style="min-height:100px;">
          <div class="row mt-2">
            <div class="col-md-5 col-5 text-left pl-3">
              <a type="button" href="#" class="btn-floating btn-lg danger-color ml-3"><i class="fas fa-exclamation-triangle"></i></a>
            </div>
            <div class="col-md-7 col-7 text-right pr-4">
              <h3 class="ml-3 mt-3 mb-1 font-weight-bold white-text txtDitolak">0</h3>
              <p class="font-small white-text font-weight-bold">Ditolak</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-12">
      <div class="card aqua-gradient z-depth-2" style="min-height:100px;">
        <div class="row mt-2">
          <div class="col-md-5 col-5 text-left pl-3">
            <a type="button" href="#" class="btn-floating btn-lg info-color ml-3"><i class="fas fa-check-circle"></i></a>
          </div>
          <div class="col-md-7 col-7 text-right pr-4">
            <h3 class="ml-3 mt-3 mb-1 font-weight-bold white-text txtDiterima">0</h3>
            <p class="font-small white-text font-weight-bold">Diterima</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Morris Donut Chart -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Karakteristik</h4>
        <div id="morris-donut-chart" style="height: 300px;"></div>
      </div>
    </div>
  </div>

  <!-- Task List with Scrollable Table -->
  <div class="col-lg-12 mt-4">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Pekerjaan</h4>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
          <table class="table table-hover">
            <thead>
              <tr>
                <th style="position: sticky; top: 0; background: white; z-index: 10;">Nama Pegawai</th>
                <th style="position: sticky; top: 0; background: white; z-index: 10;">Nama Pekerjaan</th>
                <th style="position: sticky; top: 0; background: white; z-index: 10;">Jabatan</th>
                <th style="position: sticky; top: 0; background: white; z-index: 10;">Point</th>
              </tr>
            </thead>
            <tbody id="high-priority-tasks">
              <!-- Data will be loaded here -->
            </tbody>
          </table>
        </div>
        <div class="mt-2">
          <small class="text-muted" id="table-info">Menampilkan 0 data</small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include required libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

<!-- Previous HTML code remains the same until the script section -->

<script>
// Global variables
var donutChart = null;

$(document).ready(function() {
    // Initialize datepicker
    $('#date-range').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        language: "id"
    });
    
    // Initialize select2
    $('.select2').select2();
    
    // Load initial data
    loadData();
});

function loadData() {
    var start_date = $('#start').val();
    var end_date = $('#end').val();
    var jabatan = $('#jabatan').val();
    var pegawai = $('#pegawai').val();
    
    // Show loading state
    $('#high-priority-tasks').html('<tr><td colspan="4" class="text-center">Memuat data...</td></tr>');
    $('#morris-donut-chart').html('<div class="text-center">Memuat chart...</div>');
    
    $.ajax({
        url: '<?php echo base_url("Pekerjaan/get_dashboard_data"); ?>',
        type: 'POST',
        data: {
            start_date: start_date,
            end_date: end_date,
            jabatan: jabatan,
            pegawai: pegawai
        },
        dataType: 'json',
        success: function(response) {
            // Update all components with the filtered data
            updateDashboard(response);
        },
        error: function(xhr, status, error) {
            console.error('Error loading data:', error);
            $('#high-priority-tasks').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data</td></tr>');
            $('#morris-donut-chart').html('<div class="text-center text-danger">Gagal memuat chart</div>');
        }
    });
}

function updateDashboard(response) {
    // Update stat cards
    updateStatCards(response);
    
    // Update donut chart - first clear the container
    $('#morris-donut-chart').empty();
    updateStatusChart(response.percentages || []);
    
    // Update task table
    updateTaskTable(response.today_tasks || []);
}

function updateStatCards(data) {
    $('.txtTotalPekerjaan').text(data.total || 0);
    $('.txtSedangDikerjakan').text(data.pending || 0);
    $('.txtMenungguPersetujuan').text(data.complete || 0);
    $('.txtDitolak').text(data.reject || 0);
    $('.txtDiterima').text(data.approve || 0);
}

function updateStatusChart(data) {
    // Ensure the container is completely empty
    $('#morris-donut-chart').empty();
    
    // Prepare data for Morris Donut
    var chartData = [];
    var colors = [];
    
    data.forEach(function(item) {
        chartData.push({
            label: item.status,
            value: item.value
        });
        
        // Assign colors based on status
        switch(item.status) {
            case 'Pending': colors.push('#ffbb33'); break;
            case 'Complete': colors.push('#00c851'); break;
            case 'Reject': colors.push('#ff3547'); break;
            case 'Approve': colors.push('#00d4ff'); break;
            default: colors.push('#6c757d');
        }
    });
    
    // Destroy previous chart if exists
    if (donutChart) {
        try {
            donutChart.destroy();
        } catch(e) {
            console.error('Error destroying chart:', e);
        }
        donutChart = null;
    }
    
    // Only create chart if we have data
    if (chartData.length > 0) {
        try {
            donutChart = Morris.Donut({
                element: 'morris-donut-chart',
                data: chartData,
                resize: true,
                colors: colors,
                formatter: function (value) { return value + '%'; }
            });
        } catch(e) {
            console.error('Error creating chart:', e);
            $('#morris-donut-chart').html('<div class="text-center text-danger">Gagal menampilkan chart</div>');
        }
    } else {
        $('#morris-donut-chart').html('<div class="text-center">Tidak ada data untuk ditampilkan</div>');
    }
}

function updateTaskTable(tasks) {
    var tableBody = $('#high-priority-tasks');
    tableBody.empty();
    
    if (tasks.length === 0) {
        tableBody.append('<tr><td colspan="4" class="text-center">Tidak ada data pekerjaan untuk ditampilkan</td></tr>');
        $('#table-info').text('Menampilkan 0 data');
        return;
    }
    
    // Add all tasks to table
    tasks.forEach(function(task) {
        var row = '<tr>' +
            '<td>' + (task.nama_pegawai || '-') + '</td>' +
            '<td>' + (task.nama_pekerjaan || '-') + '</td>' +
            '<td>' + (task.namajabatan || '-') + '</td>' +
            '<td>' + (task.point || '0') + '</td>' +
            '</tr>';
        tableBody.append(row);
    });
    
    // Update table info
    $('#table-info').text('Menampilkan '+tasks.length+' data' + 
                         (tasks.length > 10 ? ' (scroll untuk melihat lebih banyak)' : ''));
}
</script>