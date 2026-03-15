<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kpi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ==================== MASTER BOBOT ====================

    public function get_all_bobot()
    {
        return $this->db->get_where('master_data_bobot', ['is_active' => 1])->result_array();
    }

    public function get_bobot_by_komponen($komponen)
    {
        return $this->db->get_where('master_data_bobot', [
            'komponen' => $komponen,
            'is_active' => 1
        ])->row_array();
    }

    public function update_bobot($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('master_data_bobot', $data);
    }

    public function validate_total_bobot()
    {
        $query = $this->db->select_sum('bobot')
            ->where('is_active', 1)
            ->get('master_data_bobot');
        $result = $query->row_array();
        return $result['bobot'];
    }

    // ==================== PERHITUNGAN KOMPONEN ====================

    /**
     * Hitung nilai presensi (0-100)
     * Formula: (Jumlah Hadir / Total Hari Kerja) * 100
     */
    public function hitung_nilai_presensi($pegawai_id, $bulan, $tahun)
    {
        // Hitung total hari kerja (exclude weekend & libur)
        $total_hari_kerja = $this->get_total_hari_kerja($bulan, $tahun);

        if ($total_hari_kerja == 0) return 0;

        // Hitung kehadiran
        $this->db->select('COUNT(DISTINCT DATE(waktu)) as total_hadir');
        $this->db->where('pegawai_uuid', $pegawai_id);
        $this->db->where('MONTH(waktu)', $bulan);
        $this->db->where('YEAR(waktu)', $tahun);
        $this->db->where('waktu IS NOT NULL');
        $query = $this->db->get('absensi');
        $result = $query->row_array();

        $total_hadir = $result['total_hadir'] ?? 0;

        // Hitung persentase
        $nilai = ($total_hadir / $total_hari_kerja) * 100;

        return round($nilai, 2);
    }

    /**
     * Hitung nilai kegiatan (0-100)
     * Formula: (Jumlah Kegiatan Diikuti / Target Kegiatan) * 100
     */
    public function hitung_nilai_kegiatan($pegawai_id, $bulan, $tahun)
    {
        // Hitung kegiatan yang diikuti
        $this->db->select('COUNT(*) as total_kegiatan');
        $this->db->join("absen_kegiatan", "kegiatan.idkegiatan = absen_kegiatan.kegiatan_idkegiatan");
        $this->db->where('pegawai_uuid', $pegawai_id);
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $query = $this->db->get('kegiatan');
        $result = $query->row_array();

        $total_kegiatan = $result['total_kegiatan'] ?? 0;

        // Target minimal 4 kegiatan per bulan
        $target_kegiatan = 4;

        $nilai = ($total_kegiatan / $target_kegiatan) * 100;

        // Maksimal 100
        return min(round($nilai, 2), 100);
    }

    /**
     * Hitung nilai cuti (0-100)
     * Formula: 100 - (Jumlah Cuti * Penalty)
     * Semakin sedikit cuti, semakin baik
     */
    public function hitung_nilai_cuti($pegawai_id, $bulan, $tahun)
    {
        // Hitung total hari cuti
        $this->db->select('SUM(DATEDIFF(tanggal_akhir, tanggal_mulai) + 1) as total_hari_cuti');
        $this->db->where('pegawai_uuid', $pegawai_id);
        $this->db->where('status', '1');
        $this->db->where('MONTH(tanggal_mulai)', $bulan);
        $this->db->where('YEAR(tanggal_mulai)', $tahun);
        $query = $this->db->get('izin');
        $result = $query->row_array();

        $total_hari_cuti = $result['total_hari_cuti'] ?? 0;

        // Penalty 5 poin per hari cuti
        $penalty_per_hari = 5;
        $nilai = 100 - ($total_hari_cuti * $penalty_per_hari);

        // Minimal 0
        return max(round($nilai, 2), 0);
    }

    /**
     * Hitung nilai pekerjaan (0-100)
     * Formula: (Pekerjaan Selesai / Total Pekerjaan) * 100
     */
    public function hitung_nilai_pekerjaan($pegawai_id, $bulan, $tahun)
    {
        // Total pekerjaan yang ditugaskan
        $this->db->select('SUM(jumlah) as total_pekerjaan');
        $this->db->where('pegawai_idpegawai', $pegawai_id);
        $this->db->where('MONTH(created_at)', $bulan);
        $this->db->where('YEAR(created_at)', $tahun);
        $query_total = $this->db->get('riwayat_pekerjaan');
        $total = $query_total->row_array();

        $total_pekerjaan = $total['total_pekerjaan'] ?? 0;

        if ($total_pekerjaan == 0) return 100; // Tidak ada pekerjaan = nilai sempurna

        // Pekerjaan yang selesai
        $this->db->select('SUM(jumlah) as pekerjaan_selesai');
        $this->db->where('pegawai_idpegawai', $pegawai_id);
        $this->db->where('status', 'approve');
        $this->db->where('MONTH(created_at)', $bulan);
        $this->db->where('YEAR(created_at)', $tahun);
        $query_selesai = $this->db->get('riwayat_pekerjaan');
        $selesai = $query_selesai->row_array();

        $pekerjaan_selesai = $selesai['pekerjaan_selesai'] ?? 0;

        $nilai = ($pekerjaan_selesai / $total_pekerjaan) * 100;

        return round($nilai, 2);
    }

    /**
     * Hitung nilai dinas luar (0-100)
     * Formula: (Dinas Luar Approved / Total Dinas Luar) * 100
     */
    public function hitung_nilai_dinas_luar($pegawai_id, $bulan, $tahun)
    {
        // Total dinas luar
        $this->db->select('COUNT(*) as total_dinas');
        $this->db->join("pegawai_dinasluar", "pegawai_dinasluar.dinas_luar_iddinas_luar = dinas_luar.iddinas_luar");
        $this->db->where('pegawai_uuid', $pegawai_id);
        $this->db->where('MONTH(tanggal_mulai)', $bulan);
        $this->db->where('YEAR(tanggal_mulai)', $tahun);
        $query_total = $this->db->get('dinas_luar');
        $total = $query_total->row_array();

        $total_dinas = $total['total_dinas'] ?? 0;

        if ($total_dinas == 0) return 100; // Tidak ada dinas = nilai sempurna

        // Dinas luar yang approved
        $this->db->select('COUNT(*) as dinas_approved');
        $this->db->join("pegawai_dinasluar", "pegawai_dinasluar.dinas_luar_iddinas_luar = dinas_luar.iddinas_luar");
        $this->db->where('pegawai_uuid', $pegawai_id);
        $this->db->where('status_approval', 'approved');
        $this->db->where('MONTH(tanggal_mulai)', $bulan);
        $this->db->where('YEAR(tanggal_mulai)', $tahun);
        $query_approved = $this->db->get('dinas_luar');
        $approved = $query_approved->row_array();

        $dinas_approved = $approved['dinas_approved'] ?? 0;

        $nilai = ($dinas_approved / $total_dinas) * 100;

        return round($nilai, 2);
    }

    // ==================== PERHITUNGAN KPI FINAL ====================

    public function hitung_kpi($pegawai_id, $bulan, $tahun)
    {
        // Ambil semua bobot
        $bobot_data = $this->get_all_bobot();
        $bobot = [];
        foreach ($bobot_data as $b) {
            $bobot[$b['komponen']] = $b['bobot'];
        }

        // Hitung nilai setiap komponen
        $nilai_presensi = $this->hitung_nilai_presensi($pegawai_id, $bulan, $tahun);
        $nilai_kegiatan = $this->hitung_nilai_kegiatan($pegawai_id, $bulan, $tahun);
        $nilai_cuti = $this->hitung_nilai_cuti($pegawai_id, $bulan, $tahun);
        $nilai_pekerjaan = $this->hitung_nilai_pekerjaan($pegawai_id, $bulan, $tahun);
        $nilai_dinas_luar = $this->hitung_nilai_dinas_luar($pegawai_id, $bulan, $tahun);

        // Hitung KPI final dengan bobot
        $kpi_final = (
            ($nilai_presensi * $bobot['presensi'] / 100) +
            ($nilai_kegiatan * $bobot['kegiatan'] / 100) +
            ($nilai_cuti * $bobot['cuti'] / 100) +
            ($nilai_pekerjaan * $bobot['pekerjaan'] / 100) +
            ($nilai_dinas_luar * $bobot['dinas_luar'] / 100)
        );

        return [
            'pegawai_id' => $pegawai_id,
            'periode_bulan' => $bulan,
            'periode_tahun' => $tahun,
            'nilai_presensi' => $nilai_presensi,
            'nilai_kegiatan' => $nilai_kegiatan,
            'nilai_cuti' => $nilai_cuti,
            'nilai_pekerjaan' => $nilai_pekerjaan,
            'nilai_dinas_luar' => $nilai_dinas_luar,
            'nilai_kpi_final' => round($kpi_final, 2),
            'bobot_snapshot' => json_encode($bobot)
        ];
    }

    public function save_kpi_calculation($data)
    {
        // Cek apakah sudah ada data untuk periode yang sama
        $existing = $this->db->get_where('kpi_calculation_log', [
            'pegawai_id' => $data['pegawai_id'],
            'periode_bulan' => $data['periode_bulan'],
            'periode_tahun' => $data['periode_tahun']
        ])->row_array();

        if ($existing) {
            // Update
            $this->db->where('id', $existing['id']);
            return $this->db->update('kpi_calculation_log', $data);
        } else {
            // Insert
            return $this->db->insert('kpi_calculation_log', $data);
        }
    }

    // ==================== QUERY DATA KPI ====================

    public function get_kpi_by_periode($bulan, $tahun, $unit_id = null)
    {
        $this->db->select('v.*');
        $this->db->from('view_kpi_summary v');
        // $this->db->where('v.periode_bulan', $bulan);
        // $this->db->where('v.periode_tahun', $tahun);

        // if ($unit_id) {
        //     $this->db->join('pegawai p', 'v.pegawai_id = p.id');
        //     $this->db->where('p.unit', $unit_id);
        // }

        // $this->db->order_by('v.nilai_kpi_final', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_kpi_pegawai($pegawai_id, $bulan = null, $tahun = null)
    {
        $this->db->where('pegawai_id', $pegawai_id);

        if ($bulan) {
            $this->db->where('periode_bulan', $bulan);
        }
        if ($tahun) {
            $this->db->where('periode_tahun', $tahun);
        }

        $this->db->order_by('periode_tahun', 'DESC');
        $this->db->order_by('periode_bulan', 'DESC');

        return $this->db->get('view_kpi_summary')->result_array();
    }

    public function get_ranking_kpi($bulan, $tahun, $limit = 10)
    {
        $this->db->where('periode_bulan', $bulan);
        $this->db->where('periode_tahun', $tahun);
        $this->db->order_by('nilai_kpi_final', 'DESC');
        $this->db->limit($limit);

        return $this->db->get('view_kpi_summary')->result_array();
    }

    // ==================== HELPER ====================

    private function get_total_hari_kerja($bulan, $tahun)
    {
        $total_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $hari_kerja = 0;

        for ($i = 1; $i <= $total_hari; $i++) {
            $tanggal = "$tahun-$bulan-$i";
            $day_of_week = date('N', strtotime($tanggal));

            // Skip weekend (6=Sabtu, 7=Minggu)
            if ($day_of_week >= 6) {
                continue;
            }

            // Cek apakah hari libur
            $is_libur = $this->db->get_where('tanggal_libur', [
                'tanggal' => $tanggal
            ])->num_rows() > 0;

            if (!$is_libur) {
                $hari_kerja++;
            }
        }

        return $hari_kerja;
    }

    public function get_statistik_kpi($bulan, $tahun)
    {
        $this->db->select('
            COUNT(*) as total_pegawai,
            AVG(nilai_kpi_final) as rata_rata_kpi,
            MAX(nilai_kpi_final) as kpi_tertinggi,
            MIN(nilai_kpi_final) as kpi_terendah,
            SUM(CASE WHEN nilai_kpi_final >= 90 THEN 1 ELSE 0 END) as sangat_baik,
            SUM(CASE WHEN nilai_kpi_final >= 80 AND nilai_kpi_final < 90 THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN nilai_kpi_final >= 70 AND nilai_kpi_final < 80 THEN 1 ELSE 0 END) as cukup,
            SUM(CASE WHEN nilai_kpi_final >= 60 AND nilai_kpi_final < 70 THEN 1 ELSE 0 END) as kurang,
            SUM(CASE WHEN nilai_kpi_final < 60 THEN 1 ELSE 0 END) as sangat_kurang
        ');
        $this->db->where('periode_bulan', $bulan);
        $this->db->where('periode_tahun', $tahun);

        return $this->db->get('kpi_calculation_log')->row_array();
    }
}
