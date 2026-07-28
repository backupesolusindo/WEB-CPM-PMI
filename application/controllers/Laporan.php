<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelRiwayat");
    $this->load->model("ModelPerizinan");
    $this->load->model("ModelDinasLuar");
    $this->load->model("ModelUnit");
    $this->load->model("ModelKegiatan");
    $this->load->model("ModelJadwalWF");
    $this->load->model("ModelPegawai");
    $this->load->model("ModelLaporan");
    $this->load->model("ModelAbsensi");
    $this->load->model("ModelJabatan");
    $this->load->model("ModelLibur");
    $this->load->model("ModelJadwalMasuk");
    $this->load->model("ModelLembur");
  }

  function sub_unit()
  {
    $parent = $this->input->post("unit");
    $html = "<option value=''>Semua Sub Unit</option>";
    foreach ($this->ModelUnit->get_sub_unit($parent)->result() as $value) {
      $html .= '<option value="' . $value->nama_unit . '">' . $value->nama_unit . '</option>';
    }
    echo $html;
  }

  function LaporanPresensi()
  {
    $data = array(
      'title'         => "Laporan Presensi Pegawai",
      'body'          => 'Laporan/Presensi/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
    );
    $this->load->view('index', $data);
  }

  function PresensiAktif()
  {
    $data = array(
      'title'         => "Laporan Presensi Pegawai",
      'body'          => 'Laporan/Aktif/index',
      'pegawai'       => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function RealtimeLocatioan($pegawai_uuid)
  {
    $pegawai          = $this->ModelPegawai->edit($pegawai_uuid)->row_array();
    $realtime         = $this->db->where("user_id", $pegawai_uuid)->get("realtime_location")->row_array();
    // $absen            = $this->ModelAbsensi->get_Absensi($idabsensi)->row_array();
    // $absenpulang      = @$this->ModelAbsensi->get_AbsensiPulang($absen["idabsensi"])->row_array();
    $data = array(
      'title'         => "Lokasi Realtime Pegawai",
      'body'          => 'Laporan/Presensi/realtime_location',
      'pegawai'       => $pegawai,
      'realtime'      => $realtime
      // 'absensi'       => $absen,
      // 'absensi_pulang' => $absenpulang,
    );
    // die(json_encode($data));
    $this->load->view('index', $data);
  }

  function DetailLaporanPresensi($idabsensi)
  {
    $absen            = $this->ModelAbsensi->get_Absensi($idabsensi)->row_array();
    $pegawai          = $this->ModelPegawai->edit($absen["pegawai_uuid"])->row_array();
    $absenpulang      = @$this->ModelAbsensi->get_AbsensiPulang($absen["idabsensi"])->row_array();
    $istirahat        = @$this->ModelRiwayat->get_Absensi_Istirahat($absen["pegawai_uuid"], date("Y-m-d", strtotime($absen['waktu'])))->row_array();
    $selesaiIstirahat = @$this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"])->row_array();
    $data = array(
      'title'         => "Laporan Presensi Pegawai",
      'body'          => 'Laporan/Presensi/detail',
      'pegawai'       => $pegawai,
      'absensi'       => $absen,
      'absensi_pulang' => $absenpulang,
      'istirahat'     => $istirahat,
      'selesaiIstirahat' => $selesaiIstirahat,
    );
    $this->load->view('index', $data);
  }

  function tabelPresensi()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $status_filter  = $this->input->post("status");
    $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoring($unit, null, $tgl_mulai, $tgl_akhir, $sub_unit);

    // Ambil data libur pegawai dalam rentang tanggal yang dipilih
    // Buat lookup array: ['uuid-tanggal'] => data libur
    $libur_pegawai_raw = $this->db
      ->select('libur_pegawai.*, pegawai.nama_pegawai, pegawai.NIP, pegawai.unit')
      ->join('pegawai', 'pegawai.uuid = libur_pegawai.pegawai_uuid', 'left')
      ->where('libur_pegawai.tanggal >=', $tgl_mulai)
      ->where('libur_pegawai.tanggal <=', $tgl_akhir)
      ->order_by('libur_pegawai.tanggal', 'ASC')
      ->order_by('pegawai.nama_pegawai', 'ASC')
      ->get('libur_pegawai');

    // Filter berdasarkan unit jika dipilih
    $libur_pegawai_list = array();
    foreach ($libur_pegawai_raw->result() as $row) {
      if ($unit && $row->unit != $unit && strpos($row->unit, $unit) === false) {
        continue;
      }
      $key = $row->pegawai_uuid . '_' . $row->tanggal;
      $libur_pegawai_list[$key] = $row;
    }

    // Buat set UUID pegawai yang sudah hadir presensi per tanggal (untuk exclude dari libur)
    $hadir_set = array();
    foreach ($absenharian->result() as $ab) {
      $tgl = date("Y-m-d", strtotime($ab->waktu));
      $hadir_set[$ab->uuid . '_' . $tgl] = true;
    }

    $data = array(
      'presensi'           => $absenharian,
      'tgl_mulai'          => $tgl_mulai,
      'tgl_akhir'          => $tgl_akhir,
      'status_filter'      => $status_filter,
      'libur_pegawai_list' => $libur_pegawai_list,
      'hadir_set'          => $hadir_set,
    );
    $this->load->view('Laporan/Presensi/tabel', $data);
  }

  function LaporanKegiatan()
  {
    $data = array(
      'title'         => "Laporan Presensi Kegiatan Pegawai",
      'body'          => 'Laporan/Kegiatan/index',
      'unit'   => $this->ModelUnit->get_unit()->result(),
    );
    $this->load->view('index', $data);
  }

  function tabelKegiatan()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    // $tgl_mulai = "2021-05-01";
    // $tgl_akhir = "2021-05-15";
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    // $unit = "";
    $kegiatan = $this->ModelKegiatan->get_all($tgl_mulai, $tgl_akhir, $unit, $sub_unit);
    $data = array(
      'kegiatan'          => $kegiatan
    );
    $this->load->view('Laporan/Kegiatan/tabel', $data);
  }

  function detailKegiatan($idkegiatan)
  {
    $idkegiatan = $this->core->decrypt_url($idkegiatan);
    $kegiatan   = $this->ModelKegiatan->get_data($idkegiatan)->row_array();

    // Generate semua tanggal kegiatan
    $tgl_mulai   = strtotime($kegiatan['tanggal']);
    $tgl_selesai = strtotime($kegiatan['tanggal_selesai']);
    $tanggal_list = array();
    for ($t = $tgl_mulai; $t <= $tgl_selesai; $t = strtotime('+1 day', $t)) {
      $tanggal_list[] = date('Y-m-d', $t);
    }

    // Ambil semua undangan
    $semua_undangan = $this->ModelKegiatan->getUndanganPeserta($idkegiatan)->result();

    // Ambil semua yang hadir
    $semua_hadir = $this->ModelKegiatan->getPesertaKegiatan($idkegiatan)->result();

    // Kelompokkan hadir per tanggal
    $hadir_per_tgl = array();
    foreach ($semua_hadir as $p) {
      $tgl = date('Y-m-d', strtotime($p->jam_presensi));
      $hadir_per_tgl[$tgl][] = $p;
    }

    // Kelompokkan tidak hadir per tanggal (undangan yang uuid-nya tidak ada di hadir hari itu)
    $tidak_hadir_per_tgl = array();
    foreach ($tanggal_list as $tgl) {
      $uuid_hadir_tgl = array();
      if (!empty($hadir_per_tgl[$tgl])) {
        foreach ($hadir_per_tgl[$tgl] as $p) {
          $uuid_hadir_tgl[] = $p->uuid;
        }
      }
      $tidak_hadir_per_tgl[$tgl] = array();
      foreach ($semua_undangan as $u) {
        if (!in_array($u->uuid, $uuid_hadir_tgl)) {
          $tidak_hadir_per_tgl[$tgl][] = $u;
        }
      }
    }

    $data = array(
      'title'               => "Detail Laporan Kegiatan",
      'body'                => 'Laporan/Kegiatan/detail',
      'kegiatan'            => $kegiatan,
      'tanggal_list'        => $tanggal_list,
      'hadir_per_tgl'       => $hadir_per_tgl,
      'tidak_hadir_per_tgl' => $tidak_hadir_per_tgl,
      // backward compat untuk printableArea
      'peserta'             => $this->ModelKegiatan->getPesertaKegiatan($idkegiatan),
      'foto_kegiatan'       => $this->db->where("kegiatan_idkegiatan", $idkegiatan)->order_by("created_at", "DESC")->get("kegiatan_foto")->result(),
    );
    $this->load->view('index', $data);
  }

  function simpan_notulensi()
  {
    $idkegiatan = $this->input->post("idkegiatan");
    $notulensi  = $this->input->post("notulensi");
    $this->db->where("idkegiatan", $idkegiatan);
    if ($this->db->update("kegiatan", array('notulensi' => $notulensi))) {
      echo json_encode(array('status' => 200, 'message' => 'Notulensi berhasil disimpan'));
    } else {
      echo json_encode(array('status' => 500, 'message' => 'Gagal menyimpan notulensi'));
    }
  }

  function upload_foto_kegiatan()
  {
    $idkegiatan = $this->input->post("idkegiatan");
    $patch      = "document/foto_kegiatan_laporan/";

    $config['upload_path']   = "./" . $patch;
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size']      = 10240;
    $config['encrypt_name']  = TRUE;

    if (!is_dir("./" . $patch)) {
      mkdir("./" . $patch, 0755, true);
    }

    $this->load->library('upload', $config);
    $this->upload->initialize($config);

    if ($this->upload->do_upload('foto_kegiatan')) {
      $file_name  = $this->upload->data()['file_name'];
      $foto_path  = $patch . $file_name;

      // Simpan ke tabel kegiatan_foto
      $this->db->insert("kegiatan_foto", array(
        'kegiatan_idkegiatan' => $idkegiatan,
        'foto'                => $foto_path,
        'created_at'          => date("Y-m-d H:i:s"),
      ));
      $new_id = $this->db->insert_id();
      echo json_encode(array('status' => 200, 'message' => 'Foto berhasil diupload', 'foto' => base_url() . $foto_path, 'id' => $new_id));
    } else {
      echo json_encode(array('status' => 500, 'message' => $this->upload->display_errors('', '')));
    }
  }

  function get_foto_kegiatan()
  {
    $idkegiatan = $this->input->post("idkegiatan");
    $foto       = $this->db->where("kegiatan_idkegiatan", $idkegiatan)->order_by("created_at", "DESC")->get("kegiatan_foto")->result();
    echo json_encode(array('status' => 200, 'data' => $foto));
  }

  function hapus_foto_kegiatan()
  {
    $id   = $this->input->post("id");
    $row  = $this->db->where("id", $id)->get("kegiatan_foto")->row_array();
    if ($row) {
      @unlink("./" . $row['foto']);
      $this->db->where("id", $id)->delete("kegiatan_foto");
      echo json_encode(array('status' => 200, 'message' => 'Foto berhasil dihapus'));
    } else {
      echo json_encode(array('status' => 404, 'message' => 'Foto tidak ditemukan'));
    }
  }

  function approval_kegiatan()
  {
    $idabsen_kegiatan = $this->input->post("idabsen_kegiatan");
    $status           = $this->input->post("status");
    $this->db->where("idabsen_kegiatan", $idabsen_kegiatan);
    if ($this->db->update("absen_kegiatan", array('status_aproval' => $status))) {
      echo json_encode(array('status' => 200, 'message' => 'Berhasil'));
    } else {
      echo json_encode(array('status' => 500, 'message' => 'Gagal'));
    }
  }

  function LaporanCuti()
  {
    $data = array(
      'title'         => "Laporan Cuti Pegawai",
      'body'          => 'Laporan/Cuti/index',
      'unit'   => $this->ModelUnit->get_unit()->result(),
    );
    $this->load->view('index', $data);
  }

  function tabelCuti()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $cuti = $this->ModelPerizinan->get_riwayatMonitoring($unit, null, $tgl_mulai, $tgl_akhir, $sub_unit);
    $data = array(
      'cuti'          => $cuti,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
    );
    $this->load->view('Laporan/Cuti/tabel', $data);
  }

  function hapus_cuti()
  {
    $idizin = $this->input->post("idizin");
    $this->db->where("idizin", $idizin);
    if ($this->db->delete("izin")) {
      echo "berhasil";
    } else {
      echo "gagal";
    }
  }

  function RekapitulasiPresensi()
  {
    $data = array(
      'title'         => "Laporan Rekapitulasi Presensi",
      'body'          => 'Laporan/RekapitulasiPresensi/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
      'tipe'          => $this->ModelPegawai->tipe_pegawai()->result(),
      'jabatan'       => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }

  function tabelRekapitulasiPresensi()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $tipe_pegawai  = $this->input->post("tipe_pegawai");
    $jabatan  = $this->input->post("jabatan");
    $pegawai   = $this->ModelPegawai->get_UnitPegawai($unit, $sub_unit, $tipe_pegawai, $jabatan);
    if ($unit == "") {
      $unit = "Semua Unit";
    }
    $data = array(
      'unit'          => $unit,
      'pegawai'       => $pegawai,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
    );
    $this->load->view('Laporan/RekapitulasiPresensi/tabel', $data);
  }

  function TotalPresensi()
  {
    $data = array(
      'title'         => "Laporan Total Presensi",
      'body'          => 'Laporan/TotalPresensi/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
      'tipe'          => $this->ModelPegawai->tipe_pegawai()->result(),
      'jabatan'       => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }

  function tabelTotalPresensi()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $tipe_pegawai  = $this->input->post("tipe_pegawai");
    $jabatan  = $this->input->post("jabatan");
    $pegawai   = $this->ModelPegawai->get_TotalPegawai($unit, $sub_unit, $tipe_pegawai, $jabatan);
    if ($unit == "") {
      $unit = "Semua Unit";
    }
    $data = array(
      'unit'          => $unit,
      'tipe_pegawai'  => $tipe_pegawai,
      'pegawai'       => $pegawai,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
    );
    $this->load->view('Laporan/TotalPresensi/tabel', $data);
  }

  function TotalPresensiDispensasi()
  {
    $data = array(
      'title'         => "Laporan Total Presensi Dispensasi",
      'body'          => 'Laporan/TotalPresensiDispensasi/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
      'tipe'          => $this->ModelPegawai->tipe_pegawai()->result(),
      'jabatan'       => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }

  function tabelTotalPresensiDispensasi()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    // $tgl_mulai = "2021-10-01";
    // $tgl_akhir = "2021-10-31";
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $tipe_pegawai  = $this->input->post("tipe_pegawai");
    $jabatan  = $this->input->post("jabatan");
    $pegawai   = $this->ModelPegawai->get_TotalPegawai($unit, $sub_unit, $tipe_pegawai, $jabatan);
    if ($unit == "") {
      $unit = "Semua Unit";
    }
    $data = array(
      'unit'          => $unit,
      'tipe_pegawai'  => $tipe_pegawai,
      'pegawai'       => $pegawai,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
    );
    $this->load->view('Laporan/TotalPresensiDispensasi/tabel', $data);
  }

  function LaporanDinasLuar()
  {
    $data = array(
      'title'         => "Laporan Dinas Luar",
      'body'          => 'Laporan/LaporanDinasLuar/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
      'tipe'          => $this->ModelPegawai->tipe_pegawai()->result(),
      'jabatan'       => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }

  function tabelLaporanDinasLuar()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));

    // $tgl_mulai = "2021-10-01";
    // $tgl_akhir = "2021-10-31";
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $tipe_pegawai  = $this->input->post("tipe_pegawai");
    $jabatan  = $this->input->post("jabatan");
    $pegawai   = $this->ModelPegawai->get_TotalPegawai($unit, $sub_unit, $tipe_pegawai, $jabatan);
    if ($unit == "") {
      $unit = "Semua Unit";
    }
    $data = array(
      'unit'          => $unit,
      'tipe_pegawai'  => $tipe_pegawai,
      'pegawai'       => $pegawai,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
    );
    $this->load->view('Laporan/LaporanDinasLuar/tabel', $data);
  }

  function coba()
  {
    $data = $this->db->get("pegawai")->result();
    $array = array();
    foreach ($data as $value) {
      if (!array_key_exists($value->tipe_pegawai, $array)) {
        array_push($array, $value->tipe_pegawai);
      };
    }
    echo json_encode($array);
  }

  function DetailRekap($uuid)
  {
    $data = array(
      'title'         => "Laporan Detail Rekapitulasi Presensi",
      'body'          => 'Laporan/RekapitulasiPresensi/detail',
      'pegawai'       => $this->ModelPegawai->edit($uuid)->row_array(),
    );
    $this->load->view('index', $data);
  }

  function tabelDetailRekap()
  {
    $tgl_mulai  = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir  = date("Y-m-d", strtotime($this->input->post("end")));
    // $tgl_mulai  = "2021-11-01";
    // $tgl_akhir  = "2021-11-30";
    $uuid       = $this->input->post("uuid");
    // $uuid       = "1f92b01f-00eb-11eb-ab7b-fefcfe8d8c7c";
    $this->load->model("ModelLembur");
    $data = array(
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
      'pegawai'       => $this->ModelPegawai->edit($uuid)->row_array(),
      'presensi'      => $this->ModelRiwayat->RiwayatHarian($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
      'kegiatan'      => $this->ModelLaporan->rekapKegiatan($uuid, $tgl_mulai, $tgl_akhir)->result(),
      'luar_jam'      => $this->ModelLaporan->rekapPresensiLuarJam($uuid, $tgl_mulai, $tgl_akhir)->result(),
      'cuti'          => $this->ModelPerizinan->get_riwayat($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
      'lembur'        => $this->ModelLembur->riwayat_lembur($uuid, null, $tgl_mulai, $tgl_akhir)->result()
    );
    $this->load->view('Laporan/RekapitulasiPresensi/detail_tabel', $data);
  }

  function exportDetailRekapPdf()
  {
    $tgl_mulai  = date("Y-m-d", strtotime($this->input->get("start")));
    $tgl_akhir  = date("Y-m-d", strtotime($this->input->get("end")));
    $uuid       = $this->input->get("uuid");

    $this->load->model("ModelLembur");

    $data = array(
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
      'pegawai'       => $this->ModelPegawai->edit($uuid)->row_array(),
      'presensi'      => $this->ModelRiwayat->RiwayatHarian($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
      'kegiatan'      => $this->ModelLaporan->rekapKegiatan($uuid, $tgl_mulai, $tgl_akhir)->result(),
      'luar_jam'      => $this->ModelLaporan->rekapPresensiLuarJam($uuid, $tgl_mulai, $tgl_akhir)->result(),
      'cuti'          => $this->ModelPerizinan->get_riwayat($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
      'lembur'        => $this->ModelLembur->riwayat_lembur($uuid, null, $tgl_mulai, $tgl_akhir)->result()
    );

    $this->load->view('Laporan/RekapitulasiPresensi/export_detail_pdf', $data);
  }

  function exportDetailRekapExcel()
  {
    $tgl_mulai  = date("Y-m-d", strtotime($this->input->get("start")));
    $tgl_akhir  = date("Y-m-d", strtotime($this->input->get("end")));
    $uuid       = $this->input->get("uuid");

    $this->load->model("ModelLembur");

    $pegawai = $this->ModelPegawai->edit($uuid)->row_array();

    // Build filename
    $nama_file = "Detail_Rekap_Presensi_" . str_replace(' ', '_', $pegawai['nama_pegawai'])
      . "_" . date("dMY", strtotime($tgl_mulai))
      . "_sd_" . date("dMY", strtotime($tgl_akhir));

    // Prepare data
    $data = array(
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
      'pegawai'       => $pegawai,
      'presensi'      => $this->ModelRiwayat->RiwayatHarian($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
      'kegiatan'      => $this->ModelLaporan->rekapKegiatan($uuid, $tgl_mulai, $tgl_akhir)->result(),
      'luar_jam'      => $this->ModelLaporan->rekapPresensiLuarJam($uuid, $tgl_mulai, $tgl_akhir)->result(),
      'cuti'          => $this->ModelPerizinan->get_riwayat($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
      'lembur'        => $this->ModelLembur->riwayat_lembur($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
      'nama_file'     => $nama_file
    );

    // Set headers SEBELUM output apapun
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"{$nama_file}.xls\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    $this->load->view('Laporan/RekapitulasiPresensi/export_detail_excel', $data);
  }

  function LaporanKejanggalanPresensi()
  {
    $data = array(
      'title'         => "Laporan Kejanggalan Presensi",
      'body'          => 'Laporan/KejanggalanPresensi/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
      'tipe'          => $this->ModelPegawai->tipe_pegawai()->result(),
      'jabatan'       => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }

  function tabelLaporanKejanggalanPresensi()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $tipe_pegawai  = $this->input->post("tipe_pegawai");
    $jabatan  = $this->input->post("jabatan");

    $pegawai   = $this->ModelPegawai->get_UnitPegawai($unit, $sub_unit, $tipe_pegawai, $jabatan)->result();
    if ($unit == "") {
      $unit = "Semua Unit";
    }
    $data = array(
      'unit'          => $unit,
      'pegawai'       => $pegawai,
      'tipe_pegawai'  => $tipe_pegawai,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
    );
    $this->load->view('Laporan/KejanggalanPresensi/tabel', $data);
    // $tgl_mulai  = date("Y-m-d", strtotime($this->input->post("start")));
    // $tgl_akhir  = date("Y-m-d", strtotime($this->input->post("end")));
    // $uuid       = $this->input->post("uuid");
    // $data = array(
    //   'tgl_mulai'     => $tgl_mulai,
    //   'tgl_akhir'     => $tgl_akhir,
    //   'presensi'      => $this->ModelRiwayat->RiwayatHarian($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
    //   'kegiatan'      => $this->ModelLaporan->rekapKegiatan($uuid, $tgl_mulai, $tgl_akhir)->result(),
    //   'luar_jam'      => $this->ModelLaporan->rekapPresensiLuarJam($uuid, $tgl_mulai, $tgl_akhir)->result(),
    //   'cuti'          => $this->ModelPerizinan->get_riwayat($uuid, null, $tgl_mulai, $tgl_akhir)->result()
    //  );
    // $this->load->view('Laporan/RekapitulasiPresensi/detail_tabel', $data);
  }

  function LaporanDiluarJam()
  {
    $data = array(
      'title'         => "Laporan Presensi Diluar Jam Kerja",
      'body'          => 'Laporan/DiluarJamKerja/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
      'tipe'          => $this->ModelPegawai->tipe_pegawai()->result(),
      'jabatan'       => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }


  function tabelLaporanDiluarJam()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $tipe_pegawai  = $this->input->post("tipe_pegawai");
    $jabatan  = $this->input->post("jabatan");

    $pegawai   = $this->ModelPegawai->get_UnitPegawai($unit, $sub_unit, $tipe_pegawai, $jabatan)->result();
    if ($unit == "") {
      $unit = "Semua Unit";
    }
    $data = array(
      'unit'          => $unit,
      'pegawai'       => $pegawai,
      'tipe_pegawai'  => $tipe_pegawai,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
    );
    $this->load->view('Laporan/DiluarJamKerja/tabel', $data);
  }

  function LaporanJadwalWF()
  {
    $data = array(
      'title'         => "Laporan Jadwal Kerja Pegawai",
      'body'          => 'Laporan/JadwalWF/index',
      'unit'          => $this->ModelUnit->get_unit()->result(),
    );
    $this->load->view('index', $data);
  }

  function CalendarJadwalWF()
  {
    $uuid = $this->input->post("pegawai");
    $data = array(
      'jadwal'        => $this->ModelJadwalWF->getJadwal($uuid)
    );
    $this->load->view('Laporan/JadwalWF/Kalender', $data);
  }

  function getPegawai()
  {
    $unit      = $this->input->post("unit");
    $pegawai   = $this->ModelPegawai->get_UnitPegawai($unit);
    $html = "";
    foreach ($pegawai->result() as $value) {
      $html .= '<option value="' . $value->uuid . '">' . $value->nama_pegawai . '</option>';
    }
    echo $html;
  }

  function LaporanLembur()
  {
    $data = array(
      'title'         => "Laporan Presensi Diluar Jam Kerja",
      'body'          => 'Laporan/Lembur/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
      'tipe'          => $this->ModelPegawai->tipe_pegawai()->result(),
      'jabatan'       => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }


  function tabelLemburs()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $tipe_pegawai  = $this->input->post("tipe_pegawai");
    $jabatan  = $this->input->post("jabatan");

    $pegawai   = $this->ModelPegawai->get_UnitPegawai($unit, $sub_unit, $tipe_pegawai, $jabatan)->result();
    if ($unit == "") {
      $unit = "Semua Unit";
    }
    $data = array(
      'unit'          => $unit,
      'pegawai'       => $pegawai,
      'tipe_pegawai'  => $tipe_pegawai,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
    );
    $this->load->view('Laporan/Lembur/tabel', $data);
  }

  function rekapitulasi_cuti()
  {
    $array = array(
      'title' => "Rekapitulasi Cuti Pegawai",
      'body'  => "Laporan/Cuti/RekapitulasiCuti",
      'unit'  => $this->ModelUnit->get_parent_unit()->result(),
    );
    $this->load->view('index', $array);
  }

  function data_rekapitulasi_cuti()
  {
    $tgl_mulai = $this->input->post("start");
    $tgl_akhir = $this->input->post("end");
    $unit = $this->input->post("unit");
    $sub_unit = $this->input->post("sub_unit");
    $status = $this->input->post("status");

    // Ambil data cuti
    $cuti_data = $this->ModelPerizinan->get_riwayatMonitoring($unit, $status, $tgl_mulai, $tgl_akhir, $sub_unit);

    $rekapitulasi = array();
    $pegawai_cuti = array();

    foreach ($cuti_data->result() as $value) {
      $nip = $value->NIP ?? $value->NIK ?? "-";

      if (!isset($pegawai_cuti[$nip])) {
        $pegawai_cuti[$nip] = array(
          'nip' => $nip,
          'nama' => $value->nama_pegawai,
          'unit' => $value->unit ?? "-",
          'cuti_tahunan' => $value->total_cuti ?? 0,
          'total_cuti' => 0,
          'cuti_disetujui' => 0,
          'cuti_pending' => 0,
          'cuti_ditolak' => 0,
          'detail' => array()
        );
      }

      $pegawai_cuti[$nip]['total_cuti']++;

      if ($value->status == "1") {
        $pegawai_cuti[$nip]['cuti_disetujui']++;
      } elseif ($value->status == "0") {
        $pegawai_cuti[$nip]['cuti_pending']++;
      } elseif ($value->status == "2") {
        $pegawai_cuti[$nip]['cuti_ditolak']++;
      }

      // Hitung durasi cuti
      $tanggal_mulai = strtotime($value->tanggal_mulai);
      $tanggal_akhir = strtotime($value->tanggal_akhir);
      $durasi = ceil(($tanggal_akhir - $tanggal_mulai) / (60 * 60 * 24)) + 1;

      $pegawai_cuti[$nip]['detail'][] = array(
        'jenis' => $value->jenis_izin ?? "Cuti",
        'tanggal_mulai' => date("d-m-Y", strtotime($value->tanggal_mulai)),
        'tanggal_selesai' => date("d-m-Y", strtotime($value->tanggal_akhir)),
        'durasi' => $durasi . " hari",
        'status' => $value->status,
        'keterangan' => $value->keterangan ?? "-"
      );
    }

    // Convert ke array biasa
    foreach ($pegawai_cuti as $data) {
      $rekapitulasi[] = $data;
    }

    echo json_encode($rekapitulasi);
  }

  function export_rekapitulasi_cuti()
  {
    $tgl_mulai = $this->input->get("start");
    $tgl_akhir = $this->input->get("end");
    $unit = $this->input->get("unit");
    $sub_unit = $this->input->get("sub_unit");
    $status = $this->input->get("status");

    // Ambil data cuti
    $cuti_data = $this->ModelPerizinan->get_riwayatMonitoring($unit, $status, $tgl_mulai, $tgl_akhir, $sub_unit);

    $data = array(
      'title' => 'Rekapitulasi Cuti Pegawai',
      'periode' => date("d-m-Y", strtotime($tgl_mulai)) . ' s/d ' . date("d-m-Y", strtotime($tgl_akhir)),
      'unit' => $unit ?? 'Semua Unit',
      'data' => $cuti_data->result()
    );

    $this->load->view('Laporan/Cuti/ExportRekapitulasiCuti', $data);
  }

  function RekapitulasiLembur()
  {
    $data = array(
      'title'         => "Laporan Rekapitulasi Lembur Pegawai",
      'body'          => 'Laporan/RekapitulasiLembur/index',
      'unit'          => $this->ModelUnit->get_parent_unit()->result(),
      'tipe'          => $this->ModelPegawai->tipe_pegawai()->result(),
      'jabatan'       => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }

  function tabelRekapitulasiLembur()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit      = $this->input->post("unit");
    $sub_unit  = $this->input->post("sub_unit");
    $tipe_pegawai  = $this->input->post("tipe_pegawai");
    $jabatan  = $this->input->post("jabatan");
    $status_approval  = $this->input->post("status_approval");

    $pegawai   = $this->ModelPegawai->get_UnitPegawai($unit, $sub_unit, $tipe_pegawai, $jabatan);
    if ($unit == "") {
      $unit = "Semua Unit";
    }
    $data = array(
      'unit'          => $unit,
      'pegawai'       => $pegawai,
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
      'status_approval' => $status_approval,
    );
    $this->load->view('Laporan/RekapitulasiLembur/tabel', $data);
  }

  function DetailLembur($uuid)
  {
    $data = array(
      'title'         => "Detail Laporan Lembur Pegawai",
      'body'          => 'Laporan/RekapitulasiLembur/detail',
      'pegawai'       => $this->ModelPegawai->edit($uuid)->row_array(),
    );
    $this->load->view('index', $data);
  }

  function tabelDetailLembur()
  {
    $tgl_mulai  = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir  = date("Y-m-d", strtotime($this->input->post("end")));
    $uuid       = $this->input->post("uuid");
    $this->load->model("ModelLembur");
    $data = array(
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
      'pegawai'       => $this->ModelPegawai->edit($uuid)->row_array(),
      'lembur'        => $this->ModelLembur->riwayat_lembur($uuid, null, $tgl_mulai, $tgl_akhir)->result()
    );
    $this->load->view('Laporan/RekapitulasiLembur/detail_tabel', $data);
  }
}
