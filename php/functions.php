<?php
require 'xml.php';

/**
 * @author Peter-The-Great
 * @license http://opensource.org/licenses/MIT MIT
 */

class document {
    protected $file;
    public $filename;
    public $filenametemp;
    public $fileExt;
    public $swifile;
    public $jsonfile;
    public $excelfile;

    public function __construct() {
        $this->file = $_FILES['file'];
        $this->filename = $_FILES['file']['name'];
        $this->filenametemp = $_FILES['file']['tmp_name'];
        $this->fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $this->swifile = array("txt","swi","mt940", "mta");
        $this->jsonfile = array("txt","json");
        $this->excelfile = array("xlsx", "xls");
    }
    /**
     * Parse the given swi file into an json file by using the MT940 parser from Kingsquare.
     */
    public function switojson(){
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
        $parsedStatements = $parser->parse(file_get_contents($this->filenametemp), $engine);

        //Hier gaan we het coderen naar json. Dat is makkelijk om te converten naar excel als je excel gebruikt. Ook kun je verder online converters vinden hier: 
        //https://data.page/json/csv
        $parsedStatements = json_encode($parsedStatements);

        //Hier zorgen we ervoor dat we een json bestand schrijven.
        $newfile = fopen($this->filename . ".json", "w");
        fwrite($newfile, $parsedStatements);
        fclose($newfile);
        $newfile = fopen($this->filename . ".json", "r");
        $fsize = filesize($this->filename . ".json");

        //Hier zorgen we ervoor dat het automatisch het bestand download.
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $this->filename .'.json"');
        header('Expires: 0');
        header('Content-Length: ' . $fsize);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($this->filename . ".json",true);
        unlink($this->filename . ".json");
    }
    
    /**
     * Parse the given excel file into an json file by using the excel to json function included.
     */
    public function ExceltoJson(){
        
        //Zorg dat alles wordt omgezet naar json.
        $parsedStatements = excel2json();

        //Maak nieuw bestand aan.
        $newfile = fopen($this->filename . ".json", "w");
        fwrite($newfile, $parsedStatements);
        fclose($newfile);

        $newfile = fopen($this->filename . ".json", "r");
        $fsize = filesize($this->filename . ".json");

        //Hier zorgen we ervoor dat het automatisch het bestand download.
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $this->filename .'.json"');
        header('Expires: 0');
        header('Content-Length: ' . $fsize);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($this->filename . ".json",true);
        unlink($this->filename . ".json");
    }

    /**
     * Parse the given json file into an xml file by using the array to xml function which is customly made.
     */
    public function jsontoxml(){
        
        // Decodeer jSON naar array
        $JSON = json_decode(file_get_contents($this->filenametemp), true);

        // Bewerk de array tot een xml bestand
        //$xml = Array2XML::createXML('<record/>', $JSON);
        $xml = array2xml($JSON, false);

        //Maak nieuw bestand aan.
        $newfile = fopen($this->filename . ".xml", "w");
        fwrite($newfile, $xml);
        fclose($newfile);

        $newfile = fopen($this->filename . ".xml", "r");
        $fsize = filesize($this->filename . ".xml");

        //Hier zorgen we ervoor dat het automatisch het bestand download.
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $this->filename .'.xml"');
        header('Expires: 0');
        header('Content-Length: ' . $fsize);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($this->filename . ".xml",true);
        unlink($this->filename . ".xml");
    }
}
?>