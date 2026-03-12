<ul id="sidebarnav">
  <!-- <li>
        <div class="hide-menu text-center">
            <div id="eco-spark"></div>
            <small>TOTAL EARNINGS - JUNE 2018</small>
            <h4>$2,478.00</h4>
        </div>
    </li> -->

  <?php
  $this->load->model('ModelRole');
  $user_Roles = $this->db->get_where('user', array('id_user' => $_SESSION['id_login'],))->row_array();
  $roles_Roles = explode(', ', $user_Roles['roles']);
  foreach ($roles_Roles as $value) {
    $Menu_Roles[$value] = true;
    $this->db->reset_query();
    $this->db->select('group_roles_idgroup_roles');
    $this->db->group_by('group_roles_idgroup_roles');
    $Group_Roles = $this->db->get_where('roles', array('roles' => $value,))->result();
    foreach ($Group_Roles as $value) {
      $Menu_Group[$value->group_roles_idgroup_roles] = true;
    }
  }
  ?>

  <li class="nav-small-cap">--- DASHBOARD</li>
  <li <?php if ($this->uri->segment(1) == ''): ?>
    class="active"
    <?php endif; ?>>
    <a href="<?php echo base_url() . ''; ?>">
      <i class="fas fa-user-tie"></i><span class="hide-menu">Dashboard</span></a>
    <!-- <span class="inbox-num">3</span> -->
    <?php if (['Dashboard']): ?>
  <li <?php if ($this->uri->segment(1) == 'Dashboard' && $this->uri->segment(2) == 'kalender'): ?>
    class="active"
    <?php endif; ?>>
    <a href="<?php echo base_url() . 'Dashboard/kalender'; ?>">
      <i class="fas fa-calendar-alt"></i><span class="hide-menu">Dashboard Kalender</span></a>
  </li>
  <li <?php if ($this->uri->segment(1) == 'Dashboard'): ?>
    class="active"
    <?php endif; ?>>
    <a href="<?php echo base_url() . 'Pekerjaan/Dashboard'; ?>">
      <i class="fas fa-user-tie"></i><span class="hide-menu">Dashboard CPM</span></a>
  </li>
<?php endif; ?>
</li>


