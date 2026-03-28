<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelPdp extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->order_by("id_pdp", "DESC");
        return $this->db->get("pdp");
    }

    function get_by_id($id_pdp)
    {
        $this->db->where("id_pdp", $id_pdp);
        return $this->db->get("pdp");
    }

    function insert($data)
    {
        return $this->db->insert("pdp", $data);
    }

    function update($id_pdp, $data)
    {
        $this->db->where("id_pdp", $id_pdp);
        return $this->db->update("pdp", $data);
    }

    function delete($id_pdp)
    {
        // Hapus peserta dulu sebelum hapus pdp
        $this->db->where("pdp_id_pdp", $id_pdp);
        $this->db->delete("pdp_peserta");

        $this->db->where("id_pdp", $id_pdp);
        return $this->db->delete("pdp");
    }

    function get_peserta($id_pdp)
    {
        $this->db->join("pegawai", "pegawai.uuid = pdp_peserta.pegawai_uuid");
        $this->db->where("pdp_id_pdp", $id_pdp);
        return $this->db->get("pdp_peserta");
    }

    function insert_peserta($id_pdp, $list_uuid)
    {
        // Hapus peserta lama dulu
        $this->db->where("pdp_id_pdp", $id_pdp);
        $this->db->delete("pdp_peserta");

        // Insert peserta baru
        $data = array();
        foreach ($list_uuid as $uuid) {
            $data[] = array(
                'pdp_id_pdp'   => $id_pdp,
                'pegawai_uuid' => $uuid
            );
        }

        if (!empty($data)) {
            return $this->db->insert_batch("pdp_peserta", $data);
        }
        return true;
    }

}