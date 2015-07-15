
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - G-Cloud Analysis - helping you win more business with Government</title>
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
   if ($supplier != "all"){$rank = "total";}

   $client = $_GET['client']; // Specifies the client string to search for
   if (strlen($client)==0) {$client = "all";}
   if ($client != "all"){$rank = "total";}

   $product = $_GET['product']; // Specifies the client string to search for
   if (strlen($product)==0) {$product = "all";}
   $sme = $_GET['sme']; 
   if (strlen($sme)==0){$sme= "all";}
   $analysis = $_GET['analysis']; 
   if (strlen($analysis)==0){$analysis= "count";}
   $sector = $_GET['sector']; 
   if (strlen($sector)==0){$sector = "all";}
   $rank = $_GET['rank']; 
   if (strlen($rank)==0){$rank = "number";}
   $count_type = $_GET['count_type']; 
   if (strlen($count_type) == 0) {$count_type ="cumulative";}


    echo "<h1>G-Cloud framework analysis</h1>"; 
    echo "<p><form name=\"input\" action=\"g-cloud-stats.php\" method=\"get\">";
    echo "<div class=\"datagrid\"><table>";
   	echo "<tr>"; 
   	echo "<td>"; 
	
 	   echo "Analysis : <select name=\"analysis\" style=\"width: 65%\">";
	   if ($analysis == "count")
	   	{	echo "<option value=\"count\" selected>Count by month</option>";}
	   else
	   	{	echo "<option value=\"count\">Count by month</option>";}
	   if ($analysis == "supplier")
	   	{	echo "<option value=\"supplier\" selected>Suppliers</option>";}
	   else
	   	{	echo "<option value=\"supplier\">Suppliers</option>";}
	   if ($analysis == "customer")
	   	{	echo "<option value=\"customer\" selected>Customers</option>";}
	   else
	   	{	echo "<option value=\"customer\">Customers</option>";}

		echo "</select>";	   	
   	echo "</td>"; 
   	echo "<td >"; 
   	echo "</td>"; 
   	echo "<td>"; 
 	echo "</td>"; 
   	echo "<td>"; 
   	echo "</td>"; 

	echo "</tr>";
   	echo "<tr>"; 
   	echo "<td>Filters and options"; 
   	echo "</td>"; 
   	echo "<td>"; 
   	echo "</td>"; 
   	echo "<td>"; 
   	echo "</td>"; 
   	echo "<td>"; 
   	echo "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"".$url."\" data-text=\"G-Cloud spend analysis\" data-via=\"GovSpendOrgUK\" data-size=\"large\" data-hashtags=\"GOVUKdigimkt\">Tweet results</a> 
 		  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
   	echo "</td>"; 

	echo "</tr>";

	
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
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
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
	echo "<td>";
		
  	   echo "Sector : <select name=\"sector\" style=\"width: 75%\">";
	   if ($sector == "all")
	   	{	echo "<option value=\"all\" selected>all</option>";}
	   else
	   	{	echo "<option value=\"all\">all</option>";}

	   if ($sector == "central")
	   	{	echo "<option value=\"central\" selected>Central Government</option>";}
	   else
	   	{	echo "<option value=\"central\">Central Government</option>";}

	   if ($sector == "local")
	   	{	echo "<option value=\"local\" selected>Local Government</option>";}
	   else
	   	{	echo "<option value=\"local\">Local Government</option>";}

	   if ($sector == "health")
	   	{	echo "<option value=\"health\" selected>Health</option>";}
	   else
	   	{	echo "<option value=\"health\">Health</option>";}

	   if ($sector == "fire")
	   	{	echo "<option value=\"fire\" selected>Fire & rescue</option>";}
	   else
	   	{	echo "<option value=\"fire\">Fire & rescue</option>";}

	   if ($sector == "police")
	   	{	echo "<option value=\"police\" selected>Police</option>";}
	   else
	   	{	echo "<option value=\"police\">Police</option>";}

	   if ($sector == "devolved")
	   	{	echo "<option value=\"devolved\" selected>Devolved</option>";}
	   else
	   	{	echo "<option value=\"devolved\">Devolved</option>";}

	   if ($sector == "education")
	   	{	echo "<option value=\"education\" selected>Education</option>";}
	   else
	   	{	echo "<option value=\"education\">Education</option>";}

	   if ($sector == "profit")
	   	{	echo "<option value=\"profit\" selected>Not for profit</option>";}
	   else
	   	{	echo "<option value=\"profit\">Not for profit</option>";}

	   if ($sector == "private")
	   	{	echo "<option value=\"private\" selected>Private</option>";}
	   else
	   	{	echo "<option value=\"private\">Private</option>";}
		echo "</select>";	   	
	echo "</td>";
	echo "<td>";
	if (($analysis == "customer") or ($analysis == "supplier"))
	{
		echo "Rank by : <select name=\"rank\" style=\"width: 65%\">";
	   if ($rank == "number")
	   	{	echo "<option value=\"number\" selected>Number</option>";}
	   else
	   	{	echo "<option value=\"number\">Number</option>";}   	
	   if ($rank == "total")
	   	{	echo "<option value=\"total\" selected>Total £k</option>";}
	   else
	   	{	echo "<option value=\"total\">Total £k</option>";}
	   if ($rank == "2015")
	   	{	echo "<option value=\"2015\" selected>2015 £k</option>";}
	   else
	   	{	echo "<option value=\"2015\">2015 £k</option>";}
	   if ($rank == "2014")
	   	{	echo "<option value=\"2014\" selected>2014 £k</option>";}
	   else
	   	{	echo "<option value=\"2014\">2014 £k</option>";}
	   if ($rank == "2013")
	   	{	echo "<option value=\"2013\" selected>2013 £k</option>";}
	   else
	   	{	echo "<option value=\"2013\">2013 £k</option>";}
	   if ($rank == "2012")
	   	{	echo "<option value=\"2012\" selected>2012 £k</option>";}
	   else
	   	{	echo "<option value=\"2012\">2012 £k</option>";}
		echo "</select>";	   	
	}
	elseif ($analysis == "count")
	{
		echo "Count : <select name=\"count_type\" style=\"width: 65%\">";
	   if ($count_type == "cumulative")
	   	{	echo "<option value=\"cumulative\" selected>Cumulative</option>";}
	   else
	   	{	echo "<option value=\"cumulative\">Cumulative</option>";}   	
	   if ($count_type == "month")
	   	{	echo "<option value=\"month\" selected>By Month</option>";}
	   else
	   	{	echo "<option value=\"month\">By Month</option>";}
		echo "</select>";	   	
	}
	
	echo "</td>";
	echo "<td>";
	echo "<input type=\"submit\" class = \"tv_button\"value=\"Run Analysis\">";
	echo "</td>";
	echo "</tr>"; // New row
	echo "<tr><td></td><td></td>";
	echo "<td>";
	echo "</td><td></td>";
	echo "</tr>";

	echo "</table></div>";

