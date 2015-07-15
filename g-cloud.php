
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
/* Page Name:  	G-Cloud
 * Desc:      	
 *
 */
   include_once("analyticstracking.php");
   include('header_main.php'); 
   include('menu_g_cloud.php');
   include("init.php");  
   $cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
// Calculate the number of records that are being analysed
   $sql = "SELECT count(*) FROM `g-cloud`";
   $result=mysqli_query($cxn,$sql);
   $num_recs = mysqli_fetch_row($result);
// Process the parameters in the URL
   $type = $_GET['type']; // This is a very important variable and controls the format of the page - there are three values:
   					   //   - Summary (or null) - shows the summary tables only e.g. Spend by month
   					   //   - Supplier - shows the spend details for suppliers
   					   //   - Customer - show the spend details for customers
   $rank = $_GET['rank']; // Specifies the sort order of the customer and supplier lists
   if (strlen($rank) == 0) {$rank = "total";}
   $term = $_GET['term']; // Specifies the product string to search for
   $search_supplier = $_GET['search_supplier']; // Specifies the supplier string to search for
   if (strlen($search_supplier)==0) {$search_supplier = "all";}
   $search_client = $_GET['search_client']; // Specifies the client string to search for
   if (strlen($search_client)==0) {$search_client = "all";}
   if (strlen($type)==0) 
     {	if (strlen($term)>0) 
        	{$type = "Customer";$rank=total;} // Page was called without a type
//      else
//        {$type = "Customer";} // Page was called with no type so assume Summary.
     }    
   if (strlen($term)==0){$term= "all";}   
   $scope = $_GET["scope"];  // specifies a sub query e.g. local = Local Government  
   if (strlen($scope)==0){$scope= "all";}
   $gs_year = $_GET['gs_year']; 
   if (strlen($gs_year)==0){$gs_year= "all";}
   $sme = $_GET['sme']; 
   if (strlen($sme)==0){$sme= "all";}
   $chart_type = $_GET['chart']; 
   if (strlen($chart_type)==0){$chart_type= "q-bar";}

   if ($scope == "local")      {
       echo "<h1>G-Cloud Spend Analysis - Local Government</h1>";
       $scope_sql =  "      ((`Customer` like '%council%') or 
                            (`Customer` like '%borough%')) and 
                            (`Customer` <> 'British Council')";
       $scope_sql = "(`Sector` like '%local%')";                     
        }
   elseif ($scope == "health") {
       echo "<h1>G-Cloud Spend Analysis - Health</h1>";
       $scope_sql = "      ((`Customer`like '%nhs%') or 
                            (`Customer` like '%cmu%') or 
                            (`Customer` like '%dh%') or 
                            (`Customer` like '%hospital%') or 
                            (`Customer` like '%pct%') or 
                            (`Customer` like '%care quality%') or 
                            (`Customer` like '%health%')) and
                            (`Customer` not like '%Animal%')";
       $scope_sql = "(`Sector` like '%health%')";                     
                            
                            }
   elseif ($scope == "fire") {
       echo "<h1>G-Cloud Spend Analysis - Fire and Rescue</h1>";
       $scope_sql =  "      (`Customer` like '%polic%') or 
                            (`Customer` like '%fire%') ";
        $scope_sql = "(`Sector` like '%fire%') ";                     
                           
                            }        
   elseif ($scope == "all")  {
       if ($type == "Summary")
            {echo "<h1>G-Cloud Spend Analysis - Summary</h1>";}
       else {echo "<h1>G-Cloud Spend Analysis - All sectors</h1>";}
       $scope_sql = "";}
   elseif ($scope == "central") {
       echo "<h1>G-Cloud Spend Analysis - Central Government</h1>"; 
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
                            ";
       $scope_sql = "(`Sector` like '%central%')";                     

							}
	   elseif ($scope == "police") {$scope_sql = "(`Sector` like '%police%')"; echo "<h1>G-Cloud Spend Analysis - Police</h1>"; }
	   elseif ($scope == "devolved") {$scope_sql = "(`Sector` like '%devolved%')";echo "<h1>G-Cloud Spend Analysis - Devolved Administrations</h1>"; }
	   elseif ($scope == "education") {$scope_sql = "(`Sector` like '%education%')";echo "<h1>G-Cloud Spend Analysis - Education</h1>"; }
	   elseif ($scope == "notforprofit") {$scope_sql = "(`Sector` like '%profit%')"; echo "<h1>G-Cloud Spend Analysis - Not for Profit</h1>"; }
	   elseif ($scope == "private") {$scope_sql = "(`Sector` like '%private%')"; echo "<h1>G-Cloud Spend Analysis - Private Sector</h1>"; }

  if ($term <> "all") {echo "<h2>Analysing purchases where product contains: ".$term."</h2>";}
//  if ($type == "Summary") 
//     { 
       	echo "<p><form name=\"input\" action=\"g-cloud.php\" method=\"get\">";
    	echo "<div class=\"datagrid\"><table>";
   		echo "<tr>"; 

   		echo "<td>"; 
       echo "Product : <input type=\"text\" name=\"term\" value=\"$term\" style=\"width: 65%\">";
   		echo "</td>"; 
   		echo "<td>"; 
       echo "Supplier : <input type=\"text\" name=\"search_supplier\" value=\"$search_supplier\" style=\"width: 65%\">";
   		echo "</td>"; 
   		echo "<td>"; 
       echo "Client : <input type=\"text\" name=\"search_client\" value=\"$search_client\" style=\"width: 65%\"><br>";
   		echo "</td>"; 
   		echo "<td>"; 
