<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelPerizinan extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // ============================
    // GET LIST IZIN
    // ============================
    public function get_list()
    {
        $this->db->select('izin.*, pegawai.NIP, pegawai.nama_pegawai, jenis_perizinan.jenis_izin');
        $this->db->from('izin');
        $this->db->join('pegawai', 'pegawai.uuid = izin.pegawai_uuid', 'left');
        $this->db->join('jenis_perizinan', 'jenis_perizinan.idjenis_perizinan = izin.jenis_perizinan_idjenis_perizinan', 'left');
        return $this->db->get();
    }

    // ============================
    // DETAIL 1 IZIN
    // ============================
    public function get_perizinan($id)
    {
        $this->db->join("pegawai", "pegawai.uuid = izin.pegawai_uuid");
        $this->db->where("idizin", $id);
        return $this->db->get("izin");
    }

    // ============================
    // RIWAYAT IZIN (BERDASARKAN PEGAWAI)
    // ============================
    public function get_riwayat($uuid, $aproval = null, $tgl_mulai = null, $tgl_akhir = null)
    {
        $this->db->join("jenis_perizinan", "jenis_perizinan.idjenis_perizinan = izin.jenis_perizinan_idjenis_perizinan");
        $this->db->join("pegawai", "pegawai.uuid = izin.pegawai_uuid");
        $this->db->where("uuid", $uuid);

        if ($aproval != null || $aproval != "") {
            $this->db->where("status", $aproval);
        }

        if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "") {
            $this->db->where('izin.tanggal_mulai BETWEEN "' . $tgl_mulai . '" AND "' . $tgl_akhir . '"');
        }

        return $this->db->get("izin");
    }

    // ============================
    // RIWAYAT MONITORING IZIN
    // ============================
    public function get_riwayatMonitoring($unit = null, $aproval = null, $tgl_mulai = null, $tgl_akhir = null, $sub_unit = null)
    {
        $this->db->join("jenis_perizinan", "jenis_perizinan.idjenis_perizinan = izin.jenis_perizinan_idjenis_perizinan");
        $this->db->join("pegawai", "pegawai.uuid = izin.pegawai_uuid");
        $this->db->join("unit", "unit.nama_unit = pegawai.unit");
        // $this->db->join("cuti_tahunan", "cuti_tahunan.pegawai_uuid = pegawai.uuid AND cuti_tahunan.tahun_cuti = YEAR(izin.tanggal_mulai)", 'left');

        if ($aproval != null || $aproval != "") {
            $this->db->where("izin.status", $aproval);
        }

        if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "") {
            $this->db->where('izin.tanggal_mulai BETWEEN "' . $tgl_mulai . '" AND "' . $tgl_akhir . '"');
        }

        if ($sub_unit != null || $sub_unit != "") {
            $this->db->like("unit.nama_unit", $sub_unit);
        } elseif ($unit != null || $unit != "") {
            $this->db->group_start();
            $this->db->like("unit.nama_unit", $unit);
            $this->db->or_like("unit.parent_unit", $unit);
            $this->db->group_end();
        }

        return $this->db->get("izin");
    }

    // ============================
    // LAPORAN CUTI
    // ============================
    public function get_laporan_cuti($tgl_mulai, $tgl_akhir, $unit = null)
    {
        $this->db->select('izin.*, pegawai.NIP, pegawai.nama_pegawai, jenis_perizinan.jenis_izin');
        $this->db->from('izin');
        $this->db->join('pegawai', 'pegawai.uuid = izin.pegawai_uuid', 'left');
        $this->db->join('jenis_perizinan', 'jenis_perizinan.idjenis_perizinan = izin.jenis_perizinan_idjenis_perizinan', 'left');

        $this->db->where('tanggal_mulai >=', $tgl_mulai);
        $this->db->where('tanggal_akhir <=', $tgl_akhir);

        if ($unit && $unit != "Semua Unit") {
            $this->db->where('pegawai.unit', $unit);
        }

        return $this->db->get();
    }

    // ============================
    // FILTER IZIN (UTAMA UNTUK CUTIPEGAWAI)
    // ============================
    public function get_filtered($start = null, $end = null, $status = null)
    {
        $this->db->select('izin.*, pegawai.NIP, pegawai.nama_pegawai, jenis_perizinan.jenis_izin');
        $this->db->from('izin');
        $this->db->join('pegawai', 'pegawai.uuid = izin.pegawai_uuid', 'left');
        $this->db->join('jenis_perizinan', 'jenis_perizinan.idjenis_perizinan = izin.jenis_perizinan_idjenis_perizinan', 'left');

        // Filter tanggal
        if (!empty($start) && !empty($end)) {
            $this->db->where('izin.tanggal_mulai >=', $start);
            $this->db->where('izin.tanggal_akhir <=', $end);
        }

        // Filter status
        if ($status !== "" && $status !== null) {
            $this->db->where('izin.status', $status);
        }

        // Urutkan paling baru di atas
        $this->db->order_by('izin.tanggal_mulai', 'DESC');

        return $this->db->get();
    }


    // ============================
    // UPDATE STATUS
    // ============================
    public function updateStatusIzin($id_izin, $data)
    {
        $this->db->where('idizin', $id_izin);
        return $this->db->update('izin', $data);
    }

    // ============================
    // HAPUS IZIN
    // ============================
    public function hapusIzin($id_izin)
    {
        $this->db->where('idizin', $id_izin);
        return $this->db->delete('izin');
    }

    // ==============================
    // HAPUS MASSAL IZIN
    // ==============================
    public function hapusMassalIzin($ids)
    {
        // Validasi input: pastikan $ids adalah array dan tidak kosong
        if (!is_array($ids) || empty($ids)) {
            return false;
        }

        // Konversi semua elemen menjadi integer untuk keamanan
        $ids = array_map('intval', $ids);

        // Hapus dari tabel 'izin' berdasarkan ID
        $this->db->where_in('idizin', $ids);
        $result = $this->db->delete('izin');

        // Kembalikan hasil operasi delete
        return $result;
    }

    // ==============================
    // UPDATE STATUS MASSAL IZIN
    // ==============================
    public function updateStatusMassalIzin($ids, $status)
    {
        // Validasi input
        if (!is_array($ids) || empty($ids)) {
            return false;
        }

        // Konversi semua elemen menjadi integer untuk keamanan
        $ids = array_map('intval', $ids);

        // Siapkan data untuk update
        $data = array('status' => $status);

        // Tambahkan tanggal_update jika kolomnya ada
        if ($this->db->field_exists('tanggal_update', 'izin')) {
            $data['tanggal_update'] = date('Y-m-d H:i:s');
        }

        // Update status untuk semua ID yang dipilih
        $this->db->where_in('idizin', $ids);
        return $this->db->update('izin', $data);
    }


    // ============================
    // GET BY ID
    // ============================
    public function getIzinById($id_izin)
    {
        $this->db->where('idizin', $id_izin);
        return $this->db->get('izin')->row();
    }
}
