<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>MT940 Formateur</title>
  </head>
<body class="bg-dark text-white">

<main class="form-signin">
  <h1 class="h3 mb-3 fw-normal text-center">MT940 Formateur Naar JSON</h1>
  <p>Werkt met Excel, JSON en SWI.</p>
  <form method="POST" enctype="multipart/form-data" action="verwerk.php" target="_blank">
    <div class="mb-3">
      <label for="file" class="form-lable"></label>
      <input type="file" class="form-control" name="file" id="file" required accept=".swi, .txt, .mt940, .mta, .xlsx, .json">
    </div>
    <div class="mb-3">
      <select required class="form-select" name="engine">
          <option disabled selected>Kies welke bank u gebruikt voor dit bestand</option>
          <option value="Rabo">Rabobank</option>
          <option value="Ing">ING</option>
          <option value="Abn">ABN AMRO</option>
      </select>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Convert</button>
  </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
</body>
</html>