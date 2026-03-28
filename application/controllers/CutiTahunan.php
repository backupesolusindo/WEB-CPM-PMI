<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CutiTahunan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelCutiTahunan');
        $this->load->model('ModelPegawai');
    }

    // Halaman list data cuti tahunan
    public function index()
    {
        // Kalau tidak ada GET tahun sama sekali -> default tahun ini
        // Kalau ada GET tahun tapi kosong (pilih semua) -> null = tampil semua
        if ($this->input->get('tahun') !== false && $this->input->get('tahun') !== null) {
            $tahun_filter = $this->input->get('tahun');
        } else {
            $tahun_filter = date('Y');
        }

        $data = array(
            'title'       => 'Cuti Tahunan',
            'body'        => 'CutiTahunan/index',
            'cuti'        => $this->ModelCutiTahunan->get_all($tahun_filter ?: null)->result(),
            'tahun_list'  => $this->ModelCutiTahunan->get_tahun_list()->result(),
            'tahun_aktif' => $tahun_filter
        );
        $this->load->view('index', $data);
    }

    // Halaman tambah - pilih pegawai + input cuti
    public function tambah()
    {
        $data = array(
            'title'   => 'Tambah Cuti Tahunan',
            'body'    => 'CutiTahunan/tambah',
            'pegawai' => $this->ModelPegawai->get_list()->result()
        );
        $this->load->view('index', $data);
    }

    // Simpan semua data cuti
    public function simpan()
    {
        $list_uuid  = $this->input->post('uuid');
        $list_cuti  = $this->input->post('total_cuti');
        $tahun_cuti = $this->input->post('tahun_cuti');

        if (empty($list_uuid)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Belum ada pegawai yang dipilih"));
            redirect('CutiTahunan/tambah');
        }

        $data_batch = array();
        foreach ($list_uuid as $i => $uuid) {
            $total = isset($list_cuti[$i]) ? $list_cuti[$i] : 0;
            if ($uuid && $total > 0) {
                $data_batch[] = array(
                    'pegawai_uuid' => $uuid,
                    'total_cuti'   => $total,
                    'tahun_cuti'   => $tahun_cuti,
                    'created_at'   => date('Y-m-d H:i:s')
                );
            }
        }

        if (empty($data_batch)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Total cuti tidak boleh kosong atau 0"));
            redirect('CutiTahunan/tambah');
        }

        if ($this->ModelCutiTahunan->insert_batch($data_batch)) {
            $jumlah = count($data_batch);
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Berhasil menyimpan cuti tahunan {$jumlah} pegawai tahun {$tahun_cuti}"));
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal menyimpan data"));
        }

        redirect('CutiTahunan?tahun=' . $tahun_cuti);
    }

    // Halaman edit
    public function edit($id)
    {
        $data = array(
            'title' => 'Edit Cuti Tahunan',
            'body'  => 'CutiTahunan/edit',
            'cuti'  => $this->ModelCutiTahunan->get_by_id($id)->row()
        );
        $this->load->view('index', $data);
    }

    // Proses update
    public function update()
    {
        $id   = $this->input->post('idcuti_tahunan');
        $data = array(
            'total_cuti' => $this->input->post('total_cuti'),
            'tahun_cuti' => $this->input->post('tahun_cuti')
        );

        if ($this->ModelCutiTahunan->update($id, $data)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Data cuti tahunan berhasil diupdate"));
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal mengupdate data"));
        }

        redirect('CutiTahunan');
    }

    // Hapus satu data
    public function hapus($id)
    {
        $this->ModelCutiTahunan->delete($id);
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Data berhasil dihapus"));
        redirect('CutiTahunan');
    }

}