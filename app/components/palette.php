<?php

  if (isset($_GET['paletteName'])) {
    $safePaletteName = htmlentities($_GET['paletteName'], ENT_QUOTES);
    addPalette(getDb(), $safePaletteName);
  }

  function addPalette($db, $name) {
    $stmt = "insert into palettes (palette_name) values ("
      . '\'' . $name . '\');';
    $result = pg_query($stmt);
  }

?>

<h4>New Palette</h4>
<form class="form-inline" method="get" action="" style="padding: 0 0 30px 0;">
  <label class="sr-only" for="paletteName">Palette Name</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="paletteName" name="paletteName" placeholder="The most beautiful...">
  <button type="submit" class="btn btn-secondary">Add Palette</button>
</form>
