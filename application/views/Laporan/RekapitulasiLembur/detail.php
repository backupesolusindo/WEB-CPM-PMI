<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Detail Laporan Lembur Pegawai</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>NIP</strong></td>
                                <td>: <?php echo $pegawai['NIP'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Pegawai</strong></td>
                                <td>: <?php echo $pegawai['nama_pegawai'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email SSO</strong></td>
                                <td>: <?php echo $pegawai['email'] ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Unit</strong></td>
                                <td>: <?php echo $pegawai['unit'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tipe Pegawai</strong></td>
                                <td>: <?php echo $pegawai['tipe_pegawai'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>Menurut Tanggal :</label>
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control" name="start" id="start" value="<?php echo date("01-m-Y") ?>" readonly />
                            <div class="input-group-append">
                                <span class="input-group-text bg-info b-0 text-white">S/D</span>
                            </div>
                            <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <button type="button" class="btn btn-info btn-md" onclick="search()">
                            <i class="fa fa-search"></i> Cari
                        </button>
                    </div>
                    <div class="col-md-2">
                        <br>
                        <button type="button" class="btn btn-default btn-md" onclick="window.history.back()">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </button>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="loader__figure" hidden="true"></div>
                    <div class="table-responsive hasilSearch">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        search();
    });

    function search() {
        var start = $('#start').val();
        var end = $('#end').val();
        var uuid = '<?php echo $pegawai['uuid'] ?>';

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>Laporan/tabelDetailLembur",
            data: {
                start: start,
                end: end,
                uuid: uuid
            },
            beforeSend: function() {
                $('.loader__figure').attr("hidden", false);
            },
            success: function(data) {
                $('.loader__figure').attr("hidden", true);
                $('.hasilSearch').html(data);
            },
            error: function(e) {
                $('.loader__figure').attr("hidden", true);
                alert('Terjadi kesalahan saat memuat data');
            },
        });
    }
</script>