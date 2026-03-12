<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekapitulasi_Cuti_" . date('Y-m-d') . ".xls");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Rekapitulasi Cuti Pegawai</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2><?php echo $title; ?></h2>
        <p>Periode: <?php echo $periode; ?></p>
        <p>Unit: <?php echo $unit; ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Unit</th>
                <th>Jenis Cuti</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Durasi (Hari)</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($data) > 0): ?>
                <?php
                $no = 1;
                foreach ($data as $row):
                    // Hitung durasi
                    $tanggal_mulai = strtotime($row->tanggal_mulai);
                    $tanggal_selesai = strtotime($row->tanggal_selesai);
                    $durasi = ceil(($tanggal_selesai - $tanggal_mulai) / (60 * 60 * 24)) + 1;

                    // Status
                    $status_text = '';
                    if ($row->status == '1') {
                        $status_text = 'Disetujui';
                    } elseif ($row->status == '0') {
                        $status_text = 'Pending';
                    } elseif ($row->status == '2') {
                        $status_text = 'Ditolak';
                    }
                ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td><?php echo $row->NIP ?? $row->NIK ?? '-'; ?></td>
                        <td><?php echo $row->nama_pegawai; ?></td>
                        <td><?php echo $row->unit ?? '-'; ?></td>
                        <td><?php echo $row->jenis_izin ?? 'Cuti'; ?></td>
                        <td class="text-center"><?php echo date('d-m-Y', strtotime($row->tanggal_mulai)); ?></td>
                        <td class="text-center"><?php echo date('d-m-Y', strtotime($row->tanggal_selesai)); ?></td>
                        <td class="text-center"><?php echo $durasi; ?></td>
                        <td class="text-center"><?php echo $status_text; ?></td>
                        <td><?php echo $row->keterangan ?? '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <p><i>Dicetak pada: <?php echo date('d-m-Y H:i:s'); ?></i></p>
</body>

</html>