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
  <link rel="icon" type="image/png" href="rainbow_heart.png">
</head>
<body>

<?php 

  if (isset($_GET['colorName']) && isset($_GET['hexCode'])) {
    $safeColorName = htmlentities($_GET['colorName']);
    $safeHexCode = htmlentities($_GET['hexCode']);
    addColor(getDb(), $safeColorName, $safeHexCode);
  }

  if (isset($_GET['removeId'])) {
    $safeId = htmlentities($_GET['removeId']);
    removeColor(getDb(), $safeId);
  }

  function getDb() {
    $db = pg_connect("host=localhost port=5432 dbname=colordb_dev user=coloruser password=color");
    return $db;
  }

  function getColors($db) {
    $request = pg_query(getDb(), "SELECT * FROM colors;");
    return pg_fetch_all($request);
  }

  function addColor($db, $name, $hex) {

    $stmt = "insert into colors (color_name, hex_code) values ("
      . '\'' . $name . '\', \''
      . $hex . '\');';

    $result = pg_query($stmt);
  }

  function removeColor($db, $id) {
    $stmt = "delete from colors where id=" . $id;
    $result = pg_query($stmt);
  }

?>

<div class="container">

<h1>Colorthing</h1>

<div>

<form class="form-inline" method="get" action="">

  <label class="sr-only" for="colorName">Color Name</label>
  <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="colorName" name="colorName" placeholder="The deep dark void...">

  <label class="sr-only" for="hexCode">Hex Code</label>
  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
    <div class="input-group-addon">#</div>
    <input type="text" class="form-control" id="hexCode" name="hexCode" placeholder="000000">
  </div>

  <button type="submit" class="btn btn-secondary">Add Color</button>
</form>

<?php 
  foreach (getColors(getDb()) as $color) {
?>

  <div class="row mx-auto" style="padding: 10px 0;">

    <form method="get" action="">
      <input name="removeId" value="<?=$color['id'];?>" type="hidden">
      <button type="submit" class="close" aria-label="Remove">
        <span aria-hidden="true" style="padding-right: 10px;">&times;</span>
      </button>
    </form>

    <div class="col" style="min-height: 40px; max-width: 100px; background-color: #<?=$color['hex_code'];?>;"></div>
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
</body>
</html>