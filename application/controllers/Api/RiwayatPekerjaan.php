<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property ModelRiwayatPekerjaan $ModelRiwayatPekerjaan
 * @property ModelOauth2 $ModelOauth2
 * @property ModelAuth $ModelAuth
 */
class RiwayatPekerjaan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelRiwayatPekerjaan');
        $this->load->model('ModelAuth');
        $this->ModelAuth->verify_token();
    }

    public function get_all_riwayat()
    {
        try {
            $data = $this->ModelRiwayatPekerjaan->get_all();
            if (!empty($data)) {
                $status_code = 200;
                $response = [
                    'status'  => 200,
                    'message' => 'Data riwayat ditemukan',
                    'data'    => $data
                ];
            } else {
                $status_code = 404;
                $response = [
                    'status' => 404,
                    'error'  => 'Tidak ada data riwayat untuk hari ini',
                    'data'   => [] // Konsisten dengan format respons
                ];
            }
        } catch (Exception $e) {
            $status_code = 500;
            $response = [
                'status' => $status_code,
                'error'  => 'Terjadi kesalahan pada server'
                // Jangan tampilkan $e->getMessage() di produksi
            ];
        }

        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }


    public function get_riwayat()
    {
        $pegawai_id = $this->input->get('pegawai_idpegawai');
        try {
            $data = $this->ModelRiwayatPekerjaan->get_today_tasks(null, null, null, $pegawai_id);
            if (!empty($data)) {
                $status_code = 200; 
                $response = [
                    'status'  => 200,
                    'message' => 'Data riwayat ditemukan',
                    'data'    => $data
                ];
            } else {
                $status_code = 404;
                $response = [
                    'status' => 404,
                    'error'  => 'Tidak ada data riwayat untuk hari ini',
                    'data'   => [] // Konsisten dengan format respons
                ];
            }
        } catch (Exception $e) {
            $status_code = 500;
            $response = [
                'status' => $status_code,
                'error'  => 'Terjadi kesalahan pada server'
                // Jangan tampilkan $e->getMessage() di produksi
            ];
        }

        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }



    public function store()
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);
            if (!$input) {
                throw new Exception('Invalid input format', 400);
            }

            // Validasi input wajib ada
            if (
                empty($input['pekerjaan_idpekerjaan']) ||
                empty($input['pegawai_idpegawai']) ||
                empty($input['status']) ||
                empty($input['jumlah'])
            ) {
                throw new Exception('Semua field harus diisi', 400);
            }

            $pekerjaan_id = $input['pekerjaan_idpekerjaan'];
            $pegawai_id   = $input['pegawai_idpegawai'];
            $status       = $input['status'];
            $jumlah       = $input['jumlah'];
            $batas_menit  = 10; // Batas waktu (dalam menit) untuk memeriksa duplikasi data

            // Cek apakah ada duplikasi data di hari ini
            if ($this->ModelRiwayatPekerjaan->is_duplicate_today($pekerjaan_id, $pegawai_id)) {
                throw new Exception('Data sudah ada hari ini!', 409);
            }


            // Siapkan data yang akan disimpan
            $data = [
                'pekerjaan_idpekerjaan' => $pekerjaan_id,
                'pegawai_idpegawai'     => $pegawai_id,
                'status'                => $status,
                'jumlah'                => $jumlah,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s')
            ];

            // Lakukan penyimpanan data dalam transaksi
            $this->db->trans_start();
            $insert = $this->ModelRiwayatPekerjaan->insert($data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE || !$insert) {
                throw new Exception('Gagal menambahkan data', 500);
            }

            $status_code = 201;
            $response = [
                'status'  => 201,
                'message' => 'Data berhasil ditambahkan'
            ];
        } catch (Exception $e) {
            $status_code = $e->getCode() ?: 500;
            $response = [
                'status' => $status_code,
                'error'  => $e->getMessage()
            ];
        }

        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    public function update($id)
    {
        try {
            $input = json_decode(file_get_contents("php://input"), true);

            // Validasi input
            if (!isset($input['status'])) {
                throw new Exception('Status harus diisi', 400);
            }

            $data = [];
            if (isset($input['status'])) {
                $data['status'] = $input['status'];
            }
            if (isset($input['jumlah'])) {
                $data['jumlah'] = $input['jumlah'];
            }
            if (isset($input['updated_at'])) {
                $data['updated_at'] = $input['updated_at'];
            }

            // Periksa apakah ID ada dalam database sebelum update
            $riwayat = $this->ModelRiwayatPekerjaan->get_by_id($id);
            if (!$riwayat) {
                throw new Exception('ID tidak ditemukan', 404);
            }

            // Proses update
            if ($this->ModelRiwayatPekerjaan->update($id, $data)) {
                $status_code = 200;
                $response = [
                    'status' => 200,
                    'message' => 'Status berhasil diperbarui'
                ];
            } else {
                throw new Exception('Gagal memperbarui status', 500);
            }
        } catch (Exception $e) {
            $status_code = $e->getCode() ?: 500;
            $response = [
                'status' => $status_code,
                'error' => $e->getMessage()
            ];
        }

        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }


    // GET: Ambil data berdasarkan ID
    // public function show($id)
    // {
    //     try {
    //         $data = $this->ModelRiwayatPekerjaan->get_by_id($id);

    //         if ($data) {
    //             $status_code = 200;
    //             $response = [
    //                 'status' => 200,
    //                 'message' => 'Data ditemukan',
    //                 'data' => $data
    //             ];
    //         } else {
    //             $status_code = 404;
    //             $response = [
    //                 'status' => 404,
    //                 'error' => 'Data tidak ditemukan'
    //             ];
    //         }
    //     } catch (Exception $e) {
    //         $status_code = 500;
    //         $response = [
    //             'status' => 500,
    //             'error' => 'Terjadi kesalahan pada server',
    //             'detail' => $e->getMessage()
    //         ];
    //     }

    //     $this->output
    //         ->set_status_header($status_code)
    //         ->set_content_type('application/json', 'utf-8')
    //         ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    // }





    // DELETE: Hapus riwayat pekerjaan
    // public function delete($id)
    // {
    //     try {
    //         // Periksa apakah ID ada dalam database sebelum menghapus
    //         $riwayat = $this->ModelRiwayatPekerjaan->get_by_id($id);
    //         if (!$riwayat) {
    //             throw new Exception('ID tidak ditemukan', 404);
    //         }

    //         // Proses hapus data
    //         if ($this->ModelRiwayatPekerjaan->delete($id)) {
    //             $status_code = 200;
    //             $response = [
    //                 'status' => 200,
    //                 'message' => 'Data berhasil dihapus'
    //             ];
    //         } else {
    //             throw new Exception('Gagal menghapus data', 500);
    //         }
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
}
