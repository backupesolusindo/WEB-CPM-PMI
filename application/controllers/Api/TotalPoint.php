<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TotalPoint extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelTotalPoint');
        $this->load->model('ModelRiwayatPekerjaan');
        $this->load->model('ModelPegawai');
        $this->load->model('ModelAuth');
        $this->ModelAuth->verify_token();
    }
    public function get_all_point()
    {
        try {
            $data = $this->ModelTotalPoint->get_all();

            if (!empty($data)) {
                $status_code = 200;
                $response = [
                    'status' => 200,
                    'message' => 'Data ditemukan',
                    'data' => $data
                ];
            } else {
                $status_code = 404;
                $response = [
                    'status' => 404,
                    'error' => 'Data point tidak ditemukan'
                ];
            }
        } catch (Exception $e) {
            $status_code = 500; // Internal Server Error
            $response = [
                'status' => 500,
                'error' => 'Terjadi kesalahan pada server',
                'message' => $e->getMessage()
            ];
        }

        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }

    public function get_total_poin($pegawai_idpegawai)
    {
        try {
            $pegawai = $this->ModelPegawai->get_nama_pegawai($pegawai_idpegawai);
            if (!$pegawai) {
                throw new Exception('Pegawai tidak ditemukan', 404);
            }

            // Ambil total poin hanya untuk hari ini
            $result = $this->ModelTotalPoint->get_total_poin_today($pegawai_idpegawai);
            $total_point_today = ($result && isset($result->total_point)) ? $result->total_point : 0;

            $response = [
                'status' => 200,
                'nama_pegawai' => $pegawai->nama_pegawai,
                'total_point' => $total_point_today
            ];
            $status_code = 200;
        } catch (Exception $e) {
            $status_code = $e->getCode() ?: 500;
            $response = [
                'status' => $status_code,
                'error' => $e->getMessage(),
                'nama_pegawai' => null,
                'total_point' => 0
            ];
        }

        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT));
    }


    public function update_total_poin($pegawai_idpegawai)
    {
        try {
            // Hitung total poin pekerjaan yang sudah di-approve untuk pegawai ini
            $this->db->select('SUM(pekerjaan.point * riwayat_pekerjaan.jumlah) AS total_point', false);
            $this->db->join('pekerjaan', 'riwayat_pekerjaan.pekerjaan_idpekerjaan = pekerjaan.id_pekerjaan');
            $this->db->where('riwayat_pekerjaan.pegawai_idpegawai', $pegawai_idpegawai);
            $this->db->where('riwayat_pekerjaan.status', 'approve');
            $query = $this->db->get('riwayat_pekerjaan');

            // Pastikan total_point tidak NULL
            $total_point = ($query->num_rows() > 0 && $query->row()->total_point !== null) ? $query->row()->total_point : 0;

            // Cek apakah sudah ada di total_point_pegawai
            $cek = $this->db->get_where('total_point_pegawai', ['pegawai_idpegawai' => $pegawai_idpegawai])->row();

            if ($cek) {
                // Jika sudah ada, update
                $this->db->where('pegawai_idpegawai', $pegawai_idpegawai);
                $this->db->update('total_point_pegawai', [
                    'total_point' => $total_point,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Jika belum ada, insert
                $this->db->insert('total_point_pegawai', [
                    'pegawai_idpegawai' => $pegawai_idpegawai,
                    'total_point' => $total_point, // **Pastikan tidak NULL**
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $response = ['status' => 200, 'message' => "Total poin pegawai $pegawai_idpegawai diperbarui"];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } catch (Exception $e) {
            $response = ['status' => 500, 'message' => "Gagal memperbarui total poin pegawai $pegawai_idpegawai"];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }



    // public function update_total_poin_all()
    // {
    //     try {
    //         $pegawai_list = $this->db->get('pegawai')->result();
    //         $errors = [];
    //         $success_count = 0;

    //         foreach ($pegawai_list as $pegawai) {
    //             if (empty($pegawai->uuid)) {
    //                 $errors[] = "UUID pegawai tidak ditemukan";
    //                 continue;
    //             }

    //             $result = $this->update_total_poin($pegawai->uuid);
    //         }
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode([
    //                 'status' => 200,
    //                 'message' => 'Total poin semua pegawai berhasil diperbarui',
    //             ]));
    //     } catch (Exception $e) {
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(500)
    //             ->set_output(json_encode([
    //                 'status' => 500,
    //                 'message' => "Gagal memperbarui total poin semua pegawai",
    //                 'error' => $e->getMessage()
    //             ]));
    //     }
    // }
}
