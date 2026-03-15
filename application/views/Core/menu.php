<ul id="sidebarnav">
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

  <!-- DASHBOARD -->
  <li class="nav-small-cap">--- DASHBOARD</li>
  <li <?php if ($this->uri->segment(1) == ''): ?>class="active" <?php endif; ?>>
    <a href="<?php echo base_url(); ?>">
      <i class="fas fa-home"></i><span class="hide-menu">Dashboard Utama</span>
    </a>
  </li>
  <?php if (['Dashboard']): ?>
    <li>
      <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
        <i class="fas fa-tachometer-alt"></i>
        <span class="hide-menu">Dashboard Lainnya</span>
      </a>
      <ul aria-expanded="false" class="collapse">
        <li <?php if ($this->uri->segment(1) == 'Dashboard' && $this->uri->segment(2) == 'kalender'): ?>class="active" <?php endif; ?>>
          <a href="<?php echo base_url() . 'Dashboard/kalender'; ?>">Dashboard Kalender</a>
        </li>
        <li <?php if ($this->uri->segment(1) == 'Pekerjaan' && $this->uri->segment(2) == 'Dashboard'): ?>class="active" <?php endif; ?>>
          <a href="<?php echo base_url() . 'Pekerjaan/Dashboard'; ?>">Dashboard CPM</a>
        </li>
        <li <?php if ($this->uri->segment(1) == 'Kpi' && $this->uri->segment(2) == ''): ?>class="active" <?php endif; ?>>
          <a href="<?php echo base_url() . 'Kpi'; ?>">Dashboard KPI</a>
        </li>
      </ul>
    </li>
  <?php endif; ?>
  <!-- PRESENSI -->
  <?php if (@$Menu_Group['presensi']): ?>
    <li class="nav-small-cap">--- PRESENSI</li>
    <?php if (@$Menu_Roles['Kegiatan']): ?>
      <li <?php if ($this->uri->segment(1) == 'Kegiatan'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'Kegiatan'; ?>">
          <i class="fas fa-clipboard-list"></i><span class="hide-menu">Kegiatan</span>
        </a>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['Lembur']): ?>
      <li <?php if ($this->uri->segment(1) == 'Lembur'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'Lembur'; ?>">
          <i class="fas fa-clock"></i><span class="hide-menu">Jadwal Lembur</span>
        </a>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['TugasBelajar']): ?>
      <li <?php if ($this->uri->segment(1) == 'TugasBelajar'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'TugasBelajar'; ?>">
          <i class="fas fa-graduation-cap"></i><span class="hide-menu">Tugas Belajar</span>
        </a>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['DinasLuar']): ?>
      <li <?php if ($this->uri->segment(1) == 'DinasLuar'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'DinasLuar'; ?>">
          <i class="fas fa-briefcase"></i><span class="hide-menu">Dinas Luar</span>
        </a>
      </li>
      <li <?php if ($this->uri->segment(1) == 'CutiPegawai'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'CutiPegawai'; ?>">
          <i class="fas fa-umbrella-beach"></i><span class="hide-menu">Cuti Pegawai</span>
        </a>
      </li>
      <li <?php if ($this->uri->segment(1) == 'CutiTahunan'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'CutiTahunan'; ?>">
          <i class="fas fa-umbrella-beach"></i><span class="hide-menu">Cuti Tahunan</span>
        </a>
      </li>
    <?php endif; ?>
  <?php endif; ?>

  <!-- PEKERJAAN -->
  <?php if (['Pekerjaan']): ?>
    <li class="nav-small-cap">--- PEKERJAAN</li>
    <li>
      <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
        <i class="fas fa-briefcase"></i>
        <span class="hide-menu">Pekerjaan</span>
      </a>
      <ul aria-expanded="false" class="collapse">
        <?php if (['ListPekerjaan']): ?>
          <li <?php if ($this->uri->segment(1) == 'ListPekerjaan'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Pekerjaan/ListPekerjaan'; ?>">List Pekerjaan</a>
          </li>
        <?php endif; ?>
        <?php if (['RiwayatPekerjaan']): ?>
          <li <?php if ($this->uri->segment(1) == 'RiwayatPekerjaan'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Pekerjaan/RiwayatPekerjaan'; ?>">Riwayat Pekerjaan</a>
          </li>
        <?php endif; ?>
        <?php if (['RekapPekerjaan']): ?>
          <li <?php if ($this->uri->segment(1) == 'RekapPekerjaan'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Pekerjaan/RekapPekerjaan'; ?>">Rekap Pekerjaan</a>
          </li>
        <?php endif; ?>
      </ul>
    </li>
    <?php if (@$Menu_Roles['KPI'] || @$Menu_Group['kpi']): ?>
      <li>
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
          <i class="fas fa-chart-line"></i>
          <span class="hide-menu">Key Performance Indicator</span>
        </a>
        <ul aria-expanded="false" class="collapse">
          <li <?php if ($this->uri->segment(1) == 'Kpi' && $this->uri->segment(2) == 'detail' && $this->uri->segment(3) == $_SESSION['id_login']): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Kpi/detail/' . $_SESSION['id_login']; ?>">KPI Pegawai</a>
          </li>
          <li <?php if ($this->uri->segment(1) == 'Kpi' && $this->uri->segment(2) == 'pengaturan_bobot'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Kpi/pengaturan_bobot'; ?>">Pengaturan Bobot</a>
          </li>
        </ul>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['Pengembangan_Diri']): ?>
      <li>
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
          <i class="fas fa-chart-line"></i>
          <span class="hide-menu">Pengembangan Diri</span>
        </a>
        <ul aria-expanded="false" class="collapse">
          <li <?php if ($this->uri->segment(1) == 'pdp'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'pdp'; ?>">Personal Development Plan</a>
          </li>
          <li <?php if ($this->uri->segment(1) == 'Skillinventory'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Skillinventory'; ?>">Skill Inventory</a>
          </li>
        </ul>
      </li>
    <?php endif; ?>
  <?php endif; ?>

  <!-- LAPORAN PRESENSI -->
  <?php if (@$Menu_Group['laporan']): ?>
    <li class="nav-small-cap">--- LAPORAN</li>
    <li>
      <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
        <i class="fas fa-file-alt"></i>
        <span class="hide-menu">Laporan Presensi</span>
      </a>
      <ul aria-expanded="false" class="collapse">
        <?php if (@$Menu_Roles['LaporanPresensi']): ?>
          <li <?php if ($this->uri->segment(2) == 'LaporanPresensi'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Laporan/LaporanPresensi'; ?>">Laporan Presensi</a>
          </li>
        <?php endif; ?>
        <?php if (@$Menu_Roles['LaporanTidakPresensi']): ?>
          <li <?php if ($this->uri->segment(1) == 'LaporanTidakPresensi'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'LaporanTidakPresensi'; ?>">Laporan Tidak Presensi</a>
          </li>
        <?php endif; ?>
        <?php if (@$Menu_Roles['RekapitulasiPresensi']): ?>
          <li <?php if ($this->uri->segment(2) == 'RekapitulasiPresensi'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Laporan/RekapitulasiPresensi'; ?>">Rekapitulasi Presensi</a>
          </li>
          <li <?php if ($this->uri->segment(2) == 'TotalPresensi'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Laporan/TotalPresensi'; ?>">Total Presensi</a>
          </li>
          <li <?php if ($this->uri->segment(2) == 'TotalPresensiDispensasi'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Laporan/TotalPresensiDispensasi'; ?>">Total Presensi Dispensasi</a>
          </li>
          <li <?php if ($this->uri->segment(2) == 'LaporanKejanggalanPresensi'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Laporan/LaporanKejanggalanPresensi'; ?>">Laporan Kejanggalan</a>
          </li>
        <?php endif; ?>
        <?php if (@$Menu_Roles['LaporanKegiatan']): ?>
          <li <?php if ($this->uri->segment(2) == 'LaporanKegiatan'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Laporan/LaporanKegiatan'; ?>">Laporan Kegiatan</a>
          </li>
        <?php endif; ?>
      </ul>
    </li>

    <?php if (@$Menu_Roles['LaporanCuti'] || @$Menu_Roles['LaporanLembur'] || @$Menu_Roles['LaporanDinasLuar'] || @$Menu_Roles['LaporanDiluarJam'] || @$Menu_Roles['LaporanJadwalWF']): ?>
      <li>
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
          <i class="fas fa-chart-pie"></i>
          <span class="hide-menu">Laporan Lainnya</span>
        </a>
        <ul aria-expanded="false" class="collapse">
          <?php if (@$Menu_Roles['LaporanCuti']): ?>
            <li <?php if ($this->uri->segment(2) == 'LaporanCuti'): ?>class="active" <?php endif; ?>>
              <a href="<?php echo base_url() . 'Laporan/LaporanCuti'; ?>">Laporan Cuti</a>
            </li>
            <li <?php if ($this->uri->segment(2) == 'rekapitulasi_cuti'): ?>class="active" <?php endif; ?>>
              <a href="<?php echo base_url() . 'Laporan/rekapitulasi_cuti'; ?>">Rekapitulasi Cuti</a>
            </li>
          <?php endif; ?>
          <?php if (@$Menu_Roles['LaporanLembur']): ?>
            <li <?php if ($this->uri->segment(2) == 'RekapitulasiLembur'): ?>class="active" <?php endif; ?>>
              <a href="<?php echo base_url() . 'Laporan/RekapitulasiLembur'; ?>">Rekapitulasi Lembur</a>
            </li>
          <?php endif; ?>
          <?php if (@$Menu_Roles['LaporanDinasLuar']): ?>
            <li <?php if ($this->uri->segment(2) == 'LaporanDinasLuar'): ?>class="active" <?php endif; ?>>
              <a href="<?php echo base_url() . 'Laporan/LaporanDinasLuar'; ?>">Laporan Dinas Luar</a>
            </li>
          <?php endif; ?>
          <?php if (@$Menu_Roles['LaporanDiluarJam']): ?>
            <li <?php if ($this->uri->segment(2) == 'LaporanDiluarJam'): ?>class="active" <?php endif; ?>>
              <a href="<?php echo base_url() . 'Laporan/LaporanDiluarJam'; ?>">Laporan Diluar Jam</a>
            </li>
          <?php endif; ?>
          <?php if (@$Menu_Roles['LaporanJadwalWF']): ?>
            <li <?php if ($this->uri->segment(2) == 'LaporanJadwalWF'): ?>class="active" <?php endif; ?>>
              <a href="<?php echo base_url() . 'Laporan/LaporanJadwalWF'; ?>">Laporan Jadwal Kerja</a>
            </li>
          <?php endif; ?>
        </ul>
      </li>
    <?php endif; ?>
  <?php endif; ?>

  <!-- KEPEGAWAIAN -->
  <?php if (@$Menu_Group['kepegawaian']): ?>
    <li class="nav-small-cap">--- KEPEGAWAIAN</li>
    <li>
      <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
        <i class="fas fa-users"></i>
        <span class="hide-menu">Kepegawaian</span>
      </a>
      <ul aria-expanded="false" class="collapse">
        <?php if (@$Menu_Roles['Pegawai']): ?>
          <li <?php if ($this->uri->segment(1) == 'Pegawai'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Pegawai'; ?>">Pegawai</a>
          </li>
        <?php endif; ?>
        <?php if (@$Menu_Roles['Jabatan']): ?>
          <li <?php if ($this->uri->segment(1) == 'Jabatan'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Jabatan'; ?>">Jabatan</a>
          </li>
        <?php endif; ?>
        <?php if (@$Menu_Roles['Monitoring']): ?>
          <li <?php if ($this->uri->segment(1) == 'Monitoring'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Monitoring'; ?>">Monitoring Pegawai</a>
          </li>
        <?php endif; ?>
        <?php if (@$Menu_Roles['JadwalLokasi']): ?>
          <li <?php if ($this->uri->segment(1) == 'JadwalLokasi'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'JadwalLokasi'; ?>">Jadwal Lokasi</a>
          </li>
        <?php endif; ?>
      </ul>
    </li>
  <?php endif; ?>

  <!-- DATA MASTER -->
  <?php if (@$Menu_Group['DM']): ?>
    <li class="nav-small-cap">--- DATA MASTER</li>
    <?php if (@$Menu_Roles['Unit']): ?>
      <li <?php if ($this->uri->segment(1) == 'Unit'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'Unit'; ?>">
          <i class="fas fa-sitemap"></i><span class="hide-menu">Unit</span>
        </a>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['Kampus']): ?>
      <li <?php if ($this->uri->segment(1) == 'Kampus'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'Kampus'; ?>">
          <i class="fas fa-building"></i><span class="hide-menu">Kantor</span>
        </a>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['Gedung']): ?>
      <li <?php if ($this->uri->segment(1) == 'Gedung'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'Gedung'; ?>">
          <i class="fas fa-city"></i><span class="hide-menu">Gedung</span>
        </a>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['JadwalMasuk']): ?>
      <li <?php if ($this->uri->segment(1) == 'JadwalMasuk'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'JadwalMasuk'; ?>">
          <i class="fas fa-calendar-day"></i><span class="hide-menu">Jadwal Masuk</span>
        </a>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['Libur']): ?>
      <li <?php if ($this->uri->segment(1) == 'Libur'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'Libur'; ?>">
          <i class="fas fa-calendar-times"></i><span class="hide-menu">Libur</span>
        </a>
      </li>
    <?php endif; ?>
    <?php if (@$Menu_Roles['JenisPerizinan']): ?>
      <li <?php if ($this->uri->segment(1) == 'JenisPerizinan'): ?>class="active" <?php endif; ?>>
        <a href="<?php echo base_url() . 'JenisPerizinan'; ?>">
          <i class="fas fa-file-signature"></i><span class="hide-menu">Jenis Perizinan</span>
        </a>
      </li>
    <?php endif; ?>
  <?php endif; ?>

  <!-- USER ACCESS -->
  <?php if (@$Menu_Group['access']): ?>
    <li class="nav-small-cap">--- USER ACCESS</li>
    <li>
      <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
        <i class="fas fa-shield-alt"></i>
        <span class="hide-menu">Access</span>
      </a>
      <ul aria-expanded="false" class="collapse">
        <?php if (@$Menu_Roles['User']): ?>
          <li <?php if ($this->uri->segment(1) == 'User'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'User'; ?>">User</a>
          </li>
        <?php endif; ?>
        <?php if (@$Menu_Roles['Roles']): ?>
          <li <?php if ($this->uri->segment(1) == 'Roles'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'Roles'; ?>">Roles</a>
          </li>
        <?php endif; ?>
        <?php if (@$Menu_Roles['GroupRole']): ?>
          <li <?php if ($this->uri->segment(1) == 'GroupRole'): ?>class="active" <?php endif; ?>>
            <a href="<?php echo base_url() . 'GroupRole'; ?>">Group Roles</a>
          </li>
        <?php endif; ?>
      </ul>
    </li>
  <?php endif; ?>
</ul>