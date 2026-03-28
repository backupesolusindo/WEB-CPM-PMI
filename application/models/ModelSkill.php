<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelSkill extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // =============================================
    // MASTER SKILL (DM Skill)
    // =============================================

    function get_all_skill()
    {
        $this->db->order_by('id_skill', 'DESC');
        return $this->db->get('dm_skill');
    }

    function get_skill_by_id($id_skill)
    {
        $this->db->where('id_skill', $id_skill);
        return $this->db->get('dm_skill');
    }

    function insert_skill($data)
    {
        return $this->db->insert('dm_skill', $data);
    }

    function update_skill($id_skill, $data)
    {
        $this->db->where('id_skill', $id_skill);
        return $this->db->update('dm_skill', $data);
    }

    function delete_skill($id_skill)
    {
        $this->db->where('id_skill', $id_skill);
        return $this->db->delete('dm_skill');
    }

    // =============================================
    // SKILL KARYAWAN
    // =============================================

    function get_all_skill_karyawan()
    {
        $this->db->select('skill_karyawan.*, dm_skill.nama_skill, dm_skill.kategori, pegawai.nama_pegawai, pegawai.NIK');
        $this->db->join('dm_skill', 'dm_skill.id_skill = skill_karyawan.id_skill', 'left');
        $this->db->join('pegawai', 'pegawai.NIK = skill_karyawan.id_karyawan', 'left');
        $this->db->order_by('skill_karyawan.id_skill_karyawan', 'DESC');
        return $this->db->get('skill_karyawan');
    }

    function get_skill_karyawan_by_nik($nik)
    {
        $this->db->select('skill_karyawan.*, dm_skill.nama_skill, dm_skill.kategori');
        $this->db->join('dm_skill', 'dm_skill.id_skill = skill_karyawan.id_skill', 'left');
        $this->db->where('skill_karyawan.id_karyawan', $nik);
        $this->db->order_by('skill_karyawan.id_skill_karyawan', 'DESC');
        return $this->db->get('skill_karyawan');
    }

    function insert_skill_karyawan($data)
    {
        return $this->db->insert('skill_karyawan', $data);
    }

    function delete_skill_karyawan($id)
    {
        $this->db->where('id_skill_karyawan', $id);
        return $this->db->delete('skill_karyawan');
    }

}