<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 */

class ModelPekerjaan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Ambil semua pekerjaan
    public function get_all($idjabatan = null)
    {
        $this->db->select('pekerjaan.*, pekerjaan.tipe_pekerjaan, jabatan.namajabatan');
        $this->db->from('pekerjaan');
        if ($idjabatan != null) {
            $this->db->where('jabatan_idjabatan', $idjabatan);
        }
        $this->db->join('jabatan', 'jabatan.idjabatan = pekerjaan.jabatan_idjabatan', 'left'); // JOIN untuk ambil nama jabatan
        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->select('pekerjaan.*, pekerjaan.tipe_pekerjaan, jabatan.namajabatan');
        $this->db->from('pekerjaan');
        $this->db->join('jabatan', 'jabatan.idjabatan = pekerjaan.jabatan_idjabatan', 'left');
        $this->db->where('pekerjaan.id_pekerjaan', $id);
        return $this->db->get()->row_array();
    }

    public function get_edit($id)
    {
        $this->db->select('pekerjaan.*, pekerjaan.tipe_pekerjaan, jabatan.namajabatan'); // Pilih kolom yang diperlukan
        $this->db->from('pekerjaan');
        $this->db->join('jabatan', 'jabatan.idjabatan = pekerjaan.jabatan_idjabatan');
        $this->db->where('pekerjaan.id_pekerjaan', $id);
        $query = $this->db->get();
        return $query->row_array(); // Mengembalikan hasil sebagai array asosiatif
    }
    

    // Tambah pekerjaan baru
    public function insert($data)
    {
        return $this->db->insert('pekerjaan', $data);
    }

    // Update pekerjaan
    public function update($id, $data)
    {
        $this->db->where('id_pekerjaan', $id);
        return $this->db->update('pekerjaan', $data);
    }

    // Hapus pekerjaan
    public function delete($id)
    {
        return $this->db->delete('pekerjaan', ['id_pekerjaan' => $id]);
    }

    public function getTotalPekerjaan()
    {
        return $this->db->count_all('pekerjaan');
    }

}
