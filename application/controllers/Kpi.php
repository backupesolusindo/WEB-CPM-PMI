<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kpi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kpi_model');
    }

    /**
     * Dashboard KPI
     */
    public function index()
    {
        $data['title'] = 'Dashboard KPI';

        $data['user'] = $this->session->userdata();

        // Default periode: bulan dan tahun sekarang
        $data['bulan_selected'] = date('n');
        $data['tahun_selected'] = date('Y');

        // Ambil daftar unit untuk filter
        $data['unit_list'] = $this->db->get('unit')->result_array();
        $data['body'] = 'kpi/dashboard';
        // die(json_encode($data));
        $this->load->view('index', $data);
    }

    /**
     * Halaman Pengaturan Bobot
     */
    public function pengaturan_bobot()
    {
        $data['title'] = 'Pengaturan Bobot KPI';
        $data['user'] = $this->session->userdata();

        $data['bobot_list'] = $this->Kpi_model->get_all_bobot();
        $data['total_bobot'] = $this->Kpi_model->validate_total_bobot();
        $data['body'] = 'kpi/pengaturan_bobot';

        $this->load->view('index', $data);
    }

    /**
     * Proses update bobot
     */
    public function update_bobot()
    {
        // Cek role admin
        if ($this->session->userdata('role') != 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses');
            redirect('kpi');
        }

        $bobot_data = $this->input->post('bobot');

        if (!$bobot_data) {
            $this->session->set_flashdata('error', 'Data bobot tidak valid');
            redirect('kpi/pengaturan_bobot');
        }

        // Validasi total bobot
        $total = 0;
        foreach ($bobot_data as $id => $bobot) {
            $total += floatval($bobot);
        }

        if ($total != 100) {
            $this->session->set_flashdata('error', "Total bobot harus 100%. Saat ini: $total%");
            redirect('kpi/pengaturan_bobot');
        }

        try {
            $this->db->trans_start();

            foreach ($bobot_data as $id => $bobot) {
                $data = [
                    'bobot' => $bobot,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $this->Kpi_model->update_bobot($id, $data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan data');
            }

            $this->session->set_flashdata('success', 'Bobot KPI berhasil diupdate');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal update bobot: ' . $e->getMessage());
        }

        redirect('kpi/pengaturan_bobot');
    }

    /**
     * AJAX: Ambil list pegawai untuk proses hitung KPI sequential
     */
    public function ajax_get_pegawai_list()
    {
        $bulan    = $this->input->post('bulan');
        $tahun    = $this->input->post('tahun');
        $unit_id  = $this->input->post('unit_id');

        if (!$bulan || !$tahun) {
            echo json_encode(['status' => false, 'message' => 'Bulan dan tahun wajib diisi']);
            return;
        }

        $this->db->select('uuid, nip, nama_pegawai');
        if ($unit_id) {
            $this->db->where('unit', $unit_id);
        }
        $pegawai_list = $this->db->get('pegawai')->result_array();

        echo json_encode([
            'status' => true,
            'bulan'  => $bulan,
            'tahun'  => $tahun,
            'total'  => count($pegawai_list),
            'data'   => $pegawai_list
        ]);
    }

    /**
     * AJAX: Hitung KPI satu pegawai (dipanggil sequential dari frontend)
     */
    public function ajax_hitung_single()
    {
        $pegawai_id = $this->input->post('pegawai_id');
        $bulan      = $this->input->post('bulan');
        $tahun      = $this->input->post('tahun');

        if (!$pegawai_id || !$bulan || !$tahun) {
            echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
            return;
        }

        try {
            $result = $this->Kpi_model->hitung_kpi($pegawai_id, $bulan, $tahun);
            $this->Kpi_model->save_kpi_calculation($result);

            echo json_encode([
                'status'      => true,
                'pegawai_id'  => $pegawai_id,
                'message'     => 'Berhasil'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status'      => false,
                'pegawai_id'  => $pegawai_id,
                'message'     => $e->getMessage()
            ]);
        }
    }

    /**
     * Daftar Pegawai dengan KPI
     */
    public function daftar_pegawai()
    {
        $data['title'] = 'Daftar Pegawai & KPI';
        $data['user'] = $this->session->userdata();
        $data['bulan_selected'] = $this->input->get('bulan') ?: date('n');
        $data['tahun_selected'] = $this->input->get('tahun') ?: date('Y');
        $data['unit_id_selected'] = $this->input->get('unit_id') ?: '';
        $data['unit_list'] = $this->db->get('unit')->result_array();
        $data['pegawai_kpi'] = $this->Kpi_model->get_daftar_pegawai_kpi(
            $data['bulan_selected'],
            $data['tahun_selected'],
            $data['unit_id_selected']
        );
        $data['body'] = 'kpi/daftar_pegawai';
        $this->load->view('index', $data);
    }

    /**
     * Detail KPI Pegawai
     */
    public function detail($pegawai_id)
    {
        $data['title'] = 'Detail KPI Pegawai';
        $data['user'] = $this->session->userdata();

        // Ambil data pegawai
        $data['pegawai'] = $this->db->get_where('pegawai', ['uuid' => $pegawai_id])->row_array();

        if (!$data['pegawai']) {
            $this->session->set_flashdata('error', 'Pegawai tidak ditemukan');
            redirect('kpi');
        }

        // Ambil history KPI
        $data['kpi_history'] = $this->Kpi_model->get_kpi_pegawai($pegawai_id);

        $data['body'] = 'kpi/detail';
        $this->load->view('index', $data);
    }

    /**
     * Export KPI ke Excel
     */
    public function export()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        $unit_id = $this->input->get('unit_id');

        if (!$bulan || !$tahun) {
            $this->session->set_flashdata('error', 'Bulan dan tahun wajib diisi');
            redirect('kpi');
        }

        $data = $this->Kpi_model->get_kpi_by_periode($bulan, $tahun, $unit_id);

        // Set header untuk download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="KPI_' . $bulan . '_' . $tahun . '.xls"');
        header('Cache-Control: max-age=0');

        echo '<table border="1">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>NIP</th>';
        echo '<th>Nama</th>';
        echo '<th>Departemen</th>';
        echo '<th>Presensi</th>';
        echo '<th>Kegiatan</th>';
        echo '<th>Cuti</th>';
        echo '<th>Pekerjaan</th>';
        echo '<th>Dinas Luar</th>';
        echo '<th>KPI Final</th>';
        echo '<th>Kategori</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $no = 1;
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $row['nip'] . '</td>';
            echo '<td>' . $row['nama_pegawai'] . '</td>';
            echo '<td>' . $row['departemen'] . '</td>';
            echo '<td>' . $row['nilai_presensi'] . '</td>';
            echo '<td>' . $row['nilai_kegiatan'] . '</td>';
            echo '<td>' . $row['nilai_cuti'] . '</td>';
            echo '<td>' . $row['nilai_pekerjaan'] . '</td>';
            echo '<td>' . $row['nilai_dinas_luar'] . '</td>';
            echo '<td>' . $row['nilai_kpi_final'] . '</td>';
            echo '<td>' . $row['kategori_kinerja'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    /**
     * AJAX: Get data KPI untuk tabel
     */
    public function ajax_get_data()
    {
        $bulan   = $this->input->get('bulan');
        $tahun   = $this->input->get('tahun');
        $unit_id = $this->input->get('unit_id');

        // Pastikan tidak ada output sebelumnya
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        $data = $this->Kpi_model->get_kpi_by_periode($bulan, $tahun, $unit_id);

        echo json_encode([
            'status' => true,
            'bulan'  => $bulan,
            'tahun'  => $tahun,
            'count'  => count($data),
            'data'   => $data
        ]);
    }

    /**
     * AJAX: Get statistik KPI
     */
    public function ajax_get_statistik()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        $statistik = $this->Kpi_model->get_statistik_kpi($bulan, $tahun);

        echo json_encode([
            'status' => true,
            'data'   => $statistik ?: (object)[]
        ]);
    }

    /**
     * AJAX: Get ranking KPI
     */
    public function ajax_get_ranking()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        $limit = $this->input->get('limit') ?? 10;

        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        $ranking = $this->Kpi_model->get_ranking_kpi($bulan, $tahun, $limit);

        echo json_encode([
            'status' => true,
            'data'   => $ranking
        ]);
    }
}
