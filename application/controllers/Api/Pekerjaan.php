<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pekerjaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelPekerjaan');
        $this->load->model('ModelAuth');
        $this->load->model('ModelPegawai');
        $this->ModelAuth->verify_token();
    }

    public function get_pekerjaan($uuid = null)
    {
        try {
            // Ambil data pekerjaan
            if ($uuid != null) {
                $data_pegawai = $this->ModelPegawai->edit($uuid)->row();
                $jabatan = $data_pegawai->jab_struktur;
                $data = $this->ModelPekerjaan->get_all($jabatan);
            }else{
                $data = $this->ModelPekerjaan->get_all();
            }
            if (!empty($data)) {
                return $this->output_json(200, 'Data ditemukan', $data, 200);
            }
            return $this->output_json(false, 'Data pekerjaan tidak ditemukan', null, 404);
        } catch (Exception $e) {
            return $this->output_json(false, 'Terjadi kesalahan: ' . $e->getMessage(), null, 500);
        }
    }

    // Fungsi untuk output JSON
    private function output_json($status, $message, $data = null, $http_code = 200)
    {
        $response = ['status' => $status, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    public function show($id)
    {
        try {
            // Ambil data pekerjaan berdasarkan ID
            $data = $this->ModelPekerjaan->get_by_id($id);

            if (!empty($data)) {
                return $this->output_json(200, 'Data ditemukan', $data, 200);
            }

            return $this->output_json(false, 'ID tidak ditemukan', null, 404);
        } catch (Exception $e) {
            return $this->output_json(false, 'Terjadi kesalahan pada server: ' . $e->getMessage(), null, 500);
        }
    }
}



    // POST: Tambah pekerjaan baru
    // public function create()
    // {
    //     try {
    //         $input = json_decode(trim(file_get_contents("php://input")), true);

    //         // Pastikan data tidak kosong
    //         if (empty($input)) {
    //             throw new Exception('Data tidak boleh kosong', 400);
    //         }

    //         // Validasi apakah jabatan_idjabatan ada di tabel jabatan
    //         $jabatan = $this->db->get_where('jabatan', ['idjabatan' => $input['jabatan_idjabatan']])->row_array();
    //         if (!$jabatan) {
    //             throw new Exception('ID Jabatan tidak ditemukan', 404);
    //         }

    //         // Pastikan tipe_pekerjaan ada, jika tidak, beri default 0
    //         $input['tipe_pekerjaan'] = isset($input['tipe_pekerjaan']) ? (int)$input['tipe_pekerjaan'] : 0;

    //         // Insert data ke database
    //         if (!$this->ModelPekerjaan->insert($input)) {
    //             throw new Exception('Gagal menambahkan pekerjaan', 500);
    //         }

    //         // Jika berhasil
    //         $status_code = 201;
    //         $response = [
    //             'status' => 201,
    //             'message' => 'Pekerjaan berhasil ditambahkan'
    //         ];
    //     } catch (Exception $e) {
    //         $status_code = $e->getCode() ?: 500; // Jika tidak ada kode error, default 500
    //         $response = [
    //             'status' => $status_code,
    //             'error' => $e->getMessage()
    //         ];
    //     }

    //     $this->output
    //         ->set_status_header($status_code)
    //         ->set_content_type('application/json', 'utf-8')
    //         ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    // }



    // public function update($id)
    // {
    //     try {
    //         $input = json_decode(trim(file_get_contents("php://input")), true);

    //         // Validasi apakah pekerjaan dengan ID yang diberikan ada
    //         $pekerjaan = $this->ModelPekerjaan->get_by_id($id);
    //         if (!$pekerjaan) {
    //             throw new Exception('ID pekerjaan tidak ditemukan', 404);
    //         }

    //         // Validasi apakah jabatan_idjabatan ada di tabel jabatan jika diberikan
    //         if (isset($input['jabatan_idjabatan'])) {
    //             $jabatan = $this->db->get_where('jabatan', ['idjabatan' => $input['jabatan_idjabatan']])->row_array();
    //             if (!$jabatan) {
    //                 throw new Exception('ID Jabatan tidak ditemukan', 404);
    //             }
    //         }

    //         // Konversi tipe_pekerjaan jika ada
    //         if (isset($input['tipe_pekerjaan'])) {
    //             $input['tipe_pekerjaan'] = (int)$input['tipe_pekerjaan'];
    //         }

    //         // Proses update
    //         if (!$this->ModelPekerjaan->update($id, $input)) {
    //             throw new Exception('Gagal memperbarui pekerjaan', 500);
    //         }

    //         // Jika berhasil
    //         $status_code = 200;
    //         $response = [
    //             'status' => 200,
    //             'message' => 'Pekerjaan berhasil diperbarui'
    //         ];
    //     } catch (Exception $e) {
    //         $status_code = $e->getCode() ?: 500;
    //         $response = [
    //             'status' => $status_code,
    //             'error' => $e->getMessage()
    //         ];
    //     }

    //     $this->output
    //         ->set_status_header($status_code)
    //         ->set_content_type('application/json', 'utf-8')
    //         ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    // }




    // // DELETE: Hapus pekerjaan
    // public function delete($id)
    // {
    //     try {
    //         // Periksa apakah pekerjaan ada
    //         $pekerjaan = $this->ModelPekerjaan->get_by_id($id);
    //         if (!$pekerjaan) {
    //             throw new Exception('ID pekerjaan tidak ditemukan', 404);
    //         }

    //         // Hapus data
    //         if (!$this->ModelPekerjaan->delete($id)) {
    //             throw new Exception('Gagal menghapus pekerjaan', 500);
    //         }

    //         $status_code = 200;
    //         $response = [
    //             'status' => 200,
    //             'message' => 'Pekerjaan berhasil dihapus'
    //         ];
    //     } catch (Exception $e) {
    //         $status_code = $e->getCode() ?: 500;
    //         $response = [
    //             'status' => $status_code,
    //             'error' => $e->getMessage()
    //         ];
    //     }

    //     $this->output
    //         ->set_status_header($status_code)
    //         ->set_content_type('application/json', 'utf-8')
    //         ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    // }
