<html xmlns="http://www.w3.org/1999/xhtml">
<!-- -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="keywords" content="Government spend public sector contracts finder g-cloud gcloud data contracts-finder">
    <meta name="author" content="Creative Space Learning">
    <title>Import g-cloud data</title>
    <link rel="stylesheet" href="main.css" type="text/css" />
    <link rel="stylesheet" href="button.css" type="text/css" />

</head>

<body>

<?php
// IMPORTANT - 	to stop anyone uploading rubbish into the database, define a variable $security_code in init.php and set it to a code
// 				that users must enter to run the import and update the database.  
$debug = 0;
include('header_main.php'); 
include('menu_g_cloud.php');
include("init.php");  
$cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 

echo "<h1>Preparing file to import into GovSpend database</h1>";


$filename = "./upload/G-Cloud-Total-Spend-22-11-13.csv"; // name of the file to import
$default_filename = "./upload/G-Cloud-Spend-10-04-14 Processed.csv"; // name of the file to import
//$filename = "http://gcloud.civilservice.gov.uk/files/2012/06/G-Cloud-Total-Spend-13-12-13.csv";
$filename = $_POST['filename'];
$import_date = $_POST['import_date'];
$s_code = $_POST['code'];

echo "<p><form name=\"input\" action=\"import_g_cloud.php\" method=\"post\">";
echo "<p style = \"color:#3366ff; font-size:10pt\">
		Import file url: <input type=\"text\" name=\"filename\" size=\"80\"
		value=\"$default_filename\"><br>";
echo "		Data as at: <input type=\"text\" name=\"import_date\" size=\"30\"><br>";
echo "		Security code: <input type=\"text\" name=\"code\" size=\"30\"><br>";
echo "<input type=\"submit\" value=\"Import\" >";
echo "</form></p>";
if (strlen($filename)==0) {die('<p>Please enter the URL and press submit</p>');}
if ($s_code!=$security_code) {die('<p>Incorrect security code '. $s_code."</p>");} // $security_code should be defined in init.php

$sql = "delete from `g-cloud` where 1";
$result=mysqli_query($cxn,$sql);
echo "<p>".mysqli_affected_rows($cxn) . " deleted to make room for import</p>";

$sql = "update `sys_messages` set `message` = '$import_date' where `key_id` = 'g-cloud import'";
$result=mysqli_query($cxn,$sql);
echo "<p>".mysqli_affected_rows($cxn) . " system message updated</p>";


$out_file = "./tmp/data_g_cloud_out.csv";
echo "<p>Import file name is ". $filename ." </p>";
if(!is_file($filename)) {echo "<p>File does not exist - searching for URL</p>";} else {echo "<p>File exists </p>";}
echo "<p>Output file name is ".$out_file." <br>";

