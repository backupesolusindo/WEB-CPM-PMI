<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Cron Job Controller untuk KPI
 * Untuk menjalankan perhitungan KPI otomatis
 */
class Cron_kpi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Validasi akses hanya dari CLI atau IP tertentu
        if (!$this->input->is_cli_request()) {
            $allowed_ips = ['127.0.0.1', '::1']; // Tambahkan IP server Anda
            if (!in_array($this->input->ip_address(), $allowed_ips)) {
                show_404();
            }
        }

        $this->load->model('Kpi_model');
        $this->load->helper('kpi_helper');
    }

    /**
     * Hitung KPI untuk semua pegawai
     * Jalankan setiap awal bulan untuk menghitung bulan sebelumnya
     * 
     * Cron: 0 0 1 * * /usr/bin/php /path/to/index.php cron_kpi hitung_bulanan
     */
    public function hitung_bulanan()
    {
        // Hitung untuk bulan lalu
        $bulan = date('n', strtotime('-1 month'));
        $tahun = date('Y', strtotime('-1 month'));

        $this->log_message("===========================================");
        $this->log_message("CRON JOB: Hitung KPI Bulanan");
        $this->log_message("Periode: " . format_periode_kpi($bulan, $tahun));
        $this->log_message("Waktu: " . date('Y-m-d H:i:s'));
        $this->log_message("===========================================");

        // Ambil semua pegawai aktif
        $pegawai_list = $this->db->get_where('pegawai', ['status' => 'aktif'])->result_array();

        $this->log_message("Total pegawai: " . count($pegawai_list));
        $this->log_message("");

        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($pegawai_list as $pegawai) {
            try {
                $result = $this->Kpi_model->hitung_kpi($pegawai['id'], $bulan, $tahun);
                $this->Kpi_model->save_kpi_calculation($result);

                $this->log_message("✓ [{$pegawai['nip']}] {$pegawai['nama_lengkap']} - KPI: {$result['nilai_kpi_final']}");
                $success++;

                // Kirim notifikasi jika KPI rendah
                if ($result['nilai_kpi_final'] < 70) {
                    $this->kirim_notifikasi_kpi_rendah($pegawai, $result);
                }
            } catch (Exception $e) {
                $this->log_message("✗ [{$pegawai['nip']}] {$pegawai['nama_lengkap']} - Error: {$e->getMessage()}");
                $failed++;
                $errors[] = [
                    'pegawai' => $pegawai['nama_lengkap'],
                    'error' => $e->getMessage()
                ];
            }
        }

        $this->log_message("");
        $this->log_message("===========================================");
        $this->log_message("HASIL:");
        $this->log_message("Berhasil: $success");
        $this->log_message("Gagal: $failed");
        $this->log_message("===========================================");

        // Kirim laporan ke admin
        $this->kirim_laporan_admin($bulan, $tahun, $success, $failed, $errors);
    }

    /**
     * Hitung KPI untuk pegawai tertentu
     * 
     * Usage: php index.php cron_kpi hitung_pegawai [pegawai_id] [bulan] [tahun]
     */
    public function hitung_pegawai($pegawai_id = null, $bulan = null, $tahun = null)
    {
        if (!$pegawai_id) {
            $this->log_message("Error: pegawai_id wajib diisi");
            return;
        }

        $bulan = $bulan ?? date('n');
        $tahun = $tahun ?? date('Y');

        $pegawai = $this->db->get_where('pegawai', ['id' => $pegawai_id])->row_array();

        if (!$pegawai) {
            $this->log_message("Error: Pegawai tidak ditemukan");
            return;
        }

        $this->log_message("Menghitung KPI untuk: {$pegawai['nama_lengkap']}");
        $this->log_message("Periode: " . format_periode_kpi($bulan, $tahun));

        try {
            $result = $this->Kpi_model->hitung_kpi($pegawai_id, $bulan, $tahun);
            $this->Kpi_model->save_kpi_calculation($result);

            $this->log_message("✓ Berhasil! KPI: {$result['nilai_kpi_final']}");
            $this->log_message("Detail:");
            $this->log_message("  - Presensi: {$result['nilai_presensi']}");
            $this->log_message("  - Kegiatan: {$result['nilai_kegiatan']}");
            $this->log_message("  - Cuti: {$result['nilai_cuti']}");
            $this->log_message("  - Pekerjaan: {$result['nilai_pekerjaan']}");
            $this->log_message("  - Dinas Luar: {$result['nilai_dinas_luar']}");
        } catch (Exception $e) {
            $this->log_message("✗ Error: {$e->getMessage()}");
        }
    }

    /**
     * Hitung ulang KPI untuk periode tertentu
     * Berguna jika ada perubahan data atau koreksi
     * 
     * Usage: php index.php cron_kpi recalculate [bulan] [tahun]
     */
    public function recalculate($bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? date('n');
        $tahun = $tahun ?? date('Y');

        $this->log_message("===========================================");
        $this->log_message("RECALCULATE KPI");
        $this->log_message("Periode: " . format_periode_kpi($bulan, $tahun));
        $this->log_message("===========================================");

        if (!$this->confirm_action("Hitung ulang KPI untuk periode ini?")) {
            $this->log_message("Dibatalkan.");
            return;
        }

        // Hapus data KPI lama untuk periode ini
        $this->db->where('periode_bulan', $bulan);
        $this->db->where('periode_tahun', $tahun);
        $deleted = $this->db->delete('kpi_calculation_log');

        $this->log_message("Data lama dihapus: $deleted record");
        $this->log_message("");

        // Hitung ulang
        $pegawai_list = $this->db->get_where('pegawai', ['status' => 'aktif'])->result_array();

        $success = 0;
        $failed = 0;

        foreach ($pegawai_list as $pegawai) {
            try {
                $result = $this->Kpi_model->hitung_kpi($pegawai['id'], $bulan, $tahun);
                $this->Kpi_model->save_kpi_calculation($result);

                $this->log_message("✓ {$pegawai['nama_lengkap']} - KPI: {$result['nilai_kpi_final']}");
                $success++;
            } catch (Exception $e) {
                $this->log_message("✗ {$pegawai['nama_lengkap']} - Error: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->log_message("");
        $this->log_message("Selesai! Berhasil: $success, Gagal: $failed");
    }

    /**
     * Kirim reminder ke pegawai dengan KPI rendah
     */
    public function kirim_reminder_kpi_rendah()
    {
        $bulan = date('n');
        $tahun = date('Y');

        $this->log_message("Mengirim reminder KPI rendah...");

        // Ambil pegawai dengan KPI < 70
        $this->db->select('k.*, p.nama_lengkap, p.email');
        $this->db->from('kpi_calculation_log k');
        $this->db->join('pegawai p', 'k.pegawai_id = p.id');
        $this->db->where('k.periode_bulan', $bulan);
        $this->db->where('k.periode_tahun', $tahun);
        $this->db->where('k.nilai_kpi_final <', 70);
        $query = $this->db->get();

        $count = 0;
        foreach ($query->result_array() as $row) {
            if ($this->kirim_notifikasi_kpi_rendah($row, $row)) {
                $this->log_message("✓ Email terkirim ke: {$row['nama_lengkap']}");
                $count++;
            }
        }

        $this->log_message("Total email terkirim: $count");
    }

    // ==================== HELPER FUNCTIONS ====================

    private function log_message($message)
    {
        echo $message . "\n";

        // Log ke file juga
        $log_file = APPPATH . 'logs/cron_kpi_' . date('Y-m-d') . '.log';
        file_put_contents($log_file, date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
    }

    private function confirm_action($message)
    {
        if (!$this->input->is_cli_request()) {
            return true; // Auto confirm jika bukan CLI
        }

        echo $message . " (y/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);

        return trim(strtolower($line)) === 'y';
    }

    private function kirim_notifikasi_kpi_rendah($pegawai, $kpi_data)
    {
        if (empty($pegawai['email'])) {
            return false;
        }

        $this->load->library('email');

        $recommendations = get_kpi_recommendation($kpi_data);

        $message = "
            <h2>Perhatian: KPI Anda Perlu Ditingkatkan</h2>
            <p>Halo {$pegawai['nama_lengkap']},</p>
            <p>KPI Anda untuk periode " . format_periode_kpi($kpi_data['periode_bulan'], $kpi_data['periode_tahun']) . " 
            adalah <strong>{$kpi_data['nilai_kpi_final']}</strong> ({$kpi_data['kategori_kinerja']}).</p>
            
            <h3>Rekomendasi Perbaikan:</h3>
            <ul>";

        foreach ($recommendations as $rec) {
            $message .= "<li><strong>{$rec['komponen']}</strong> (Nilai: {$rec['nilai']}): {$rec['rekomendasi']}</li>";
        }

        $message .= "
            </ul>
            <p>Silakan lihat detail lengkap di: <a href='" . base_url('kpi/detail/' . $pegawai['id']) . "'>Dashboard KPI</a></p>
            <p>Salam,<br>Tim HR</p>
        ";

        $this->email->from('noreply@company.com', 'Sistem KPI');
        $this->email->to($pegawai['email']);
        $this->email->subject('Reminder: KPI Perlu Ditingkatkan');
        $this->email->message($message);

        return $this->email->send();
    }

    private function kirim_laporan_admin($bulan, $tahun, $success, $failed, $errors)
    {
        // Ambil email admin
        $admin_emails = $this->db->select('email')
            ->where('role', 'admin')
            ->get('pegawai')
            ->result_array();

        if (empty($admin_emails)) {
            return false;
        }

        $this->load->library('email');

        $message = "
            <h2>Laporan Cron Job KPI</h2>
            <p>Periode: " . format_periode_kpi($bulan, $tahun) . "</p>
            <p>Waktu: " . date('Y-m-d H:i:s') . "</p>
            
            <h3>Hasil:</h3>
            <ul>
                <li>Berhasil: <strong>$success</strong></li>
                <li>Gagal: <strong>$failed</strong></li>
            </ul>
        ";

        if (!empty($errors)) {
            $message .= "<h3>Error Detail:</h3><ul>";
            foreach ($errors as $error) {
                $message .= "<li>{$error['pegawai']}: {$error['error']}</li>";
            }
            $message .= "</ul>";
        }

        $message .= "<p>Lihat detail di: <a href='" . base_url('kpi') . "'>Dashboard KPI</a></p>";

        foreach ($admin_emails as $admin) {
            $this->email->clear();
            $this->email->from('noreply@company.com', 'Sistem KPI');
            $this->email->to($admin['email']);
            $this->email->subject('Laporan Cron Job KPI - ' . format_periode_kpi($bulan, $tahun));
            $this->email->message($message);
            $this->email->send();
        }

        return true;
    }
}
