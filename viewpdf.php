<?php 
if(isset($_REQUEST["URL"])){
    $URL = $_REQUEST["URL"];
    $pdf_content = file_get_contents($URL);
    //Specify that the content has PDF Mime Type
    header("Content-Type: application/pdf");
    //Display it
    echo $pdf_content;    
}
?>