$num_header_lines = 0 ; // number of lines to skip at the head of the file
$out_file_handle = fopen($out_file, "w");
$handle = fopen($filename, "r");
if ($handle) {for ($i=1; $i <= $num_header_lines; $i++) {$buffer = fgets($handle);}   // skip header lines
$i = 0; // Counter for record number
$no_failed = 0; // the number of failed inserts into the database
$buffer = fgets($handle); // Get the field names
$field_list = "ID,Framework,Lot,Supplier,Customer,For_Month,Product_Service_Description,Total_Charge";  // For G-Cloud hardcode field names (to remove spaces etc)
fwrite ($out_file_handle, $field_list.chr(13).chr(10));
if ($debug > 0) {   echo $buffer."<br>"; }  // Print field names

    $fields = explode(",", $buffer);
    $num_fields = count ($fields); // number of fields in the file
//    $num_fields = 8; // hardcoded for G-Cloud due to issues in the file format issued by Cabinet Office
    //$num_fields = 8;
	echo "Number of fields is: ".$num_fields. "<br>";
    $i = 0; // Reset for record count
    $at_record_start = 1; // Variable = 1 if we are at the start of a new record or 0 if we are on a record that spans multiple lines
    $in_string = 0; // used to see if we are in a string field that may contain commas that are part of the string and not delimiters e.g. "£2,130.00"
    while (($buffer = fgets($handle)) !== false) {
      $loop_handler += 1;
      $buffer=str_replace(",,,,,,,,,","",$buffer); // this line included to sort issues in the Jan 2014 data release
      if ($at_record_start == 1)
      {  $curr_field = 1; unset($fielddata); $out_string = ""; $at_record_start=0; $sql_out ="'"; } // initialise variables for a new row
    	 for ($j = 0; $j < strlen($buffer); $j++) 
    	 { $char = substr($buffer,$j,1);
    	   if ( $char == "\"")  // If the character is a double quote, it is the beginning or end of a string
    	   {  if ($in_string == 0) {$in_string = 1;} else {$in_string = 0; } } 
    	   elseif ($char == ",") //If the character is a comma AND $in_string ==0, then the character is a field delimiter
    	   {  if ($in_string == 0) 
    	       {  
    	          $curr_field += 1; 
    	          if ($curr_field == 6) 
    	          {  
    	          	 $out_string = $out_string . date_format(date_create("01-".$date_string),"Y-m-d");
    	             $sql_out = $sql_out .date_format(date_create("01-".$date_string),"Y-m-d");
					echo "<p>$date_string</p>";
//    	          	 $out_string = $out_string . date_format(date_create($date_string),"Y-m-d");
//    	             $sql_out = $sql_out .date_format(date_create($date_string),"Y-m-d");
    	          }
    	          $out_string = $out_string . $char; 
    	          $sql_out = $sql_out ."'". $char."'";

    	       }
    	   }
    	   elseif ( ($char == chr(10)) or ($char == chr(13)) or ($char==chr(163)) or (ord($char) >127 ) ) {} // strip carriage returns and line feeds - For G-Cloud only strip £ signs from value field
    	   else // not a double quote or a comma so just add to outstring
    	   { if ($curr_field == 5) {$date_string = $date_string . $char;} // This variable is used to convert field 5 into a date
    	    else 
    	      { $out_string = $out_string . $char; 
    	        $date_string = ""; 
    	        if ($char != "'")
    	           {$sql_out = $sql_out.$char;}
    	      } 
    	   }
    	 }
        if ($curr_field == $num_fields) // in this case, the end of record and end of line are OK
         { $i = $i + 1 ;
           $at_record_start = 1;
           if ($debug > 0) {echo  $curr_field. "," . $i .",".$out_string . "<br>"; }
           fwrite ($out_file_handle,  $i .",".$out_string.chr(13).chr(10) );
           $sql = "insert into `g-cloud` (Framework, Lot, Supplier, Customer, For_Month, Product_Service_Description, Total_Charge, Sector, SME)";
     	   $sql = $sql ." values (".$sql_out."')";          
           echo "<p>".$sql."</p>";
		   $result=mysqli_query($cxn,$sql);
		   if ($result) {echo "<p> $loop_handler - inserted</p>";} else {echo "<p>$loop_handler - insert failed</p>";}
		   if ($result) {} else {$no_failed += 1;}
           
         }
        else { 
       //       echo  $buffer ;
             }
    if ($loop_handler > 15000) {echo "<p>Loop break at 15,000 records</p>";  break;} // this line put is as a safety measure
    if ($debug > 0) {echo "<p>Loop Handler" . $loop_handler."</p>";}
    }
 
 
 
 
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
    fclose ($out_file_handle);
    echo "<h2>$i records written to output file</h2>";
    echo "<p>Number of failed inserts: $no_failed</p>";
    echo "<p>Use MySQL import with settings </p>";
    echo "<p> - Number of records (queries) to skip from start - 1 </p>";
    echo "<p> - Fields terminated by - , </p>";
}
else
{echo "Unable to open: " + $filename;}
?>
</body>
</html>
