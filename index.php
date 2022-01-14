<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>MT940 to JSON</title>
  </head>
<body>
<main class="form-signin">
  <h1 class="h3 mb-5 fw-normal text-center">MT940 to JSON Converter</h1>
  <form method="POST" enctype="multipart/form-data" action="verwerk.php">
    <div class="mb-3">
      <label for="file" class="form-lable"></label>
      <input type="file" class="form-control" name="file" id="file" required accept=".swi, .txt, .mt940, .mta">
    </div>
    <div class="mb-3">
      <select required class="form-select" name="engine">
          <option selected>Kies welke bank u gebruikt voor dit bestand</option>
          <option value="Rabo">Rabobank</option>
          <option value="Ing">ING</option>
          <option value="Abn">ABN AMRO</option>
      </select>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Upload</button>
  </form>
  <?php
    if($_GET['result']){
      echo "<a href=". $_GET['result'] ." download=". $_GET['result'] ."><button class='mt-3 w-100 btn btn-lg btn-primary'>Download</button>";
    }
  ?>
</main>

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    
</body>
</html>