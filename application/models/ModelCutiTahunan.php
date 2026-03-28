<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelCutiTahunan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function get_all($tahun = null)
    {
        $this->db->select('cuti_tahunan.*, pegawai.NIP, pegawai.nama_pegawai, pegawai.unit, pegawai.jab_struktur');
        $this->db->join('pegawai', 'pegawai.uuid = cuti_tahunan.pegawai_uuid', 'left');
        if ($tahun) {
            $this->db->where('cuti_tahunan.tahun_cuti', $tahun);
        }
        $this->db->order_by('pegawai.nama_pegawai', 'ASC');
        return $this->db->get('cuti_tahunan');
    }

    function get_by_id($id)
    {
        $this->db->select('cuti_tahunan.*, pegawai.NIP, pegawai.nama_pegawai, pegawai.unit');
        $this->db->join('pegawai', 'pegawai.uuid = cuti_tahunan.pegawai_uuid', 'left');
        $this->db->where('idcuti_tahunan', $id);
        return $this->db->get('cuti_tahunan');
    }

    function get_tahun_list()
    {
        $this->db->distinct();
        $this->db->select('tahun_cuti');
        $this->db->order_by('tahun_cuti', 'DESC');
        return $this->db->get('cuti_tahunan');
    }

    function insert_batch($data_batch)
    {
        return $this->db->insert_batch('cuti_tahunan', $data_batch);
    }

    function update($id, $data)
    {
        $this->db->where('idcuti_tahunan', $id);
        return $this->db->update('cuti_tahunan', $data);
    }

    function delete($id)
    {
        $this->db->where('idcuti_tahunan', $id);
        return $this->db->delete('cuti_tahunan');
    }

}