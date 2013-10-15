
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
   include('menu1.php');
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
   $term = $_GET['term']; // Specifies the product string to search for
   if (strlen($type)==0) 
     {if (strlen($term)>0) 
        {$type = "Product";$rank=total;} // Page was called with a search term
      else
        {$type = "Summary";} // Page was called with no type so assume Summary.
     }    
   if (strlen($term)==0){$term= "all";}   
   $scope = $_GET["scope"];  // specifies a sub query e.g. local = Local Government  
   if (strlen($scope)==0){$scope= "all";}
   if ($scope == "local")      {
       echo "<h1>G-Cloud Spend Analysis - Local Government</h1>";
       $scope_sql =  "      ((`Customer` like '%council%') or 
                            (`Customer` like '%borough%')) and 
                            (`Customer` <> 'British Council')";}
   elseif ($scope == "health") {
       echo "<h1>G-Cloud Spend Analysis - Health</h1>";
       $scope_sql = "      ((`Customer`like '%nhs%') or 
                            (`Customer` like '%cmu%') or 
                            (`Customer` like '%dh%') or 
                            (`Customer` like '%hospital%') or 
                            (`Customer` like '%pct%') or 
                            (`Customer` like '%care quality%') or 
                            (`Customer` like '%health%')) and
                            (`Customer` not like '%Animal%')";}
   elseif ($scope == "emergency") {
       echo "<h1>G-Cloud Spend Analysis - Fire and Police</h1>";
       $scope_sql =  "      (`Customer` like '%polic%') or 
                            (`Customer` like '%fire%') ";}        
   elseif ($scope == "all")  {
       if ($type == "Summary")
            {echo "<h1>G-Cloud Spend Analysis - Summary</h1>";}
       else {echo "<h1>G-Cloud Spend Analysis - All ".$type."s</h1>";}
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
                            ";}
  if ($term <> "all") {echo "<h2>Analysing purchases where product contains: ".$term."</h2>";}
  
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
            FROM `g-cloud`"; 

// Add in the where clause for both / either a product query or a scope query            
    if (($scope <> "all") and ($term <> "all")) 
      {$sql = $sql . " WHERE ((". $scope_sql .") and (`Product_Service_Description` LIKE '%".$term."%'))"  ;}   
    elseif (($scope <> "all") and ($term == "all")) 
      {$sql = $sql . " WHERE (". $scope_sql .")";}   
    elseif (($scope == "all") and ($term <> "all" )) 
      {$sql = $sql . " WHERE (`Product_Service_Description` LIKE '%". $term ."%')";}   
            
	$sql=$sql . " GROUP BY fw_year desc";

// Execute the SQL Statement            
   $result=mysqli_query($cxn,$sql);
// Output titles
   echo "<h2>Summary by Date - £k</h2>";
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
   while ($spend = mysqli_fetch_row($result))
      {echo "<tr>";
        echo "<td align = \"right\">".$spend[0]."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[1]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[2]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[3]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[4]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[5]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[6]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[7]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[8]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[9]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[10]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[11]/1000))."</td>";
        echo "<td align = \"right\">".number_format(intval($spend[12]/1000))."</td>";
        echo "<td align = \"right\">£".number_format(intval($spend[13]/1000))."</td>";
        $total += $spend[13];
      echo "</tr>";
      }
// Output total for last column only
      echo "<tr>"; 
      echo "<td>Total</td>";
      echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
      echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
      echo "<td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td><td align = \"right\"></td>";
      echo "<td align = \"right\"><b>£".number_format(intval($total/1000))."</b></th>";
      echo "</tr>";
      echo "</tbody></table></div>";       
} 
// END OF SUMMARY TABLE BY MONTH
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
  $sql = "SELECT Framework, 
            SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, 
            SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2,
            SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3,
            SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4,
            sum(`Total_Charge`) as `Total_Charge`
            FROM `g-cloud`"; 
// Add in the where clause for both / either a product query or a scope query            
    if (($scope <> "all") and ($term <> "all")) 
      {$sql = $sql . " WHERE ((". $scope_sql .") and (`Product_Service_Description` LIKE '%".$term."%'))"  ;}   
    elseif (($scope <> "all") and ($term == "all")) 
      {$sql = $sql . " WHERE (". $scope_sql .")";}   
    elseif (($scope == "all") and ($term <> "all" )) 
      {$sql = $sql . " WHERE (`Product_Service_Description` LIKE '%". $term ."%')";}   

	$sql=$sql . " GROUP BY Framework";
// Execute the SQL Statement            
   $result=mysqli_query($cxn,$sql);
// Output titles
   echo "<h2>Summary by Framework and Lot - £k</h2>";
// Output the table div tag and header row
   echo "<div class=\"datagrid\"><table>";
   echo "<thead>";
   echo "<tr>";
   echo "<th>Framework</th>";
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
// OUTPUT THE NAV BUTTONS
// -------------------------------------------------------------------------------------------------------------------------------    

   echo "<p style = \"color:#3366ff; font-size:10pt\">Switch &nbsp&nbsp: ";
   if (($type == "Summary") or ($type == "Supplier") or ($type == "Product"))
     {echo "<a href=\"$server/g-cloud.php?type=Customer&rank=$rank&scope=$scope&term=".urlencode($term)."\" class=tv_button>Show customers</a>&nbsp&nbsp";}
   if (($type == "Summary") or ($type == "Customer") or ($type == "Product"))
     {echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=$rank&scope=$scope&term=".urlencode($term)."\" class=tv_button>Show suppliers</a>&nbsp&nbsp";}
    if (($type == "Summary") or ($type == "Customer") or ($type == "Supplier"))
     {echo "<a href=\"$server/g-cloud.php?type=Product&rank=$rank&scope=$scope&term=".urlencode($term)."\" class=tv_button>Show Products</a>&nbsp&nbsp</p>";}
  if ($type == "Summary") 
     {
       echo "<p><form name=\"input\" action=\"g-cloud.php\" method=\"get\">";
       echo "<p style = \"color:#3366ff; font-size:10pt\">Product name: <input type=\"text\" name=\"term\">";
       echo "<input type=\"submit\" value=\"Search\">";
       echo "</form></p>";
       echo "<p style = \"color:#3366ff; font-size:10pt\">For example, to search for all sales of 'Agile' just type in the word Agile and press the search button.</p>";
       echo "<p style = \"color:#3366ff; font-size:10pt\">Show all : ";
       echo "<a href=\"$server/g-cloud_detail.php?type=All+Purchases\" class=tv_button>Show all source data (". number_format($num_recs[0])." records)</a>";}
       echo "</p>";
}
?>

