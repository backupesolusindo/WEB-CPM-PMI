<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KategoriMenu extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('ModelKategoriMenu');
  }

  function index()
  {
    $data = array(
      'title'                => "KategoriMenu",
      'body'                 => 'KategoriMenu/list',
      'kategori_menu'         => $this->ModelKategoriMenu->list()->result(),
     );
    $this->load->view('index', $data);
  }

  function input()
  {
    $array = array(
      'title'       => "KategoriMenu",
      'body'        => "KategoriMenu/input",
    );
    $this->load->view('index', $array);
  }

  public function insert()
  {
    $data = array(
      'idkategori_menu'       => $this->input->post('idkategori_menu'),
      'nama_kategori'     => $this->input->post('nama_kategori')
    );
    $insert = $this->db->insert('kategori_menu', $data);
    if ($insert) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Menambahkan Kategori Menu Baru."));
    }else {
      if ($this->ModelBahanBaku->edit($this->input->post('idkategori_menu'))->num_rows() > 0) {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Berhasil Menambahkan Data Kategori Menu Baru"));
      }else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Maaf Gagal Menambahkan Data Kategori Menu Baru"));
      }
    }
    redirect("KategoriMenu");
  }

  function edit($id)
  {
    $array = array(
      'title'                 => "KategoriMenu",
      'body'                  => "KategoriMenu/update",
      'kategori_menu'         => $this->ModelKategoriMenu->edit($id)->row_array()
    );
    $this->load->view('index', $array);
  }

  function update()
  {
    $id = $this->input->post('idkategori_menu');
    $data = array(
      'idkategori_menu'       => $this->input->post('idkategori_menu'),
      'nama_kategori'         => $this->input->post('nama_kategori')
    );
    $this->db->where('idkategori_menu', $id);
    if ($this->db->update('kategori_menu', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Merubah Data Kategori Menu Baru"));
    }else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Maaf Gagal Merubah Data Kategori Menu Baru"));
    }
    redirect("KategoriMenu");
  }

  function hapus($id)
  {
    $this->db->where('idkategori_menu', $id);
    if ($this->db->delete('kategori_menu')) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Menghapus Kategori Menu."));
    }else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Menghapus Data Kategori Menu, Karena Tercatat di Transaksi"));
    }
    redirect('KategoriMenu');
  }
}
