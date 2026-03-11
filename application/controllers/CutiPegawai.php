<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CutiPegawai extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("ModelPerizinan");
        $this->load->model("ModelPegawai");
        $this->load->model("ModelJenisPerizinan");
    }

    // ==============================
    // HALAMAN LIST
    // ==============================
    public function index()
    {
        $data = array(
            'title' => 'IZIN PESERTA PKL',
            'body'  => 'CutiPegawai/list',
            'data'  => $this->ModelPerizinan->get_list()->result()
        );
        $this->load->view('index', $data);
    }

    // ==============================
    // FILTER TANGGAL + STATUS (AJAX)
    // ==============================
    public function tabelIzinFiltered()
    {
        $start  = $this->input->post('start');
        $end    = $this->input->post('end');
        $status = $this->input->post('status');

        // Convert tanggal ke format Y-m-d untuk query
        if (!empty($start)) $start = date("Y-m-d", strtotime($start));
        if (!empty($end))   $end   = date("Y-m-d", strtotime($end));

        $data = $this->ModelPerizinan->get_filtered($start, $end, $status)->result();

        // ===============================
        // BANGUN HTML TABEL DENGAN KOLOM "PILIH"
        // ===============================
        $html = '<table id="myTable" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Akhir</th>
                            <th>Jenis Izin</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                            <th>File</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>';

        $no = 1;
        foreach ($data as $d) {

            // Status badge
            $statusBadge =
                ($d->status == 1) ? '<span class="badge badge-success p-2">Disetujui</span>' :
                (($d->status == 2) ? '<span class="badge badge-danger p-2">Ditolak</span>' :
                                   '<span class="badge badge-warning p-2">Menunggu</span>');

            // File download button
            $fileBtn = (empty($d->file) || $d->file == "document/izin/")
                        ? '<span class="badge badge-secondary">Tidak Ada File</span>'
                        : '<a href="'.base_url($d->file).'" target="_blank" class="btn btn-info btn-sm">
                                <i class="fas fa-download"></i> Download
                           </a>';

            // Aksi button
            $aksi = '';
            if ($d->status != 1) $aksi .= '<button class="btn btn-success btn-sm" onclick="updateStatus('.$d->idizin.',1)">Setujui</button> ';
            if ($d->status != 2) $aksi .= '<button class="btn btn-danger btn-sm" onclick="updateStatus('.$d->idizin.',2)">Tolak</button> ';
            if ($d->status == 1 || $d->status == 2) $aksi .= '<button class="btn btn-warning btn-sm" onclick="updateStatus('.$d->idizin.',0)">Reset</button>';

            // Tombol Pilih (custom checkbox) — SESUAI DENGAN list.php
            $pilihBtn = '<button 
                class="pilih-btn" 
                data-id="'.$d->idizin.'"
                data-status="'.$d->status.'"
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
            </button>';

            // Bangun row
            $html .= '<tr>
                        <td>'.$pilihBtn.'</td>
                        <td>'.$no++.'</td>
                        <td>'.htmlspecialchars($d->NIP).'</td>
                        <td>'.htmlspecialchars($d->nama_pegawai).'</td>
                        <td>'.date("d-m-Y", strtotime($d->tanggal_mulai)).'</td>
                        <td>'.date("d-m-Y", strtotime($d->tanggal_akhir)).'</td>
                        <td>'.htmlspecialchars($d->jenis_izin).'</td>
                        <td>'.htmlspecialchars($d->alasan).'</td>
                        <td>'.$statusBadge.'</td>
                        <td>'.$aksi.'</td>
                        <td>'.$fileBtn.'</td>
                        <td><button class="btn btn-danger btn-sm" onclick="hapus('.$d->idizin.')"><i class="fas fa-trash"></i></button></td>
                      </tr>';
        }

        $html .= '</tbody></table>';

        // Kembalikan HTML langsung ke AJAX
        echo $html;
    }

    // ==============================
    // FORM INPUT
    // ==============================
    public function input()
    {
        $data = array(
            'title'   => 'FORM INPUT IZIN PESERTA PKL',
            'body'    => 'CutiPegawai/input',
            'pegawai' => $this->ModelPegawai->get_list()->result(),
            'jenis'   => $this->ModelJenisPerizinan->get_jenisperizinan()->result(),
        );

        $this->load->view('index', $data);
    }

    // ==============================
    // INSERT DATA
    // ==============================
    public function insert()
    {
        $data = array(
            'alasan'    => $this->input->post("alasan"),
            'jenis_perizinan_idjenis_perizinan' => $this->input->post("jenis_perizinan"),
            'status'    => "0",
            'tanggal_mulai'  => date("Y-m-d", strtotime($this->input->post("tanggal_mulai"))),
            'tanggal_akhir'  => date("Y-m-d", strtotime($this->input->post("tanggal_akhir"))),
            'pegawai_uuid'   => $this->input->post("pegawai_uuid"),
            'file'           => $this->upload_file("file")["link"],
        );

        if ($this->db->insert('izin', $data)) {
            $this->session->set_flashdata('notifJS', array('message' => 'Data berhasil ditambahkan', 'type' => 'success'));
            redirect(base_url().'CutiPegawai');
        } else {
            $this->session->set_flashdata('notifJS', array('message' => 'Gagal menambah data', 'type' => 'error'));
            redirect(base_url().'CutiPegawai');
        }
    }

    // ==============================
    // UPLOAD FILE
    // ==============================
    public function upload_file($file)
    {
        $msg = "";
        $nama = "";
        $config['upload_path'] = './document/izin';
        $config['allowed_types'] = '*';
        $config['max_size'] = 1000;

        if (isset($_FILES[$file]["name"]) && !empty($_FILES[$file]["name"])) {
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($file)) {
                $msg = $this->upload->display_errors();
            } else {
                $upload = $this->upload->data();
                $nama = $upload['file_name'];
                $msg  = "Berhasil Upload ".$nama;
            }
        } else {
            $msg = "File Kosong";
            $nama = "";
        }

        return array(
            'pesan' => $msg,
            'nama'  => $nama,
            'link'  => "document/izin/".$nama
        );
    }

    // ==============================
    // UPDATE STATUS
    // ==============================
    public function updateStatus()
    {
        header('Content-Type: application/json');

        $id_izin = $this->input->post('id_izin');
        $status  = $this->input->post('status');

        if (empty($id_izin)) {
            echo json_encode(array('success' => false, 'message' => 'ID Izin tidak valid'));
            return;
        }

        $data = array('status' => $status);

        if ($this->db->field_exists('tanggal_update', 'izin')) {
            $data['tanggal_update'] = date('Y-m-d H:i:s');
        }

        try {
            $result = $this->ModelPerizinan->updateStatusIzin($id_izin, $data);

            if ($result) {
                echo json_encode(array('success' => true, 'message' => 'Status berhasil diupdate'));
            } else {
                $db_error = $this->db->error();
                echo json_encode(array('success' => false, 'message' => 'Gagal update status: '.$db_error['message']));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => 'Error: '.$e->getMessage()));
        }
    }

    // ==============================
    // HAPUS DATA
    // ==============================
    public function hapus()
    {
        header('Content-Type: application/json');

        $id_izin = $this->input->post('id_izin');

        if (empty($id_izin)) {
            echo json_encode(array('success' => false, 'message' => 'ID Izin tidak valid'));
            return;
        }

        try {
            $result = $this->ModelPerizinan->hapusIzin($id_izin);

            if ($result) {
                echo json_encode(array('success' => true, 'message' => 'Data berhasil dihapus'));
            } else {
                $db_error = $this->db->error();
                echo json_encode(array('success' => false, 'message' => 'Gagal menghapus data: '.$db_error['message']));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => 'Error: '.$e->getMessage()));
        }
    }

    // ==============================
    // AKSI MASSAL (PERBAIKAN BUG)
    // ==============================
    public function massAction()
    {
        header('Content-Type: application/json');

        $action = $this->input->post('action');
        $ids = $this->input->post('ids');

        if (!$ids || !is_array($ids) || empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada data yang dipilih.']);
            return;
        }

        $status = null;
        switch ($action) {
            case 'approve': $status = 1; break;
            case 'reject': $status = 2; break;
            case 'reset': $status = 0; break;
            case 'delete':
                // Hapus langsung
                $this->db->where_in('idizin', $ids);
                $result = $this->db->delete('izin');
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Berhasil menghapus ' . count($ids) . ' data.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal menghapus data.']);
                }
                return;
            default:
                echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
                return;
        }

        if ($status !== null) {
            $this->db->where_in('idizin', $ids);
            $data = ['status' => $status];
            if ($this->db->field_exists('tanggal_update', 'izin')) {
                $data['tanggal_update'] = date('Y-m-d H:i:s');
            }
            $result = $this->db->update('izin', $data);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Berhasil memperbarui ' . count($ids) . ' data.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data.']);
            }
        }
    }
}