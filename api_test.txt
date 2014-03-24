<?php

$url = $_GET['url'];
if (strlen($url) == 0)		
	// For demo purposes, we are using the html format.  You can change this to csv or xml, it just wont look pretty on screen.
//	{	$filename = "http://localhost/govspend/api.php?format=html&spend_from=100&spend_to=1000&date_from=2014-01-01";}
	{	$filename = "http://govspend.org.uk/api.php?format=html&spend_from=100&spend_to=1000&date_from=2014-01-01";}
else
	{	$filename=$url;}
	
// Open the URL result set
$handle = fopen($filename, "r");

// if a result set was received
if ($handle) 
{	
	$count == 0; // counts the number of rows returned
// To allow formatting of tables (when using html format) the calling programme needs to provide the table definition and any css.
	echo "<html><head></head><body>";
// This is the form that allows you to amend and test URLs.
	echo "<p><form name=\"input\" action=\"api_test.php\" method=\"get\">";
	echo "URL <input type=\"text\" name=\"url\" value=\"$filename\" size=\"150\"><br>";
	echo "<input type=\"submit\" value=\"Search\">";
	echo "</form></p>";
// This is the table header - titles are set to be parameter names for easy reference	
	echo "<table>" ;
	echo "<tr><th>framework</th><th>lot</th><th>supplier</th><th>customer</th><th>date_from<br>date_to</th>";
	echo "<th>product</th><th>spend_from<br>spend_to</th></tr>";

// This while loop is the business end of this test program.  It reads and processes the contents of the query results.	
	while (($buffer = fgets($handle)) !== false) // read until end of file
	{
		$count += 1; 						// Increment counter by 1		
		$fields = explode("," ,$buffer); 	// expand the result set into an array - works for csv format only
		$framework = $fields[0];			// The next six lines show how to extract fields from the import line - csv format only
		$lot = $fields[1];
		$supplier = $fields[2];
		$customer = $fields[3];
		$date = $fields[4];
		$product = $fields[5];
		$spend = $product[6];
		$numfields = count($fields) ;		// The number of fields returned	
		echo $buffer."<br>";				// This will output the row unchanged except for a new line for each row.
	}
	echo "</table></body></html>";
	fclose($handle);
}
else {echo "error 1 - unable to open file";}
	
?>