//       echo "<p style = \"color:#3366ff; font-size:10pt\">Search : ";
   		echo "</td>"; 
   		echo "</tr>"; 
   		echo "<tr>"; 
   		echo "<td>"; 
	   echo "Year : <select name=\"gs_year\" style=\"width: 80%\">";
	   if ($gs_year == "all")
	   	{	echo "<option value=\"all\" selected>all</option>";}
	   else
	   	{	echo "<option value=\"all\">all</option>";}
	   if ($gs_year == "2015")	   
	   	{	echo "<option value=\"2015\" selected>2015</option>";}
	   else
	   	{	echo "<option value=\"2015\">2015</option>";}
	   
	   if ($gs_year == "2014")	   
	   	{	echo "<option value=\"2014\" selected>2014</option>";}
	   else
	   	{	echo "<option value=\"2014\">2014</option>";}
	   
	   if ($gs_year == "2013")	   
	   	{	echo "<option value=\"2013\" selected>2013</option>";}
	   else
	   	{	echo "<option value=\"2013\">2013</option>";}

	   if ($gs_year == "2012")	   
	   	{	echo "<option value=\"2012\" selected>2012</option>";}
	   else
	   	{	echo "<option value=\"2012\">2012</option>";}
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
   		echo "<td>"; 
  	   echo "Sector : <select name=\"scope\" style=\"width: 75%\">";
	   if ($scope == "all")
	   	{	echo "<option value=\"all\" selected>all</option>";}
	   else
	   	{	echo "<option value=\"all\">all</option>";}

	   if ($scope == "central")
	   	{	echo "<option value=\"central\" selected>Central Government</option>";}
	   else
	   	{	echo "<option value=\"central\">Central Government</option>";}

	   if ($scope == "local")
	   	{	echo "<option value=\"local\" selected>Local Government</option>";}
	   else
	   	{	echo "<option value=\"local\">Local Government</option>";}

	   if ($scope == "health")
	   	{	echo "<option value=\"health\" selected>Health</option>";}
	   else
	   	{	echo "<option value=\"health\">Health</option>";}

	   if ($scope == "fire")
	   	{	echo "<option value=\"fire\" selected>Fire & rescue</option>";}
	   else
	   	{	echo "<option value=\"fire\">Fire & rescue</option>";}

	   if ($scope == "police")
	   	{	echo "<option value=\"police\" selected>Police</option>";}
	   else
	   	{	echo "<option value=\"police\">Police</option>";}

	   if ($scope == "devolved")
	   	{	echo "<option value=\"devolved\" selected>Devolved</option>";}
	   else
	   	{	echo "<option value=\"devolved\">Devolved</option>";}

	   if ($scope == "education")
	   	{	echo "<option value=\"education\" selected>Education</option>";}
	   else
	   	{	echo "<option value=\"education\">Education</option>";}

	   if ($scope == "notforprofit")
	   	{	echo "<option value=\"notforprofit\" selected>Not for profit</option>";}
	   else
	   	{	echo "<option value=\"notforprofit\">Not for profit</option>";}

	   if ($scope == "private")
	   	{	echo "<option value=\"private\" selected>Private</option>";}
	   else
	   	{	echo "<option value=\"private\">Private</option>";}
		echo "</select>";	   	
   		echo "</td>"; 
   		echo "<td>"; 
       echo "<input type=\"submit\" class = \"tv_button\"value=\"Run Analysis\">";
   		echo "</td>"; 
   		echo "</tr>"; 
   		echo "<tr>"; 

   		echo "<td>"; 
 	   echo "Detail by : <select name=\"type\" style=\"width: 60%\">";
	   if ($type == "Customer")
	   	{	echo "<option value=\"Customer\" selected>Customer</option>";}
	   else
	   	{	echo "<option value=\"Customer\">Customer</option>";}
	   if ($type == "Supplier")
	   	{	echo "<option value=\"Supplier\" selected>Supplier</option>";}
	   else
	   	{	echo "<option value=\"Supplier\">Supplier</option>";}
	   if ($type == "Product")
	   	{	echo "<option value=\"Product\" selected>Product</option>";}
	   else
	   	{	echo "<option value=\"Product\">Product</option>";}
		echo "</select>";	   	

   		echo "</td>"; 

   		echo "<td>"; 
 	   echo "Rank by : <select name=\"rank\" style=\"width: 70%\">";
	   if ($rank == "alpha")
	   	{	echo "<option value=\"alpha\" selected>Name</option>";}
	   else
	   	{	echo "<option value=\"alpha\">Name</option>";}
	   	
	   if ($rank == "lot1")
	   	{	echo "<option value=\"lot1\" selected>Lot 1</option>";}
	   else
	   	{	echo "<option value=\"lot1\">Lot 1</option>";}
	   	
	   if ($rank == "lot2")
	   	{	echo "<option value=\"lot2\" selected>Lot 2</option>";}
	   else
	   	{	echo "<option value=\"lot2\">Lot 2</option>";}
	   	
	   if ($rank == "lot3")
	   	{	echo "<option value=\"lot3\" selected>Lot 3</option>";}
	   else
	   	{	echo "<option value=\"lot3\">Lot 3</option>";}
	   	
	   if ($rank == "lot4")
	   	{	echo "<option value=\"lot4\" selected>Lot 4</option>";}
	   else
	   	{	echo "<option value=\"lot4\">Lot 4</option>";}
	   	
	   if ($rank == "total")
	   	{	echo "<option value=\"total\" selected>Total Spend</option>";}
	   else
	   	{	echo "<option value=\"total\">Total Spend</option>";}

	   if ($rank == "sme")
	   	{	echo "<option value=\"sme\" selected>SME %</option>";}
	   else
	   	{	echo "<option value=\"sme\">SME %</option>";}
		echo "</select>";	   	
	   
	   
	   
   		echo "</td>"; 
		
   		echo "<td>"; 
   	   echo "Chart type : <select name=\"chart\" style=\"width: 60%\">";
	   if ($chart_type == "m-line")
	   	{	echo "<option value=\"m-line\" selected>Monthly - line</option>";}
	   else
	   	{	echo "<option value=\"m-line\">Monthly - Line</option>";}
	   if ($chart_type == "m-bar")
	   	{	echo "<option value=\"m-bar\" selected>Monthly - bar</option>";}
	   else
	   	{	echo "<option value=\"m-bar\">Monthly - bar</option>";}
	   if ($chart_type == "q-bar")
	   	{	echo "<option value=\"q-bar\" selected>Quarterly - bar</option>";}
	   else
	   	{	echo "<option value=\"q-bar\">Quarterly - bar</option>";}
	   if ($chart_type == "sector-bar")
	   	{	echo "<option value=\"sector-bar\" selected>By sector - bar</option>";}
	   else
	   	{	echo "<option value=\"sector-bar\">By sector - bar</option>";}
	   if ($chart_type == "sector-pie")
	   	{	echo "<option value=\"sector-pie\" selected>By sector - pie</option>";}
	   else
	   	{	echo "<option value=\"sector-pie\">By sector - pie</option>";}
	   if ($chart_type == "lot-bar")
	   	{	echo "<option value=\"lot-bar\" selected>By lot - bar</option>";}
	   else
	   	{	echo "<option value=\"lot-bar\">By lot - bar</option>";}
	   if ($chart_type == "lot-pie")
	   	{	echo "<option value=\"lot-pie\" selected>By lot - pie</option>";}
	   else
	   	{	echo "<option value=\"lot-pie\">By lot - pie</option>";}

		echo "</select>";	   	
 		
   		echo "</td>"; 
   		echo "<td>"; 
   		 $url = "http://govspend.org.uk/g-cloud.php?".$_SERVER['QUERY_STRING']; 
 		echo "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"".$url."\" data-text=\"G-Cloud spend analysis\" data-via=\"GovSpendOrgUK\" data-size=\"large\" data-hashtags=\"GOVUKdigimkt\">Tweet</a> 
 				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
   		echo "</td>"; 
   		echo "</tr>"; 
	   echo "</table>";
   		echo "</div>"; 
       echo "</form></p>";
    
       echo "<p style = \"color:#3366ff; font-size:10pt\">For example, to search for all sales of 'Agile' just type the word Agile into the Product box and press the search button.</p>";

//       echo "<p style = \"color:#3366ff; font-size:10pt\"><b>Note 15th Feb 2015</b> - we have expanded the search options above to allow SME and Year as search criteria.  We have also removed the Sector, Detail By and Rank By buttons below and added them to the search box above.  Is that helpful / better?  Feedback welcome - email <a href=\"mailto:support@govspend.org.uk?Subject=GovSpend%20feedback\">support@govspend.org.uk</a></p>";
//       echo "<p style = \"color:#3366ff; font-size:10pt;\"><b>Notes 15th Feb 2015</b></p>";
//       echo "<p style = \"color:#3366ff; font-size:10pt;\"><b>Enhanced analysis features</b> - we have expanded the search options above to allow SME and Year as search criteria.  We have also removed the Sector, Detail By and Rank By buttons below and added them to the search box above along with different chart types.  Is that helpful / better?  Feedback welcome - email us at support@govspend.org.uk</p>";
//		echo "<p style = \"color:#3366ff; font-size:10pt\"><b>Spreadsheet users</b> - We have enhanced GovSpend so that you can copy and paste individual tables into spreadsheets.</p>";
//		echo "<p style = \"color:#3366ff; font-size:10pt\"><b>Twitter</b> - We are trialling the twitter button above to allow people to share their analysis results (copying and pasting the URL works as well). </p>";
//		WARNING twitter has introduced a new service Linkis.com that it uses to shorten URLs in tweets.  We have no control over this feature but are worried by the privacy implications of the Linkis.com service.  Worth consideration.</p>";
//		echo "<p>URL : $url \< </p>";
//       }
       echo "</p>";
  
