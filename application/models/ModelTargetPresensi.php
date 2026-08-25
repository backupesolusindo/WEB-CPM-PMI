<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelTargetPresensi extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ambil target presensi satu pegawai berdasarkan tahun.
     */
    public function get_by_pegawai_tahun($pegawai_uuid, $tahun)
    {
        return $this->db->get_where('target_presensi', [
            'pegawai_uuid' => $pegawai_uuid,
            'tahun'        => $tahun,
        ])->row_array();
    }

    /**
     * Ambil semua target presensi semua pegawai untuk tahun tertentu.
     */
    public function get_all_by_tahun($tahun)
    {
        $this->db->select('target_presensi.*, pegawai.nama_pegawai, pegawai.NIP, pegawai.unit, pegawai.jenis_unit');
        $this->db->from('target_presensi');
        $this->db->join('pegawai', 'pegawai.uuid = target_presensi.pegawai_uuid', 'right');
        $this->db->where('pegawai.status_aktif', '1');
        $this->db->where('target_presensi.tahun', $tahun);
        $this->db->order_by('pegawai.nama_pegawai', 'ASC');
        return $this->db->get();
    }

    /**
     * Ambil semua pegawai aktif beserta data target presensi (LEFT JOIN).
     * Pegawai yang belum punya target akan tetap muncul dengan nilai NULL.
     */
    public function get_all_pegawai_dengan_target($tahun)
    {
        $this->db->select('pegawai.uuid, pegawai.nama_pegawai, pegawai.NIP, pegawai.unit, pegawai.jenis_unit,
            tp.id_target,
            tp.bulan_1,  tp.bulan_2,  tp.bulan_3,
            tp.bulan_4,  tp.bulan_5,  tp.bulan_6,
            tp.bulan_7,  tp.bulan_8,  tp.bulan_9,
            tp.bulan_10, tp.bulan_11, tp.bulan_12');
        $this->db->from('pegawai');
        $this->db->join(
            "target_presensi tp",
            "tp.pegawai_uuid = pegawai.uuid AND tp.tahun = " . $this->db->escape($tahun),
            'left'
        );
        $this->db->where('pegawai.status_aktif', '1');
        $this->db->order_by('pegawai.nama_pegawai', 'ASC');
        return $this->db->get();
    }

    /**
     * Simpan atau update target presensi pegawai untuk satu tahun.
     * Jika sudah ada → UPDATE, belum ada → INSERT.
     */
    public function save($pegawai_uuid, $tahun, $bulan_data)
    {
        $existing = $this->get_by_pegawai_tahun($pegawai_uuid, $tahun);

        $data = array_merge([
            'tahun'        => $tahun,
            'pegawai_uuid' => $pegawai_uuid,
        ], $bulan_data);

        if ($existing) {
            $this->db->where('pegawai_uuid', $pegawai_uuid);
            $this->db->where('tahun', $tahun);
            return $this->db->update('target_presensi', $data);
        } else {
            return $this->db->insert('target_presensi', $data);
        }
    }

    /**
     * Hapus target presensi pegawai berdasarkan id_target.
     */
    public function delete($id_target)
    {
        $this->db->where('id_target', $id_target);
        return $this->db->delete('target_presensi');
    }
}
