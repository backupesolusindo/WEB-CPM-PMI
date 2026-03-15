<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * KPI Helper Functions
 * Helper untuk mempermudah penggunaan fitur KPI
 */

if (!function_exists('get_kpi_badge_class')) {
    /**
     * Get badge class berdasarkan nilai KPI
     * 
     * @param float $nilai_kpi
     * @return string
     */
    function get_kpi_badge_class($nilai_kpi)
    {
        if ($nilai_kpi >= 90) return 'bg-green';
        if ($nilai_kpi >= 80) return 'bg-light-blue';
        if ($nilai_kpi >= 70) return 'bg-yellow';
        if ($nilai_kpi >= 60) return 'bg-orange';
        return 'bg-red';
    }
}

if (!function_exists('get_kpi_kategori')) {
    /**
     * Get kategori kinerja berdasarkan nilai KPI
     * 
     * @param float $nilai_kpi
     * @return string
     */
    function get_kpi_kategori($nilai_kpi)
    {
        if ($nilai_kpi >= 90) return 'Sangat Baik';
        if ($nilai_kpi >= 80) return 'Baik';
        if ($nilai_kpi >= 70) return 'Cukup';
        if ($nilai_kpi >= 60) return 'Kurang';
        return 'Sangat Kurang';
    }
}

if (!function_exists('get_kpi_icon')) {
    /**
     * Get icon berdasarkan nilai KPI
     * 
     * @param float $nilai_kpi
     * @return string
     */
    function get_kpi_icon($nilai_kpi)
    {
        if ($nilai_kpi >= 90) return 'fa-trophy';
        if ($nilai_kpi >= 80) return 'fa-thumbs-up';
        if ($nilai_kpi >= 70) return 'fa-check';
        if ($nilai_kpi >= 60) return 'fa-exclamation-triangle';
        return 'fa-times-circle';
    }
}

if (!function_exists('format_periode_kpi')) {
    /**
     * Format periode KPI menjadi string yang readable
     * 
     * @param int $bulan
     * @param int $tahun
     * @return string
     */
    function format_periode_kpi($bulan, $tahun)
    {
        $nama_bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $nama_bulan[$bulan] . ' ' . $tahun;
    }
}

if (!function_exists('get_kpi_color')) {
    /**
     * Get warna hex berdasarkan nilai KPI
     * 
     * @param float $nilai_kpi
     * @return string
     */
    function get_kpi_color($nilai_kpi)
    {
        if ($nilai_kpi >= 90) return '#00a65a'; // Green
        if ($nilai_kpi >= 80) return '#00c0ef'; // Light Blue
        if ($nilai_kpi >= 70) return '#f39c12'; // Yellow
        if ($nilai_kpi >= 60) return '#ff851b'; // Orange
        return '#dd4b39'; // Red
    }
}

if (!function_exists('hitung_persentase_perubahan_kpi')) {
    /**
     * Hitung persentase perubahan KPI dari periode sebelumnya
     * 
     * @param float $kpi_sekarang
     * @param float $kpi_sebelumnya
     * @return array ['persentase' => float, 'status' => 'naik'|'turun'|'tetap']
     */
    function hitung_persentase_perubahan_kpi($kpi_sekarang, $kpi_sebelumnya)
    {
        if ($kpi_sebelumnya == 0) {
            return [
                'persentase' => 0,
                'status' => 'tetap'
            ];
        }

        $perubahan = (($kpi_sekarang - $kpi_sebelumnya) / $kpi_sebelumnya) * 100;

        $status = 'tetap';
        if ($perubahan > 0) {
            $status = 'naik';
        } elseif ($perubahan < 0) {
            $status = 'turun';
        }

        return [
            'persentase' => round(abs($perubahan), 2),
            'status' => $status
        ];
    }
}

if (!function_exists('get_kpi_recommendation')) {
    /**
     * Get rekomendasi perbaikan berdasarkan komponen KPI
     * 
     * @param array $kpi_data
     * @return array
     */
    function get_kpi_recommendation($kpi_data)
    {
        $recommendations = [];

        // Cek setiap komponen
        if ($kpi_data['nilai_presensi'] < 80) {
            $recommendations[] = [
                'komponen' => 'Presensi',
                'nilai' => $kpi_data['nilai_presensi'],
                'rekomendasi' => 'Tingkatkan kehadiran dan ketepatan waktu masuk kerja'
            ];
        }

        if ($kpi_data['nilai_kegiatan'] < 80) {
            $recommendations[] = [
                'komponen' => 'Kegiatan',
                'nilai' => $kpi_data['nilai_kegiatan'],
                'rekomendasi' => 'Ikuti lebih banyak kegiatan organisasi'
            ];
        }

        if ($kpi_data['nilai_cuti'] < 80) {
            $recommendations[] = [
                'komponen' => 'Cuti',
                'nilai' => $kpi_data['nilai_cuti'],
                'rekomendasi' => 'Kurangi penggunaan cuti yang tidak perlu'
            ];
        }

        if ($kpi_data['nilai_pekerjaan'] < 80) {
            $recommendations[] = [
                'komponen' => 'Pekerjaan',
                'nilai' => $kpi_data['nilai_pekerjaan'],
                'rekomendasi' => 'Selesaikan lebih banyak pekerjaan yang ditugaskan'
            ];
        }

        if ($kpi_data['nilai_dinas_luar'] < 80) {
            $recommendations[] = [
                'komponen' => 'Dinas Luar',
                'nilai' => $kpi_data['nilai_dinas_luar'],
                'rekomendasi' => 'Pastikan pengajuan dinas luar disetujui dengan lengkap'
            ];
        }

        // Sort berdasarkan nilai terendah
        usort($recommendations, function ($a, $b) {
            return $a['nilai'] <=> $b['nilai'];
        });

        return $recommendations;
    }
}

