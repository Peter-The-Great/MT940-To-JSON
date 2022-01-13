<?php
require 'vendor/autoload.php'; // require composer dependencies
//http://jsonviewer.stack.hu/
use Composer\Console\Application;

// instantiate the actual parser
// and parse them from a given file, this could be any file or a posted string
//__DIR__.

if(isset($_FILES['file'],$_POST['engine'])){
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
        $tmpFile = $_FILES['file'];
        $Tijdelijk = $tmpFile['tmp_name'];
        $name = $tmpFile['name'];
        $parsedStatements = $parser->parse(file_get_contents($Tijdelijk), $engine);

        $parsedStatements = json_encode($parsedStatements);

        echo $parsedStatements;
    }
}
?>