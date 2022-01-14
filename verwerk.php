<?php
require 'vendor/autoload.php'; // require composer dependencies
//http://jsonviewer.stack.hu/
use Composer\Console\Application;

// instantiate the actual parser
// and parse them from a given file, this could be any file or a posted string
//__DIR__.

//Check of de files er zijn.
if(isset($_FILES['file'],$_POST['engine'])){
    //Check of het bestand geldig is ja of nee.
    $Toegestaan = array("txt","swi","mt940");
    $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    
    if(in_array($fileExt,$Toegestaan)){
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
        $tmpFile = $_FILES['file'];
        $Tijdelijk = $tmpFile['tmp_name'];

        //Hier parsen we het bestand, daarna maken we de content.
        $parsedStatements = $parser->parse(file_get_contents($Tijdelijk), $engine);

        //Hier gaan we het coderen naar json. Dat is makkelijk om te converten naar excel als je excel gebruikt. Ook kun je verder online converters vinden hier: 
        //https://data.page/json/csv
        $parsedStatements = json_encode($parsedStatements);

        $newfile = fopen("result.json", "w");
        fwrite($newfile, $parsedStatements);
        fclose($newfile);
        echo file_exists($newfile);
        exit();
        if (file_exists($newfile)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($newfile).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($newfile));
            readfile($newfile);
            header("Location: index.php");
        }

    }else{
        header("Location: index.php?error=ditbestandisnietgeldig.");
    }
}
else{
    header("Location: index.php?error=geenbestandenmeeverzonden.");
}
?>