<?php
require 'vendor/autoload.php'; // require composer dependencies
//http://jsonviewer.stack.hu/
use Composer\Console\Application;

// instantiate the actual parser
// and parse them from a given file, this could be any file or a posted string
//__DIR__.

if(isset($_FILES['file'])){
    $parser = new \Kingsquare\Parser\Banking\Mt940();
    $engine = new \Kingsquare\Parser\Banking\Mt940\Engine\Rabo();
    $tmpFile = $_FILES['file'];
    $Tijdelijk = $tmpFile['tmp_name'];
    $name = $tmpFile['name'];
    $type = $tmpFile['type'];
    $parsedStatements = $parser->parse(file_get_contents($tmpFile), $engine);
    //print_r($parsedStatements);

    $parsedStatements = json_encode($parsedStatements);

    echo $parsedStatements;
}
?>