<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller{

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
  }

  function sub_unit()
  {
    $parent = $this->input->post("unit");
    $html = "<option value=''>Semua Sub Unit</option>";
    foreach ($this->ModelUnit->get_sub_unit($parent)->result() as $value) {
      $html .= '<option value="'.$value->nama_unit.'">'.$value->nama_unit.'</option>';
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
      'absensi_pulang'=> $absenpulang,
      'istirahat'     => $istirahat,
      'selesaiIstirahat'=> $selesaiIstirahat,
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
    $data = array(
      'presensi'        => $absenharian,
      'tgl_mulai'       => $tgl_mulai,
      'tgl_akhir'       => $tgl_akhir,
      'status_filter'       => $status_filter,
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
    $data = array(
      'title'         => "Detail Laporan Kegiatan",
      'body'          => 'Laporan/Kegiatan/detail',
      'kegiatan'      => $this->ModelKegiatan->get_data($idkegiatan)->row_array(),
      'peserta'       => $this->ModelKegiatan->getPesertaKegiatan($idkegiatan),
     );
    $this->load->view('index', $data);
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
    }else {
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
    $data = array(
      'tgl_mulai'     => $tgl_mulai,
      'tgl_akhir'     => $tgl_akhir,
      'pegawai'       => $this->ModelPegawai->edit($uuid)->row_array(),
      'presensi'      => $this->ModelRiwayat->RiwayatHarian($uuid, null, $tgl_mulai, $tgl_akhir)->result(),
      'kegiatan'      => $this->ModelLaporan->rekapKegiatan($uuid, $tgl_mulai, $tgl_akhir)->result(),
      'luar_jam'      => $this->ModelLaporan->rekapPresensiLuarJam($uuid, $tgl_mulai, $tgl_akhir)->result(),
      'cuti'          => $this->ModelPerizinan->get_riwayat($uuid, null, $tgl_mulai, $tgl_akhir)->result()
     );
    $this->load->view('Laporan/RekapitulasiPresensi/detail_tabel', $data);
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
      $html .= '<option value="'.$value->uuid.'">'.$value->nama_pegawai.'</option>';
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

}
