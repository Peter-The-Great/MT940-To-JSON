<?php

/** 
 * Iterative XML constructor 
 * 
 * @param array $array 
 * @param object|boolean $xml 
 * @return string 
 */

function array2xml( $array, $xml = false) {
    // Test if iteration
    if ( $xml === false ) {
      $xml = new SimpleXMLElement('');
    }
    
    // Loop through array
    foreach( $array as $key => $value ) {
        // Another array? Iterate
        if ( is_array( $value ) ) {
          array2xml( $value, $xml->addChild( $key ) );
        } else {
          $xml->addChild( $key, $value );
        }
    }
    
    // Return XML
    return $xml->asXML();
}

function excel2json(){
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
      $keys = array();
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
  return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

class Array2XML {

  private static $xml = null;
  private static $encoding = 'UTF-8';

  /**
   * Initialize the root XML node [optional]
   * @param $version
   * @param $encoding
   * @param $format_output
   */
  public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
      self::$xml = new DomDocument($version, $encoding);
      self::$xml->formatOutput = $format_output;
  self::$encoding = $encoding;
  }

  /**
   * Convert an Array to XML
   * @param string $node_name - name of the root node to be converted
   * @param array $arr - aray to be converterd
   * @return DomDocument
   */
  public static function &createXML($node_name, $arr=array()) {
      $xml = self::getXMLRoot();
      $xml->appendChild(self::convert($node_name, $arr));

      self::$xml = null;    // clear the xml node in the class for 2nd time use.
      return $xml;
  }

  /**
   * Convert an Array to XML
   * @param string $node_name - name of the root node to be converted
   * @param array $arr - aray to be converterd
   * @return DOMNode
   */
  private static function &convert($node_name, $arr=array()) {

      //print_arr($node_name);
      $xml = self::getXMLRoot();
      $node = $xml->createElement($node_name);

      if(is_array($arr)){
          // get the attributes first.;
          if(isset($arr['@attributes'])) {
              foreach($arr['@attributes'] as $key => $value) {
                  if(!self::isValidTagName($key)) {
                      throw new Exception('[Array2XML] Illegal character in attribute name. attribute: '.$key.' in node: '.$node_name);
                  }
                  $node->setAttribute($key, self::bool2str($value));
              }
              unset($arr['@attributes']); //remove the key from the array once done.
          }

          // check if it has a value stored in @value, if yes store the value and return
          // else check if its directly stored as string
          if(isset($arr['@value'])) {
              $node->appendChild($xml->createTextNode(self::bool2str($arr['@value'])));
              unset($arr['@value']);    //remove the key from the array once done.
              //return from recursion, as a note with value cannot have child nodes.
              return $node;
          } else if(isset($arr['@cdata'])) {
              $node->appendChild($xml->createCDATASection(self::bool2str($arr['@cdata'])));
              unset($arr['@cdata']);    //remove the key from the array once done.
              //return from recursion, as a note with cdata cannot have child nodes.
              return $node;
          }
      }

      //create subnodes using recursion
      if(is_array($arr)){
          // recurse to get the node for that key
          foreach($arr as $key=>$value){
              if(!self::isValidTagName($key)) {
                  throw new Exception('[Array2XML] Illegal character in tag name. tag: '.$key.' in node: '.$node_name);
              }
              if(is_array($value) && is_numeric(key($value))) {
                  // MORE THAN ONE NODE OF ITS KIND;
                  // if the new array is numeric index, means it is array of nodes of the same kind
                  // it should follow the parent key name
                  foreach($value as $k=>$v){
                      $node->appendChild(self::convert($key, $v));
                  }
              } else {
                  // ONLY ONE NODE OF ITS KIND
                  $node->appendChild(self::convert($key, $value));
              }
              unset($arr[$key]); //remove the key from the array once done.
          }
      }

      // after we are done with all the keys in the array (if it is one)
      // we check if it has any text value, if yes, append it.
      if(!is_array($arr)) {
          $node->appendChild($xml->createTextNode(self::bool2str($arr)));
      }

      return $node;
  }

  /*
   * Get the root XML node, if there isn't one, create it.
   */
  private static function getXMLRoot(){
      if(empty(self::$xml)) {
          self::init();
      }
      return self::$xml;
  }

  /*
   * Get string representation of boolean value
   */
  private static function bool2str($v){
      //convert boolean to text value.
      $v = $v === true ? 'true' : $v;
      $v = $v === false ? 'false' : $v;
      return $v;
  }

  /*
   * Check if the tag name or attribute name contains illegal characters
   * Ref: http://www.w3.org/TR/xml/#sec-common-syn
   */
  private static function isValidTagName($tag){
      $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';
      return preg_match($pattern, $tag, $matches) && $matches[0] == $tag;
  }
}
?>
