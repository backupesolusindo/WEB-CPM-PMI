<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelPegawai extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_list()
  {
    $this->db->where("pegawai.status_aktif", "1");
    $this->db->order_by("nama_pegawai", "ASC");
    return $this->db->get("pegawai");
  }

  function get_allmonitoring()
  {
    $this->db->join("kepala_unit", "kepala_unit.pegawai_uuid = pegawai.uuid", "left");
    $this->db->join("unit", "unit.idunit = kepala_unit.unit_idunit", "left");
    $this->db->order_by("unit.level", "ASC");
    $this->db->where("pegawai.status_aktif", "1");
    // $this->db->where("pegawai.status_monitoring","1");
    return $this->db->get_where("pegawai");
  }

  function get_kepalaunit($uuid)
  {
    $this->db->join("kepala_unit", "kepala_unit.pegawai_uuid = pegawai.uuid");
    $this->db->join("unit", "unit.idunit = kepala_unit.unit_idunit");
    $this->db->where("pegawai.status_aktif", "1");
    return $this->db->get_where("pegawai", array('pegawai_uuid' => $uuid));
  }

  function get_jabatunit($idunit)
  {
    $this->db->join("kepala_unit", "kepala_unit.pegawai_uuid = pegawai.uuid");
    $this->db->join("unit", "unit.idunit = kepala_unit.unit_idunit");
    $this->db->where("pegawai.status_aktif", "1");
    return $this->db->get_where("pegawai", array('unit.idunit' => $idunit));
  }

  function edit($id)
  {
    return $this->db->get_where("pegawai", array('uuid' => $id));
  }

  function get_anggotamonitoring($uuid, $idkepala = null)
  {
    $this->db->join("unit", "unit.idunit = kepala_unit.unit_idunit");
    $pegawai = $this->db->get_where("kepala_unit", array('pegawai_uuid' => $uuid))->row_array();
    $this->db->reset_query();

    $this->db->join("unit", "unit.nama_unit LIKE CONCAT_WS(' ', pegawai.jenis_unit, pegawai.unit)");
    $this->db->where("pegawai.status_aktif", "1");
    $this->db->where("unit.nama_unit", $pegawai['nama_unit']);
    if ($idkepala != null) {
      $this->db->where_not_in("pegawai.uuid", $idkepala);
    }
    return $this->db->get("pegawai");
  }

  function get_kepalamonitoring($uuid, $hidden_diri = true)
  {
    $this->db->join("unit", "unit.idunit = kepala_unit.unit_idunit");
    $pegawai = $this->db->get_where("kepala_unit", array('pegawai_uuid' => $uuid))->row_array();
    $this->db->reset_query();

    $this->db->join("kepala_unit", "kepala_unit.pegawai_uuid = pegawai.uuid");
    $this->db->join("unit", "unit.idunit = kepala_unit.unit_idunit");
    $this->db->where("pegawai.status_aktif", "1");
    if ($hidden_diri) {
      $this->db->where("pegawai.uuid != ", $uuid);
    }
    $this->db->group_start();
    $this->db->where("unit.nama_unit", $pegawai['nama_unit']);
    // $this->db->or_where("unit.parent_unit",$pegawai['nama_unit']);
    $this->db->group_end();
    return $this->db->get("pegawai");
  }

  function cek_pegawai($nip)
  {
    // Validasi input tidak boleh kosong
    if (empty($nip) || trim($nip) == "") {
      return $this->db->where("1", "0")->get("pegawai"); // Return empty result
    }

    $this->db->where("NIP", $nip);
    $this->db->or_where("email", $nip);
    $this->db->or_where("NIK", $nip);
    return $this->db->get("pegawai");
  }

  function cek_pegawai_aktif($nip)
  {
    $this->db->where("status_aktif", "1");
    $this->db->group_start();
    $this->db->where("NIP", $nip);
    $this->db->or_where("email", $nip);
    $this->db->or_where("NIK", $nip);
    $this->db->or_where("uuid", $nip);
    $this->db->group_end();

    return $this->db->get("pegawai");
  }

  function get_UnitPegawai($unit = null, $sub_unit = null, $tipe_pegawai = null, $jabatan = null)
  {
    $this->db->join("unit", "unit.nama_unit LIKE pegawai.unit", 'left');
    $this->db->join("jabatan", "jabatan.idjabatan = pegawai.jab_struktur", "left");
    $this->db->where("pegawai.status_aktif", "1");
    if ($tipe_pegawai != null || $tipe_pegawai != "") {
      $this->db->where("tipe_pegawai", $tipe_pegawai);
    }
    if ($jabatan != null || $jabatan != "") {
      $this->db->where("jab_struktur", $jabatan);
    }
    if ($sub_unit != null || $sub_unit != "") {
      $this->db->where("unit.nama_unit", $sub_unit);
    } elseif ($unit != null || $unit != "") {
      $this->db->group_start();
      $this->db->where("unit.nama_unit", $unit);
      $this->db->or_where("unit.parent_unit", $unit);
      $this->db->group_end();
    }
    return $this->db->get("pegawai");
  }

  function get_TotalPegawai($unit = null, $sub_unit = null, $tipe_pegawai = null, $jabatan = null)
  {
    $this->db->join("unit", "unit.nama_unit LIKE pegawai.unit");
    $this->db->where("pegawai.status_aktif", "1");
    if ($tipe_pegawai != null || $tipe_pegawai != "") {
      $this->db->where("tipe_pegawai", $tipe_pegawai);
    }
    if ($jabatan != null || $jabatan != "") {
      $this->db->where("jab_struktur", $jabatan);
    }
    if ($sub_unit != null || $sub_unit != "") {
      $this->db->where("unit.nama_unit", $sub_unit);
    } elseif ($unit != null || $unit != "") {
      $this->db->group_start();
      $this->db->where("unit.nama_unit", $unit);
      $this->db->or_where("unit.parent_unit", $unit);
      $this->db->group_end();
    }
    $this->db->order_by("nama_pegawai", "ASC");
    return $this->db->get("pegawai");
  }

  function tipe_pegawai()
  {
    return $this->db->get("tipe_pegawai");
  }

  function get_atasan()
  {
    $this->db->where("jab_atasan", "direktur");
    $this->db->or_where("jab_atasan", "wadir");
    $this->db->order_by("jab_atasan");
    return $this->db->get("pegawai");
  }

  function get_kepala_kepegawaian()
  {
    $this->db->join("kepala_unit", "kepala_unit.pegawai_uuid = pegawai.uuid");
    $this->db->join("unit", "kepala_unit.unit_idunit = unit.idunit");
    $this->db->where("nama_unit", "SUB BAGIAN KEPEGAWAIAN DAN TATA LAKSANA");
    return $this->db->get("pegawai");
  }

  function get_direktur()
  {
    $this->db->join("kepala_unit", "kepala_unit.pegawai_uuid = pegawai.uuid");
    $this->db->join("unit", "kepala_unit.unit_idunit = unit.idunit");
    $this->db->where("jab_atasan", "direktur");
    return $this->db->get("pegawai");
  }
  public function get_nama_pegawai($pegawai_idpegawai)
  {
    return $this->db->get_where('pegawai', ['uuid' => $pegawai_idpegawai])->row();
  }

  /**
   * Mengambil data pegawai berdasarkan NIP.
   *
   * @param string $nip NIP pegawai.
   * @return object|false Data pegawai atau false jika tidak ditemukan.
   */
  public function get_pegawai_by_nip($nip)
  {
    if (empty($nip)) {
      return false;
    }

    $this->db->where("NIP", $nip);
    $query = $this->db->get("pegawai");

    return $query->num_rows() > 0 ? $query->row() : false;
  }

  /**
   * Mengambil data pegawai berdasarkan UUID.
   *
   * @param string $uuid UUID pegawai.
   * @return object|false Data pegawai atau false jika tidak ditemukan.
   */
  public function get_pegawai_by_uuid($uuid)
  {
    if (empty($uuid)) {
      return false;
    }

    $this->db->where("uuid", $uuid);
    return $this->db->get("pegawai")->row();
  }

  /**
   * Memverifikasi password pegawai.
   *
   * @param string $password Password yang diinput.
   * @param string $hashed_password Hash password dari database.
   * @return bool True jika password cocok, false jika tidak.
   */
  public function verify_password($password, $hashed_password)
  {
    return password_verify($password, $hashed_password);
  }

  /**
   * Parse token dari database (bisa berupa JSON array, comma-separated, atau single string)
   *
   * @param string|null $raw_tokens
   * @return array
   */
  public function parse_tokens($raw_tokens)
  {
    if (empty($raw_tokens)) {
      return array();
    }
    // Coba decode JSON array
    $decoded = json_decode($raw_tokens, true);
    if (is_array($decoded)) {
      return array_values(array_filter(array_map('trim', $decoded)));
    }
    // Coba comma-separated
    if (strpos($raw_tokens, ',') !== false) {
      return array_values(array_filter(array_map('trim', explode(',', $raw_tokens))));
    }
    // Single plain token
    $trimmed = trim($raw_tokens);
    return !empty($trimmed) ? array($trimmed) : array();
  }

  /**
   * Menambahkan token baru ke daftar token (maksimal devices diatur oleh $max_devices, default 2)
   * Jika melebihi limit, token terlama akan dihapus (FIFO)
   *
   * @param string|null $current_tokens
   * @param string $new_token
   * @param int $max_devices 0 untuk unlimited, default 2
   * @return string JSON array string
   */
  public function add_token($current_tokens, $new_token, $max_devices = 2)
  {
    $tokens = $this->parse_tokens($current_tokens);
    $new_token = trim($new_token);
    if (empty($new_token)) {
      return json_encode($tokens);
    }

    // Hapus jika sudah ada untuk menghindari duplikat dan menaruh token terbaru di akhir
    $tokens = array_values(array_diff($tokens, array($new_token)));
    $tokens[] = $new_token;

    // Batasi jumlah device jika $max_devices > 0
    if ($max_devices > 0 && count($tokens) > $max_devices) {
      $tokens = array_slice($tokens, -$max_devices);
    }

    return json_encode($tokens);
  }

  /**
   * Menghapus token tertentu dari daftar token
   *
   * @param string|null $current_tokens
   * @param string $token_to_remove
   * @return string JSON array string
   */
  public function remove_token($current_tokens, $token_to_remove)
  {
    $tokens = $this->parse_tokens($current_tokens);
    $token_to_remove = trim($token_to_remove);
    $tokens = array_values(array_diff($tokens, array($token_to_remove)));
    return json_encode($tokens);
  }

  /**
   * Memeriksa apakah token ada dalam daftar token yang valid
   *
   * @param string|null $current_tokens
   * @param string $check_token
   * @return bool
   */
  public function is_token_valid($current_tokens, $check_token)
  {
    $tokens = $this->parse_tokens($current_tokens);
    $check_token = trim($check_token);
    if (empty($check_token)) {
      return false;
    }
    return in_array($check_token, $tokens);
  }
}

