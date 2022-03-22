<?php
require 'vendor/autoload.php'; // require composer dependencies
//http://jsonviewer.stack.hu/
use Composer\Console\Application;

// instantiate the actual parser
// and parse them from a given file, this could be any file or a posted string
//__DIR__.

require 'php/functions.php';

//Check of de files er zijn.
if(isset($_FILES['file'],$_POST['engine'],$_POST['crsftoken'])){
    $bestand = new document();
    if(in_array($bestand->fileExt, $bestand->swifile) && $_POST['type'] = "JSON"){
        $bestand->switojson();
        exit();
    }elseif (in_array($bestand->fileExt, $bestand->excelfile) && $_POST['type'] = "JSON") {
        $bestand->ExceltoJson();
        exit();
    }elseif (in_array($bestand->fileExt, $bestand->excelfile) && $_POST['type'] = "XML") {
        $bestand->jsontoxml();
        exit();
    }
    else{
        header("Location: index.php?error=ditbestandisnietgeldig");
    }
} else{
    header("Location: index.php?error=geenbestandenmeeverzonden");
}
?>
?>