<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelJadwalMasuk extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Ambil semua jadwal masuk + nama jabatan
   * + daftar pegawai yang ter-assign (digabung sebagai string)
   */
  public function get_all_jadwalmasuk()
  {
    $this->db->select('jadwal_masuk.*, jabatan.namajabatan');
    $this->db->join("jabatan", "jabatan.idjabatan = jadwal_masuk.jabatan_idjabatan");
    return $this->db->get('jadwal_masuk');
  }

  /**
   * Ambil jadwal berdasarkan jabatan (dipakai di tempat lain)
   */
  public function get_jadwalmasuk($idjabatan = null, $jenis = 1)
  {
    $this->db->join("jabatan", "jabatan.idjabatan = jadwal_masuk.jabatan_idjabatan", "left");
    if (!empty($idjabatan)) {
      $this->db->where("jabatan_idjabatan", $idjabatan);
    }
    if ($jenis == 2) {
      $this->db->where_in("jenis", array("2", "3"));
    } elseif ($jenis == 1) {
      $this->db->where("jenis", "1");
    }
    return $this->db->get('jadwal_masuk');
  }

  /**
   * Ambil 1 data jadwal untuk form edit
   */
  public function get_edit($idjadwal_masuk)
  {
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    $this->db->join("jabatan", "jabatan.idjabatan = jadwal_masuk.jabatan_idjabatan");
    return $this->db->get("jadwal_masuk");
  }

  /**
   * Ambil jadwal berdasarkan jabatan + jenis (WFO/WFH)
   */
  public function get_jadwal_jabatan($jabatan, $wf)
  {
    $this->db->where("jabatan_idjabatan", $jabatan);
    $this->db->where("jenis", $wf);
    return $this->db->get("jadwal_masuk");
  }

  /**
   * Ambil daftar pegawai aktif berdasarkan jabatan
   */
  public function get_pegawai_by_jabatan($idjabatan = null)
  {
    $this->db->select("uuid, nama_pegawai");
    if ($idjabatan != null && $idjabatan != '' && $idjabatan != 0) {
      $this->db->where("jab_struktur", $idjabatan);
    }
    $this->db->where("status_aktif", "1");
    $this->db->order_by("nama_pegawai", "ASC");
    return $this->db->get("pegawai");
  }

  /**
   * Ambil UUID pegawai yang sudah di-assign ke jadwal tertentu
   * Return: array of uuid string
   */
  public function get_pegawai_by_jadwal($idjadwal_masuk)
  {
    $this->db->select("pegawai_uuid");
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    $rows = $this->db->get("jadwal_masuk_pegawai")->result_array();
    return array_column($rows, 'pegawai_uuid');
  }

  /**
   * Ambil detail pegawai yang di-assign ke jadwal tertentu
   */
  public function get_pegawai_detail_by_jadwal($idjadwal_masuk)
  {
    $this->db->select("p.uuid, p.nama_pegawai");
    $this->db->from("jadwal_masuk_pegawai jmp");
    $this->db->join("pegawai p", "p.uuid = jmp.pegawai_uuid");
    $this->db->where("jmp.idjadwal_masuk", $idjadwal_masuk);
    $this->db->order_by("p.nama_pegawai", "ASC");
    return $this->db->get();
  }

  /**
   * Simpan relasi jadwal <-> pegawai (bulk insert)
   * $pegawai_uuids: array of UUID string
   */
  public function save_jadwal_pegawai($idjadwal_masuk, array $pegawai_uuids)
  {
    // Hapus relasi lama dulu
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    $this->db->delete("jadwal_masuk_pegawai");

    if (empty($pegawai_uuids)) {
      return true; // tidak ada pegawai spesifik = berlaku untuk semua jabatan
    }

    $insert_data = array();
    foreach ($pegawai_uuids as $uuid) {
      if (!empty(trim($uuid))) {
        $insert_data[] = array(
          'idjadwal_masuk' => $idjadwal_masuk,
          'pegawai_uuid'   => trim($uuid),
        );
      }
    }

    if (!empty($insert_data)) {
      return $this->db->insert_batch("jadwal_masuk_pegawai", $insert_data);
    }
    return true;
  }

  /**
   * Hapus semua relasi pegawai untuk jadwal tertentu
   */
  public function delete_jadwal_pegawai($idjadwal_masuk)
  {
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    return $this->db->delete("jadwal_masuk_pegawai");
  }

  /**
   * Ambil ID kampus yang sudah di-assign ke jadwal tertentu
   * Return: array of idkampus
   */
  public function get_kampus_by_jadwal($idjadwal_masuk)
  {
    $this->db->select("idkampus");
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    $rows = $this->db->get("jadwal_masuk_kampus")->result_array();
    return array_column($rows, 'idkampus');
  }

  /**
   * Simpan relasi jadwal <-> kampus (bulk insert)
   * $kampus_ids: array of idkampus — kosong berarti berlaku untuk semua kampus
   */
  public function save_jadwal_kampus($idjadwal_masuk, array $kampus_ids)
  {
    // Hapus relasi lama dulu
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    $this->db->delete("jadwal_masuk_kampus");

    if (empty($kampus_ids)) {
      return true; // tidak ada kampus spesifik = berlaku untuk semua kampus
    }

    $insert_data = array();
    foreach ($kampus_ids as $idkampus) {
      if (!empty($idkampus)) {
        $insert_data[] = array(
          'idjadwal_masuk' => $idjadwal_masuk,
          'idkampus'       => $idkampus,
        );
      }
    }

    if (!empty($insert_data)) {
      return $this->db->insert_batch("jadwal_masuk_kampus", $insert_data);
    }
    return true;
  }

  /**
   * Hapus semua relasi kampus untuk jadwal tertentu
   */
  public function delete_jadwal_kampus($idjadwal_masuk)
  {
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    return $this->db->delete("jadwal_masuk_kampus");
  }

  /**
   * Mapping nilai jenis ke label nama
   */
  public static function nama_jenis($jenis)
  {
    $map = array(
      '1' => 'WFO',
      '2' => 'WFH',
      '3' => 'Mobile Unit',
    );
    return isset($map[(string)$jenis]) ? $map[(string)$jenis] : '-';
  }

  /**
   * Logika utama untuk API mobile (getNotif):
   *
   * 1. Jika $pegawai_uuid dikirim:
   *    → Cari jadwal yang secara spesifik di-assign ke pegawai ini
   *    → Jika ditemukan, kembalikan jadwal itu
   * 2. Fallback: cari jadwal berdasarkan jabatan yang TIDAK punya
   *    pegawai spesifik (berlaku untuk semua pegawai jabatan)
   * 3. Fallback terakhir: jadwal apapun yang tersedia
   *
   * $jenis: null = semua, 1 = WFO, 2 = WFH/Shift (jenis IN 2,3)
   */
  public function get_jadwal_for_pegawai($pegawai_uuid, $idjabatan = null, $jenis = null)
  {
    // Helper closure untuk apply filter jenis
    $applyJenis = function ($jenis) {
      if ($jenis == 2) {
        $this->db->where_in("jm.jenis", array("2", "3"));
      } elseif ($jenis == 1) {
        $this->db->where("jm.jenis", "1");
      }
    };

    // Step 1: jadwal spesifik untuk pegawai ini
    if (!empty($pegawai_uuid)) {
      $this->db->select('jm.*, jab.namajabatan');
      $this->db->from('jadwal_masuk jm');
      $this->db->join('jabatan jab', 'jab.idjabatan = jm.jabatan_idjabatan');
      $this->db->join('jadwal_masuk_pegawai jmp', 'jmp.idjadwal_masuk = jm.idjadwal_masuk');
      $this->db->where('jmp.pegawai_uuid', $pegawai_uuid);
      $applyJenis($jenis);
      $result = $this->db->get();
      if ($result->num_rows() > 0) {
        return $result;
      }
    }

    // Step 2: jadwal jabatan tanpa pegawai spesifik (tidak ada di tabel relasi)
    if (!empty($idjabatan)) {
      $this->db->select('jm.*, jab.namajabatan');
      $this->db->from('jadwal_masuk jm');
      $this->db->join('jabatan jab', 'jab.idjabatan = jm.jabatan_idjabatan');
      $this->db->where('jm.jabatan_idjabatan', $idjabatan);
      $this->db->where("jm.idjadwal_masuk NOT IN (SELECT idjadwal_masuk FROM jadwal_masuk_pegawai)", null, false);
      $applyJenis($jenis);
      $result = $this->db->get();
      if ($result->num_rows() > 0) {
        return $result;
      }
    }

    // Step 3: fallback ke semua jadwal yang tidak punya pegawai spesifik
    $this->db->select('jm.*, jab.namajabatan');
    $this->db->from('jadwal_masuk jm');
    $this->db->join('jabatan jab', 'jab.idjabatan = jm.jabatan_idjabatan');
    $this->db->where("jm.idjadwal_masuk NOT IN (SELECT idjadwal_masuk FROM jadwal_masuk_pegawai)", null, false);
    $applyJenis($jenis);
    return $this->db->get();
  }
}
