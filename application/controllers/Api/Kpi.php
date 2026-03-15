<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kpi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kpi_model');
        $this->load->library('form_validation');
        header('Content-Type: application/json');
    }

    /**
     * Hitung KPI untuk satu pegawai
     * POST /api/kpi/hitung
     * Body: {pegawai_id, bulan, tahun}
     */
    public function hitung()
    {
        $pegawai_id = $this->input->post('pegawai_id');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        if (!$pegawai_id || !$bulan || !$tahun) {
            echo json_encode([
                'status' => false,
                'message' => 'Parameter pegawai_id, bulan, dan tahun wajib diisi'
            ]);
            return;
        }

        try {
            $result = $this->Kpi_model->hitung_kpi($pegawai_id, $bulan, $tahun);
            $this->Kpi_model->save_kpi_calculation($result);

            echo json_encode([
                'status' => true,
                'message' => 'KPI berhasil dihitung',
                'data' => $result
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menghitung KPI: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hitung KPI untuk semua pegawai dalam periode tertentu
     * POST /api/kpi/hitung_batch
     * Body: {bulan, tahun, unit_id (optional)}
     */
    public function hitung_batch()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $unit_id = $this->input->post('unit_id');

        if (!$bulan || !$tahun) {
            echo json_encode([
                'status' => false,
                'message' => 'Parameter bulan dan tahun wajib diisi'
            ]);
            return;
        }

        try {
            // Ambil semua pegawai
            $this->db->select('id');
            if ($unit_id) {
                $this->db->where('unit_id', $unit_id);
            }
            $pegawai_list = $this->db->get('pegawai')->result_array();

            $success_count = 0;
            $failed_count = 0;

            foreach ($pegawai_list as $pegawai) {
                try {
                    $result = $this->Kpi_model->hitung_kpi($pegawai['id'], $bulan, $tahun);
                    $this->Kpi_model->save_kpi_calculation($result);
                    $success_count++;
                } catch (Exception $e) {
                    $failed_count++;
                }
            }

            echo json_encode([
                'status' => true,
                'message' => "KPI berhasil dihitung untuk $success_count pegawai",
                'data' => [
                    'success' => $success_count,
                    'failed' => $failed_count,
                    'total' => count($pegawai_list)
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal menghitung KPI batch: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get KPI berdasarkan periode
     * GET /api/kpi/get_by_periode?bulan=1&tahun=2025&unit_id=1
     */
    public function get_by_periode()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        $unit_id = $this->input->get('unit_id');

        if (!$bulan || !$tahun) {
            echo json_encode([
                'status' => false,
                'message' => 'Parameter bulan dan tahun wajib diisi'
            ]);
            return;
        }

        try {
            $data = $this->Kpi_model->get_kpi_by_periode($bulan, $tahun, $unit_id);

            echo json_encode([
                'status' => true,
                'message' => 'Data KPI berhasil diambil',
                'data' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengambil data KPI: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get KPI pegawai tertentu (riwayat semua periode)
     * GET /api/kpi/get_pegawai?pegawai_id=uuid&bulan=1&tahun=2025
     */
    public function get_pegawai()
    {
        $pegawai_id = $this->input->get('pegawai_id');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        if (!$pegawai_id) {
            echo json_encode([
                'status' => false,
                'message' => 'Parameter pegawai_id wajib diisi'
            ]);
            return;
        }

        try {
            $data = $this->Kpi_model->get_kpi_pegawai($pegawai_id, $bulan, $tahun);

            echo json_encode([
                'status' => true,
                'message' => 'Data KPI pegawai berhasil diambil',
                'data' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengambil data KPI: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get detail lengkap KPI satu pegawai pada periode tertentu
     *
     * GET /api/kpi/detail?pegawai_id=uuid&bulan=3&tahun=2026
     *
     * Response:
     * {
     *   "status": true,
     *   "data": {
     *     "pegawai": { uuid, nip, nama_pegawai, email, no_hp, departemen, jabatan },
     *     "periode": { bulan, tahun, label },
     *     "kpi": {
     *       "nilai_presensi", "nilai_kegiatan", "nilai_cuti",
     *       "nilai_pekerjaan", "nilai_dinas_luar",
     *       "nilai_kpi_final", "kategori_kinerja",
     *       "bobot_snapshot", "dihitung_pada"
     *     },
     *     "riwayat": [ ...semua periode sebelumnya ]
     *   }
     * }
     */
    public function detail()
    {
        $pegawai_id = $this->input->get('pegawai_id');
        $bulan      = $this->input->get('bulan')  ?: date('n');
        $tahun      = $this->input->get('tahun')  ?: date('Y');

        if (!$pegawai_id) {
            http_response_code(400);
            echo json_encode([
                'status'  => false,
                'message' => 'Parameter pegawai_id wajib diisi'
            ]);
            return;
        }

        // Ambil data pegawai
        $pegawai = $this->db->select('
                p.uuid, p.NIP as nip, p.nama_pegawai, p.email,
                u.nama_unit  AS departemen,
                p.jab_struktur AS jabatan
            ', FALSE)
            ->from('pegawai p')
            ->join('unit u', 'u.idunit = p.unit', 'left')
            ->where('p.uuid', $pegawai_id)
            ->get()->row_array();

        if (!$pegawai) {
            http_response_code(404);
            echo json_encode([
                'status'  => false,
                'message' => 'Pegawai tidak ditemukan'
            ]);
            return;
        }

        // Ambil KPI periode yang diminta
        $kpi_row = $this->db
            ->where('pegawai_id',    $pegawai_id)
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->get('kpi_calculation_log')->row_array();

        // Susun objek kpi (null-safe jika belum dihitung)
        $kpi = null;
        if ($kpi_row) {
            $nf = floatval($kpi_row['nilai_kpi_final']);
            if      ($nf >= 90) $kategori = 'Sangat Baik';
            elseif  ($nf >= 80) $kategori = 'Baik';
            elseif  ($nf >= 70) $kategori = 'Cukup';
            elseif  ($nf >= 60) $kategori = 'Kurang';
            else                $kategori = 'Sangat Kurang';

            $kpi = [
                'nilai_presensi'   => (float) $kpi_row['nilai_presensi'],
                'nilai_kegiatan'   => (float) $kpi_row['nilai_kegiatan'],
                'nilai_cuti'       => (float) $kpi_row['nilai_cuti'],
                'nilai_pekerjaan'  => (float) $kpi_row['nilai_pekerjaan'],
                'nilai_dinas_luar' => (float) $kpi_row['nilai_dinas_luar'],
                'nilai_kpi_final'  => (float) $kpi_row['nilai_kpi_final'],
                'kategori_kinerja' => $kategori,
                'bobot_snapshot'   => json_decode($kpi_row['bobot_snapshot'] ?? 'null'),
                'dihitung_pada'    => $kpi_row['created_at'] ?? null,
            ];
        }

        // Ambil semua riwayat KPI pegawai ini
        $riwayat_raw = $this->Kpi_model->get_kpi_pegawai($pegawai_id);
        $riwayat = array_map(function ($r) {
            $nf = floatval($r['nilai_kpi_final']);
            if      ($nf >= 90) $kat = 'Sangat Baik';
            elseif  ($nf >= 80) $kat = 'Baik';
            elseif  ($nf >= 70) $kat = 'Cukup';
            elseif  ($nf >= 60) $kat = 'Kurang';
            else                $kat = 'Sangat Kurang';

            return [
                'periode_bulan'    => (int)  $r['periode_bulan'],
                'periode_tahun'    => (int)  $r['periode_tahun'],
                'periode_label'    => date('F Y', mktime(0, 0, 0, $r['periode_bulan'], 1, $r['periode_tahun'])),
                'nilai_presensi'   => (float) $r['nilai_presensi'],
                'nilai_kegiatan'   => (float) $r['nilai_kegiatan'],
                'nilai_cuti'       => (float) $r['nilai_cuti'],
                'nilai_pekerjaan'  => (float) $r['nilai_pekerjaan'],
                'nilai_dinas_luar' => (float) $r['nilai_dinas_luar'],
                'nilai_kpi_final'  => (float) $r['nilai_kpi_final'],
                'kategori_kinerja' => $kat,
                'dihitung_pada'    => $r['created_at'] ?? null,
            ];
        }, $riwayat_raw);

        echo json_encode([
            'status'  => true,
            'message' => 'Detail KPI pegawai berhasil diambil',
            'data'    => [
                'pegawai' => $pegawai,
                'periode' => [
                    'bulan' => (int) $bulan,
                    'tahun' => (int) $tahun,
                    'label' => date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)),
                ],
                'kpi'     => $kpi,
                'riwayat' => $riwayat,
            ]
        ]);
    }

    /**
     * Get ranking KPI
     * GET /api/kpi/ranking?bulan=1&tahun=2025&limit=10
     */
    public function ranking()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        $limit = $this->input->get('limit') ?? 10;

        if (!$bulan || !$tahun) {
            echo json_encode([
                'status' => false,
                'message' => 'Parameter bulan dan tahun wajib diisi'
            ]);
            return;
        }

        try {
            $data = $this->Kpi_model->get_ranking_kpi($bulan, $tahun, $limit);

            echo json_encode([
                'status' => true,
                'message' => 'Ranking KPI berhasil diambil',
                'data' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengambil ranking KPI: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get statistik KPI
     * GET /api/kpi/statistik?bulan=1&tahun=2025
     */
    public function statistik()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        if (!$bulan || !$tahun) {
            echo json_encode([
                'status' => false,
                'message' => 'Parameter bulan dan tahun wajib diisi'
            ]);
            return;
        }

        try {
            $data = $this->Kpi_model->get_statistik_kpi($bulan, $tahun);

            echo json_encode([
                'status' => true,
                'message' => 'Statistik KPI berhasil diambil',
                'data' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengambil statistik KPI: ' . $e->getMessage()
            ]);
        }
    }

    // ==================== MASTER BOBOT ====================

    /**
     * Get semua bobot
     * GET /api/kpi/bobot
     */
    public function bobot()
    {
        try {
            $data = $this->Kpi_model->get_all_bobot();
            $total = $this->Kpi_model->validate_total_bobot();

            echo json_encode([
                'status' => true,
                'message' => 'Data bobot berhasil diambil',
                'data' => $data,
                'total_bobot' => $total,
                'is_valid' => ($total == 100)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal mengambil data bobot: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update bobot
     * POST /api/kpi/update_bobot
     * Body: {id, bobot}
     */
    public function update_bobot()
    {
        $id = $this->input->post('id');
        $bobot = $this->input->post('bobot');

        if (!$id || !isset($bobot)) {
            echo json_encode([
                'status' => false,
                'message' => 'Parameter id dan bobot wajib diisi'
            ]);
            return;
        }

        if ($bobot < 0 || $bobot > 100) {
            echo json_encode([
                'status' => false,
                'message' => 'Bobot harus antara 0-100'
            ]);
            return;
        }

        try {
            $data = [
                'bobot' => $bobot,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->Kpi_model->update_bobot($id, $data);

            // Validasi total bobot
            $total = $this->Kpi_model->validate_total_bobot();

            echo json_encode([
                'status' => true,
                'message' => 'Bobot berhasil diupdate',
                'total_bobot' => $total,
                'warning' => ($total != 100) ? 'Total bobot tidak sama dengan 100%' : null
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal update bobot: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update multiple bobot sekaligus
     * POST /api/kpi/update_bobot_batch
     * Body: {bobot: [{id: 1, bobot: 30}, {id: 2, bobot: 25}, ...]}
     */
    public function update_bobot_batch()
    {
        $bobot_list = json_decode($this->input->raw_input_stream, true);

        if (!isset($bobot_list['bobot']) || !is_array($bobot_list['bobot'])) {
            echo json_encode([
                'status' => false,
                'message' => 'Format data tidak valid'
            ]);
            return;
        }

        // Validasi total bobot
        $total_bobot = 0;
        foreach ($bobot_list['bobot'] as $item) {
            $total_bobot += $item['bobot'];
        }

        if ($total_bobot != 100) {
            echo json_encode([
                'status' => false,
                'message' => "Total bobot harus 100%. Saat ini: $total_bobot%"
            ]);
            return;
        }

        try {
            $this->db->trans_start();

            foreach ($bobot_list['bobot'] as $item) {
                $data = [
                    'bobot' => $item['bobot'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $this->Kpi_model->update_bobot($item['id'], $data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan data');
            }

            echo json_encode([
                'status' => true,
                'message' => 'Semua bobot berhasil diupdate'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Gagal update bobot: ' . $e->getMessage()
            ]);
        }
    }
}