// 	echo "<p style = \"color:#3366ff; font-size:10pt\">This page is in beta.  It is currently slow to refresh as the calculations need a lot of processing.  We're working on maximising the speed.  We welcome comments, issues and suggestions, please send to <a href= \"mailto:support@govspend.org.uk\"  style = \"display: inline-block;\" >support@govspend.org.uk</a></p>";
	// sql_base is used for the second subquery so it's really important - watch out before editing it
	$sql_base = "select count(distinct customer), count(distinct supplier) from `g-cloud` where 1 ";
	$sql_supp = " 	select * from ( SELECT supplier, sme,
					count(distinct customer) as total,
					SUM(IF(year(`For_Month`)='2012',`Total_Charge`,0)) AS Year_2012, 
					SUM(IF(year(`For_Month`)='2013',`Total_Charge`,0)) AS Year_2013, 
					SUM(IF(year(`For_Month`)='2014',`Total_Charge`,0)) AS Year_2014, 
					SUM(IF(year(`For_Month`)='2015',`Total_Charge`,0)) AS Year_2015, 
					sum(`Total_Charge`) as `Total_Charge`
					FROM `g-cloud` where 1 ";
	$sql_cust = " 	select * from ( SELECT customer, sector,
					count(distinct supplier) as total,
					SUM(IF(year(`For_Month`)='2012',`Total_Charge`,0)) AS Year_2012, 
					SUM(IF(year(`For_Month`)='2013',`Total_Charge`,0)) AS Year_2013, 
					SUM(IF(year(`For_Month`)='2014',`Total_Charge`,0)) AS Year_2014, 
					SUM(IF(year(`For_Month`)='2015',`Total_Charge`,0)) AS Year_2015, 
					sum(`Total_Charge`) as `Total_Charge`
					FROM `g-cloud` where 1 ";
	// these two sql statements are needed to calculate the Jan counts for count=By onth option
	$sql_supp_1 = "SELECT count(distinct supplier) as total FROM `g-cloud` where 1 ";
	$sql_cust_1 = "SELECT count(distinct customer) as total FROM `g-cloud` where 1 ";

	if ($product != "all")
		{	
			$sql_base .= " and Product_Service_Description like '%$product%'";
			$sql_supp .= " and Product_Service_Description like '%$product%'";
			$sql_cust .= " and Product_Service_Description like '%$product%'";
			$sql_supp_1 .= " and Product_Service_Description like '%$product%'";
			$sql_cust_1 .= " and Product_Service_Description like '%$product%'";
		}
	if ($client != "all")
		{	
			$sql_base .= " and Customer like '%$client%'";
			$sql_supp .= " and Customer like '%$client%'";
			$sql_cust .= " and Customer like '%$client%'";
			$sql_supp_1 .= " and Customer like '%$client%'";
			$sql_cust_1 .= " and Customer like '%$client%'";
		}
	if ($supplier != "all")
		{	
			$sql_base .= " and Supplier like '%$supplier%'";
			$sql_supp .= " and Supplier like '%$supplier%'";
			$sql_cust .= " and Supplier like '%$supplier%'";
			$sql_supp_1 .= " and Supplier like '%$supplier%'";
			$sql_cust_1 .= " and Supplier like '%$supplier%'";
		}
	if ((strlen($sme)>0) and ($sme != "all"))
	{	
		$sql_base .= " and SME = '$sme' ";
		$sql_supp .= " and SME = '$sme' ";
		$sql_cust .= " and SME = '$sme' ";
		$sql_supp_1 .= " and SME = '$sme' ";
		$sql_cust_1 .= " and SME = '$sme' ";
	}
	if ((strlen($sector)>0) and ($sector != "all"))
	{	
		$sql_base .= " and sector like '%$sector%' ";
		$sql_supp .= " and sector like '%$sector%' ";
		$sql_cust .= " and sector like '%$sector%' ";
		$sql_supp_1 .= " and sector like '%$sector%' ";
		$sql_cust_1 .= " and sector like '%$sector%' ";
	}

	if ($rank == "number") {$rank_sql = "total desc";}
	elseif ($rank == "total") {$rank_sql = "Total_Charge desc";}
	elseif ($rank == "2015") {$rank_sql = "Year_2015 desc";}
	elseif ($rank == "2014") {$rank_sql = "Year_2014 desc";}
	elseif ($rank == "2013") {$rank_sql = "Year_2013 desc";}
	elseif ($rank == "2012") {$rank_sql = "Year_2012 desc";}

	$sql_supp .= " group by supplier, sme) as t order by $rank_sql, Total_Charge desc";
	$sql_cust .= " group by customer, sector) as t order by $rank_sql, Total_Charge desc";
	$sql_supp_1 .= " ";
	$sql_cust_1 .= " ";

