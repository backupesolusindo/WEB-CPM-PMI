<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelTotalPoint extends CI_Model
{

    private $table = 'total_point_pegawai';

    public function get_all()
    {
        return $this->db->get('total_point_pegawai')->result_array();
    }
    public function get_total_poin_today($pegawai_idpegawai)
    {
        $this->db->select('SUM(riwayat_pekerjaan.jumlah * pekerjaan.point) as total_point'); // Menjumlahkan total poin
        $this->db->join("pekerjaan", "pekerjaan.id_pekerjaan = riwayat_pekerjaan.pekerjaan_idpekerjaan");
        $this->db->where('pegawai_idpegawai', $pegawai_idpegawai);
        $this->db->where('DATE(created_at)', date('Y-m-d')); // Filter hanya untuk hari ini
        $this->db->where('status', 'approve');
        $query = $this->db->get("riwayat_pekerjaan"); // Menggunakan tabel yang sudah didefinisikan

        return $query->row(); // Mengembalikan satu baris hasil
    }


    public function get_total_poin($pegawai_idpegawai)
    {
        return $this->db->get_where($this->table, ['pegawai_idpegawai' => $pegawai_idpegawai])->row();
    }

    public function update_total_poin($pegawai_idpegawai, $total_poin)
    {
        $data = ['total_point' => $total_poin];
        $exists = $this->db->get_where($this->table, ['pegawai_idpegawai' => $pegawai_idpegawai])->row();

        if ($exists) {
            $this->db->where('pegawai_idpegawai', $pegawai_idpegawai)->update($this->table, $data);
        } else {
            $data['pegawai_idpegawai'] = $pegawai_idpegawai;
            $this->db->insert($this->table, $data);
        }
    }
    // public function get_rekap_pekerjaan($pegawai_id = null)
    // {
    //     $this->db->select('tp.pegawai_idpegawai as uuid, p.nama_pegawai, tp.total_point');
    //     $this->db->from('total_point_pegawai tp');
    //     $this->db->join('pegawai p', 'tp.pegawai_idpegawai = p.uuid', 'left');

    //     if ($pegawai_id) {
    //         $this->db->where('tp.pegawai_idpegawai', $pegawai_id);
    //     }

    //     $this->db->order_by('tp.total_point', 'DESC');
    //     return $this->db->get()->result_array();
    // }


    public function get_rekap_pekerjaan($pegawai_idpegawai = null, $limit = 10, $start_date = null, $end_date = null) {
        // Start with a base query
        $this->db->select('pegawai.uuid, pegawai.nama_pegawai, SUM(pekerjaan.point * riwayat_pekerjaan.jumlah) as total_point', false);
        $this->db->from('riwayat_pekerjaan');        
        $this->db->join('pegawai', 'pegawai.uuid = riwayat_pekerjaan.pegawai_idpegawai', 'inner');
        $this->db->join('pekerjaan', 'pekerjaan.id_pekerjaan = riwayat_pekerjaan.pekerjaan_idpekerjaan', 'inner');
        
        // Apply status filter - only count approved tasks
        $this->db->where('riwayat_pekerjaan.status', 'approve');
        
        // Apply date range filter if provided
        if (!empty($start_date)) {
            $this->db->where('DATE(riwayat_pekerjaan.updated_at) >=', $start_date);
        }
        
        if (!empty($end_date)) {
            $this->db->where('DATE(riwayat_pekerjaan.updated_at) <=', $end_date);
        }
        
        // Apply employee filter if provided
        if (!empty($pegawai_idpegawai)) {
            $this->db->where('pegawai.uuid', $pegawai_idpegawai);
        }
        
        // Group by employee and order by total points descending
        $this->db->group_by('pegawai.uuid, pegawai.nama_pegawai');
        $this->db->order_by('total_point', 'DESC');
        
        // Apply limit
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result_array();
    }




    public function update_total_poin_pegawai($pegawai_idpegawai)
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
                    'total_point' => $total_point,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            return [
                'status' => 200,
                'message' => "Total poin pegawai $pegawai_idpegawai diperbarui"
            ];
        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => "Gagal memperbarui total poin pegawai $pegawai_idpegawai: " . $e->getMessage()
            ];
        }
    }

    public function update_total_poin_all()
    {
        try {
            $pegawai_list = $this->db->get('pegawai')->result();
            $errors = [];
            $success_count = 0;

            foreach ($pegawai_list as $pegawai) {
                if (empty($pegawai->uuid)) {
                    $errors[] = "UUID pegawai tidak ditemukan";
                    continue;
                }

                $result = $this->update_total_poin_pegawai($pegawai->uuid);
                if ($result['status'] == 200) {
                    $success_count++;
                } else {
                    $errors[] = $result['message'];
                }
            }

            return [
                'status' => 200,
                'message' => "Total poin $success_count pegawai berhasil diperbarui",
                'errors' => $errors
            ];
        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => "Gagal memperbarui total poin semua pegawai",
                'error' => $e->getMessage()
            ];
        }
    }

    // public function get_rekap_pekerjaan($pegawai_id = null) {
    //     $this->db->select('tp.pegawai_idpegawai as uuid, p.nama_pegawai, tp.total_point');
    //     $this->db->from('total_point_pegawai tp');
    //     $this->db->join('pegawai p', 'tp.pegawai_idpegawai = p.uuid', 'left');

    //     if ($pegawai_id) {
    //         $this->db->where('tp.pegawai_idpegawai', $pegawai_id);
    //     }

    //     $this->db->order_by('tp.total_point', 'DESC');
    //     return $this->db->get()->result_array();
    // }
}