<?php 

if  (($type == "Customer") or ($type == "Supplier") or ($type == "Product"))    
{
// Output the nav buttons - &scope=$scope&term=".urlencode($term)."
   if (1) // ($scope == 'all')
   {
     echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by :  ";
     echo "<a href=\"$server/g-cloud.php?type=$type&rank=alpha&scope=$scope&term=".urlencode($term)."\" class=tv_button>$type Name</a>&nbsp&nbsp";
     echo "<a href=\"$server/g-cloud.php?type=$type&rank=Lot+1&scope=$scope&term=".urlencode($term)."\" class=tv_button>IaaS</a>&nbsp&nbsp";
     echo "<a href=\"$server/g-cloud.php?type=$type&rank=Lot+2&scope=$scope&term=".urlencode($term)."\" class=tv_button>PaaS</a>&nbsp&nbsp";
     echo "<a href=\"$server/g-cloud.php?type=$type&rank=Lot+3&scope=$scope&term=".urlencode($term)."\" class=tv_button>SaaS</a>&nbsp&nbsp";
     echo "<a href=\"$server/g-cloud.php?type=$type&rank=Lot+4&scope=$scope&term=".urlencode($term)."\" class=tv_button>SCS</a>&nbsp&nbsp";
     echo "<a href=\"$server/g-cloud.php?type=$type&rank=total&scope=$scope&term=".urlencode($term)."\" class=tv_button>Total</a></p>";
   }  
   echo "<p style = \"color:#3366ff; font-size:10pt\">Filter by :  ";
   if ($type == "Supplier")
   {echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=local&term=".urlencode($term)."\" class=tv_button>Local Gov</a>&nbsp&nbsp";
    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=health&term=".urlencode($term)."\" class=tv_button>Health</a>&nbsp&nbsp";
    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=emergency&term=".urlencode($term)."\" class=tv_button>Fire & Police</a>&nbsp&nbsp";
    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=total&scope=central&term=".urlencode($term)."\" class=tv_button>Central</a>&nbsp&nbsp";
    echo "<a href=\"$server/g-cloud.php?type=Supplier&rank=alpha&term=".urlencode($term)."\" class=tv_button>All (no filter)</a>&nbsp&nbsp</p>";}
   else
   {echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=local&term=".urlencode($term)."\" class=tv_button>Local Gov</a>&nbsp&nbsp";
    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=health&term=".urlencode($term)."\" class=tv_button>Health</a>&nbsp&nbsp";
    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=emergency&term=".urlencode($term)."\" class=tv_button>Fire & Police</a>&nbsp&nbsp";
    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=total&scope=central&term=".urlencode($term)."\" class=tv_button>Central</a>&nbsp&nbsp";
    echo "<a href=\"$server/g-cloud.php?type=Customer&rank=alpha&term=".urlencode($term)."\" class=tv_button>All (no filter)</a>&nbsp&nbsp</p>";}

     if ($scope == "local") 
       {echo "<p style = \"color:#3366ff; font-size:10pt\">Filter is &nbsp: Customers with names containing Council or Borough; excludes British Council.</p>";}
     elseif ($scope == "health") 
       {echo "<p style = \"color:#3366ff; font-size:10pt\">Filter is &nbsp: Customers with names containing NHS, PCT, CMU, DH, Hospital or Health.  Includes Care Quality Commission.</p>";}
     elseif ($scope == "emergency") 
       {echo "<p style = \"color:#3366ff; font-size:10pt\">Filter is &nbsp: Customers with names containing Fire, and Police.</p>";}
     elseif ($scope == "central") 
       {echo "<p style = \"color:#3366ff; font-size:10pt\">Filter is &nbsp: Customers who do not appear in the Local, Health, Fire and Police listings.</p>";}
     else  
       {echo "<p style = \"color:#3366ff; font-size:10pt\">Showing all records (no filter).</p>";}

     if ($rank == "total")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : Total </p>";}
     elseif ($rank == "alpha")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : $type name </p>";}
     elseif ($rank == "Lot 1")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : IaaS </p>";}
     elseif ($rank == "Lot 2")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : PaaS </p>";}
     elseif ($rank == "Lot 3")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : SaaS </p>";}
     elseif ($rank == "Lot 4")  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : SCS </p>";}
     else  {echo "<p style = \"color:#3366ff; font-size:10pt\">Rank by : $type </p>";}
       
// -------------------------------------------------------------------------------------------------------------------------------    
// OUTPUT THE SUMMARY TABLE OF SPEND BY CUSTOMER OR SUPPLIER
// -------------------------------------------------------------------------------------------------------------------------------    
       
// Initialisations
  $colour = 0;
  $grand_total = array(0,0,0,0,0,0,0,0,0);
  $i=0;
  if (strlen($rank)==0){$rank = "alpha";}  // Present the list by alpha as default
       
    echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Summary\"> <- Back to  G-Cloud Spend home page</a></p>";

// Prepare the SQL statement to do the cross tab   
  if ($type == "Supplier")
  { $sql = "SELECT `Supplier`, sum(`Total_Charge`) as 'Total_Charge' FROM `g-cloud` WHERE 1 group by `Supplier`";
    $sql = "SELECT `Supplier`, 
            SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, 
            SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2,
            SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3,
            SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4,
            sum(`Total_Charge`) as `Total_Charge`
            FROM `g-cloud`";}
  elseif ($type == "Product")
  {
        $sql = "SELECT `Product_Service_Description`, 
            SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, 
            SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2,
            SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3,
            SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4,
            sum(`Total_Charge`) as `Total_Charge`
            FROM `g-cloud`";}
  else
  { $sql = "SELECT `Customer`, sum(`Total_Charge`) as 'Total_Charge' FROM `g-cloud` WHERE 1 group by `Customer`";
    $sql = "SELECT `Customer`, 
            SUM(IF(Lot='1',`Total_Charge`,0)) AS Lot1, 
            SUM(IF(Lot='2',`Total_Charge`,0)) AS Lot2,
            SUM(IF(Lot='3',`Total_Charge`,0)) AS Lot3,
            SUM(IF(Lot='4',`Total_Charge`,0)) AS Lot4,
            sum(`Total_Charge`) as `Total_Charge`
            FROM `g-cloud`";}
// Add in the where clause for both / either a product query or a scope query            
    if (($scope <> "all") and ($term <> "all")) 
      {$sql = $sql . " WHERE ((". $scope_sql .") and (`Product_Service_Description` LIKE '%".$term."%'))"  ;}   
    elseif (($scope <> "all") and ($term == "all")) 
      {$sql = $sql . " WHERE (". $scope_sql .")";}   
    elseif (($scope == "all") and ($term <> "all" )) 
      {$sql = $sql . " WHERE (`Product_Service_Description` LIKE '%". $term ."%')";}   
// Add in the group by clauses
  if ($type == "Supplier")
    {$sql = $sql . " GROUP BY `Supplier`";}
  elseif ($type == "Product")
    {$sql = $sql . " GROUP BY `Product_Service_Description`";}
  else
    {$sql = $sql . " GROUP BY `Customer`";}
// Add in the sort order - note no sort required if sort order is same as group by column
  if ($rank == "total") {$sql=$sql." order by Total_Charge desc";}
  elseif ($rank == "Lot 1") {$sql=$sql." order by Lot1 desc";}
  elseif ($rank == "Lot 2") {$sql=$sql." order by Lot2 desc";}
  elseif ($rank == "Lot 3") {$sql=$sql." order by Lot3 desc";}
  elseif ($rank == "Lot 4") {$sql=$sql." order by Lot4 desc";}

//echo $sql;
// Execute the SQL Statement            
   $result=mysqli_query($cxn,$sql);
// Output the table div tag and header row
// <!-- Table code from http://tablestyler.com/#-->
   echo "<div class=\"datagrid\"><table>";
   echo "<thead>";
   echo "<tr>";
   echo "<th></th>"; // Empty cell for ranking / sequence number
   if ($type == "Supplier") {echo "<th>Supplier</th>";} elseif ($type == "Product") {echo "<th>Product</th>";} else {echo "<th>Customer</th>";}
   echo "<th align = \"right\">IaaS<br>Lot 1</th><th align = \"right\">PaaS<br>Lot 2</th>";
   echo "<th align = \"right\">SaaS<br>Lot 3</th><th align = \"right\">SCS<br>Lot 4</th>";
   echo "<th align = \"right\">Total</th>";
   echo "</tr>";
   echo "</thead><tbody>";
   
// Process the query set results - not worrying about no results as there should always be some!    
   while ($spend = mysqli_fetch_row($result))     
    {       
      if (strlen($spend[0])>0)  // This removes empty rows 
        { if ($colour == 0) {echo "<tr><td><b>";$colour = 1;} else {echo "<tr  class=\"alt\"><td><b>";$colour = 0;} 
          $i=$i+1;
          echo $i."</b></td>";
          if ($type == "Supplier")
            { echo "<td><a href=\"$server/g-cloud_detail.php?type=Supplier&rank=$rank&scope=$scope&term=".urlencode($term)."&Supplier=".urlencode($spend[0])."\">".$spend[0]."</a></td>";}
          elseif ($type == "Product")
            { echo "<td><a href=\"$server/g-cloud_detail.php?type=Product&rank=$rank&scope=$scope&term=".urlencode($term)."&Product=".urlencode($spend[0])."\">".$spend[0]."</a></td>";}
          else
            { echo "<td><a href=\"$server/g-cloud_detail.php?type=Customer&rank=$rank&scope=$scope&term=".urlencode($term)."&Customer=".urlencode($spend[0])."\">".$spend[0]."</a></td>";}
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
   echo "<td><p style = \"color:#3366ff; font-size:10pt\"><b>Grand Total</b></p></td>";
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
?>

<?php include ("footer.php"); ?>
</body>
</html>