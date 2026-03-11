<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        getMaps("realtime", <?php echo $realtime['latitude'] ?>, <?php echo $realtime['longitude'] ?>);

    });

    function getMaps(id, lat, lng) {
        var map;
        var marker;
        var myLatlng = new google.maps.LatLng(lat, lng);
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();
        var mapOptions = {
            zoom: 18,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById(id), mapOptions);

        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            draggable: false
        });

        geocoder.geocode({
            'latLng': myLatlng
        }, function(results, status) {
            geocoder.geocode({
                'latLng': marker.getPosition()
            }, function(results, status) {
                $("#latitude").val(marker.getPosition().lat());
                $("#longitude").val(marker.getPosition().lng());
            });
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({
                'latLng': marker.getPosition()
            }, function(results, status) {
                $("#latitude").val(marker.getPosition().lat());
                $("#longitude").val(marker.getPosition().lng());
            });
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
            <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
                <div>
                </div>
                <h3 class="white-text mx-3">Lokasi Realtime Pegawai</h3>
                <div>

                </div>
            </div>
            <div class="card-body row">
                <div class="col-6">
                    <table width="100%" border="0">
                        <tr>
                            <td>NIP</td>
                            <td>: <?php echo $pegawai['NIP'] ?></td>
                        </tr>
                        <tr>
                            <td>Nama Pegawai</td>
                            <td>: <?php echo $pegawai['nama_pegawai'] ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table width="100%" border="0">
                        <tr>
                            <td>Email SSO</td>
                            <td>: <?php echo $pegawai['email'] ?></td>
                        </tr>
                        <tr>
                            <td>Unit</td>
                            <td>: <?php echo $pegawai['unit'] ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-12">
                    <?php if($realtime != null):?>
                        <?php if(date('Y-m-d') == date('Y-m-d', strtotime($realtime['timestamp']))): ?>
                            <label>Lokasi Terakhir Pegawai Saat : <?= date('d-m-Y H:i:s', strtotime($realtime['timestamp'])) ?></label>
                            <div id="realtime" style="width:100%; height:400px"></div>
                        <?php else: ?>
                            <label>Lokasi Terakhir Pegawai Belum Ada</label>
                        <?php endif; ?>
                    <?php else: ?>
                    <label>Lokasi Terakhir Pegawai Belum Ada</label>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>