<html xmlns="http://www.w3.org/1999/xhtml">
<!-- -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="keywords" content="Government spend public sector contracts finder g-cloud gcloud data contracts-finder">
    <meta name="author" content="Creative Space Learning">
    <title>Import pipeline data</title>
    <link rel="stylesheet" href="main.css" type="text/css" />
    <link rel="stylesheet" href="button.css" type="text/css" />

</head>

<body>
<h1>Preparing import file</h1>

<?php
$debug = 0;
$filename = "./data_list_of_current_pipelines.csv"; // name of the file to import
$filename = "http://online.contractsfinder.businesslink.gov.uk/public_files/Reports/NotSet/List_of_current_pipelines.csv";
$out_file = "./tmp/data_list_of_current_pipelines_out.csv";

echo "Import file name is ./tmp/data_list_of_current_pipelines_out.csv";

$num_header_lines = 3 ; // number of lines to skip at the head of the file
$out_file_handle = fopen($out_file, "w");
$handle = @fopen($filename, "r");
if ($handle) {
    for ($i=1; $i <= $num_header_lines; $i++) {$buffer = fgets($handle);} 
    $i = 0; // Counter for record number
    $buffer = fgets($handle); // Get the field names
if ($debug > 0) {   echo $buffer; }  // Print field names

    $fields = explode(",", $buffer);
    $num_fields = count ($fields); // number of fields in the file
    echo $numfields;
    $i = 0; // Reset for record count
    $at_record_start = 1; // Variable = 1 if we are at the start of a new record or 0 if we are on a record that spans multiple lines
    $in_string = 0; // used to see if we are in a string field that may contain commas that are part of the string and not delimiters e.g. "£2,130.00"
    while (($buffer = fgets($handle)) !== false) {
      if ($at_record_start == 1)
      {  $curr_field = 1; unset($fielddata); $out_string = ""; $at_record_start=0; } // initialise variables for a new row
    	 for ($j = 0; $j < strlen($buffer); $j++) 
    	 { $char = substr($buffer,$j,1);
    	   if ( $char == "\"")  // If the character is a double quote, it is the beginning or end of a string
    	   {  if ($in_string == 0) {$in_string = 1;} else {$in_string = 0; } } 
    	   elseif ($char == ",") //If the character is a comma AND $in_string ==0, then the character is a field delimiter
    	   {  if ($in_string == 0) 
    	       {  $out_string = $out_string . $char; 
    	          $curr_field += 1; 
    	       }
    	   }
    	   elseif ( ($char == chr(10)) or ($char == chr(13))) {} // strip carriage returns and line feeds
    	   else // not a double quote or a comma so just add to outstring
    	   {$out_string = $out_string . $char;}
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
    
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
    fclose ($out_file_handle);
    echo "<h2>$i records written to import file</h2>";
}
else
{echo "Unable to open: " + $filename;}
?>
</body>
</html>