// -------------------------------------------------------------------------------------------------------------------------------    
// OUTPUT THE SUMMARY TABLE OF SPEND BY MONTH
// -------------------------------------------------------------------------------------------------------------------------------    
if (1) // (($type == "Summary") or ($scope != "all"))
{ 
// Prepare the SQL statement to do the cross tab   
   $sql = "SELECT year(`For_Month`) as fw_year, 
            SUM(IF(month(`For_Month`)='1',`Total_Charge`,0)) AS `Jan`, 
            SUM(IF(month(`For_Month`)='2',`Total_Charge`,0)) AS `Feb`, 
            SUM(IF(month(`For_Month`)='3',`Total_Charge`,0)) AS `Mar`, 
            SUM(IF(month(`For_Month`)='4',`Total_Charge`,0)) AS `Apr`, 
            SUM(IF(month(`For_Month`)='5',`Total_Charge`,0)) AS `May`, 
            SUM(IF(month(`For_Month`)='6',`Total_Charge`,0)) AS `Jun`, 
            SUM(IF(month(`For_Month`)='7',`Total_Charge`,0)) AS `Jul`, 
            SUM(IF(month(`For_Month`)='8',`Total_Charge`,0)) AS `Aug`, 
            SUM(IF(month(`For_Month`)='9',`Total_Charge`,0)) AS `Sep`, 
            SUM(IF(month(`For_Month`)='10',`Total_Charge`,0)) AS `Oct`, 
            SUM(IF(month(`For_Month`)='11',`Total_Charge`,0)) AS `Nov`, 
            SUM(IF(month(`For_Month`)='12',`Total_Charge`,0)) AS `Dec`, 
            sum(`Total_Charge`) as `Total_Charge`
            FROM `g-cloud` WHERE 1 "; 
	$sme_sql = "SELECT year(`For_Month`) as fw_year, 
            	SUM(IF (SME='SME',`Total_Charge`,0)) AS `SME`, 
            	SUM(IF(SME='Large',`Total_Charge`,0)) AS `Large`, 
              	SUM(`Total_Charge`) as `Total_Charge`
            	FROM `g-cloud` WHERE 1 ";
    $sector_sql = "select * from (SELECT sector, sum(`Total_Charge`) as total FROM `g-cloud` where 1 ";        	
    $lot_sql = "select * from (SELECT lot, sum(`Total_Charge`) as total FROM `g-cloud` where 1 ";        	

	if ((strlen($gs_year)>0) and ($gs_year != "all"))
	{	
		$sql .= " and year(`For_Month`) = $gs_year ";
		$sme_sql .= " and year(`For_Month`) = $gs_year ";
		$sector_sql .= " and year(`For_Month`) = $gs_year ";
		$lot_sql .= " and year(`For_Month`) = $gs_year ";
	}

	if ((strlen($sme)>0) and ($sme != "all"))
	{	
		$sql .= " and SME = '$sme' ";
		$sme_sql .= " and SME = '$sme' ";
		$sector_sql .= " and SME = '$sme' ";
		$lot_sql .= " and SME = '$sme' ";
	}

// Add in the where clause for both / either a product query or a scope query 
    $summary_level = "m";  // m = show millions, k = show thousands
    $dec_places = 1; // the number of decimal plaves     
    if ($scope <> "all") 
    	{ 	$sql = $sql . " and (". $scope_sql .")"; 
    		$sme_sql = $sme_sql . " and (". $scope_sql .")"; 
    		$sector_sql = $sector_sql . " and (". $scope_sql .")"; 
    		$lot_sql = $lot_sql . " and (". $scope_sql .")"; 
    		if ($scope <> "central")
            { $summary_level = "k";  
             $dec_places = 0;    
            }  
    	}   
   	if ($term <> "all" ) 
      	{ 	$sql = $sql . " and (`Product_Service_Description` LIKE '%". $term ."%')";
      		$sme_sql = $sme_sql . " and (`Product_Service_Description` LIKE '%". $term ."%')";
      		$sector_sql = $sector_sql . " and (`Product_Service_Description` LIKE '%". $term ."%')";
      		$lot_sql = $lot_sql . " and (`Product_Service_Description` LIKE '%". $term ."%')";
            $summary_level = "k";        
            $dec_places = 0;      
      	}   
   	if ($search_supplier <> "all" ) 
   		{	if ($search_supplier == "atos" )
     	 	{ 	$sql = $sql . "and (`Supplier` LIKE '%atos%' or `Supplier` LIKE '%worldline%' or `Supplier` LIKE '%canopy%')";
      		 	$sme_sql = $sme_sql . "and (`Supplier` LIKE '%atos%' or `Supplier` LIKE '%worldline%' or `Supplier` LIKE '%canopy%')";
      		 	$sector_sql = $sector_sql . "and (`Supplier` LIKE '%atos%' or `Supplier` LIKE '%worldline%' or `Supplier` LIKE '%canopy%')";
      		 	$lot_sql = $lot_sql . "and (`Supplier` LIKE '%atos%' or `Supplier` LIKE '%worldline%' or `Supplier` LIKE '%canopy%')";
      	      	$summary_level = "k";        
      	      	$dec_places = 0;      
      		}
      	
      	else
     	 	{ 	$sql = $sql . "and (`Supplier` LIKE '%". $search_supplier ."%')";
      		 	$sme_sql = $sme_sql . "and (`Supplier` LIKE '%". $search_supplier ."%')";
      		 	$sector_sql = $sector_sql . "and (`Supplier` LIKE '%". $search_supplier ."%')";
      		 	$lot_sql = $lot_sql . "and (`Supplier` LIKE '%". $search_supplier ."%')";
      	      	$summary_level = "k";        
      	      	$dec_places = 0;      
      		}
		}      	 
   	if ($search_client <> "all" ) 
      { 	$sql = $sql . "and (`Customer` LIKE '%". $search_client ."%')";
       		$sme_sql = $sme_sql . "and (`Customer` LIKE '%". $search_client ."%')";
       		$sector_sql = $sector_sql . "and (`Customer` LIKE '%". $search_client ."%')";
       		$lot_sql = $lot_sql . "and (`Customer` LIKE '%". $search_client ."%')";
            $summary_level = "k";        
            $dec_places = 0;      
      }   
            
	$sql=$sql . " GROUP BY fw_year desc";
	$sme_sql=$sme_sql . " GROUP BY fw_year desc";
	$sector_sql .= "GROUP BY sector) as s order by total desc ";
	$lot_sql .= "GROUP BY lot) as s order by lot asc ";

// Execute the SQL Statement            
   $result=mysqli_query($cxn,$sql);
// Output titles
   if ($summary_level == "m")
   {echo "<h2>G-Cloud monthly spend - £m</h2>";}
   else
   {echo "<h2>G-Cloud monthly spend - £k</h2>";}
 
// Output the table div tag and header row
   echo "<div class=\"datagrid\"><table>";
   echo "<thead>";
   echo "<tr>"; 
   echo "<th>Year</th>";
   echo "<th align = \"right\">Jan</th><th align = \"right\">Feb</th><th align = \"right\">Mar</th><th align = \"right\">Apr</th>";
   echo "<th align = \"right\">May</th><th align = \"right\">Jun</th><th align = \"right\">Jul</th><th align = \"right\">Aug</th>";
   echo "<th align = \"right\">Sep</th><th align = \"right\">Oct</th><th align = \"right\">Nov</th><th align = \"right\">Dec</th>";
   echo "<th align = \"right\">Total</th>";
   echo "</tr>";
   echo "</thead>";
   echo "<tbody>";
// Process the query set results - not worrying about no results as there should always be some!   
	$spend_2012 = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
	$spend_2013 = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
	$spend_2014 = array(0,0,0,0,0,0,0,0,0,0,0,0,0);

	$qspend_2012 = array(0,0,0,0);
	$qspend_2013 = array(0,0,0,0);
	$qspend_2014 = array(0,0,0,0);

   while ($spend = mysqli_fetch_row($result))
      {echo "<tr>";
        if ($summary_level == "m") {$divide_by = 1000000;} else {$divide_by = 1000;}
        echo "<td align = \"right\">".$spend[0]."</td>";
        echo "<td align = \"right\">".number_format(($spend[1]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[2]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[3]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[4]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[5]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[6]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[7]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[8]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[9]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[10]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[11]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">".number_format(($spend[12]/$divide_by),$dec_places)."</td>";
        echo "<td align = \"right\">£".number_format(($spend[13]/$divide_by),$dec_places)."</td>";
        $total += $spend[13];
        echo "</tr>";

		if ($spend[0] == 2012)
    	  { 
    	  	$spend_2012[1] =  intval($spend[1]/$divide_by);
    	  	$spend_2012[2] =  intval($spend[2]/$divide_by);
    	  	$spend_2012[3] =  intval($spend[3]/$divide_by);
    	  	$spend_2012[4] =  intval($spend[4]/$divide_by);
    	  	$spend_2012[5] =  intval($spend[5]/$divide_by);
    	  	$spend_2012[6] =  intval($spend[6]/$divide_by);
    	  	$spend_2012[7] =  intval($spend[7]/$divide_by);
    	  	$spend_2012[8] =  intval($spend[8]/$divide_by);
    	  	$spend_2012[9] =  intval($spend[9]/$divide_by);
    	  	$spend_2012[10] = intval($spend[10]/$divide_by);
    	  	$spend_2012[11] = intval($spend[11]/$divide_by);
    	  	$spend_2012[12] = intval($spend[12]/$divide_by);
			$qspend_2012[1] = round(($spend[1] + $spend[2] + $spend[3] + 0) / $divide_by,2);
			$qspend_2012[2] = round(($spend[4] + $spend[5] + $spend[6] + 0) / $divide_by,2);
			$qspend_2012[3] = round(($spend[7] + $spend[8] + $spend[9] + 0) / $divide_by,2);
			$qspend_2012[4] = round(($spend[10] + $spend[11] + $spend[12] + 0) / $divide_by,2);

    	  }
		if ($spend[0] == 2013)
    	  { 
    	  	$spend_2013[1] =  intval($spend[1]/$divide_by);
    	  	$spend_2013[2] =  intval($spend[2]/$divide_by);
    	  	$spend_2013[3] =  intval($spend[3]/$divide_by);
    	  	$spend_2013[4] =  intval($spend[4]/$divide_by);
    	  	$spend_2013[5] =  intval($spend[5]/$divide_by);
    	  	$spend_2013[6] =  intval($spend[6]/$divide_by);
    	  	$spend_2013[7] =  intval($spend[7]/$divide_by);
    	  	$spend_2013[8] =  intval($spend[8]/$divide_by);
    	  	$spend_2013[9] =  intval($spend[9]/$divide_by);
    	  	$spend_2013[10] = intval($spend[10]/$divide_by);
    	  	$spend_2013[11] = intval($spend[11]/$divide_by);
    	  	$spend_2013[12] = intval($spend[12]/$divide_by);
 			$qspend_2013[1] = round(($spend[1] + $spend[2] + $spend[3] + 0) / $divide_by,2);
			$qspend_2013[2] = round(($spend[4] + $spend[5] + $spend[6] + 0) / $divide_by,2);
			$qspend_2013[3] = round(($spend[7] + $spend[8] + $spend[9] + 0) / $divide_by,2);
			$qspend_2013[4] = round(($spend[10] + $spend[11] + $spend[12] + 0) / $divide_by,2);

    	  }
		if ($spend[0] == 2014)
    	  { 
    	  	$spend_2014[1] =  intval($spend[1]/$divide_by);
    	  	$spend_2014[2] =  intval($spend[2]/$divide_by);
    	  	$spend_2014[3] =  intval($spend[3]/$divide_by);
    	  	$spend_2014[4] =  intval($spend[4]/$divide_by);
    	  	$spend_2014[5] =  intval($spend[5]/$divide_by);
    	  	$spend_2014[6] =  intval($spend[6]/$divide_by);
    	  	$spend_2014[7] =  intval($spend[7]/$divide_by);
    	  	$spend_2014[8] =  intval($spend[8]/$divide_by);
    	  	$spend_2014[9] =  intval($spend[9]/$divide_by);
    	  	$spend_2014[10] = intval($spend[10]/$divide_by);
    	  	$spend_2014[11] = intval($spend[11]/$divide_by);
    	  	$spend_2014[12] = intval($spend[12]/$divide_by);
			$qspend_2014[1] = round(($spend[1] + $spend[2] + $spend[3] + 0) / $divide_by,2);
			$qspend_2014[2] = round(($spend[4] + $spend[5] + $spend[6] + 0) / $divide_by,2);
			$qspend_2014[3] = round(($spend[7] + $spend[8] + $spend[9] + 0) / $divide_by,2);
			$qspend_2014[4] = round(($spend[10] + $spend[11] + $spend[12] + 0) / $divide_by,2);

    	  }

		if ($spend[0] == 2015)
    	  { 
    	  	$spend_2015[1] =  intval($spend[1]/$divide_by);
    	  	$spend_2015[2] =  intval($spend[2]/$divide_by);
    	  	$spend_2015[3] =  intval($spend[3]/$divide_by);
    	  	$spend_2015[4] =  intval($spend[4]/$divide_by);
    	  	$spend_2015[5] =  intval($spend[5]/$divide_by);
    	  	$spend_2015[6] =  intval($spend[6]/$divide_by);
    	  	$spend_2015[7] =  intval($spend[7]/$divide_by);
    	  	$spend_2015[8] =  intval($spend[8]/$divide_by);
    	  	$spend_2015[9] =  intval($spend[9]/$divide_by);
    	  	$spend_2015[10] = intval($spend[10]/$divide_by);
    	  	$spend_2015[11] = intval($spend[11]/$divide_by);
    	  	$spend_2015[12] = intval($spend[12]/$divide_by);
			$qspend_2015[1] = round(($spend[1] + $spend[2] + $spend[3] + 0) / $divide_by,2);
			$qspend_2015[2] = round(($spend[4] + $spend[5] + $spend[6] + 0) / $divide_by,2);
			$qspend_2015f   = round((($spend[1] + $spend[2] + $spend[3] + 0)*2/4 )/ $divide_by,2);
			$qspend_2015[3] = round(($spend[7] + $spend[8] + $spend[9] + 0) / $divide_by,2);
			$qspend_2015[4] = round(($spend[10] + $spend[11] + $spend[12] + 0) / $divide_by,2);

    	  }

      }
// Output total for last column only

      echo "<tr>"; 
      echo "<td>Total</td>";
      echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
      echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
      echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
	  if ($total == 0) // to avoid divide by zero errors
      { echo "<td align = \"right\"><b>£0</b></th>";}
      else
      { echo "<td align = \"right\"><b>£".number_format(($total/$divide_by),$dec_places)."</b></th>";}
      echo "</tr>";
      echo "</tbody></table></div>";   
} 
// END OF SUMMARY TABLE BY MONTH
// -------------------------------------------------------------------------------------------------------------------------------    



    echo "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>";
    echo "<script type=\"text/javascript\">";
    echo "google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});";
    echo "google.setOnLoadCallback(drawChart);";

	if (($chart_type == "m-line") or ($chart_type == "m-bar"))
    {	echo "function drawChart() {";
	    echo "var data = google.visualization.arrayToDataTable([";
	    echo "['Month', '2012', '2013', '2014' , '2015'],";
	    echo "['Jan',  $spend_2012[1],   $spend_2013[1], $spend_2014[1], $spend_2015[1]],";
	    echo "['Feb',  $spend_2012[2],   $spend_2013[2], $spend_2014[2], $spend_2015[2]],";
	    echo "['Mar',  $spend_2012[3],   $spend_2013[3], $spend_2014[3], $spend_2015[3]],";
	    echo "['Apr',  $spend_2012[4],   $spend_2013[4], $spend_2014[4], $spend_2015[4]],";
	    echo "['May',  $spend_2012[5],   $spend_2013[5], $spend_2014[5], $spend_2015[5]],";
	    echo "['Jun',  $spend_2012[6],   $spend_2013[6], $spend_2014[6], $spend_2015[6]],";
	    echo "['Jul',  $spend_2012[7],   $spend_2013[7], $spend_2014[7], $spend_2015[7]],";
	    echo "['Aug',  $spend_2012[8],   $spend_2013[8], $spend_2014[8], $spend_2015[8]],";
	    echo "['Sep',  $spend_2012[9],   $spend_2013[9], $spend_2014[9], $spend_2015[9]],";
	    echo "['Oct',  $spend_2012[10],   $spend_2013[10], $spend_2014[10], $spend_2015[10]],";
	    echo "['Nov',  $spend_2012[11],   $spend_2013[11], $spend_2014[11], $spend_2015[11]],";
	    echo "['Dec',  $spend_2012[12],   $spend_2013[12], $spend_2014[12], $spend_2015[12]]";
		echo " ]);";
	}
	elseif ($chart_type == "q-bar")
	{
    echo "function drawChart() {";
    echo "var data = google.visualization.arrayToDataTable([";
    echo "['Quarter', 'Spend', 'Forecast'],";
    echo "['All 2012',  ".($qspend_2012[1] + $qspend_2012[2] + $qspend_2012[3] + $qspend_2012[4]+0)." ,0],";
    echo "['Q1 2013',  ".($qspend_2013[1] + 0).",0 ],";
    echo "['Q2 2013',  ".($qspend_2013[2] + 0).",0 ],";
    echo "['Q3 2013',  ".($qspend_2013[3] + 0).",0 ],";
    echo "['Q4 2013',  ".($qspend_2013[4] + 0).",0 ],";
    echo "['Q1 2014',  ".($qspend_2014[1] + 0).",0 ],";
    echo "['Q2 2014',  ".($qspend_2014[2] + 0).",0 ],";
    echo "['Q3 2014',  ".($qspend_2014[3] + 0).",0 ],";
    echo "['Q4 2014',  ".($qspend_2014[4] + 0).",0 ],";
    echo "['Q1 2015',  ".($qspend_2015[1] + 0).",0 ],";
    echo "['Q2 2015',  ".($qspend_2015[2] + 0).",$qspend_2015f ],";
//	if ($summary_level == "m")
//    {	echo "['Q1 2015',  ".($qspend_2015[1] + 0).",60 ],";}
//	else	
//    {	echo "['Q1 2015',  ".($qspend_2015[1] + 0).",".(($qspend_2015[1] + 0)*.8)." ],";}
//    echo "['Q1 2015',  0 ],";
//    echo "['Q2 2015',  0 ],";
//    echo "['Q3 2015',  0 ],";
//    echo "['Q4 2015',  0 ],";
	echo " ]);  ";
	}
	elseif (($chart_type == "sector-bar") or ($chart_type == "sector-pie")) 
	{	
	    echo "function drawChart() {";
 	  	 echo "var data = google.visualization.arrayToDataTable([";
   		 echo "['Sector', 'Spend'],";
   		 $result=mysqli_query($cxn,$sector_sql);
   		 while ($spend = mysqli_fetch_row($result))
			{
			    echo "['".$spend[0]." £".round((($spend[1] + 0)/1000000),1)."m',  ".round((($spend[1] + 0)/1000000),1)." ],";
	
			}
		echo " ]);";	
	}
	elseif (($chart_type == "lot-bar") or ($chart_type == "lot-pie")) 
	{	
	    echo "function drawChart() {";
 	  	 echo "var data = google.visualization.arrayToDataTable([";
   		 echo "['Lot', 'Spend'],";
   		 $result=mysqli_query($cxn,$lot_sql);
   		 while ($spend = mysqli_fetch_row($result))
			{
				if ($spend[0] == 1)
			    {	echo "['IaaS £".round((($spend[1] + 0)/1000000),1)."m',  ".round((($spend[1] + 0)/1000000),1)." ],";}
				elseif ($spend[0] == 2)
			    {	echo "['PaaS £".round((($spend[1] + 0)/1000000),1)."m',  ".round((($spend[1] + 0)/1000000),1)." ],";}
				elseif ($spend[0] == 3)
			    {	echo "['SaaS £".round((($spend[1] + 0)/1000000),1)."m',  ".round((($spend[1] + 0)/1000000),1)." ],";}
				elseif ($spend[0] == 4)
			    {	echo "['SCS £".round((($spend[1] + 0)/1000000),1)."m',  ".round((($spend[1] + 0)/1000000),1)." ],";}
	
			}
		echo " ]);";	
	}
	if ($summary_level == "m")
		if ($chart_type == "q-bar")
		{	echo "var options = {title: 'G-Cloud spend - £m',  isStacked: true,};";}
		else
		{	echo "var options = {title: 'G-Cloud spend - £m'};";}
	else
		if ($chart_type == "q-bar")
		{echo "var options = {title: 'G-Cloud spend - £k', isStacked: true, };";}
		else
		{echo "var options = {title: 'G-Cloud spend - £k'};";}
	if ($chart_type == "m-line")
	{	echo "var chart = new google.visualization.LineChart(document.getElementById('chart_div'));";}
	elseif ($chart_type == "m-bar")
	{	echo "var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));";}
	elseif ($chart_type == "q-bar")
	{	echo "var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));";}
	elseif ($chart_type == "sector-bar")
	{	echo "var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));";}
	elseif ($chart_type == "sector-pie")
	{	echo "var chart = new google.visualization.PieChart(document.getElementById('chart_div'));";}
	elseif ($chart_type == "lot-bar")
	{	echo "var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));";}
	elseif ($chart_type == "lot-pie")
	{	echo "var chart = new google.visualization.PieChart(document.getElementById('chart_div'));";}
	echo "chart.draw(data, options);";
	echo "}</script>";
  	echo "<p>  <div id=\"chart_div\" style=\"width: 800px; height: 200px;\"></div></p>";

//      echo "<p style = \"color:#3366ff; font-size:10pt\">Note Oct 2014 - the Public Sector is doing a great job of using G-Gloud. So much so that we have had to re-format the table and graph above to fit all the spend on one page.  
//      Now if you search for All or Central Government spend, the data is £ millions (£m) as opposed to £ thousands (£k).</p>";    


//     if ($scope == "local") 
//       {echo "<p style = \"color:#3366ff; font-size:10pt\">Filter is &nbsp: Customers with names containing Council or Borough; excludes British Council.</p>";}
//     elseif ($scope == "health") 
//       {echo "<p style = \"color:#3366ff; font-size:10pt\">Filter is &nbsp: Customers with names containing NHS, PCT, CMU, DH, Hospital or Health.  Includes Care Quality Commission.</p>";}
//     elseif ($scope == "emergency") 
//       {echo "<p style = \"color:#3366ff; font-size:10pt\">Filter is &nbsp: Customers with names containing Fire, and Police.</p>";}
//     elseif ($scope == "central") 
//       {echo "<p style = \"color:#3366ff; font-size:10pt\">Filter is &nbsp: Customers who do not appear in the Local, Health, Fire and Police listings.</p>";}
//     else  
//       {echo "<p style = \"color:#3366ff; font-size:10pt\">Showing all records (no filter).</p>";}

?>
<?php     
// -------------------------------------------------------------------------------------------------------------------------------    
// OUTPUT THE SUMMARY TABLE OF SPEND BY SME / LARGE
// -------------------------------------------------------------------------------------------------------------------------------    

   $result=mysqli_query($cxn,$sme_sql);
// Output titles
   echo "<h2>Summary by SME / Non SME - £k</h2>";
// Output the table div tag and header row
   echo "<div class=\"datagrid\"><table>";
   echo "<thead>";
   echo "<tr>";
   echo "<th>Year</th>";
   echo "<th align = \"right\">SME Spend</th><th align = \"right\">Non SME Spend</th>";
   echo "<th align = \"right\">SME %</th><th align = \"right\">Non SME %</th>";
   echo "<th align = \"right\">Total</th>";
   echo "</tr></thead>";
   echo "<tbody>";
// Process the query set results - not worrying about no results as there should always be some!   
   while ($spend = mysqli_fetch_row($result))
      {   echo "<tr><td> ".$spend[0]."</td>";
          echo "<td align = \"right\"> £".number_format(intval($spend[1]/1000))."</td>";
          $grand_total[1] += $spend[1];       
          echo "<td align = \"right\"> £".number_format(intval($spend[2]/1000))."</td>";
          $grand_total[2] += $spend[2];       
          echo "<td align = \"right\"> ".number_format(intval(($spend[1]/$spend[3])*100))."%</td>";
//          $grand_total[3] += $spend[3];       
          echo "<td align = \"right\"> ".number_format(intval(($spend[2]/$spend[3])*100))."%</td>";
//          $grand_total[4] += $spend[4];       
          echo "<td align = \"right\"> £".number_format(intval($spend[3]/1000))."</td></tr>";
          $grand_total[3] += $spend[3];       
      }
if ($grand_total[3]==0) {$grand_total[3]=1;}     
// Output totals 
   echo "<tr>";
   echo "<td><b>Grand Totals</b></td>";
   echo "<td align = \"right\"><b>£".number_format(intval($grand_total[1]/1000))."</b></td>";
   echo "<td align = \"right\"><b>£".number_format(intval($grand_total[2]/1000))."</b></td>";
   echo "<td align = \"right\"><b>".number_format(intval(($grand_total[1]/$grand_total[3])*100))."%</b></td>";
   echo "<td align = \"right\"><b>".number_format(intval(($grand_total[2]/$grand_total[3])*100))."%</b></td>";
   echo "<td align = \"right\"><b>£".number_format(intval($grand_total[3]/1000))."</b></td>";
   echo "</tr>";
   echo "</tbody></table></div>";

// END OF SUMMARY TABLE BY SME / LARGE
// -------------------------------------------------------------------------------------------------------------------------------      

?>

<?php     
// -------------------------------------------------------------------------------------------------------------------------------    
// OUTPUT THE SUMMARY TABLE OF SPEND BY FRAMEWORK AND LOT
// -------------------------------------------------------------------------------------------------------------------------------    
if (1) // (($type == "Summary") or ($scope != "all"))
{
// Initialisations
   $grand_total = array(0,0,0,0,0,0,0,0,0);  // Array to calculate store the column totals
// Prepare the SQL statement to do the cross tab   
//  $sql = "SELECT Framework, 
   $sql = "SELECT year(`For_Month`) as fw_year, 
            SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, 
            SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2,
            SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3,
            SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4,
            sum(`Total_Charge`) as `Total_Charge`
            FROM `g-cloud` where 1 "; 

 	if ((strlen(gs_year)>0) and ($gs_year != "all"))
	{	
		$sql .= " and year(`For_Month`) = $gs_year ";
	}

	if ((strlen($sme)>0) and ($sme != "all"))
	{	
		$sql .= " and SME = '$sme' ";
	}

           
// Add in the where clause for both / either a product query or a scope query    
    if ($scope <> "all") 
    	{ $sql = $sql . " and (". $scope_sql .")"; }   
   	if ($term <> "all" ) 
      	{ $sql = $sql . " and (`Product_Service_Description` LIKE '%". $term ."%')";}   
   	if ($search_supplier <> "all" ) 
	   	{	if ($search_supplier == "atos" ) 
    
   		   	{ $sql = $sql . "and (`Supplier` LIKE '%atos%' or `Supplier` LIKE '%worldline%' or `Supplier` LIKE '%canopy%')";}   
			else
   		   	{ $sql = $sql . "and (`Supplier` LIKE '%". $search_supplier ."%')";}   

		}
   	if ($search_client <> "all" ) 
      { $sql = $sql . "and (`Customer` LIKE '%". $search_client ."%')";}   

//	$sql=$sql . " GROUP BY Framework";
	$sql=$sql . " GROUP BY fw_year desc";
//	echo $sql;
// Execute the SQL Statement            
   $result=mysqli_query($cxn,$sql);
// Output titles
   echo "<h2>Summary by Lot - £k</h2>";
// Output the table div tag and header row
   echo "<div class=\"datagrid\"><table>";
   echo "<thead>";
   echo "<tr>";
   echo "<th>Year</th>";
   echo "<th align = \"right\">IaaS<br>(Lot 1)</th><th align = \"right\">PaaS<br>(Lot 2)</th>";
   echo "<th align = \"right\">SaaS<br>(Lot 3)</th><th align = \"right\">SCS<br>(Lot 4)</th>";
   echo "<th align = \"right\">Total</th>";
   echo "</tr></thead>";
   echo "<tbody>";
// Process the query set results - not worrying about no results as there should always be some!   
   while ($spend = mysqli_fetch_row($result))
      {   echo "<tr><td> ".$spend[0]."</td>";
          echo "<td align = \"right\"> £".number_format(intval($spend[1]/1000))."</td>";
          $grand_total[1] += $spend[1];       
          echo "<td align = \"right\"> £".number_format(intval($spend[2]/1000))."</td>";
          $grand_total[2] += $spend[2];       
          echo "<td align = \"right\"> £".number_format(intval($spend[3]/1000))."</td>";
          $grand_total[3] += $spend[3];       
          echo "<td align = \"right\"> £".number_format(intval($spend[4]/1000))."</td>";
          $grand_total[4] += $spend[4];       
          echo "<td align = \"right\"> £".number_format(intval($spend[5]/1000))."</td></tr>";
          $grand_total[5] += $spend[5];       
      }
// Output totals 
   echo "<tr>";
   echo "<td><b>Grand Totals</b></td>";
   echo "<td align = \"right\"><b>£".number_format(intval($grand_total[1]/1000))."</b></td>";
   echo "<td align = \"right\"><b>£".number_format(intval($grand_total[2]/1000))."</b></td>";
   echo "<td align = \"right\"><b>£".number_format(intval($grand_total[3]/1000))."</b></td>";
   echo "<td align = \"right\"><b>£".number_format(intval($grand_total[4]/1000))."</b></td>";
   echo "<td align = \"right\"><b>£".number_format(intval($grand_total[5]/1000))."</b></td>";
   echo "</tr>";
   echo "</tbody></table></div>";

// END OF SUMMARY TABLE BY FRAMEWORK AND LOT
// -------------------------------------------------------------------------------------------------------------------------------      

// -------------------------------------------------------------------------------------------------------------------------------    
// OUTPUT THE NAV BUTTONS - Note 15th feb - buttons removed as incorporated into new search box at top of page
// -------------------------------------------------------------------------------------------------------------------------------  term=all&search_supplier=all&search_client=cabinet  

//   echo "<p style = \"color:#3366ff; font-size:10pt\">Show sector :</p>  ";
//   if ($type == "Supplier")
//   {
//    echo "<p><a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=central&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Central Gov</a>&nbsp&nbsp";
//   	echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=local&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Local Gov</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=health&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Health</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=fire&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Fire & Rescue</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=police&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Police</a>&nbsp&nbsp</p>";
//
//    echo "<p><a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=devolved&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Devolved</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=education&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Education</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=notforprofit&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Not For Profit</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=private&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Private</a>&nbsp&nbsp";
//
//    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=alpha&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>All (no filter)</a>&nbsp&nbsp</p>";}
//   else
 //  {
//    echo "<p><a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=central&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Central Gov</a>&nbsp&nbsp";
//   	echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=local&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Local Gov</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=health&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Health</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=fire&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Fire & Rescue</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=police&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Police</a>&nbsp&nbsp</p>";
//
//    echo "<p><a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=devolved&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Devolved</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=education&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Education</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=notforprofit&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Not for Profit</a>&nbsp&nbsp";
//    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=private&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Private Sector</a>&nbsp&nbsp";
//
//
//    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=alpha&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>All (no filter)</a>&nbsp&nbsp</p>";}
//
//   echo "<p style = \"color:#3366ff; font-size:10pt\">Show detail for &nbsp&nbsp: </p><p>";
//   if (($type == "Summary") or ($type == "Supplier") or ($type == "Product"))
//     {echo "<a href=\"$server/g-cloud.php?type=Customer&rank=$rank&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Customers</a>&nbsp&nbsp";}
//   if (($type == "Summary") or ($type == "Customer") or ($type == "Product"))
//     {echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=$rank&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Suppliers</a>&nbsp&nbsp";}
//    if (($type == "Summary") or ($type == "Customer") or ($type == "Supplier"))
//     {echo "<a href=\"$server/g-cloud.php?type=Product&rank=$rank&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Products</a>&nbsp&nbsp</p>";}
//}
?>

<?php 

//if  (($type == "Customer") or ($type == "Supplier") or ($type == "Product"))    
//{
// Output the nav buttons - &scope=$scope&term=".urlencode($term)."
//   if (1) // ($scope == 'all')
//   {
//     echo "<p style = \"color:#3366ff; font-size:10pt\">Rank detail by : </p><p> ";
//     echo "<a href=\"$server/g-cloud.php?type=$type&rank=alpha&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>$type Name</a>&nbsp&nbsp";
//     echo "<a href=\"$server/g-cloud.php?type=$type&rank=Lot+1&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>IaaS</a>&nbsp&nbsp";
//     echo "<a href=\"$server/g-cloud.php?type=$type&rank=Lot+2&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>PaaS</a>&nbsp&nbsp";
//     echo "<a href=\"$server/g-cloud.php?type=$type&rank=Lot+3&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>SaaS</a>&nbsp&nbsp";
//     echo "<a href=\"$server/g-cloud.php?type=$type&rank=Lot+4&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>SCS</a>&nbsp&nbsp";
//     echo "<a href=\"$server/g-cloud.php?type=$type&rank=total&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."&gs_year=$gs_year&sme=$sme\" class=tv_button>Total</a></p>";
//   }  
//
//
//     if ($rank == "total")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : Total </p>";}
//     elseif ($rank == "alpha")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : $type name </p>";}
//     elseif ($rank == "lot1")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : IaaS </p>";}
//     elseif ($rank == "lot2")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : PaaS </p>";}
//     elseif ($rank == "lot3")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : SaaS </p>";}
//     elseif ($rank == "lot4")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : SCS </p>";}
//     else  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : $type </p>";}
       
// -------------------------------------------------------------------------------------------------------------------------------    
// OUTPUT THE SUMMARY TABLE OF SPEND BY CUSTOMER OR SUPPLIER
// -------------------------------------------------------------------------------------------------------------------------------    

   echo "<h2>Detail by $type - £</h2>";
       
// Initialisations
  $colour = 0;
  $grand_total = array(0,0,0,0,0,0,0,0,0);
  $i=0;
  if (strlen($rank)==0){$rank = "alpha";}  // Present the list by alpha as default
       
//    echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Summary\"> <- Back to  G-Cloud Spend home page</a></p>";

// select * , ((sme / total_charge)*100) as smep from (SELECT `Customer`, SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2, SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3, SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4, sum(`Total_Charge`) as `Total_Charge`, SUM(IF(sme='sme',`Total_Charge`,0)) AS sme FROM `g-cloud` where 1 GROUP BY `Customer`) as q order by smep desc, total_charge desc

//select * , ((sme / total_charge)*100) as smep from (SELECT `Supplier` as name, SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2, SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3, SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4, sum(`Total_Charge`) as `Total_Charge`, SUM(IF(sme='sme',`Total_Charge`,0)) AS sme FROM `g-cloud` where 1 and (`Customer` LIKE '%Legal Aid Agency%') GROUP BY Supplier ) as q order by Total_Charge desc
//select * , ((sme / total_charge)*100) as smep from (SELECT `Supplier` aa name, SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2, SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3, SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4, sum(`Total_Charge`) as `Total_Charge`, SUM(IF(sme='sme',`Total_Charge`,0)) AS sme FROM `g-cloud` where 1 and (`Customer` LIKE '%Legal Aid Agency%') GROUP BY `Supplier` ) as q order by Total_Charge desc


// Prepare the SQL statement to do the cross tab   
  if ($type == "Supplier")
  { $sql = "SELECT `Supplier`, sum(`Total_Charge`) as 'Total_Charge' FROM `g-cloud` WHERE 1 group by `Supplier`";
    $sql = "select * , ((sme / total_charge)*100) as smep from (SELECT Supplier, 
            SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, 
            SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2,
            SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3,
            SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4,
            sum(`Total_Charge`) as `Total_Charge`,
            SUM(IF(sme='sme',`Total_Charge`,0)) AS sme
            FROM `g-cloud` where 1 ";}
  elseif ($type == "Product")
  {
        $sql = "select * , ((sme / total_charge)*100) as smep from (SELECT `Product_Service_Description`, 
            SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, 
            SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2,
            SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3,
            SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4,
            sum(`Total_Charge`) as `Total_Charge`,
            SUM(IF(sme='sme',`Total_Charge`,0)) AS sme
            FROM `g-cloud` where 1 ";}
  else
  { $sql = "SELECT `Customer`, sum(`Total_Charge`) as 'Total_Charge' FROM `g-cloud` WHERE 1 group by `Customer`";
    $sql = "select * , ((sme / total_charge)*100) as smep from (SELECT `Customer`, 
            SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, 
            SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2,
            SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3,
            SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4,
            sum(`Total_Charge`) as `Total_Charge`,
            SUM(IF(sme='sme',`Total_Charge`,0)) AS sme
            FROM `g-cloud` where 1 ";}
            
            
 	if ((strlen(gs_year)>0) and ($gs_year != "all"))
	{	
		$sql .= " and year(`For_Month`) = $gs_year ";
	}
	
	if ((strlen($sme)>0) and ($sme != "all"))
	{	
		$sql .= " and SME = '$sme' ";
	}

           
// Add in the where clause for both / either a product query or a scope query   
         
    if ($scope <> "all") 
    	{ $sql = $sql . " and (". $scope_sql .")"; }   
   	if ($term <> "all" ) 
      	{ $sql = $sql . " and (`Product_Service_Description` LIKE '%". $term ."%')";}   
   	if ($search_supplier <> "all" ) 
	   	{	if ($search_supplier == "atos" ) 
    
   		   	{ $sql = $sql . "and (`Supplier` LIKE '%atos%' or `Supplier` LIKE '%worldline%' or `Supplier` LIKE '%canopy%')";}   
			else
   		   	{ $sql = $sql . "and (`Supplier` LIKE '%". $search_supplier ."%')";}   

		}
   	if ($search_client <> "all" ) 
      { $sql = $sql . "and (`Customer` LIKE '%". $search_client ."%')";}   


// Add in the group by clauses
  if ($type == "Supplier")
    {$sql = $sql . " GROUP BY Supplier ";}
  elseif ($type == "Product")
    {$sql = $sql . " GROUP BY `Product_Service_Description` ";}
  else
    {$sql = $sql . " GROUP BY `Customer` ";}
// Add in the sort order - note no sort required if sort order is same as group by column
  if ($rank == "total") {$sql=$sql." ) as q order by Total_Charge desc";}
  elseif ($rank == "alpha")
  {
  
	if ($type == "Supplier") {$sql=$sql." ) as q order by Supplier asc";}
   	elseif ($type == "Product") {$sql=$sql." ) as q order by `Product_Service_Description` asc";}
 	else {$sql=$sql." ) as q order by `Customer` asc";}
  }
  elseif ($rank == "lot1") {$sql=$sql." ) as q order by Lot1 desc";}
  elseif ($rank == "lot2") {$sql=$sql." ) as q order by Lot2 desc";}
  elseif ($rank == "lot3") {$sql=$sql." ) as q order by Lot3 desc";}
  elseif ($rank == "lot4") {$sql=$sql." ) as q order by Lot4 desc";}
  elseif ($rank == "sme") {$sql=$sql." ) as q order by smep desc, total_charge desc";}

// echo $sql;
// Execute the SQL Statement   
         
   $result=mysqli_query($cxn,$sql);
// Output the table div tag and header row
// <!-- Table code from http://tablestyler.com/#-->
   echo "<div class=\"datagrid\"><table>";
   echo "<thead>";
   echo "<tr>";
   echo "<th></th>"; // Empty cell for ranking / sequence number
   if ($type == "Supplier") {echo "<th>Supplier</th>";} elseif ($type == "Product") {echo "<th>Product</th>";} else {echo "<th>Customer</th>";}
   echo "<th align = \"right\">SME %</th><th align = \"right\">IaaS<br>Lot 1</th><th align = \"right\">PaaS<br>Lot 2</th>";
   echo "<th align = \"right\">SaaS<br>Lot 3</th><th align = \"right\">SCS<br>Lot 4</th>";
   echo "<th align = \"right\">Total</th>";
   echo "</tr>";
   echo "</thead><tbody>";
   
// Process the query set results - not worrying about no results as there should always be some!    
   while ($spend = mysqli_fetch_row($result))     
    {       
      if (strlen($spend[0])>=0)  // This removes empty rows 
        { if ($colour == 0) {echo "<tr><td><b>";$colour = 1;} else {echo "<tr  class=\"alt\"><td><b>";$colour = 0;} 
          $i=$i+1;
          echo $i."</b></td>";
          if ($type == "Supplier")
            { //echo "<td><a href=\"$server/g-cloud_detail.php?type=Supplier&rank=$rank&scope=$scope&term=".urlencode($term)."&Supplier=".urlencode($spend[0])."\">".$spend[0]."</a></td>";
                echo "<td><a href=\"$server/g-cloud.php?type=$type&rank=alpha&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($spend[0])."&search_client=".urlencode($search_client)."\">$spend[0]</a></td>";

            }
          elseif ($type == "Product")
            { //echo "<td><a href=\"$server/g-cloud_detail.php?type=Product&rank=$rank&scope=$scope&term=".urlencode($term)."&Product=".urlencode($spend[0])."\">".$spend[0]."</a></td>";
				if (strlen($spend[0])>0)                
				{echo "<td><a href=\"$server/g-cloud.php?type=$type&rank=alpha&scope=$scope&term=".urlencode($spend[0])."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($search_client)."\">$spend[0]</a></td>";}
				else
				{echo "<td>No product details provided</td>";}
            }
          else
            { //echo "<td><a href=\"$server/g-cloud_detail.php?type=Customer&rank=$rank&scope=$scope&term=".urlencode($term)."&Customer=".urlencode($spend[0])."\">".$spend[0]."</a></td>";
                echo "<td><a href=\"$server/g-cloud.php?type=$type&rank=alpha&scope=$scope&term=".urlencode($term)."&search_supplier=".urlencode($search_supplier)."&search_client=".urlencode($spend[0])."\">$spend[0]</a></td>";
            }
          echo "<td align = \"center\"> ".intval($spend[7])."%</td>";
          $sme_total += $spend[6];
          echo "<td align = \"right\"> £".number_format(intval($spend[1]))."</td>";
          $grand_total[1] += $spend[1];

          echo "<td align = \"right\"> £".number_format(intval($spend[2]))."</td>";
          $grand_total[2] += $spend[2];

          echo "<td align = \"right\"> £".number_format(intval($spend[3]))."</td>";
          $grand_total[3] += $spend[3];

          echo "<td align = \"right\"> £".number_format(intval($spend[4]))."</td>";
          $grand_total[4] += $spend[4];
          
          $total_spend = $spend[5];
          $grand_total[5] += $total_spend;       
          echo "<td align = \"right\"><b>£".number_format(intval($total_spend))."</b></td>";
          echo "</tr>";
          
        } // end if (str_len($spend[0])>0)
    }  // End while
// Output totals 
   if ($colour == 0) {echo "<tr><td></td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td></td>";$colour = 0;} 
   echo "<td><p style = \"color:#3366ff; font-size:10pt\"><b>Grand Total</b></p></td><td></td>";
   echo "<td align = \"right\"> £".number_format(intval($grand_total[1]))."</td>";
   echo "<td align = \"right\"> £".number_format(intval($grand_total[2]))."</td>";
   echo "<td align = \"right\"> £".number_format(intval($grand_total[3]))."</td>";
   echo "<td align = \"right\"> £".number_format(intval($grand_total[4]))."</td>";
   echo "<td align = \"right\"> £".number_format(intval($grand_total[5]))."</td>";
   echo "</tr>";
   echo "</tbody></table></div>";
// END OF TABLE BY CUSTOMER OR SUPPLIER
// -------------------------------------------------------------------------------------------------------------------------------      

   echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Summary\"> <- Back to G-Cloud Spend home page</a></p>";

}  

       echo "<p style = \"color:#3366ff; font-size:10pt\">Show all : ";
       echo "<a href=\"$server/g-cloud_detail.php?type=All+Purchases\" class=tv_button>Show all source data (". number_format($num_recs[0])." records)</a>";

?>

<?php include ("footer.php"); ?>
</body>
</html>