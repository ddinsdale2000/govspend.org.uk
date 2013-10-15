
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - Pipeline Detail - helping you find more business in Government</title>
  <link rel="stylesheet" href="main.css" type="text/css" />
  <link rel="stylesheet" href="table.css" type="text/css" />
</head>
<body>
<?php 
/* Page Name:  	Spend Pipeline
 * Desc:      	
 *
 */
    include_once("analyticstracking.php");
    include('header_main.php'); 
    include('menu1.php');
    include("init.php");  
    $cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
    $type = $_GET["type"];
// -------------------------------------------------------------------------------------------------------------------------
// rank, term and scope have no function in this page apart from being passed back to the calling page to ensure
// that the user does not end back with a different list than they started with
// -------------------------------------------------------------------------------------------------------------------------
    $rank = $_GET["rank"]; // Specifies the sort order of the customer and supplier lists
    $term = $_GET["term"]; // Specifies the product string to search for
    if (strlen($term) == 0) {$term="all";}
    $scope = $_GET["scope"];  // specifies a sub query e.g. local = Local Government  
// -------------------------------------------------------------------------------------------------------------------------
    $customer = $_GET["Customer"];
    $product = $_GET["Product"];
    $supplier = $_GET["Supplier"];
    if ($type == "All Purchases")
      {$sql = "SELECT `Customer`,`Framework`,`Lot`,`For_Month`,`Product_Service_Description`,`Total_Charge`
          FROM `g-cloud` order by `Customer` , `For_Month` desc";} 
    elseif ($type == "Customer")
      { $sql = "SELECT `Supplier`,`Framework`,`Lot`,`For_Month`,`Product_Service_Description`,`Total_Charge`
          FROM `g-cloud` WHERE `Customer` = \"$customer\" order by `Supplier`, `For_Month` desc ";} 
    elseif ($type == "Product")
      { $sql = "SELECT `Customer`,`Framework`,`Lot`,`For_Month`,`Product_Service_Description`,`Total_Charge`
          FROM `g-cloud` WHERE `Product_Service_Description` = \"$product\" order by `Customer`, `For_Month` desc ";} 
    else
      { $sql = "SELECT `Customer`,`Framework`,`Lot`,`For_Month`,`Product_Service_Description`,`Total_Charge`
          FROM `g-cloud` WHERE `Supplier` = \"$supplier\" order by `Customer`, `For_Month` desc ";}


?> 
<h1>G-Cloud Spend Details</h1>

<?php 
// Print headers
if ($type == "Customer") 
  {echo "<h2>Analysing ".$type." : ".$customer."</h2>";} 
elseif ($type == "Product") 
  {echo "<h2>Analysing ".$type." : ".$product."</h2>";}
elseif ($type == "Supplier") 
 {echo "<h2>Analysing ".$type." : ".$supplier."</h2>";}

// Set up the back to links
if ($type == "Customer") 
  {echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Customer&rank=$rank&scope=$scope&term=".urlencode($term)."\" style = \"color:#3366ff; font-size:10pt\"><-- Back to list of customers</a></p>";}
elseif ($type == "Product") 
  {echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Product&rank=$rank&scope=$scope&term=".urlencode($term)."\" style = \"color:#3366ff; font-size:10pt\"><-- Back to list of products</a></p>";}
elseif ($type == "Supplier") 
  {echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Supplier&rank=$rank&scope=$scope&term=".urlencode($term)."\" style = \"color:#3366ff; font-size:10pt\"><-- Back to list of suppliers</a></p>";}
?>

 
<!-- Table code from http://tablestyler.com/#-->
<div class="datagrid"><table>
  <thead>
    <tr>
      <th></th>
<?php if (strlen($org)>0) {echo "<th>Supplier</th>";} else {echo "<th>Customer</th>";}?>
      <th>Framework</th>
      <th>Lot</th>
      <th>Month</th>
      <th>Description</th>
      <th align = "right">Charge</th>
      </tr>
    </thead>
  <tbody>
<?php
      
  $result=mysqli_query($cxn,$sql);
  $colour = 0;
  $rank=0;
  $grand_total = array(0,0,0,0,0,0,0,0,0);
  while ($spend = mysqli_fetch_row($result))
        
    {       
      if (strlen($spend[0])>0)  // This removes empty rows 
        { if ($colour == 0) {echo "<tr>";$colour = 1;} else {echo "<tr  class=\"alt\">";$colour = 0;} 
          $rank=$rank+1;
          echo "<td><b>".$rank."</b></td>";
          if ($type == "Supplier")
            {echo "<td><a href=\"$server/g-cloud_detail.php?type=Customer&Customer=".urlencode($spend[0])."\">".$spend[0]."</a></td>";}          
          elseif ($type == "Customer")   
            {echo "<td><a href=\"$server/g-cloud_detail.php?type=Supplier&Supplier=".urlencode($spend[0])."\">".$spend[0]."</a></td>";}
          else
            {echo "<td><a href=\"$server/g-cloud_detail.php?type=Customer&Customer=".urlencode($spend[0])."\">".$spend[0]."</a></td>";}          
          echo "<td>".$spend[1]."</td>";
          echo "<td>".$spend[2]."</td>";
          echo "<td>".$spend[3]."</td>";
          echo "<td>".$spend[4]."</td>";
          echo "<td align = \"right\">£".number_format($spend[5])."</td>";
          $grand_total[5]+=$spend[5];
          echo "</tr>";
          
        } // end if (str_len($spend[0])>0)
    }
          if ($colour == 0) {echo "<tr><td></td><td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td></td><td>";$colour = 0;} 
          echo "<p style = \"color:#3366ff; font-size:10pt\"><b>Grand Total</b></p></td>";
          echo "<td></td>";
          echo "<td></td>";
          echo "<td></td>";
          echo "<td></td>";
          echo "<td align = \"right\"><b> £".number_format($grand_total[5])."</b></td>";
          echo "</tr>";
   
?>
</tbody>
</table></div>
<?php 
if ($type == "Customer") 
  {echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Customer&rank=$rank&scope=$scope&term=".urlencode($term)."\" style = \"color:#3366ff; font-size:10pt\"><-- Back to list of customers</a></p>";}
elseif ($type == "Product") 
  {echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Product&rank=$rank&scope=$scope&term=".urlencode($term)."\" style = \"color:#3366ff; font-size:10pt\"><-- Back to list of products</a></p>";}
elseif ($type == "Supplier") 
  {echo "<p style = \"color:#3366ff; font-size:10pt\"><a href=\"$server/g-cloud.php?type=Supplier&rank=$rank&scope=$scope&term=".urlencode($term)."\" style = \"color:#3366ff; font-size:10pt\"><-- Back to list of suppliers</a></p>";}
?>

<h2>Notes</h2>
<p>There are no notes at this time</p> 
<?php include ("footer.php"); ?>
</body>
</html>