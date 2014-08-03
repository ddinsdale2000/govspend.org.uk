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
<h1>Preparing file to import into MySQL database</h1>

<?php
include('header_main.php'); 
include('menu_g_cloud.php');
include("init.php");  
$cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 

$debug = 0;
$filename = "./tmp/List_of_current_pipelines.csv"; // name of the file to import
$filename = "http://online.contractsfinder.businesslink.gov.uk/public_files/Reports/NotSet/List_of_current_pipelines.csv";
$out_file = "./tmp/data_list_of_current_pipelines_out.csv";

echo "<p>Import file name is ". $filename ." </p>";
echo "<p>Output file name is ".$out_file." <br>";

$num_header_lines = 3 ; // number of lines to skip at the head of the file
$out_file_handle = fopen($out_file, "w");
$handle = fopen($filename, "r");
if ($handle) {
    for ($i=1; $i <= $num_header_lines; $i++) 
      {  $buffer = fgets($handle);
      	if ($i==2)
      	{   $import_date = explode(",", $buffer);
      	    echo "<p> Import Date is ".$import_date[1]."</p>";
      	}
      } 
    $sql = "delete from `pipeline` where 1";
	$result=mysqli_query($cxn,$sql);
	echo "<p>".mysqli_affected_rows($cxn) . " deleted to make room for import</p>";

	$sql = "update `sys_messages` set `message` = '$import_date[1]' where `key_id` = 'pipeline import'";
	$result=mysqli_query($cxn,$sql);
	echo "<p>".mysqli_affected_rows($cxn) . " sys messages updated</p>";

    $i = 0; // Counter for record number
    $buffer = fgets($handle); // Get the field names
if ($debug > 0) {   echo $buffer; }  // Print field names

    $fields = explode(",", $buffer);
    $num_fields = count ($fields); // number of fields in the file
    echo $numfields;
	// Hardcoding field names due to issue with new field (Textbox39) in august feed.  We think Textbox39 should be SpendFinancial2019_20
	$fields = "	id,NoticeOrganisationName,NoticeTitle,PipelineType,ReferenceNumber,Confidence,SpendFinancial2014_15,SpendFinancial2015_16,
				SpendFinancial2016_17,SpendFinancial2017_18,SpendFinancial2018_19,SpendFinancial2019_20,TotalCapitalCost,DeliveryLocation,
				DeliveryLocationNUTSCode,DeliveryLocationLAUCode,DeliveryLocationComments,StartDate,ApproachToMarket,ApproachDate,
				LastChangeDate,PublishedDate,ContactEmail,BuyerGroupID,BuyerGroupName,NumberOfDocuments,CPVCodes,
				NoticeID,ParentNoticeID,RootNoticeID,TopBuyerGroup,TopBuyerGroupName,TopLevelDepartment,TopLevelDepartmentName,URL" ;

    $i = 0; // Reset for record count
    $at_record_start = 1; // Variable = 1 if we are at the start of a new record or 0 if we are on a record that spans multiple lines
    $in_string = 0; // used to see if we are in a string field that may contain commas that are part of the string and not delimiters e.g. "£2,130.00"
	$sql_out = "'";
    while (($buffer = fgets($handle)) !== false) {
      If ($debug>0) {echo "Record" . $i;}  
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
    	          $sql_out = $sql_out ."'". $char."'";
    	       }
    	   }
    	   elseif ( ($char == chr(10)) or ($char == chr(13))) {} // strip carriage returns and line feeds
    	   else // not a double quote or a comma so just add to outstring
    	   {  $out_string = $out_string . $char;
    	      if ($char != "'")
    	      {  $sql_out = $sql_out.$char;}
    	   }
    	 }
        if ($curr_field == $num_fields) // in this case, the end of record and end of line are OK
         { $i = $i + 1 ;
           $at_record_start = 1;
       //    echo $buffer;
           if ($debug > 0) {echo  $curr_field. "," . $i .",".$out_string . "<br>"; }
           fwrite ($out_file_handle,  $i .",".$out_string.chr(13).chr(10) );
           $sql = "insert into pipeline ($fields) values ('".$i ."',".$sql_out."')";          
           echo "<p>".$sql."</p>";
		   $result=mysqli_query($cxn,$sql);
		   $sql_out = "'";

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
    echo "<h2>$i records written to output file</h2>";
    echo "<p>Use MySQL import with settings </p>";
    echo "<p> - Number of records (queries) to skip from start - 0 </p>";
    echo "<p> - Fields terminated by - , </p>";

}
else
{echo "Unable to open: " + $filename;}
?>
</body>
</html>
