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
<h1>Preparing file to import into MySQL database</h1>

<?php
$debug = 0;
$filename = "./tmp/G-Cloud-Total-Spend-13-12-13.csv"; // name of the file to import
//$filename = "http://gcloud.civilservice.gov.uk/files/2012/06/G-Cloud-Total-Spend-13-12-13.csv";
$out_file = "./tmp/data_g_cloud_out.csv";
echo "<p>Import file name is ". $filename ." </p>";
if(!is_file($filename)) {echo "<p>File does not exits </p>";} else {echo "<p>File exists </p>";}
echo "<p>Output file name is ".$out_file." <br>";

$num_header_lines = 0 ; // number of lines to skip at the head of the file
$out_file_handle = fopen($out_file, "w");
$handle = fopen($filename, "r");
if ($handle) {for ($i=1; $i <= $num_header_lines; $i++) {$buffer = fgets($handle);}   // skip header lines
$i = 0; // Counter for record number
$buffer = fgets($handle); // Get the field names
$field_list = "ID,Framework,Lot,Supplier,Customer,For_Month,Product_Service_Description,Total_Charge";  // For G-Cloud hardcode field names (to remove spaces etc)
fwrite ($out_file_handle, $field_list.chr(13).chr(10));
if ($debug > 0) {   echo $buffer."<br>"; }  // Print field names

    $fields = explode(",", $buffer);
    $num_fields = count ($fields); // number of fields in the file
    //$num_fields = 8;
if ($debug > 0) {echo "Number of fields is: ".$num_fields. "<br>";}
    $i = 0; // Reset for record count
    $at_record_start = 1; // Variable = 1 if we are at the start of a new record or 0 if we are on a record that spans multiple lines
    $in_string = 0; // used to see if we are in a string field that may contain commas that are part of the string and not delimiters e.g. "£2,130.00"
    while (($buffer = fgets($handle)) !== false) {
      $loop_handler += 1;
      if ($at_record_start == 1)
      {  $curr_field = 1; unset($fielddata); $out_string = ""; $at_record_start=0; } // initialise variables for a new row
    	 for ($j = 0; $j < strlen($buffer); $j++) 
    	 { $char = substr($buffer,$j,1);
    	   if ( $char == "\"")  // If the character is a double quote, it is the beginning or end of a string
    	   {  if ($in_string == 0) {$in_string = 1;} else {$in_string = 0; } } 
    	   elseif ($char == ",") //If the character is a comma AND $in_string ==0, then the character is a field delimiter
    	   {  if ($in_string == 0) 
    	       {  
    	          $curr_field += 1; 
    	          if ($curr_field == 6) 
    	          {  $out_string = $out_string . date_format(date_create("01-".$date_string),"Y-m-d");
    	          }
    	          $out_string = $out_string . $char; 

    	       }
    	   }
    	   elseif ( ($char == chr(10)) or ($char == chr(13)) or ($char==chr(163)) ) {} // strip carriage returns and line feeds - For G-Cloud only strip £ signs from value field
    	   else // not a double quote or a comma so just add to outstring
    	   { if ($curr_field == 5) {$date_string = $date_string . $char;} // This variable is used to convert field 5 into a date
    	    else {$out_string = $out_string . $char; $date_string = "";} 
    	   }
    	 }
        if ($curr_field == $num_fields) // in this case, the end of record and end of line are OK
         { $i = $i + 1 ;
           $at_record_start = 1;
       //    echo $buffer;
           if ($debug > 0) {echo  $curr_field. "," . $i .",".$out_string . "<br>"; }
           fwrite ($out_file_handle,  $i .",".$out_string.chr(13).chr(10) );
         }
        else { 
       //       echo  $buffer ;
             }
    if ($loop_handler > 10000) {echo "<p>Look break</p>";  break;} // this line put is as a safety measure
    if ($debug > 0) {echo "<p>Loop Handler" . $loop_handler."</p>";}
    }
 
 
 
 
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
    fclose ($out_file_handle);
    echo "<h2>$i records written to output file</h2>";
    echo "<p>Use MySQL import with settings </p>";
    echo "<p> - Number of records (queries) to skip from start - 1 </p>";
    echo "<p> - Fields terminated by - , </p>";
}
else
{echo "Unable to open: " + $filename;}
?>
</body>
</html>
