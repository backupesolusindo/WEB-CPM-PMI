<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property ModelJabatan $ModelJabatan
 */
class Jabatan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelJabatan');
        header('Content-Type: application/json');
        $this->load->model('ModelAuth');
        $this->ModelAuth->verify_token();
    }

    // GET: Ambil semua data jabatan
    public function get_jabatan()
    {
        echo json_encode($this->ModelJabatan->get_jabatan_with_pekerjaan());
    }

    // GET: Ambil jabatan berdasarkan ID
    public function show($id)
    {
        $data = $this->ModelJabatan->get_data_edit($id)->row_array();
        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(['error' => "Jabatan dengan ID $id tidak ditemukan"]);
        }
    }

    // POST: Tambah jabatan baru
    // public function create()
    // {
    //     $input = json_decode(trim(file_get_contents("php://input")), true);
    //     if ($this->ModelJabatan->insert($input)) {
    //         echo json_encode(['message' => 'Jabatan berhasil ditambahkan']);
    //     } else {
    //         echo json_encode(['error' => 'Gagal menambahkan jabatan']);
    //     }
    // }

    // // PUT: Update jabatan
    // public function update($id)
    // {
    //     $input = json_decode(trim(file_get_contents("php://input")), true);
    //     if ($this->ModelJabatan->update($id, $input)) {
    //         echo json_encode(['message' => 'Jabatan berhasil diperbarui']);
    //     } else {
    //         echo json_encode(['error' => 'Gagal memperbarui jabatan']);
    //     }
    // }

    // // DELETE: Hapus jabatan
    // public function delete($id)
    // {
    //     if ($this->ModelJabatan->delete($id)) {
    //         echo json_encode(['message' => 'Jabatan berhasil dihapus']);
    //     } else {
    //         echo json_encode(['error' => 'Gagal menghapus jabatan']);
    //     }
    // }
}
