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

class getstatic
{
  static public function upform()
  {
    //construct the upload form using HTML tags
        $form = '<form action="index.php?page=upload" method="post" enctype="multipart/form-data">';
        $form .= '<input type="file" name="fileToUpload" id="filetoupload"><br><br>';
        $form .= '<input type="submit" value="Upload File" name="submit">';
        $form .= '</form>';
        return $form;
  }
}

class headerstatic
{
  static public function sendto($target_file)
  {
    //direct the user to the table class by passing the parameters to the URL
   header('Location: index.php?page=table&doc=' . $target_file);
  }
}

class upload extends page
{
	public function get()
	{   
    $this->html .= '<h1><i>Upload Form</i></h1><hr>';
    //call the static function 
    $this->html .= getstatic::upform();
	}

	public function post()
	{   
		//set the path where the files will be saved
    $target_dir = "/afs/cad/u/a/n/an478/public_html/WSD_project1/uploads/";

    //store the path to the file where it is saved
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        //Check the file size
        if ($_FILES["fileToUpload"]["size"] > 500000)
        {
          echo "Sorry, your file is too large.";       
        }

      
           //move the files to the specified location from, the browser
       if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
       {
         //call static functio that uses header function
         headerstatic::sendto($target_file);
       }  
       else 
       {
         echo "Sorry, there was an error uploading your file.";
      
       }        
    }
}

class table extends page
{
  
  public function get()
  {  
   //open the file taken from the header function 
   $filedoc = $_GET['doc'];
   //open the file
   $handle = fopen($filedoc,"r") or die('Cannot open file:  '.$filedoc);
   
   //create table 
    $display = '<table border="1">';
    while(!feof($handle))
    {
      $ay = fgetcsv($handle);//reads from the file while seperating values
      $arry = (array) $ay;
      $display .= '<tr>';
      foreach($arry as $value) 
      {
        $display .= '<th>'.$value.'</th>';
      }
      $display .= '</tr>'; 
    }
    $display .= '</table>';   
    $this->html .= $display;

    //close the opned file
   fclose($handle);
  }
} 
?>
 
