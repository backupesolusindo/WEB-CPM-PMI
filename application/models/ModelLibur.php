<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelLibur extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->_create_table_libur_pegawai();
  }

  /**
   * Buat tabel libur_pegawai jika belum ada
   */
  private function _create_table_libur_pegawai()
  {
    $this->db->query("
      CREATE TABLE IF NOT EXISTS `libur_pegawai` (
        `idlibur_pegawai` INT(11) NOT NULL AUTO_INCREMENT,
        `tanggal` DATE NOT NULL,
        `keterangan` VARCHAR(255) NOT NULL DEFAULT '',
        `pegawai_uuid` VARCHAR(100) NOT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`idlibur_pegawai`),
        UNIQUE KEY `uq_libur_pegawai` (`tanggal`, `pegawai_uuid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
  }

  // ===================== LIBUR NASIONAL =====================

  function getLibur($tahun = null)
  {
    if ($tahun == null) {
      $tahun = date("Y");
    }
    $this->db->where("YEAR(tanggal) ", $tahun);
    $this->db->order_by("tanggal");
    return $this->db->get("tanggal_libur");
  }

  function getDataLibur($tanggal)
  {
    $this->db->where("tanggal", date("Y-m-d", strtotime($tanggal)));
    return $this->db->get("tanggal_libur");
  }

  // ===================== LIBUR PEGAWAI =====================

  /**
   * Ambil semua libur pegawai berdasarkan bulan & tahun
   */
  function getLiburPegawai($bulan = null, $tahun = null)
  {
    if ($bulan == null) $bulan = date("m");
    if ($tahun == null) $tahun = date("Y");

    $tgl_mulai = $tahun . '-' . $bulan . '-01';
    $tgl_akhir = date("Y-m-t", strtotime($tgl_mulai));

    $this->db->select('libur_pegawai.*, pegawai.nama_pegawai, pegawai.NIP, pegawai.unit');
    $this->db->join('pegawai', 'pegawai.uuid = libur_pegawai.pegawai_uuid', 'left');
    $this->db->where('libur_pegawai.tanggal >=', $tgl_mulai);
    $this->db->where('libur_pegawai.tanggal <=', $tgl_akhir);
    $this->db->order_by('libur_pegawai.tanggal', 'ASC');
    $this->db->order_by('pegawai.nama_pegawai', 'ASC');
    return $this->db->get('libur_pegawai');
  }

  /**
   * Ambil libur pegawai berdasarkan tanggal tertentu
   */
  function getLiburPegawaiByTanggal($tanggal)
  {
    $this->db->select('libur_pegawai.*, pegawai.nama_pegawai, pegawai.NIP, pegawai.unit');
    $this->db->join('pegawai', 'pegawai.uuid = libur_pegawai.pegawai_uuid', 'left');
    $this->db->where('libur_pegawai.tanggal', date("Y-m-d", strtotime($tanggal)));
    $this->db->order_by('pegawai.nama_pegawai', 'ASC');
    return $this->db->get('libur_pegawai');
  }

  /**
   * Ambil daftar pegawai yang sudah libur pada tanggal tertentu (untuk keperluan kalender event count)
   */
  function countLiburPegawaiPerTanggal($bulan, $tahun)
  {
    $tgl_mulai = $tahun . '-' . $bulan . '-01';
    $tgl_akhir = date("Y-m-t", strtotime($tgl_mulai));

    $this->db->select('tanggal, COUNT(*) as jumlah, keterangan');
    $this->db->where('tanggal >=', $tgl_mulai);
    $this->db->where('tanggal <=', $tgl_akhir);
    $this->db->group_by('tanggal');
    return $this->db->get('libur_pegawai');
  }

  /**
   * Insert libur pegawai
   */
  function insertLiburPegawai($tanggal, $keterangan, $pegawai_uuid)
  {
    $data = array(
      'tanggal'      => date("Y-m-d", strtotime($tanggal)),
      'keterangan'   => $keterangan,
      'pegawai_uuid' => $pegawai_uuid,
    );
    // Ignore duplikat (UNIQUE KEY)
    return $this->db->query(
      "INSERT IGNORE INTO libur_pegawai (tanggal, keterangan, pegawai_uuid) VALUES (?, ?, ?)",
      array($data['tanggal'], $data['keterangan'], $data['pegawai_uuid'])
    );
  }

  /**
   * Delete libur pegawai by ID
   */
  function deleteLiburPegawai($id)
  {
    $this->db->where('idlibur_pegawai', $id);
    return $this->db->delete('libur_pegawai');
  }

  /**
   * Delete semua libur pegawai berdasarkan tanggal
   */
  function deleteLiburPegawaiByTanggal($tanggal)
  {
    $this->db->where('tanggal', date("Y-m-d", strtotime($tanggal)));
    return $this->db->delete('libur_pegawai');
  }
}