//	echo "<p>$sql_supp_1</p>";
//	echo "<p>$sql_cust_1</p>";
	
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
					$last_dec_c = 0;
					$last_dec_s = 0;
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
					if ($count_type == "month")
					{	// This needs to be a query for Jan in year A we need the Dec figure for year A-1		

					$sql_supp_2 = $sql_supp_1." and for_month <= '".(intval($current_year)-1)."-12-01'";
					$sql_cust_2 = $sql_cust_1." and for_month <= '".(intval($current_year)-1)."-12-01'";
//					echo "<p>$sql_supp_2</p>";
//					echo "<p>$sql_cust_2</p>";

						$year_c[0] = gs_get($sql_cust_2);
						$year_s[0] = gs_get($sql_supp_2);
					}
					for ($i=1; $i<=12; $i++)
					{
						if ($year_c[$i]>0) 
						{	
							if ($count_type == "month")
							{
								$cust_html .=  "<td align = \"right\">".($year_c[$i] - $year_c[($i-1)])."</td>";
								$supp_html .=  "<td align = \"right\">".($year_s[$i] - $year_s[($i-1)])."</td>";
							}
							else
							{
								$cust_html .=  "<td align = \"right\">$year_c[$i]</td>";
								$supp_html .=  "<td align = \"right\">$year_s[$i]</td>";
							}
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
							if ($count_type == "month")
							{
								$cust_html .=  "<td align = \"right\">".($year_c[$i] - $year_c[($i-1)])."</td>";
								$supp_html .=  "<td align = \"right\">".($year_s[$i] - $year_s[($i-1)])."</td>";
							}
							else
							{
								$cust_html .=  "<td align = \"right\">$year_c[$i]</td>";
								$supp_html .=  "<td align = \"right\">$year_s[$i]</td>";
							}
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

		if ($count_type == "month")
		{	echo "<h2>Count of clients (buying organisations) - by month</h2>"; 
			echo $cust_html;
   			echo "<h2>Count of suppliers - by month</h2>"; 
			echo $supp_html;
		}
		else
		{   echo "<h2>Count of clients (buying organisations) - cumulative</h2>"; 
			echo $cust_html;
   			echo "<h2>Count of suppliers - cumulative</h2>"; 
			echo $supp_html;
		}
	}    
	elseif ($analysis == "supplier")
	{
		$titles = "Organisation;SME;Number of clients;2012<br>£k;2013<br>£k;2014<br>£k;2015<br>£k;Total<br>£k";
		$colwidths = "30 10 10 10 10 10 10 10";
		$cell_justification = "left center center right right right right right";
		$numcols = "8";
		$options = "supplier";			
		cz_table($sql_supp, $titles, $colwidths, $cell_justification, $numcols, $options);
			
	}
	elseif ($analysis == "customer")
	{
		$titles = "Organisation;Sector;Number of suppliers;2012<br>£k;2013<br>£k;2014<br>£k;2015<br>£k;Total<br>£k";
		$colwidths = "20 20 10 7 7 7 7 10";
		$cell_justification = "left center center right right right right right";
		$numcols = "8";
		$options = "customer";			
		cz_table($sql_cust, $titles, $colwidths, $cell_justification, $numcols, $options);
			
	}
	elseif ($analysis == "template")
	{
	}
	
?>            

<?php include ("footer.php"); ?>
<?php
function cz_table($sql, $titles, $colwidths, $cell_justification, $numcols, $options)
{
	$totals = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);

	// $sql is the sql that returns the result set
	// $titles are the column titles separated by semi-colon (;)
	// $colwidths is the width of the columns as integers separated by spaces.  Widths are interpreted as percentages i.e. 10 = 10%
	// $numcols specifies the number of columns to be used e.g. 5 = the first five and ignores columns after that
	// $options - if "contacts" then make each number a URL link to contacts with a filter by relationship type
   	include("init.php");
	$cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend") or die("cannot connect"); 
	$result=mysqli_query($cxn,$sql);
	$count = 0;
	if ($numcols <= 10)
	{	echo "<div class=\"datagrid\">";
		echo "	<table>";
 		$title_array = explode( ";", $titles);
 		$cell_array = explode( " ", $cell_justification);
 		if (strlen($colwidths) > 0)
 		{	
 		 	echo "		<colgroup><col span = \"1\" style=\"width:5%\">";
			$colwidths_array = explode( " ", $colwidths);
			for ($i=0; $i< $numcols; $i++)
			{
			echo "			<col span=\"1\" style=\"width: $colwidths_array[$i]%;\">";
			} 
			echo "			</colgroup>";
		}	
 		echo "		<thead>";
 		if (strlen($titles) > 0)
		{
  			echo "			<tr><th></th>";
			for ($i=0; $i< $numcols; $i++)
			{  	
	 			echo "	  			<th align = \"$cell_array[$i]\">$title_array[$i]</th>";
 			}
			echo "				</tr>";
		}
		echo "			</thead>";
		
		echo "		<tbody><tr>";
		$count = 1;
		while ($row= mysqli_fetch_row($result))
		{	echo "<td>$count</td>"; $count++;
			for ($i=0; $i< $numcols; $i++)
			{
				if ($options == "supplier")
				{
					if ($i == 0)
					{	echo "<td align = \"$cell_array[$i]\"> <a href=\"g-cloud-stats.php?supplier=".urlencode($row[$i])."&analysis=customer\">".$row[$i]."</a> </td>";}
					elseif ($i >= 3)
					{	echo "<td align = \"$cell_array[$i]\"> £".number_format(intval($row[$i]/1000))."</a> </td>";}
					else
					{	echo "<td align = \"$cell_array[$i]\">$row[$i] </td>";}
					$totals[$i] += $row[$i];
				}
				elseif ($options == "customer")
				{
					if ($i == 0)
					{	echo "<td align = \"$cell_array[$i]\"> <a href=\"g-cloud-stats.php?client=".urlencode($row[$i])."&analysis=supplier\">".$row[$i]."</a> </td>";}
					elseif ($i >= 3)
					{	echo "<td align = \"$cell_array[$i]\"> £".number_format(intval($row[$i]/1000))."</a> </td>";}
					else
					{	echo "<td align = \"$cell_array[$i]\">$row[$i] </td>";}
					$totals[$i] += $row[$i];
				}
				else
				{
					echo "<td align = \"$cell_array[$i]\">$row[$i] </td>";
				}
			}
			echo "</tr>";
		}
		if (($options == "customer") or ($options == "supplier"))
		{
			echo "<tr><td></td><td><b>Totals</b></td><td></td><td></td>";
			echo "<td align = \"right\"><b>£".number_format(intval($totals[3]/1000))."</b></td>";
			echo "<td align = \"right\"><b>£".number_format(intval($totals[4]/1000))."</b></td>";
			echo "<td align = \"right\"><b>£".number_format(intval($totals[5]/1000))."</b></td>";
			echo "<td align = \"right\"><b>£".number_format(intval($totals[6]/1000))."</b></td>";
			echo "<td align = \"right\"><b>£".number_format(intval($totals[7]/1000))."</b></td>";
			echo "</tr>";
		}
		echo "		</tbody>";
		
		echo "	</table>";
		echo "</div>";
		echo "<p></p>";
//		echo " <p align = \"right\"><a class=\"button small blue\" href=\"contacts.php\">Search and query contacts</a> </p>";
	}
	else
	{
		echo "<div class=\"datagrid\">";
		echo "	<table>";
	 	echo "		<colgroup>";
		echo "			<col span=\"1\" style=\"width:30%;\">";
		echo "			<col span=\"1\" style=\"width:10%;\">";
		echo "			<col span=\"1\" style=\"width:30%;\">";
		echo "			<col span=\"1\" style=\"width:10%;\">";
	 	echo "		</colgroup>";
 		$title_array = explode( ";", $titles);
 		$cell_array = explode( " ", $cell_justification);
		$num_rows = (($numcols/2)+0.5) ;

		$row= mysqli_fetch_row($result);
 		echo "		<thead>";
	 	echo "<tr><th>$options</th><th align = \"center\">Count</th><th>$options</th><th align = \"center\">Count</th></tr>";
 		echo "		<t/head>";
		echo "		<tbody>";

		$alt = 0;
		for ($i=0; $i< $num_rows; $i++)
		{
			if ($alt == 1)									  
			{	echo "<tr class=\"alt\">"; $alt = 0;}
			else
			{	echo "<tr>";$alt = 1;}

			if ($options == "Sector")
			{					
				$url1 = "<a href=\"contacts.php?sector=".urlencode($title_array[$i])."\">";
				$url2 = "<a href=\"contacts.php?sector=".urlencode($title_array[$i + $num_rows])."\">";
			}

	 		echo "<td>".$title_array[$i]."</td><td align = \"center\">".$url1.$row[$i]."</a></td><td>".$title_array[$i + $num_rows]."</td><td align = \"center\">".$url2.$row[$i + $num_rows]."</a></td></tr>";
		}
		echo "		</tbody>";

		echo " </table>";
		echo "</div>";
		echo "<p></p>";
	}
		
}
function gs_get($sql) // executes a SQL statement and returns the results - returns 'none' if no result returned
{	// used to count things - pass in the sql e.g. 'select count(*) from members'
	include("init.php");
	$gs_cxn1 = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend") or die("cannot connect"); 
	if ($result=mysqli_query($gs_cxn1,$sql))
	{ 	if ($row= mysqli_fetch_row($result))
      		{return $row[0];}
    	else 
    		{return "none";}
    }
    else
		{return "none";}
}

?>
</body>
</html>