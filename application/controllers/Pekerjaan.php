
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pekerjaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelPekerjaan');
        $this->load->model('ModelJabatan');
        $this->load->model('ModelRiwayatPekerjaan');
        $this->load->model('ModelTotalPoint');
        // Pastikan helper/library core sudah di-load
        $this->load->library('core'); // Ubah menjadi helper jika core adalah helper
    }

    // Tampilkan daftar pekerjaan
    public function ListPekerjaan()
    {
        $this->load->model('ModelPekerjaan');
        $data = array(
            'title' => 'List Pekerjaan',
            'body' => 'Pekerjaan/ListPekerjaan/index',
            'listpekerjaan' => $this->ModelPekerjaan->get_all()
        );

        $this->load->view('index', $data);
    }

   

    // Form tambah pekerjaan
    public function input()
    {
        $data = array(
            'title' => 'FORM INPUT PEKERJAAN',
            'form' => 'Pekerjaan/ListPekerjaan/tambah',
            'body' => 'Pekerjaan/ListPekerjaan/input',
            'jabatan' => $this->ModelJabatan->get_data()->result()
        );
        
        $this->load->view('index', $data);
    }

    // Simpan pekerjaan baru
    public function simpan()
    {
        // Ambil nilai dari input form
        $input_data = array(
            'jabatan_idjabatan' => $this->input->post('jabatan_idjabatan'),
            'nama_pekerjaan' => $this->input->post('nama_pekerjaan'),
            'point' => $this->input->post('point'),
            'tipe_pekerjaan' => $this->input->post('tipe_pekerjaan'),
        );
        
        if ($this->ModelPekerjaan->insert($input_data)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Data pekerjaan berhasil ditambahkan."));
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal menambahkan data pekerjaan."));
        }
        redirect(base_url('/Pekerjaan/ListPekerjaan'));
    }

    // Update pekerjaan
    public function update()
    {
        $id = $this->input->post('id_pekerjaan');
        $update_data = array(
            'jabatan_idjabatan' => $this->input->post('jabatan_idjabatan'),
            'nama_pekerjaan' => $this->input->post('nama_pekerjaan'),
            'point' => $this->input->post('point'),
            'tipe_pekerjaan' => $this->input->post('tipe_pekerjaan'),
        );

        if ($this->ModelPekerjaan->update($id, $update_data)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Data pekerjaan berhasil diperbarui."));
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal memperbarui data pekerjaan."));
        }
        redirect(base_url('/Pekerjaan/ListPekerjaan'));
    }
   
    // Form edit pekerjaan
    function edit($id)
    {
      $data = array(
        'title'        => 'EDIT PEKERJAAN',
        'form'         =>'Pekerjaan/ListPekerjaan/tambah',
        'body'         => 'Pekerjaan/ListPekerjaan/edit' ,
        'pekerjaan'  => $this->ModelPekerjaan->get_edit($id),
        'jabatan'      => $this->ModelJabatan->get_data()->result()
      );
      $this->load->view('index', $data);
    }
    
    // Form hapus pekerjaan
    function delete($id)
    {
        $this->db->where("id_pekerjaan", $id);
        if ($this->db->delete('pekerjaan')) {
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Data pekerjaan berhasil dihapus."));
            redirect(base_url().'Pekerjaan/ListPekerjaan');
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal menghapus data pekerjaan."));
            redirect(base_url().'Pekerjaan/ListPekerjaan');
        }
    }

    //batas list pekerjaan dan riwayat pekerjaan

    public function RiwayatPekerjaan()
    {
        $this->auto_approve_old_tasks();
        
        // Rest of your existing code...
        $data['jabatan'] = $this->db->get('jabatan')->result();
        $this->load->model('ModelRiwayatPekerjaan');
        $this->load->model('ModelJabatan'); // Assuming you have this model for jabatan
        
        // Get jabatan data for the filter
        $jabatan = $this->ModelJabatan->get_jabatan_with_pekerjaan();
        
        // Get all data (you might want to filter out pending status)
        $listpekerjaan = $this->ModelRiwayatPekerjaan->get_all_except_pending();
        
        $data = array(
            'title' => 'Riwayat Pekerjaan',
            'body' => 'Pekerjaan/RiwayatPekerjaan/index',
            'jabatan' => $this->ModelJabatan->get_data()->result(),
            'listpekerjaan' => $listpekerjaan
        );

        $this->load->view('index', $data);
    }

    public function update_status()
    {
        $id_riwayatpekerjaan = $this->input->post('id_riwayatpekerjaan');
        $status = $this->input->post('status');
    
        // Validate input
        if (empty($id_riwayatpekerjaan) || empty($status)) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }
        
        // Only allow approved/rejected status updates
        if (!in_array($status, ['approve', 'reject'])) {
            echo json_encode(['status' => 'error', 'message' => 'Status tidak valid']);
            return;
        }
        
        $update = $this->ModelRiwayatPekerjaan->update_status($id_riwayatpekerjaan, $status);
    
        if ($update) {
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Status berhasil diperbarui"));
            echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal memperbarui status"));
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status']);
        }
    }
    /**
     * Check and automatically approve tasks that are complete and older than 7 days
     * This will run when the page loads
     */
    public function auto_approve_old_tasks()
    {
        // Get all complete tasks that haven't been approved/rejected yet
        $this->db->where('status', 'complete');
        $pendingTasks = $this->db->get('riwayat_pekerjaan')->result_array();
        
        $updated = 0;
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
        
        foreach ($pendingTasks as $task) {
            $taskDate = date('Y-m-d', strtotime($task['updated_at']));
            
            // Check if the task is older than 7 days
            if ($taskDate <= $sevenDaysAgo) {
                // Update the status to approve
                $this->db->where('id_riwayatpekerjaan', $task['id_riwayatpekerjaan']);
                $this->db->update('riwayat_pekerjaan', ['status' => 'approve']);
                $updated++;
            }
        }
        
        // Now auto-reject old pending tasks
        $rejectedCount = $this->ModelRiwayatPekerjaan->auto_reject_old_pending_tasks();
        
        return ['approved' => $updated, 'rejected' => $rejectedCount];
    }

    /**
     * AJAX endpoint to check for tasks that should be auto-approved
     * Returns JSON with list of task IDs that were auto-approved
     */
    public function check_auto_approve_tasks()
    {
        // Process auto approval and rejection
        $result = $this->auto_approve_old_tasks();
        
        // Get list of tasks that were just auto-approved
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
        $this->db->select('id_riwayatpekerjaan');
        $this->db->where('status', 'approve');
        $this->db->where('updated_at <=', $sevenDaysAgo);
        $this->db->where('updated_at >=', date('Y-m-d H:i:s', strtotime('-5 minutes'))); // Check for recently updated
        $autoApprovedTasks = $this->db->get('riwayat_pekerjaan')->result_array();
        
        // Get list of tasks that were just auto-rejected
        $oneDayAgo = date('Y-m-d', strtotime('-1 day'));
        $this->db->select('id_riwayatpekerjaan');
        $this->db->where('status', 'reject');
        $this->db->where('created_at <=', $oneDayAgo);
        $this->db->where('updated_at >=', date('Y-m-d H:i:s', strtotime('-5 minutes'))); // Check for recently updated
        $autoRejectedTasks = $this->db->get('riwayat_pekerjaan')->result_array();
        
        $approvedTaskIds = array_column($autoApprovedTasks, 'id_riwayatpekerjaan');
        $rejectedTaskIds = array_column($autoRejectedTasks, 'id_riwayatpekerjaan');
        
        $response = [
            'auto_approved_tasks' => $approvedTaskIds,
            'auto_rejected_tasks' => $rejectedTaskIds
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }



    // batas total point

    // In Pekerjaan controller
    public function SearchPegawai()
    {
        $pegawai_idpegawai = $this->input->post('pegawai_idpegawai');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $limit = $this->input->post('limit') ? $this->input->post('limit') : 10;
        
        // Properly format dates for database query (handle dd-mm-yyyy format)
        if (!empty($start_date)) {
            $start_date_parts = explode('-', $start_date);
            if (count($start_date_parts) === 3 && strlen($start_date_parts[0]) === 2) {
                // If in dd-mm-yyyy format
                $start_date = $start_date_parts[2] . '-' . $start_date_parts[1] . '-' . $start_date_parts[0];
            }
        }
        
        if (!empty($end_date)) {
            $end_date_parts = explode('-', $end_date);
            if (count($end_date_parts) === 3 && strlen($end_date_parts[0]) === 2) {
                // If in dd-mm-yyyy format
                $end_date = $end_date_parts[2] . '-' . $end_date_parts[1] . '-' . $end_date_parts[0];
            }
        }
        
        // Log for debugging
        error_log("Searching with params - Employee: $pegawai_idpegawai, Start: $start_date, End: $end_date");
        
        // Get filtered data based on approve status and updated_at date
        $total_poin_pegawai = $this->ModelTotalPoint->get_rekap_pekerjaan($pegawai_idpegawai, $limit, $start_date, $end_date);
        
        $data = array(
            'total_poin_pegawai' => $total_poin_pegawai
        );
        
        if ($this->input->is_ajax_request()) {
            // If Ajax request, send result as JSON
            echo json_encode([
                'status' => 200,
                'message' => 'Data berhasil ditemukan',
                'html' => $this->load->view('Pekerjaan/RekapPekerjaan/table_content', $data, true)
            ]);
        } else {
            // If not Ajax, display the recap page with filtered data
            $data['title'] = 'Rekap Pekerjaan';
            $data['body'] = 'Pekerjaan/RekapPekerjaan/index';
            $data['pegawai'] = $this->db->get('pegawai')->result();
            
            $this->load->view('index', $data);
        }
    }

    public function RefreshTotalPoin() {
        $pegawai_idpegawai = $this->input->post('pegawai_idpegawai');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $limit = $this->input->post('limit') ? $this->input->post('limit') : 10;
        
        // Properly format dates for database query (handle dd-mm-yyyy format)
        if (!empty($start_date)) {
            $start_date_parts = explode('-', $start_date);
            if (count($start_date_parts) === 3 && strlen($start_date_parts[0]) === 2) {
                // If in dd-mm-yyyy format
                $start_date = $start_date_parts[2] . '-' . $start_date_parts[1] . '-' . $start_date_parts[0];
            }
        }
        
        if (!empty($end_date)) {
            $end_date_parts = explode('-', $end_date);
            if (count($end_date_parts) === 3 && strlen($end_date_parts[0]) === 2) {
                // If in dd-mm-yyyy format
                $end_date = $end_date_parts[2] . '-' . $end_date_parts[1] . '-' . $end_date_parts[0];
            }
        }
        
        // Log for debugging
        error_log("Refreshing with params - Employee: $pegawai_idpegawai, Start: $start_date, End: $end_date");
        
        if (!empty($pegawai_idpegawai)) {
            // Calculate total points for a specific employee (only with approve status)
            $result = $this->ModelTotalPoint->get_total_poin($pegawai_idpegawai, $limit, $start_date, $end_date);        
            $data = array(
                'total_poin_pegawai' => $this->ModelTotalPoint->get_rekap_pekerjaan($pegawai_idpegawai, $limit, $start_date, $end_date)
            );
        } else {
            // Calculate total points for all employees (only with approve status)
            $result = $this->ModelTotalPoint->update_total_poin_all($start_date, $end_date);
            $data = array(
                'total_poin_pegawai' => $this->ModelTotalPoint->get_rekap_pekerjaan(null, $limit, $start_date, $end_date)
            );
        }
        
        if ($this->input->is_ajax_request()) {
            // If Ajax request, send result as JSON
            echo json_encode([
                'status' => $result['status'],
                'message' => $result['message'],
                'html' => $this->load->view('Pekerjaan/RekapPekerjaan/table_content', $data, true)
            ]);
        } else {
            // If not Ajax, display message and redirect back to recap page
            if ($result['status'] == 200) {
                $this->session->set_flashdata('notifJS', $this->core->NotifSuccess($result['message']));
            } else {
                $this->session->set_flashdata('notifJS', $this->core->NotifError($result['message']));
            }
            redirect(base_url('Pekerjaan/RekapPekerjaan'));
        }
    }

    // Update the RekapPekerjaan method
    public function RekapPekerjaan() {
        $pegawai_idpegawai = $this->input->post('pegawai_idpegawai');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $limit = $this->input->post('limit') ? $this->input->post('limit') : 10;
        
        // Convert date format if needed
        $start_date = $start_date ? date('Y-m-d', strtotime(str_replace('-', '/', $start_date))) : null;
        $end_date = $end_date ? date('Y-m-d', strtotime(str_replace('-', '/', $end_date))) : null;
        
        // Tambahkan data yang diperlukan untuk view
        $data = array(
            'title' => 'Rekap Pekerjaan',
            'body' => 'Pekerjaan/RekapPekerjaan/index',
            'total_poin_pegawai' => $this->ModelTotalPoint->get_rekap_pekerjaan($pegawai_idpegawai, $limit, $start_date, $end_date),
            'pegawai' => $this->db->get('pegawai')->result() // Untuk dropdown pilih pegawai
        );

        $this->load->view('index', $data);
    }

    //Batas Dhasboard

    // public function Dashboard()
    // {
    //     $this->load->model('ModelRiwayatPekerjaan');
    //     $data = array(
    //         'title' => 'Dashboard',
    //         'body' => 'Pekerjaan/Dashboard/index',
    //         'Dashboard' => $this->ModelRiwayatPekerjaan->get_all()
    //     );
    //     $data['jabatan'] = $this->db->get('jabatan')->result();
    //     $data['pegawai'] = $this->db->get('pegawai')->result();
        
    //     // Load view

    //     $this->load->view('index', $data);
    // }
    public function Dashboard() {
        $this->load->model('ModelRiwayatPekerjaan');
            $data = array(
                'title' => 'Dashboard',
                'body' => 'Pekerjaan/Dashboard/index',
                'Dashboard' => $this->ModelRiwayatPekerjaan->get_all()
            );
        // Get jabatan directly from pekerjaan table
        $data['jabatan'] = $this->ModelRiwayatPekerjaan->get_jabatan_from_pekerjaan();
        
        // Get pegawai directly from riwayat_pekerjaan table
        $data['pegawai'] = $this->ModelRiwayatPekerjaan->get_pegawai_from_riwayat();
        
      
        $this->load->view('index', $data);
      
    }
    
    public function get_dashboard_data() {
        // Get filter parameters
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $jabatan = $this->input->post('jabatan');
        $pegawai = $this->input->post('pegawai');
        
        // Get counts for different status
        $total = $this->ModelRiwayatPekerjaan->count_total($start_date, $end_date, $jabatan, $pegawai);
        $pending = $this->ModelRiwayatPekerjaan->count_by_status('Pending', $start_date, $end_date, $jabatan, $pegawai);
        $complete = $this->ModelRiwayatPekerjaan->count_by_status('Complete', $start_date, $end_date, $jabatan, $pegawai);
        $reject = $this->ModelRiwayatPekerjaan->count_by_status('Reject', $start_date, $end_date, $jabatan, $pegawai);
        $approve = $this->ModelRiwayatPekerjaan->count_by_status('Approve', $start_date, $end_date, $jabatan, $pegawai);
        
        // Calculate percentages for chart
        $percentages = array();
        if ($total > 0) {
            $percentages = array(
                array('status' => 'Pending', 'value' => round(($pending / $total) * 100, 2)),
                array('status' => 'Complete', 'value' => round(($complete / $total) * 100, 2)),
                array('status' => 'Reject', 'value' => round(($reject / $total) * 100, 2)),
                array('status' => 'Approve', 'value' => round(($approve / $total) * 100, 2))
            );
        }
        
        // Calculate approve percentage
        $approve_percentage = ($total > 0) ? round(($approve / $total) * 100, 2) : 0;
        
        // Get today's tasks
        $today_tasks = $this->ModelRiwayatPekerjaan->get_riwayat_pekerjaan($start_date, $end_date, $jabatan, $pegawai);
        // Prepare response
        $response = array(
            'total' => $total,
            'pending' => $pending,
            'complete' => $complete,
            'reject' => $reject,
            'approve' => $approve,
            'approve_percentage' => $approve_percentage,
            'percentages' => $percentages,
            'today_tasks' => $today_tasks
        );
        
        echo json_encode($response);
    }
}
