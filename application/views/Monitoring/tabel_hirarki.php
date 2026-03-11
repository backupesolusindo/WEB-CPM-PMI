
<?php
$no = 1;
$parent_unit = $this->ModelUnit->hirarki_parentunit($unit_filter)->result();
foreach ($parent_unit as $value): ?>
<tr class="table-info">
  <td colspan="5">
    <h4><b>
      <?php
      @$kep_unit = @$this->ModelPegawai->get_jabatunit($value->idunit)->row_array();
      echo "lvl ".$value->level.". ".$value->nama_unit." (".@$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai'].")";
      $pegawai = $this->ModelPegawai->get_UnitPegawai($value->nama_unit, $value->nama_unit)->result(); ?>
    </b></h4>
  </td>
</tr>
<?php foreach ($pegawai as $val_peg): ?>
  <tr>
    <td></td>
    <td><?php echo $val_peg->NIP ?></td>
    <td><?php echo $val_peg->nama_pegawai ?></td>
    <td><?php echo $val_peg->email ?></td>
    <td><?php echo $val_peg->jab_struktur ?></td>
  </tr>
<?php endforeach;
  $parent_unit = $this->ModelUnit->hirarki_unit($value->nama_unit)->result();
  foreach ($parent_unit as $value): ?>
  <tr>
    <td colspan="5">
      <h5><b>
        <?php
        @$kep_unit = @$this->ModelPegawai->get_jabatunit($value->idunit)->row_array();
        echo "lvl ".$value->level.". ".$value->nama_unit." (".@$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai'].")";
        $pegawai = $this->ModelPegawai->get_UnitPegawai($value->nama_unit, $value->nama_unit)->result(); ?>
      </b></h5>
    </td>
  </tr>
  <?php foreach ($pegawai as $val_peg): ?>
    <tr>
      <td></td>
      <td><?php echo $val_peg->NIP ?></td>
      <td><?php echo $val_peg->nama_pegawai ?></td>
      <td><?php echo $val_peg->email ?></td>
      <td><?php echo $val_peg->jab_struktur ?></td>
    </tr>
  <?php endforeach;
    $parent_unit = $this->ModelUnit->hirarki_unit($value->nama_unit)->result();
    foreach ($parent_unit as $value): ?>
    <tr>
      <td colspan="5" style="padding-left:50px">
        <h6><b>
          <?php
          @$kep_unit = @$this->ModelPegawai->get_jabatunit($value->idunit)->row_array();
          echo "lvl ".$value->level.". ".$value->nama_unit." (".@$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai'].")";
          $pegawai = $this->ModelPegawai->get_UnitPegawai($value->nama_unit, $value->nama_unit)->result(); ?>
        </b></h6>
      </td>
    </tr>
      <?php foreach ($pegawai as $val_peg): ?>
        <tr>
          <td></td>
          <td><?php echo $val_peg->NIP ?></td>
          <td><?php echo $val_peg->nama_pegawai ?></td>
          <td><?php echo $val_peg->email ?></td>
          <td><?php echo $val_peg->jab_struktur ?></td>
        </tr>
      <?php endforeach;
      $parent_unit = $this->ModelUnit->hirarki_unit($value->nama_unit)->result();
      foreach ($parent_unit as $value): ?>
      <tr>
        <td colspan="5" style="padding-left:100px">
          <h6><b>
            <?php
            @$kep_unit = @$this->ModelPegawai->get_jabatunit($value->idunit)->row_array();
            echo "lvl ".$value->level.". ".$value->nama_unit." (".@$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai'].")";
            $pegawai = $this->ModelPegawai->get_UnitPegawai($value->nama_unit, $value->nama_unit)->result(); ?>
          </b></h6>
        </td>
      </tr>
        <?php foreach ($pegawai as $val_peg): ?>
          <tr>
            <td></td>
            <td><?php echo $val_peg->NIP ?></td>
            <td><?php echo $val_peg->nama_pegawai ?></td>
            <td><?php echo $val_peg->email ?></td>
            <td><?php echo $val_peg->jab_struktur ?></td>
          </tr>
        <?php endforeach;
          $parent_unit = $this->ModelUnit->hirarki_unit($value->nama_unit)->result();
          foreach ($parent_unit as $value): ?>
          <tr>
            <td colspan="5" style="padding-left:150px">
              <h6><b>
                <?php
                @$kep_unit = @$this->ModelPegawai->get_jabatunit($value->idunit)->row_array();
                echo "lvl ".$value->level.". ".$value->nama_unit." (".@$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai'].")";
                $pegawai = $this->ModelPegawai->get_UnitPegawai($value->nama_unit, $value->nama_unit)->result(); ?>
              </b></h6>
            </td>
          </tr>
            <?php foreach ($pegawai as $val_peg): ?>
              <tr>
                <td></td>
                <td><?php echo $val_peg->NIP ?></td>
                <td><?php echo $val_peg->nama_pegawai ?></td>
                <td><?php echo $val_peg->email ?></td>
                <td><?php echo $val_peg->jab_struktur ?></td>
              </tr>
            <?php endforeach;
              $parent_unit = $this->ModelUnit->hirarki_unit($value->nama_unit)->result();
              foreach ($parent_unit as $value): ?>
              <tr>
                <td colspan="5" style="padding-left:200px">
                  <h6><b>
                    <?php
                    @$kep_unit = @$this->ModelPegawai->get_jabatunit($value->idunit)->row_array();
                    echo "lvl ".$value->level.". ".$value->nama_unit." (".@$kep_unit['NIP']." - ".@$kep_unit['nama_pegawai'].")";
                    $pegawai = $this->ModelPegawai->get_UnitPegawai($value->nama_unit, $value->nama_unit)->result(); ?>
                  </b></h6>
                </td>
              </tr>
                <?php foreach ($pegawai as $val_peg): ?>
                  <tr>
                    <td></td>
                    <td><?php echo $val_peg->NIP ?></td>
                    <td><?php echo $val_peg->nama_pegawai ?></td>
                    <td><?php echo $val_peg->email ?></td>
                    <td><?php echo $val_peg->jab_struktur ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endforeach; ?>
          <?php endforeach; ?>
      <?php endforeach; ?>
    <?php endforeach; ?>
  <?php endforeach; ?>
<?php $no++; endforeach; ?>
