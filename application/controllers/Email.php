<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'third_party/phpmailer/PHPMailerAutoload.php');

class Email extends CI_Controller{

    function  __construct(){
        parent::__construct();
    }

    public function index()
    {
      $data['isi'] = '<h1>Laporan Penjualan Hari ini '.NamaWarung().'</h1>
      <a href="http://caksisrm.onklinik.net/Laporan/print_penjualan">
        Download Laporan
      </a>';
      $data['subjek'] = "Kirim Email Laporan Penjualan";
      $data['penerima'] = 'andissetya@gmail.com';
      $this->mail($data);
    }

    public function mail($data)
    {

      $mail = new PHPMailer;
      // $mail->isSMTP();
      $mail->Host = 'localhost';
      // $mail->Host = 'smtp.gmail.com';
      $mail->Port = 465;
      $mail->SMTPAuth=true;
      $mail->SMTPSecure='ssl';


      $mail->SMTPOptions = array(
          'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
          )
      );

      $mail->Username = 'grassetya@gmail.com';
      $mail->Password = 'gucialit';

      $mail->setFrom('grassetya@gmail.com', 'Cak SIS SIM RM');
      $mail->addAddress($data['penerima']);
      $mail->addReplyTo('grassetya@gmail.com');

      $mail->isHTML(true);
      $mail->Subject=$data['subjek'];
      $mail->Body=$data['isi'];

      if (!$mail->send()) {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("GAGAL Mengirim Cek Koneksi Anda."));
      }else{
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Berhasi Mengirim Email, Tunggu Beberapa Menit."));
      }
      redirect("Laporan/penjualan");
    }

}
