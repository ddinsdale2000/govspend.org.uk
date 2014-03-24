<?php 
//   include_once("analyticstracking.php");
   include("init.php");  
   $cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
// Calculate the number of records that are being analysed
   $sql = "SELECT count(*) FROM `g-cloud`";
   $result=mysqli_query($cxn,$sql);
   $num_recs = mysqli_fetch_row($result);

// Process the parameters in the URL
	$format = strtolower($_GET['format']); // Specifies the format - acceptable values are xml and csv
	if (($format != "csv") and ($format != "xml") and ($format != "html") ) {$format = "html";}	

	$rank = $_GET['rank']; // Specifies the sort order of the customer and supplier lists
	if (strlen($rank) == 0) {$rank = "total";}
      
	$framework = $_GET['framework']; // Specifies the lot to search for
	if (strlen($framework)==0) {$framework = "all";}

	$lot = $_GET['lot']; // Specifies the lot to search for
	if (strlen($lot)==0) {$lot = "all";}

	$supplier = $_GET['supplier']; // Specifies the supplier string to search for
	if (strlen($supplier)==0) {$supplier = "all";}
   
	$customer = $_GET['customer']; // Specifies the client string to search for
	if (strlen($customer)==0) {$customer = "all";}

	$product = $_GET['product']; // Specifies the client string to search for
	if (strlen($product)==0) {$product = "all";}

	$date_from = $_GET['date_from']; // Specifies the client string to search for
	if (strlen($date_from)==0) {$date_from = "all";}
	$date_to = $_GET['date_to']; // Specifies the client string to search for
	if (strlen($date_to)==0) {$date_to = "all";}

	$spend_from = $_GET['spend_from']; // Specifies the client string to search for
	if (strlen($spend_from)==0) {$spend_from = "all";}
	$spend_to = $_GET['spend_to']; // Specifies the client string to search for
	if (strlen($spend_to)==0) {$spend_to = "all";}
	


	$scope = $_GET["scope"];  // specifies a sub query e.g. local = Local Government  
	if (strlen($scope)==0){$scope= "all";}
	if ($scope == "local")      {
       $scope_sql =  "      ((`Customer` like '%council%') or 
                            (`Customer` like '%borough%')) and 
                            (`Customer` <> 'British Council')";}
	elseif ($scope == "health") {
       $scope_sql = "      ((`Customer`like '%nhs%') or 
                            (`Customer` like '%cmu%') or 
                            (`Customer` like '%dh%') or 
                            (`Customer` like '%hospital%') or 
                            (`Customer` like '%pct%') or 
                            (`Customer` like '%care quality%') or 
                            (`Customer` like '%health%')) and
                            (`Customer` not like '%Animal%')";}
	elseif ($scope == "emergency") {
       $scope_sql =  "      (`Customer` like '%polic%') or 
                            (`Customer` like '%fire%') ";}        
	elseif ($scope == "all")  {
       $scope_sql = "";}
	elseif ($scope == "central") {
       $scope_sql = "       ((`Customer` not like '%nhs%') and
                            (`Customer` not like '%cmu%') and
                            (`Customer` not like '%dh%') and 
                            (`Customer` not like '%hospital%') and 
                            (`Customer` not like '%pct%') and 
                            (`Customer` not like '%care quality%') and 
                            (`Customer` not like '%health%')  and
                            (`Customer` not like '%polic%') and 
                            (`Customer` not like '%fire%') and
                            (`Customer` not like '%ambulanc%') and
                            (`Customer` not like '%council%') and 
                            (`Customer` not like '%borough%')) or 
                            ((`Customer` = 'British Council') or
                            (`Customer` like '%Animal%'))
                            ";}

// -------------------------------------------------------------------------------------------------------------------------------    
// OUTPUT records selected
// -------------------------------------------------------------------------------------------------------------------------------    

	$sql = "select `Framework`, `Lot`, `Supplier`, `Customer`, `For_Month`, `Product_Service_Description`, `Total_Charge`  from `g-cloud` where 1"; 

	if ($framework != "all")
		$sql = $sql . " and (framework like '%$framework%')"; 
	if ($lot != "all")
		$sql = $sql . " and (lot like '%$lot%')"; 
	if ($supplier != "all")
		$sql = $sql . " and (supplier like '%$supplier%')"; 
	if ($customer != "all")
		$sql = $sql . " and (customer like '%$customer%')"; 
	if ($product != "all")
		$sql = $sql . " and (product_service_description like '%$product%')"; 
	if ($date_from != "all")
		$sql = $sql . " and (for_month >= '$date_from')"; 
	if ($date_to != "all")
		$sql = $sql . " and (for_month <= '$date_to')"; 
	if ($spend_from != "all")
		$sql = $sql . " and (total_charge >= $spend_from)"; 
	if ($spend_to != "all")
		$sql = $sql . " and (total_charge <= $spend_to)"; 
//	echo $sql."<br>";	
    $result=mysqli_query($cxn,$sql);
//    if ($format == "html") {echo "<table>";}  // this row is commented out but we use if for testing
    while ($row = mysqli_fetch_row($result))     
	{ 	if ($format == "xml")
		{echo "<record><framework>$row[0]</framework><lot>$row[1]</lot><supplier>$row[2]</supplier><customer>$row[3]</customer><month>$row[4]</month><product>$row[5]</product><total>$row[6]</total></record>\n";}
		elseif ($format == "csv")
		{echo "$row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6]\n";}
		elseif ($format == "html")
		{echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row[6]</td></tr>";}
	}
//    if ($format == "html") {echo "</table>";}
     
// END OF TABLE BY CUSTOMER OR SUPPLIER
// -------------------------------------------------------------------------------------------------------------------------------      

?>