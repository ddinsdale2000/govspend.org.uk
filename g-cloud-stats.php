
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - G-Cloud Spend Analysis - helping you find more business in Government</title>
    <link rel="stylesheet" href="main.css" type="text/css" />
    <link rel="stylesheet" href="table.css" type="text/css" />
    <link rel="stylesheet" href="button.css" type="text/css" />
</head>
<body>
<?php 
	include_once("analyticstracking.php");
	include('header_main.php'); 
	include('menu_g_cloud2.php');
	include("init.php");  
	$cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
	$cxn2 = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
// 	Calculate the number of records that are being analysed
   	$sql = "SELECT count(*) FROM `g-cloud`";
   	$result=mysqli_query($cxn,$sql);
   	$num_recs = mysqli_fetch_row($result);
// 	Process the parameters in the URL
   	$type = $_GET['type']; // This is a very important variable and controls the format of the page - there are three values:
   						   //   - Summary (or null) - shows the summary tables only e.g. Spend by month
   						   //   - Supplier - shows the spend details for suppliers
   						   //   - Customer - show the spend details for customers
   	$rank = $_GET['rank']; // Specifies the sort order of the customer and supplier lists
   	if (strlen($rank) == 0) {$rank = "total";}

   $supplier = $_GET['supplier']; // Specifies the supplier string to search for
   if (strlen($supplier)==0) {$supplier = "all";}
   $client = $_GET['client']; // Specifies the client string to search for
   if (strlen($client)==0) {$client = "all";}
   $product = $_GET['product']; // Specifies the client string to search for
   if (strlen($product)==0) {$product = "all";}
   $sme = $_GET['sme']; 
   if (strlen($sme)==0){$sme= "all";}
   $analysis = $_GET['count']; 
   if (strlen($analysis)==0){$analysis= "count";}


    echo "<h1>G-Cloud framework analysis</h1>"; 
    echo "<p><form name=\"input\" action=\"g-cloud-stats.php\" method=\"get\">";
    echo "<div class=\"datagrid\"><table>";
   	echo "<tr>"; 

   	echo "<td>"; 
    echo "Product : <input type=\"text\" name=\"product\" value=\"$product\" style=\"width: 65%\">";
   	echo "</td>"; 
   		echo "<td>"; 
       echo "Supplier : <input type=\"text\" name=\"supplier\" value=\"$supplier\" style=\"width: 65%\">";
   		echo "</td>"; 
   		echo "<td>"; 
       echo "Client : <input type=\"text\" name=\"client\" value=\"$client\" style=\"width: 65%\"><br>";
   		echo "</td>"; 
	echo "<td>";
	echo "<input type=\"submit\" class = \"tv_button\"value=\"Run Analysis\">";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>";
 	   echo "Analysis : <select name=\"analysis\" style=\"width: 70%\">";
	   if ($analysis == "count")
	   	{	echo "<option value=\"count\" selected>Count by month</option>";}
	   else
	   	{	echo "<option value=\"count\">Count by month</option>";}

		echo "</select>";	   	
	
	echo "</td>";
	echo "<td>";
 	   echo "Supplier size : <select name=\"sme\" style=\"width: 55%\">";
	   if ($sme == "all")
	   	{	echo "<option value=\"all\" selected>all</option>";}
	   else
	   	{	echo "<option value=\"all\">all</option>";}
	   if ($sme == "sme")
	   	{	echo "<option value=\"sme\" selected>sme</option>";}
	   else
	   	{	echo "<option value=\"sme\">sme</option>";}
	   if ($sme == "large")
	   	{	echo "<option value=\"large\" selected>large</option>";}
	   else
	   	{	echo "<option value=\"large\">large</option>";}
		echo "</select>";	   	
	
	echo "</td>";
	echo "<td></td>";
	echo "<td>";
 	echo "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"".$url."\" data-text=\"G-Cloud spend analysis\" data-via=\"GovSpendOrgUK\" data-size=\"large\" data-hashtags=\"GOVUKdigimkt\">Tweet</a> 
 		  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
	echo "</td>";
	echo "</tr>";
	echo "</table></div>";

	echo "<p>This page is in beta.  It is currently slow to refresh as the calculations are tricky.  We're working on speeding it up.</p>";
	// sql_base is used for the second subquery so it's really important - watch out before editing it
	$sql_base = "select count(distinct customer), count(distinct supplier) from `g-cloud` where 1 ";
	if ($product != "all")
		{	$sql_base .= " and Product_Service_Description like '%$product%'";}
	if ($client != "all")
		{	$sql_base .= " and Customer like '%$client%'";}
	if ($supplier != "all")
		{	$sql_base .= " and Supplier like '%$supplier%'";}
	if ((strlen($sme)>0) and ($sme != "all"))
	{	
		$sql_base .= " and SME = '$sme' ";
	}


    $sql = "select distinct for_month from `g-cloud` order by for_month desc";
   	$result=mysqli_query($cxn,$sql);
	if ($analysis == "count")
 	{
 		$cust_html =  "<div class=\"datagrid\"><table>";
	    $cust_html .= "<thead>";
 	  	$cust_html .= "<tr>"; 
	   	$cust_html .= "<th>Year</th>";
	   	$cust_html .= "<th align = \"right\">Jan</th><th align = \"right\">Feb</th><th align = \"right\">Mar</th><th align = \"right\">Apr</th>";
	   	$cust_html .= "<th align = \"right\">May</th><th align = \"right\">Jun</th><th align = \"right\">Jul</th><th align = \"right\">Aug</th>";
	   	$cust_html .= "<th align = \"right\">Sep</th><th align = \"right\">Oct</th><th align = \"right\">Nov</th><th align = \"right\">Dec</th>";
	   	$cust_html .= "</tr></thead><tbody>";
	   	$supp_html = $cust_html; 
		$current_year = "";
	   	while ($for_month = mysqli_fetch_row($result))     
		{
   		  	if (strlen($current_year) == 0) 
   		  		{	// Initialise
					$year_c = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
					$year_s = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	   	  			$current_year = date("Y",strtotime($for_month[0]));
   		  		}
   		  	if ($current_year ==  date("Y",strtotime($for_month[0])))
				{	// Date falls within the current year
 	  	  			$month = date("m",strtotime($for_month[0]));
					$sql2 = $sql_base . " and for_month <= '$current_year-$month-01'";
   		  			$month = intval($month);
				   	$result2=mysqli_query($cxn2,$sql2);
					$row = mysqli_fetch_row($result2);
					$year_c[$month] = $row[0];
					$year_s[$month] = $row[1];
			  	}
			else
				{	
					$cust_html .=  "</tr><td>$current_year</td>";
					$supp_html .=  "</tr><td>$current_year</td>";
					for ($i=1; $i<=12; $i++)
					{
						if ($year_c[$i]>0) 
						{	
							$cust_html .=  "<td align = \"right\">$year_c[$i]</td>";
							$supp_html .=  "<td align = \"right\">$year_s[$i]</td>";
						} 
						else 
						{	
							$cust_html .= "<td></td>";
							$supp_html .= "<td></td>";
						}
					}
					echo "</tr>";
					$year_c = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
					$year_s = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
   	  				$current_year = date("Y",strtotime($for_month[0]));
   	  				$month = date("m",strtotime($for_month[0]));
					$sql2 = $sql_base . " and for_month <= '$current_year-$month-01'";
   		  			$month = intval($month);
				   	$result2=mysqli_query($cxn2,$sql2);
					$row = mysqli_fetch_row($result2);
					$year_c[$month] = $row[0];
					$year_s[$month] = $row[1];
				}
		}
		$cust_html .=  "</tr><td>$current_year</td>";
		$supp_html .=  "</tr><td>$current_year</td>";
		for ($i=1; $i<=12; $i++)
		{
						if ($year_c[$i]>0) 
					{	
						$cust_html .=  "<td align = \"right\">$year_c[$i]</td>";
						$supp_html .=  "<td align = \"right\">$year_s[$i]</td>";
					} 
					else 
					{	
						$cust_html .= "<td></td>";
						$supp_html .= "<td></td>";
					}
		}
		$cust_html .= "</tr>";
		$supp_html .= "</tr>";

		//	   echo "<tr><td>Total</td>";
		// 	   echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
		// 	   echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
		// 	   echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
		// 	   echo "<td align = \"right\"><b>$total</b></th>";
		// 	   echo "</tr>";
		$cust_html .= "</tbody></table></div>";   
		$supp_html .= "</tbody></table></div>";   

	    echo "<h2>Count of customers by month</h2>"; 
		echo $cust_html;
   		echo "<h2>Count of suppliers by month</h2>"; 
		echo $supp_html;
	}    
?>            

<?php include ("footer.php"); ?>
</body>
</html>