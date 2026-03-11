<select multiple id="optgroup" name="roles[]">
  <?php foreach ($group as $g): ?>
    <optgroup label="<?php echo $g->nama_group ?>">
      <?php foreach ($this->ModelUsers->get_roles($g->idgroup_roles)->result() as $r): ?>
        <option value="<?php echo $r->roles ?>"
          <?php foreach ($j_roles as $value): ?>
            <?php if ($value == $r->roles): ?>
              <?php echo "selected" ?>
            <?php endif; ?>
          <?php endforeach; ?>
          ><?php echo $r->roles ?></option>
      <?php endforeach; ?>
    </optgroup>
  <?php endforeach; ?>
</select>
