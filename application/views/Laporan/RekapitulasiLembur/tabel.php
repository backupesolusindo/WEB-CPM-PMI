<?php $this->load->model("ModelLembur"); ?>
<table class="display nowrap table table-hover table-striped table-bordered" id="table-print">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>NIP</th>
            <th>Nama Pegawai</th>
            <th>Unit</th>
            <th class="text-center">Total Lembur</th>
            <th class="text-center">Disetujui</th>
            <th class="text-center">Ditolak</th>
            <th class="text-center">Menunggu</th>
            <th class="text-center">Total Durasi (Jam)</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $grand_total_lembur = 0;
        $grand_total_disetujui = 0;
        $grand_total_ditolak = 0;
        $grand_total_menunggu = 0;
        $grand_total_durasi = 0;

        foreach ($pegawai->result() as $value):
            // Ambil data lembur pegawai
            $lembur_data = $this->ModelLembur->riwayat_lembur($value->uuid, null, $tgl_mulai, $tgl_akhir)->result();

            $total_lembur = 0;
            $total_disetujui = 0;
            $total_ditolak = 0;
            $total_menunggu = 0;
            $total_durasi_menit = 0;

            foreach ($lembur_data as $lembur) {
                $total_lembur++;

                // Hitung durasi
                if ($lembur->jam_presensi_selesai) {
                    $durasi_menit = (strtotime($lembur->jam_presensi_selesai) - strtotime($lembur->jam_presensi)) / 60;

                    // Hitung berdasarkan status
                    if ($lembur->status_aproval == 'DISETUJUI' || $lembur->status_aproval == '1') {
                        $total_disetujui++;
                        $total_durasi_menit += $durasi_menit;
                    } elseif ($lembur->status_aproval == 'DITOLAK' || $lembur->status_aproval == '2') {
                        $total_ditolak++;
                    } else {
                        $total_menunggu++;
                    }
                } else {
                    $total_menunggu++;
                }
            }

            // Filter berdasarkan status approval jika dipilih
            if (!empty($status_approval)) {
                $show_row = false;
                if ($status_approval == 'DISETUJUI' && $total_disetujui > 0) {
                    $show_row = true;
                } elseif ($status_approval == 'DITOLAK' && $total_ditolak > 0) {
                    $show_row = true;
                } elseif ($status_approval == 'MENUNGGU' && $total_menunggu > 0) {
                    $show_row = true;
                }

                if (!$show_row) {
                    continue;
                }
            }

            // Skip jika tidak ada lembur
            if ($total_lembur == 0) {
                continue;
            }

            $total_durasi_jam = floor($total_durasi_menit / 60);
            $total_durasi_menit_sisa = $total_durasi_menit % 60;

            $grand_total_lembur += $total_lembur;
            $grand_total_disetujui += $total_disetujui;
            $grand_total_ditolak += $total_ditolak;
            $grand_total_menunggu += $total_menunggu;
            $grand_total_durasi += $total_durasi_menit;
        ?>
            <tr>
                <td class="text-center"><?php echo $no++ ?></td>
                <td><?php echo $value->NIP ?></td>
                <td><?php echo $value->nama_pegawai ?></td>
                <td><?php echo $value->unit ?></td>
                <td class="text-center"><span class="badge badge-info"><?php echo $total_lembur ?></span></td>
                <td class="text-center"><span class="badge badge-success"><?php echo $total_disetujui ?></span></td>
                <td class="text-center"><span class="badge badge-danger"><?php echo $total_ditolak ?></span></td>
                <td class="text-center"><span class="badge badge-warning"><?php echo $total_menunggu ?></span></td>
                <td class="text-center">
                    <strong><?php echo $total_durasi_jam ?> Jam <?php echo $total_durasi_menit_sisa ?> Menit</strong>
                </td>
                <td class="text-center">
                    <a href="<?php echo base_url() ?>Laporan/DetailLembur/<?php echo $value->uuid ?>"
                        class="btn btn-sm btn-primary"
                        data-toggle="tooltip"
                        title="Detail Lembur">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="bg-light">
            <th colspan="4" class="text-right">TOTAL:</th>
            <th class="text-center"><span class="badge badge-info"><?php echo $grand_total_lembur ?></span></th>
            <th class="text-center"><span class="badge badge-success"><?php echo $grand_total_disetujui ?></span></th>
            <th class="text-center"><span class="badge badge-danger"><?php echo $grand_total_ditolak ?></span></th>
            <th class="text-center"><span class="badge badge-warning"><?php echo $grand_total_menunggu ?></span></th>
            <th class="text-center">
                <strong>
                    <?php
                    $grand_jam = floor($grand_total_durasi / 60);
                    $grand_menit = $grand_total_durasi % 60;
                    echo $grand_jam . " Jam " . $grand_menit . " Menit";
                    ?>
                </strong>
            </th>
            <th></th>
        </tr>
    </tfoot>
</table>

<div class="mt-4">
    <div class="alert alert-info">
        <h5><i class="fas fa-info-circle"></i> Informasi</h5>
        <ul class="mb-0">
            <li>Unit: <strong><?php echo $unit ?></strong></li>
            <li>Periode: <strong><?php echo date("d-m-Y", strtotime($tgl_mulai)) ?> s/d <?php echo date("d-m-Y", strtotime($tgl_akhir)) ?></strong></li>
            <li>Total Durasi Lembur yang Disetujui: <strong><?php echo $grand_jam ?> Jam <?php echo $grand_menit ?> Menit</strong></li>
        </ul>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>