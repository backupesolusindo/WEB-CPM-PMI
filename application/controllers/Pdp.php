<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdp extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelPdp');
        $this->load->model('ModelPegawai');
    }

    // Menampilkan semua data PDP
    public function index()
    {
        $data = array(
            'title' => 'Personal Development Plan',
            'body'  => 'Pdp/index',
            'pdp'   => $this->ModelPdp->get_all()->result()
        );
        $this->load->view('index', $data);
    }

    // Halaman form tambah PDP
    public function tambah()
    {
        $data = array(
            'title' => 'Tambah PDP',
            'body'  => 'Pdp/tambah'
        );
        $this->load->view('index', $data);
    }

    // Simpan data PDP ke database
    public function simpan()
    {
        $bukti_dokumen = null;
        if (!empty($_FILES['bukti_dokumen']['name'])) {
            $config['upload_path']   = './uploads/pdp/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size']      = 5120;
            $config['file_name']     = 'pdp_' . time();

            if (!is_dir('./uploads/pdp/')) {
                mkdir('./uploads/pdp/', 0777, true);
            }

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('bukti_dokumen')) {
                $upload_data   = $this->upload->data();
                $bukti_dokumen = $upload_data['file_name'];
            }
        }

        $data = array(
            'id_karyawan'     => $this->session->userdata('nik'),
            'judul_pelatihan' => $this->input->post('judul_pelatihan'),
            'jenis_kegiatan'  => $this->input->post('jenis_kegiatan'),
            'deskripsi'       => $this->input->post('deskripsi'),
            'tanggal_mulai'   => $this->input->post('tanggal_mulai'),
            'tanggal_selesai' => $this->input->post('tanggal_selesai'),
            'bukti_dokumen'   => $bukti_dokumen,
            'keterkaitan_kpi' => $this->input->post('keterkaitan_kpi'),
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s')
        );

        $this->ModelPdp->insert($data);

        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Data PDP berhasil ditambahkan"));
        redirect('pdp');
    }

    // Halaman edit PDP
    public function edit($id_pdp)
    {
        $data = array(
            'title' => 'Edit PDP',
            'body'  => 'Pdp/edit',
            'pdp'   => $this->ModelPdp->get_by_id($id_pdp)->row()
        );
        $this->load->view('index', $data);
    }

    // Update data PDP
    public function update()
    {
        $id_pdp      = $this->input->post('id_pdp');
        $pdp_lama    = $this->ModelPdp->get_by_id($id_pdp)->row();
        $bukti_dokumen = $pdp_lama->bukti_dokumen;

        if (!empty($_FILES['bukti_dokumen']['name'])) {
            $config['upload_path']   = './uploads/pdp/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size']      = 5120;
            $config['file_name']     = 'pdp_' . time();

            if (!is_dir('./uploads/pdp/')) {
                mkdir('./uploads/pdp/', 0777, true);
            }

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('bukti_dokumen')) {
                $upload_data   = $this->upload->data();
                $bukti_dokumen = $upload_data['file_name'];
            }
        }

        $data = array(
            'judul_pelatihan' => $this->input->post('judul_pelatihan'),
            'jenis_kegiatan'  => $this->input->post('jenis_kegiatan'),
            'deskripsi'       => $this->input->post('deskripsi'),
            'tanggal_mulai'   => $this->input->post('tanggal_mulai'),
            'tanggal_selesai' => $this->input->post('tanggal_selesai'),
            'bukti_dokumen'   => $bukti_dokumen,
            'keterkaitan_kpi' => $this->input->post('keterkaitan_kpi'),
            'status'          => $this->input->post('status')
        );

        $this->ModelPdp->update($id_pdp, $data);

        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Data PDP berhasil diupdate"));
        redirect('pdp');
    }

    // Hapus data PDP
    public function hapus($id_pdp)
    {
        $this->ModelPdp->delete($id_pdp);

        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Data PDP berhasil dihapus"));
        redirect('pdp');
    }

    // Halaman kelola peserta PDP
    public function peserta($id_pdp)
    {
        $data = array(
            'title'   => 'PESERTA PDP',
            'body'    => 'Pdp/peserta',
            'pdp'     => $this->ModelPdp->get_by_id($id_pdp)->row(),
            'pegawai' => $this->ModelPegawai->get_list()->result(),
            'peserta' => $this->ModelPdp->get_peserta($id_pdp)->result()
        );
        $this->load->view('index', $data);
    }

    // Simpan peserta PDP
    public function insert_peserta()
    {
        $id_pdp   = $this->input->post('id_pdp');
        $list_uuid = $this->input->post('uuid');

        if (empty($list_uuid)) {
            $list_uuid = array();
        }

        if ($this->ModelPdp->insert_peserta($id_pdp, $list_uuid)) {
            $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Peserta PDP berhasil disimpan"));
        } else {
            $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal menyimpan peserta"));
        }

        redirect('pdp');
    }

}