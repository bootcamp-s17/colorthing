<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Colorthing</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="rainbow_heart.png">
</head>
<body>

<?php 

  if (isset($_GET['removePaletteId'])) {
    $safeId = htmlentities($_GET['removePaletteId']);
    removePalette(getDb(), $safeId);
  }

  if (isset($_GET['removeColorFromPalette'])) {
    $safeId = htmlentities($_GET['removeColorFromPalette']);
    removeColorFromPalette(getDb(), $safeId);
  }

  if (isset($_GET['colorId']) && isset($_GET['paletteId'])) {
    $safeColorId = htmlentities($_GET['colorId']);
    $safePaletteId = htmlentities($_GET['paletteId']);
    addColorToPalette(getDb(), $safeColorId, $safePaletteId);
  }

  function addColorToPalette($db, $color_id, $palette_id) {
    $stmt = "insert into color_palette (color_id, palette_id) values ("
      . $color_id . ', ' . $palette_id . ');';
    $result = pg_query($stmt);
  }

  function removeColorFromPalette($db, $id) {
    $stmt = "delete from color_palette where id=" . $id;
    pg_query($stmt);
  }

  function removePalette($db, $id) {
    $stmt_p = "delete from palettes where id=" . $id;
    $stmt_cp = "delete from color_palette where palette_id=" . $id;
    pg_query($stmt_cp);
    pg_query($stmt_p);
  }

  function getPalettes($db) {
    $request = pg_query(getDb(), "
SELECT palettes.id AS palette_id, palette_name, color_palette.id AS color_palette_id, color_palette.color_id, colors.color_name, colors.hex_code
FROM palettes 
LEFT JOIN color_palette ON palettes.id = color_palette.palette_id
LEFT JOIN colors ON color_palette.color_id = colors.id
ORDER BY palette_name;
    ");
    return pg_fetch_all($request);
  }

  function getDb() {
    $db = pg_connect("host=localhost port=5432 dbname=colordb_dev user=coloruser password=color");
    return $db;
  }

?>

<div class="container" style="padding-top: 30px;">

<h1 class="text-center">Colorthing</h1>

<div>

<?php include('components/color.php'); ?>

<?php include('components/palette.php'); ?>

<div class="row">

<div class="col">
  <h4>Palettes</h4>

<?php 

  $last_palette = '';

  foreach (getPalettes(getDb()) as $palette) {
    if ($palette['palette_id'] === $last_palette) {
      // Do we need to do anything here?
      // Case: new palette
    }
    else {

?>

  <div class="row mx-auto" style="padding: 10px 0;">
    <form method="get" action="">
      <input name="removePaletteId" value="<?=$palette['palette_id'];?>" type="hidden">
      <button type="submit" class="close" aria-label="Remove">
        <span aria-hidden="true" style="padding-right: 10px;">&times;</span>
      </button>
    </form>
    <div class="col">
      <div class="align-middle"><?=$palette['palette_name'];?></div>
    </div>
  </div>

  <form method="get" action="">
    <div class="form-inline">
      <label for="colorSelect" class="sr-only">Color Select</label>
      <select class="form-control form-control mb-2 mr-sm-2 mb-sm-0" id="colorSelect" name="colorId">
<?php 
  foreach (getColors(getDb()) as $color) {
    print '<option value="' . $color['id'] . '">' . $color['color_name'] . "</option>\n";
  }
?>
      </select>
      <input type="hidden" name="paletteId" value="<?=$palette['palette_id'];?>">
      <button type="submit" class="btn btn-secondary">Add to Palette</button>
    </div>
  </form>

<?php
    }

if (isset($palette['color_name'])) {
?>

  <div class="row mx-auto" style="padding: 10px 0;">
    <form method="get" action="">
      <input name="removeColorFromPalette" value="<?=$palette['color_palette_id'];?>" type="hidden">
      <button type="submit" class="close" aria-label="Remove">
        <span aria-hidden="true" style="padding-right: 10px;">&times;</span>
      </button>
    </form>
    <div class="col" style="min-height: 25px; max-width: 100px; background-color: #<?=$palette['hex_code'];?>;">
    </div>
    <div class="col">
      <div class="align-middle">
        <?=$palette['color_name'];?>
        (#<?=$palette['hex_code'];?>)
      </div>
    </div>
  </div>

<?php

  }

  $last_palette = $palette['palette_id'];

}
?>

</div>

<div class="col">
<h4>All Colors</h4>
<?php 
  foreach (getColors(getDb()) as $color) {
?>
  <div class="row mx-auto" style="padding: 10px 0;">
    <form method="get" action="">
      <input name="removeColorId" value="<?=$color['id'];?>" type="hidden">
      <button type="submit" class="close" aria-label="Remove">
        <span aria-hidden="true" style="padding-right: 10px;">&times;</span>
      </button>
    </form>
    <div class="col" style="min-height: 25px; max-width: 100px; background-color: #<?=$color['hex_code'];?>;">
    </div>
    <div class="col">
      <div class="align-middle">
        <?=$color['color_name'];?>
        (#<?=$color['hex_code'];?>)
      </div>
    </div>
  </div>
<?php 
  }
?>
</div>

</div>

</div>
</div>
</body>
</html>