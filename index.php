<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage
{
	public static function autoload($class)
	{
     include $class . '.php';
	}
}
  

spl_autoload_register(array('Manage','autoload'));

//instantiate the program object
$obj = new main();

class main
{
	
	function __construct()
	{
	  $pageRequest = 'upload';


	  if (isset($_REQUEST['page'])) 
	  {
	   $pageRequest = $_REQUEST['page'];
	  }
	  $page = new $pageRequest;

    //check for the appropriate methods to be called and assign the appropriate function 
	  if($_SERVER['REQUEST_METHOD'] == 'GET')
	  {
     $page->get();
	  }
	  else 
	  {
	   $page->post();
	  }
	}
}

abstract class page
{
 protected $html;
 
 public function __construct()
 {
 	$this->html .= '<html>';
 	$this->html .= '<link rel="stylesheet" href="style.css">';
 	$this->html .= '<body>';
 }

 public function __destruct()
 {
 	$this->html .='<body></html>';
 	print_r($this->html);
 }

//default get and most methods
 public function get()
 {
 	echo 'default get message';
 }

 public function post()
 {
 	print_r($_POST);
 }
} 


class upload extends page
{
	public function get()
	{   
		//construct the upload form using HTML tags
        $form = '<form action="index.php?page=upload" method="post" enctype="multipart/form-data">';
        $form .= '<input type="file" name="fileToUpload" id="filetoupload"><br><br>';
        $form .= '<input type="submit" value="Upload File" name="submit">';
        $form .= '</form>';
        $this->html .= '<h1><i>Upload Form</i></h1><hr>';
        $this->html .= $form;
	}

	public function post()
	{   
		//set the path where the files will be saved
        
    }
}

class table extends page
{
  
}
?>
 
