<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TargetPresensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelTargetPresensi');
        $this->load->model('ModelPegawai');
    }

    /**
     * Halaman daftar semua pegawai beserta target presensi per tahun.
     * URL: TargetPresensi/index[/tahun]
     */
    public function index()
    {
        $tahun = $this->uri->segment(3) ?: date('Y');
        $data  = [
            'title'   => 'Target Presensi',
            'body'    => 'TargetPresensi/list',
            'tahun'   => $tahun,
            'pegawai' => $this->ModelTargetPresensi->get_all_pegawai_dengan_target($tahun)->result(),
        ];
        $this->load->view('index', $data);
    }

    /**
     * Halaman form input/edit target presensi satu pegawai.
     * URL: TargetPresensi/form/{pegawai_uuid}[/{tahun}]
     */
    public function form()
    {
        $uuid  = $this->uri->segment(3);
        $tahun = $this->uri->segment(4) ?: date('Y');

        if (empty($uuid)) {
            redirect('TargetPresensi');
        }

        $pegawai = $this->ModelPegawai->edit($uuid)->row_array();
        if (empty($pegawai)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Data pegawai tidak ditemukan."));
            redirect('TargetPresensi');
        }

        $target = $this->ModelTargetPresensi->get_by_pegawai_tahun($uuid, $tahun);

        $data = [
            'title'   => 'Target Presensi',
            'body'    => 'TargetPresensi/form',
            'pegawai' => $pegawai,
            'tahun'   => $tahun,
            'target'  => $target,
        ];
        $this->load->view('index', $data);
    }

    /**
     * Proses simpan target presensi (insert/update).
     * Method: POST dari form
     */
    public function save()
    {
        $uuid  = $this->input->post('pegawai_uuid');
        $tahun = $this->input->post('tahun');

        if (empty($uuid) || empty($tahun)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Data tidak valid."));
            redirect('TargetPresensi');
        }

        $bulan_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $val = $this->input->post('bulan_' . $i);
            // Pastikan nilai adalah angka positif atau 0
            $bulan_data['bulan_' . $i] = (is_numeric($val) && $val >= 0) ? (int)$val : 0;
        }

        if ($this->ModelTargetPresensi->save($uuid, $tahun, $bulan_data)) {
            $pegawai = $this->ModelPegawai->edit($uuid)->row_array();
            $nama    = $pegawai['nama_pegawai'] ?? '';
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Target presensi <b>{$nama}</b> tahun {$tahun} berhasil disimpan."));
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal menyimpan target presensi."));
        }

        redirect('TargetPresensi/index/' . $tahun);
    }

    /**
     * Hapus target presensi berdasarkan id_target.
     * URL: TargetPresensi/hapus/{id_target}/{tahun}
     */
    public function hapus()
    {
        $id_target = $this->uri->segment(3);
        $tahun     = $this->uri->segment(4) ?: date('Y');

        if ($this->ModelTargetPresensi->delete($id_target)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Target presensi berhasil dihapus."));
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal menghapus target presensi."));
        }

        redirect('TargetPresensi/index/' . $tahun);
    }

    /**
     * AJAX: Ambil data tabel target presensi (POST, return HTML partial).
     */
    public function tabel()
    {
        $tahun = $this->input->post('tahun') ?: date('Y');
        $data  = [
            'tahun'   => $tahun,
            'pegawai' => $this->ModelTargetPresensi->get_all_pegawai_dengan_target($tahun)->result(),
        ];
        $this->load->view('TargetPresensi/tabel', $data);
    }

    /**
     * Halaman input massal — semua pegawai dalam satu form tabel.
     * URL: TargetPresensi/bulk[/{tahun}]
     */
    public function bulk()
    {
        $tahun = $this->uri->segment(3) ?: date('Y');
        $data  = [
            'title'   => 'Target Presensi - Input Massal',
            'body'    => 'TargetPresensi/bulk',
            'tahun'   => $tahun,
            'pegawai' => $this->ModelTargetPresensi->get_all_pegawai_dengan_target($tahun)->result(),
        ];
        $this->load->view('index', $data);
    }

    /**
     * Proses simpan bulk target presensi semua pegawai.
     * Method: POST dari form bulk
     */
    public function save_bulk()
    {
        $tahun    = $this->input->post('tahun');
        $pegawais = $this->input->post('pegawai'); // array[uuid][bulan_N]

        if (empty($tahun) || empty($pegawais) || !is_array($pegawais)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Data tidak valid."));
            redirect('TargetPresensi');
        }

        $berhasil = 0;
        $gagal    = 0;

        foreach ($pegawais as $uuid => $bulan_raw) {
            $bulan_data = [];
            for ($i = 1; $i <= 12; $i++) {
                $val = $bulan_raw['bulan_' . $i] ?? 0;
                $bulan_data['bulan_' . $i] = (is_numeric($val) && $val >= 0) ? (int)$val : 0;
            }

            if ($this->ModelTargetPresensi->save($uuid, $tahun, $bulan_data)) {
                $berhasil++;
            } else {
                $gagal++;
            }
        }

        if ($berhasil > 0) {
            $msg = "Berhasil menyimpan target presensi <b>{$berhasil} pegawai</b> tahun {$tahun}.";
            if ($gagal > 0) {
                $msg .= " ({$gagal} gagal)";
            }
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess($msg));
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal menyimpan target presensi."));
        }

        redirect('TargetPresensi/index/' . $tahun);
    }
}
