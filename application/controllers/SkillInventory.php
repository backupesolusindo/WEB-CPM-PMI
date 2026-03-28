<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SkillInventory extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelSkill');
        $this->load->model('ModelPegawai');
    }

    // =============================================
    // MASTER SKILL (DM Skill)
    // =============================================

    // List semua master skill
    public function index()
    {
        $data = array(
            'title' => 'Skill Inventory',
            'body'  => 'SkillInventory/index',
            'skill' => $this->ModelSkill->get_all_skill()->result()
        );
        $this->load->view('index', $data);
    }

    // Form tambah master skill
    public function tambah()
    {
        $data = array(
            'title' => 'Tambah Skill',
            'body'  => 'SkillInventory/tambah'
        );
        $this->load->view('index', $data);
    }

    // Simpan master skill
    public function simpan()
    {
        $data = array(
            'nama_skill'  => $this->input->post('nama_skill'),
            'kategori'    => $this->input->post('kategori'),
            'deskripsi'   => $this->input->post('deskripsi'),
            'created_at'  => date('Y-m-d H:i:s')
        );

        $this->ModelSkill->insert_skill($data);

        $this->session->set_flashdata('notifJS', array(
            'message' => 'Master Skill berhasil ditambahkan',
            'type'    => 'success'
        ));

        redirect('SkillInventory');
    }

    // Form edit master skill
    public function edit($id_skill)
    {
        $data = array(
            'title' => 'Edit Skill',
            'body'  => 'SkillInventory/edit',
            'skill' => $this->ModelSkill->get_skill_by_id($id_skill)->row()
        );
        $this->load->view('index', $data);
    }

    // Update master skill
    public function update()
    {
        $id_skill = $this->input->post('id_skill');

        $data = array(
            'nama_skill' => $this->input->post('nama_skill'),
            'kategori'   => $this->input->post('kategori'),
            'deskripsi'  => $this->input->post('deskripsi')
        );

        $this->ModelSkill->update_skill($id_skill, $data);

        $this->session->set_flashdata('notifJS', array(
            'message' => 'Master Skill berhasil diupdate',
            'type'    => 'success'
        ));

        redirect('SkillInventory');
    }

    // Hapus master skill
    public function hapus($id_skill)
    {
        $this->ModelSkill->delete_skill($id_skill);

        $this->session->set_flashdata('notifJS', array(
            'message' => 'Master Skill berhasil dihapus',
            'type'    => 'success'
        ));

        redirect('SkillInventory');
    }

    // =============================================
    // SKILL KARYAWAN
    // =============================================

    // List skill semua karyawan
    public function skill_karyawan()
    {
        $data = array(
            'title'    => 'Data Skill Karyawan',
            'body'     => 'SkillInventory/skill_karyawan',
            'data'     => $this->ModelSkill->get_all_skill_karyawan()->result(),
            'pegawai'  => $this->ModelPegawai->get_list()->result(),
            'skill'    => $this->ModelSkill->get_all_skill()->result()
        );
        $this->load->view('index', $data);
    }

    // Simpan skill karyawan oleh Admin (pilih karyawan manual)
    public function simpan_skill_karyawan()
    {
        $data = array(
            'id_karyawan' => $this->input->post('id_karyawan'),
            'id_skill'    => $this->input->post('id_skill'),
            'tahun_mulai' => $this->input->post('tahun_mulai'),
            'sertifikasi' => $this->input->post('sertifikasi'),
            'created_at'  => date('Y-m-d H:i:s')
        );

        $this->ModelSkill->insert_skill_karyawan($data);

        $this->session->set_flashdata('notifJS', array(
            'message' => 'Skill Karyawan berhasil ditambahkan',
            'type'    => 'success'
        ));

        redirect('SkillInventory/skill_karyawan');
    }

    // Halaman skill milik karyawan sendiri (untuk karyawan login)
    public function skill_saya()
    {
        $nik = $this->session->userdata('nik');

        $data = array(
            'title' => 'Skill Saya',
            'body'  => 'SkillInventory/skill_saya',
            'data'  => $this->ModelSkill->get_skill_karyawan_by_nik($nik)->result(),
            'skill' => $this->ModelSkill->get_all_skill()->result()
        );
        $this->load->view('index', $data);
    }

    // Simpan skill oleh karyawan sendiri (NIK otomatis dari session)
    public function simpan_skill_saya()
    {
        $data = array(
            'id_karyawan' => $this->session->userdata('nik'),
            'id_skill'    => $this->input->post('id_skill'),
            'tahun_mulai' => $this->input->post('tahun_mulai'),
            'sertifikasi' => $this->input->post('sertifikasi'),
            'created_at'  => date('Y-m-d H:i:s')
        );

        $this->ModelSkill->insert_skill_karyawan($data);

        $this->session->set_flashdata('notifJS', array(
            'message' => 'Skill berhasil ditambahkan',
            'type'    => 'success'
        ));

        redirect('SkillInventory/skill_saya');
    }

    // Hapus skill karyawan
    public function hapus_skill_karyawan($id)
    {
        $this->ModelSkill->delete_skill_karyawan($id);

        $this->session->set_flashdata('notifJS', array(
            'message' => 'Data skill berhasil dihapus',
            'type'    => 'success'
        ));

        redirect('SkillInventory/skill_karyawan');
    }

    // Hapus skill saya (karyawan)
    public function hapus_skill_saya($id)
    {
        $this->ModelSkill->delete_skill_karyawan($id);

        $this->session->set_flashdata('notifJS', array(
            'message' => 'Skill berhasil dihapus',
            'type'    => 'success'
        ));

        redirect('SkillInventory/skill_saya');
    }

}