<?php if (@$Menu_Group['presensi']): ?>
  <li class="nav-small-cap">--- PRESENSI</li>

  <?php if (@$Menu_Roles['Kegiatan']): ?>
    <li <?php if ($this->uri->segment(1) == 'Kegiatan'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Kegiatan'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Kegiatan</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['TugasBelajar']): ?>
    <li <?php if ($this->uri->segment(1) == 'TugasBelajar'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'TugasBelajar'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Tugas Belajar</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['DinasLuar']): ?>
    <li <?php if ($this->uri->segment(1) == 'DinasLuar'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'DinasLuar'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">DinasLuar</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
    <li <?php if ($this->uri->segment(1) == 'CutiPegawai'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'CutiPegawai'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Cuti Pegawai</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['Lembur']): ?>
    <li <?php if ($this->uri->segment(1) == 'Lembur'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Lembur'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Jadwal Lembur</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
<?php endif; ?>

<?php if (@$Menu_Group['laporan']): ?>
  <li class="nav-small-cap">--- LAPORAN PRESENSI</li>
  <?php if (@$Menu_Roles['LaporanKegiatan']): ?>
    <li <?php if ($this->uri->segment(2) == 'LaporanKegiatan'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/LaporanKegiatan'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Laporan Kegiatan</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['LaporanCuti']): ?>
    <li <?php if ($this->uri->segment(2) == 'LaporanCuti'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/LaporanCuti'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Laporan Cuti</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['LaporanCuti']): ?>
    <li <?php if ($this->uri->segment(2) == 'rekapitulasi_cuti'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/rekapitulasi_cuti'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Rekapitulasi Cuti</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['LaporanDinasLuar']): ?>
    <li <?php if ($this->uri->segment(2) == 'LaporanDinasLuar'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/LaporanDinasLuar'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Laporan Dinas Luar</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['LaporanDiluarJam']): ?>
    <li <?php if ($this->uri->segment(2) == 'LaporanDiluarJam'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/LaporanDiluarJam'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Laporan Diluar Jam</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['LaporanPresensi']): ?>
    <li <?php if ($this->uri->segment(2) == 'LaporanPresensi'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/LaporanPresensi'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Laporan Presensi</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['LaporanTidakPresensi']): ?>
    <li <?php if ($this->uri->segment(1) == 'LaporanTidakPresensi'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'LaporanTidakPresensi'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Laporan Tidak Presensi</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['RekapitulasiPresensi']): ?>
    <li <?php if ($this->uri->segment(2) == 'RekapitulasiPresensi'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/RekapitulasiPresensi'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Rekapitulasi Presensi</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
    <li <?php if ($this->uri->segment(2) == 'TotalPresensi'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/TotalPresensi'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Total Presensi</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
    <li <?php if ($this->uri->segment(2) == 'TotalPresensiDispensasi'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/TotalPresensiDispensasi'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Total Presensi Dispensasi</span></a>
    </li>
    <li <?php if ($this->uri->segment(2) == 'LaporanKejanggalanPresensi'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/LaporanKejanggalanPresensi'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Laporan Kejanggalan Presensi</span></a>
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['LaporanJadwalWF']): ?>
    <li <?php if ($this->uri->segment(2) == 'LaporanJadwalWF'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Laporan/LaporanJadwalWF'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Laporan Jadwal Kerja</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
<?php endif; ?>

<?php if (@$Menu_Group['kepegawaian']): ?>
  <li class="nav-small-cap">--- KEPEGAWAIAN</li>

  <?php if (@$Menu_Roles['Pegawai']): ?>
    <li <?php if ($this->uri->segment(1) == 'Pegawai'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Pegawai'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Pegawai</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['Monitoring']): ?>
    <li <?php if ($this->uri->segment(1) == 'Monitoring'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Monitoring'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Monitoring Pegawai</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
  <?php if (@$Menu_Roles['JadwalLokasi']): ?>
    <li <?php if ($this->uri->segment(1) == 'JadwalLokasi'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'JadwalLokasi'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Jadwal Lokasi</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>

  <?php if (@$Menu_Roles['Jabatan']): ?>
    <li <?php if ($this->uri->segment(1) == 'Jabatan'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Jabatan'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Jabatan</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
<?php endif; ?>

<?php if (@$Menu_Group['DM']): ?>
  <li class="nav-small-cap">--- DATA MASTER</li>

  <?php if (@$Menu_Roles['Unit']): ?>
    <li <?php if ($this->uri->segment(1) == 'Unit'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Unit'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Unit</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>


  <?php if (@$Menu_Roles['Libur']): ?>
    <li <?php if ($this->uri->segment(1) == 'Libur'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Libur'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Libur</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>

  <?php if (@$Menu_Roles['Kampus']): ?>
    <li <?php if ($this->uri->segment(1) == 'Kampus'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Kampus'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Kantor</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>

  <?php if (@$Menu_Roles['Gedung']): ?>
    <li <?php if ($this->uri->segment(1) == 'Gedung'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Gedung'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Gedung</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>

  <?php if (@$Menu_Roles['JenisPerizinan']): ?>
    <li <?php if ($this->uri->segment(1) == 'JenisPerizinan'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'JenisPerizinan'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Jenis Perizinan</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>

  <?php if (@$Menu_Roles['JadwalMasuk']): ?>
    <li <?php if ($this->uri->segment(1) == 'JadwalMasuk'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'JadwalMasuk'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Jadwal Masuk</span></a>
      <!-- <span class="inbox-num">3</span> -->
    </li>
  <?php endif; ?>
<?php endif; ?>

<?php if (@$Menu_Group['access']): ?>
  <li class="nav-small-cap">--- USER ACCESS</li>

  <li>
    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
      <i class="fas fa-sitemap"></i>
      <span class="hide-menu">Access </span></a>
    <ul aria-expanded="false" class="collapse">


      <?php if (@$Menu_Roles['User']): ?>
        <li <?php if ($this->uri->segment(1) == 'User'): ?>
          class="active"
          <?php endif; ?>>
          <a href="<?php echo base_url() . 'User'; ?>">
            User</a>
          <!-- <span class="inbox-num">3</span> -->
        </li>
      <?php endif; ?>
      <?php if (@$Menu_Roles['Roles']): ?>
        <li <?php if ($this->uri->segment(1) == 'Roles'): ?>
          class="active"
          <?php endif; ?>>
          <a href="<?php echo base_url() . 'Roles'; ?>">
            Roles</a>
          <!-- <span class="inbox-num">3</span> -->
        </li>
      <?php endif; ?>
      <?php if (@$Menu_Roles['GroupRole']): ?>
        <li <?php if ($this->uri->segment(1) == 'GroupRole'): ?>
          class="active"
          <?php endif; ?>>
          <a href="<?php echo base_url() . 'GroupRole'; ?>">
            Group Roles</a>
          <!-- <span class="inbox-num">3</span> -->
        </li>
      <?php endif; ?>
    </ul>
  </li>
<?php endif; ?>


<?php if (['Pekerjaan']): ?>
  <li class="nav-small-cap">--- PEKERJAAN</li>

  <?php if (['ListPekerjaan']): ?>
    <li <?php if ($this->uri->segment(1) == 'ListPekerjaan'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Pekerjaan/ListPekerjaan'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">List Pekerjaan</span></a>
    </li>
  <?php endif; ?>
  <?php if (['RiwayatPekerjaan']): ?>
    <li <?php if ($this->uri->segment(1) == 'RiwayatPekerjaan'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Pekerjaan/RiwayatPekerjaan'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Riwayat Pekerjaan</span></a>
    </li>
  <?php endif; ?>
  <?php if (['RekapPekerjaan']): ?>
    <li <?php if ($this->uri->segment(1) == 'RekapPekerjaan'): ?>
      class="active"
      <?php endif; ?>>
      <a href="<?php echo base_url() . 'Pekerjaan/RekapPekerjaan'; ?>">
        <i class="fas fa-user-tie"></i><span class="hide-menu">Rekap Pekerjaan</span></a>
    </li>
  <?php endif; ?>

<?php endif; ?>