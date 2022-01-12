<?php
require 'vendor/autoload.php'; // require composer dependencies
//http://jsonviewer.stack.hu/
use Composer\Console\Application;

// instantiate the actual parser
// and parse them from a given file, this could be any file or a posted string
//__DIR__.
$Toegestaan = array("txt","swi","mt940");
$fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

if(isset($_FILES['file']) && in_array($fileExt,$Toegestaan)){
    $parser = new \Kingsquare\Parser\Banking\Mt940();
    $engine = new \Kingsquare\Parser\Banking\Mt940\Engine\Rabo();
    $tmpFile = $_FILES['file'];
    $Tijdelijk = $tmpFile['tmp_name'];
    $name = $tmpFile['name'];
    var_dump($tmpFile);
    $parsedStatements = $parser->parse(file_get_contents($Tijdelijk), $engine);

    $parsedStatements = json_encode($parsedStatements);

    echo $parsedStatements;
}
?>