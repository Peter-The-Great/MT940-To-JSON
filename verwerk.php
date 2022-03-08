<?php
require 'vendor/autoload.php'; // require composer dependencies
//http://jsonviewer.stack.hu/
use Composer\Console\Application;

// instantiate the actual parser
// and parse them from a given file, this could be any file or a posted string
//__DIR__.

//Check of de files er zijn.
if(isset($_FILES['file'],$_POST['engine'])){

    $filename = $_FILES['file']['name'];
    $filenametemp = $_FILES['file']['tmp_name'];
    $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    //Check of het bestand geldig is ja of nee.
    $swifile = array("txt","swi","mt940", "mta");
    $jsonfile = array("txt","json");
    $excelfile = array("xlsx");
        
    if(in_array($fileExt,$swifile)){
        $parser = new \Kingsquare\Parser\Banking\Mt940();
        switch ($_POST['engine']) {
            case 'Rabo':
                $engine = new \Kingsquare\Parser\Banking\Mt940\Engine\Rabo();
                break;
            case 'Ing':
                $engine = new \Kingsquare\Parser\Banking\Mt940\Engine\Ing();
                break;
            case 'Abn':
                $engine = new \Kingsquare\Parser\Banking\Mt940\Engine\Abn();
                break;
            default:
                $engine = new \Kingsquare\Parser\Banking\Mt940\Engine\Unknown();
                break;
        }
        //Hier creer ik de variabelen van het geuploade bestand

        //Hier parsen we het bestand, daarna maken we de content.
        $parsedStatements = $parser->parse(file_get_contents($filenametemp), $engine);

        //Hier gaan we het coderen naar json. Dat is makkelijk om te converten naar excel als je excel gebruikt. Ook kun je verder online converters vinden hier: 
        //https://data.page/json/csv
        $parsedStatements = json_encode($parsedStatements);

        //Hier zorgen we ervoor dat we een json bestand schrijven.
        $newfile = fopen($filename . ".json", "w");
        fwrite($newfile, $parsedStatements);
        fclose($newfile);
        $newfile = fopen($filename . ".json", "r");
        $fsize = filesize($filename . ".json");

        //Hier zorgen we ervoor dat het automatisch het bestand download.
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename .'.json"');
        header('Expires: 0');
        header('Content-Length: ' . $fsize);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($filename . ".json",true);
        unlink($filename . ".json");
        exit();
    }

    //Check als het een Excel Bestand is.
    elseif (in_array($fileExt,$excelfile)) {
        //Maak variabelen aan van het bestand.
        $inputFileTemp = $_FILES['file']['tmp_name'];

        /**  Maak een nieuwe Reader van het type gedefinieerd in $fileExt  **/
        $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        /**  Adviseer de Reader dat we alleen cell data willen laden  **/
        $reader->setReadDataOnly(true);

        /**  Laad $inputFileTemp naar een Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileTemp);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $worksheet = $spreadsheet->getSheet(0);//

        //Haal de hoogste rij- en kolomnummers op waarnaar in het werkblad wordt verwezen
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $data = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            $riga = array();
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $riga[] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            if (1 === $row) {
                // Header rij. Sla het op in "$keys".
                $keys = $riga;
                continue;
            }
            $data[] = array_combine($keys, $riga);
        }

        //Zorg dat alles wordt omgezet naar json.
        $parsedStatements = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        //Maak nieuw bestand aan.
        $newfile = fopen($filename . ".json", "w");
        fwrite($newfile, $parsedStatements);
        fclose($newfile);

        $newfile = fopen($filename . ".json", "r");
        $fsize = filesize($filename . ".json");

        //Hier zorgen we ervoor dat het automatisch het bestand download.
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename .'.json"');
        header('Expires: 0');
        header('Content-Length: ' . $fsize);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($filename . ".json",true);
        unlink($filename . ".json");
        exit();
    }else{
        header("Location: index.php?error=ditbestandisnietgeldig");
    }
} else{
    header("Location: index.php?error=geenbestandenmeeverzonden");
}
?>