<?php

class core
{
 public static $config;
 public static $text;
 private $configFileName = 'config/config.ini';
 private $textFileName = 'text/text.php';
  
  /*
 * @method __construct
 * @Arguments: none
 * @Description: Method sets config and text variables
 */
 
 public function __construct()
 {
  if(file_exists($this -> configFileName) && (filesize($this -> configFileName) !== 0))
  { 
   $file = parse_ini_file($this -> configFileName, true);
   self::$config = $file;
  }
  else
   return false;
  
  if(file_exists($this -> textFileName) && (filesize($this -> textFileName) !== 0))
  {
   require $this -> textFileName;
   self::$text = $text;
  }
  else 
   return false;
 }
}

?>