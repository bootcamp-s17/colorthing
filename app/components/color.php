<?php 

  if (isset($_GET['colorName']) && isset($_GET['hexCode'])) {
    $safeColorName = htmlentities($_GET['colorName']);
    $safeHexCode = htmlentities($_GET['hexCode']);
    addColor(getDb(), $safeColorName, $safeHexCode);
  }

  if (isset($_GET['removeColorId'])) {
    $safeId = htmlentities($_GET['removeColorId']);
    removeColor(getDb(), $safeId);
  }

  function getColors($db) {
    $request = pg_query(getDb(), "SELECT * FROM colors order by color_name;");
    return pg_fetch_all($request);
  }

  function addColor($db, $name, $hex) {
    $stmt = 'INSERT INTO colors (color_name, hex_code) VALUES ('
      . '\'' . $name . '\', \''
      . $hex . '\');';
    pg_query($stmt);
  }

  function removeColor($db, $id) {
    $stmt_c = "delete from colors where id=" . $id;
    $stmt_cp = "delete from color_palette where color_id=" . $id;
    pg_query($stmt_cp);
    pg_query($stmt_c);
  }

?>

<h4>New Color</h4>
<form class="form-inline" method="get" action="" style="padding: 0 0 30px 0;">
  <label class="sr-only" for="colorName">Color Name</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="colorName" name="colorName" placeholder="The deep dark void...">
  <label class="sr-only" for="hexCode">Hex Code</label>
  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
    <div class="input-group-addon">#</div>
    <input type="text" class="form-control" id="hexCode" name="hexCode" placeholder="000000">
  </div>
  <button type="submit" class="btn btn-secondary">Add Color</button>
</form>