if (!function_exists('get_kpi_summary_text')) {
    /**
     * Get summary text untuk KPI
     * 
     * @param float $nilai_kpi
     * @return string
     */
    function get_kpi_summary_text($nilai_kpi)
    {
        if ($nilai_kpi >= 90) {
            return 'Kinerja Anda sangat baik! Pertahankan prestasi ini.';
        } elseif ($nilai_kpi >= 80) {
            return 'Kinerja Anda baik. Tingkatkan sedikit lagi untuk mencapai kategori sangat baik.';
        } elseif ($nilai_kpi >= 70) {
            return 'Kinerja Anda cukup. Masih ada ruang untuk perbaikan.';
        } elseif ($nilai_kpi >= 60) {
            return 'Kinerja Anda kurang. Perlu peningkatan di beberapa area.';
        } else {
            return 'Kinerja Anda perlu ditingkatkan secara signifikan.';
        }
    }
}

if (!function_exists('export_kpi_to_csv')) {
    /**
     * Export data KPI ke CSV
     * 
     * @param array $data
     * @param string $filename
     * @return void
     */
    function export_kpi_to_csv($data, $filename = 'kpi_export.csv')
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');

        // Header
        fputcsv($output, [
            'NIP',
            'Nama',
            'Departemen',
            'Presensi',
            'Kegiatan',
            'Cuti',
            'Pekerjaan',
            'Dinas Luar',
            'KPI Final',
            'Kategori'
        ]);

        // Data
        foreach ($data as $row) {
            fputcsv($output, [
                $row['nip'],
                $row['nama_lengkap'],
                $row['departemen'],
                $row['nilai_presensi'],
                $row['nilai_kegiatan'],
                $row['nilai_cuti'],
                $row['nilai_pekerjaan'],
                $row['nilai_dinas_luar'],
                $row['nilai_kpi_final'],
                $row['kategori_kinerja']
            ]);
        }

        fclose($output);
        exit;
    }
}

if (!function_exists('validate_bobot_kpi')) {
    /**
     * Validasi total bobot KPI
     * 
     * @param array $bobot_array
     * @return array ['valid' => bool, 'total' => float, 'message' => string]
     */
    function validate_bobot_kpi($bobot_array)
    {
        $total = 0;
        foreach ($bobot_array as $bobot) {
            $total += floatval($bobot);
        }

        $total = round($total, 2);

        return [
            'valid' => ($total == 100),
            'total' => $total,
            'message' => ($total == 100) ? 'Total bobot valid' : "Total bobot harus 100%. Saat ini: {$total}%"
        ];
    }
}

if (!function_exists('get_komponen_terendah')) {
    /**
     * Get komponen dengan nilai terendah
     * 
     * @param array $kpi_data
     * @return array
     */
    function get_komponen_terendah($kpi_data)
    {
        $komponen = [
            'presensi' => $kpi_data['nilai_presensi'],
            'kegiatan' => $kpi_data['nilai_kegiatan'],
            'cuti' => $kpi_data['nilai_cuti'],
            'pekerjaan' => $kpi_data['nilai_pekerjaan'],
            'dinas_luar' => $kpi_data['nilai_dinas_luar']
        ];

        asort($komponen);
        $terendah = array_key_first($komponen);

        return [
            'komponen' => ucfirst(str_replace('_', ' ', $terendah)),
            'nilai' => $komponen[$terendah]
        ];
    }
}

if (!function_exists('get_komponen_tertinggi')) {
    /**
     * Get komponen dengan nilai tertinggi
     * 
     * @param array $kpi_data
     * @return array
     */
    function get_komponen_tertinggi($kpi_data)
    {
        $komponen = [
            'presensi' => $kpi_data['nilai_presensi'],
            'kegiatan' => $kpi_data['nilai_kegiatan'],
            'cuti' => $kpi_data['nilai_cuti'],
            'pekerjaan' => $kpi_data['nilai_pekerjaan'],
            'dinas_luar' => $kpi_data['nilai_dinas_luar']
        ];

        arsort($komponen);
        $tertinggi = array_key_first($komponen);

        return [
            'komponen' => ucfirst(str_replace('_', ' ', $tertinggi)),
            'nilai' => $komponen[$tertinggi]
        ];
    }
}
