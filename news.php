
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - News - helping you find more business in Government</title>
  <link rel="stylesheet" href="main.css" type="text/css" />
      <link rel="stylesheet" href="table.css" type="text/css" />

</head>
<body>
<?php 
/* Page Name:  	Contact Us
 * Desc:      	
 *
 */
    include_once("analyticstracking.php");
    include('header_main.php'); 
    include('menu1.php');
    include('init.php');
?> 

<h1>News</h1>
<h2>16th October, 2013 - G-Cloud Spend for September 2013</h2>
<p>Spend for September 2013 has now been included.  Here are some observations from the data.</p> 

<p>Total spend was £5.6m in September.</p>
<h3>New customers</h3>
<p>The framework welcomed 14 new customers this month (bringing the total to 286).  The new customers were:</p>
<ul>
<?php
   echo "<div class=\"datagrid\"><table>";
   echo "<thead>";
   echo "<tr>";
   echo "<th>Customer</th>"; 
   echo "<th align = \"right\">Total</th>";
   echo "</tr>";
   echo "</thead><tbody>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Catalyst Housing Group")."\">Catalyst Housing Group</a></td>";
   echo "<td align = \"right\">£216</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Cell Therapy Catapult")."\">Cell Therapy Catapult</a></td>";
   echo "<td align = \"right\">£13,500</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Coal Authority The")."\">Coal Authority The</a></td>";
   echo "<td align = \"right\">£5,000</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("ConstructionSkills")."\">ConstructionSkills</a></td>";
   echo "<td align = \"right\">£1,350</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Convention of Scottish Local Authorities")."\">Convention of Scottish Local Authorities</a></td>";
   echo "<td align = \"right\">£9,945</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Driver and Vehicle Licensing Agency")."\">Driver and Vehicle Licensing Agency</a></td>";
   echo "<td align = \"right\">£1,350</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("HM Treasury")."\">HM Treasury</a></td>";
   echo "<td align = \"right\">£10,000</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("HS2 Ltd")."\">HS2 Ltd</a></td>";
   echo "<td align = \"right\">£1,650</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Huddle Subscription")."\">Huddle Subscription</a></td>";
   echo "<td align = \"right\">£27,000</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Mayors Office for Policing and Crime")."\">Mayors Office for Policing and Crime</a></td>";
   echo "<td align = \"right\">£47,797</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("National Institute for Clinical Excellence")."\">National Institute for Clinical Excellence</a></td>";
   echo "<td align = \"right\">£5,000</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Portsmouth City Council")."\">Portsmouth City Council</a></td>";
   echo "<td align = \"right\">£36,000</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("University of Stirling")."\">University of Stirling</a></td>";
   echo "<td align = \"right\">£6,240</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Customer&term=".urlencode($term)."&Customer=".urlencode("Wirral Metropolitan Borough Council")."\">Wirral Metropolitan Borough Council</a></td>";
   echo "<td align = \"right\">£850</td>";

   echo "</tbody></table></div>";

?>
</ul>
<h3>New suppliers</h3>
<p>The framework welcomed 14 new suppliers who reported their first revenue this month (bringing the total to 189).  This 
included a rather spectacular first month for Acuma Solutions Limited who jumped to sixth overall based on a significant amount
of business with Home Office.  The new suppliers were:</p>

<ul>
<?php
   echo "<div class=\"datagrid\"><table>";
   echo "<thead>";
   echo "<tr>";
   echo "<th>Customer</th>"; 
   echo "<th align = \"right\">Total</th>";
   echo "</tr>";
   echo "</thead><tbody>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("Acuma Solutions Limited")."\">Acuma Solutions Limited</a></td>";
   echo "<td align = \"right\">£814,767</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("BIG BLUE DOOR LTD")."\">BIG BLUE DOOR LTD</a></td>";
   echo "<td align = \"right\">£2,250</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("Compass Management Consulting Ltd")."\">Compass Management Consulting Ltd</a></td>";
   echo "<td align = \"right\">£41,441</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("Computing Distribution Group Ltd")."\">Computing Distribution Group Ltd</a></td>";
   echo "<td align = \"right\">£13,500</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("Deltascheme Ltd")."\">Deltascheme Ltd</a></td>";
   echo "<td align = \"right\">£7,507</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("Epimorphics Ltd")."\">Epimorphics Ltd	</a></td>";
   echo "<td align = \"right\">£12,000</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("FDM Group")."\">FDM Group</a></td>";
   echo "<td align = \"right\">£3,240</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("INTELLIGENT CONSULTING SERVICES LIMITED")."\">INTELLIGENT CONSULTING SERVICES LIMITED</a></td>";
   echo "<td align = \"right\">£33,000</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("IT Works Recruitment Ltd")."\">IT Works Recruitment Ltd</a></td>";
   echo "<td align = \"right\">£10,000</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("MEDLEY BUSINESS SOLUTIONS LTD")."\">MEDLEY BUSINESS SOLUTIONS LTD</a></td>";
   echo "<td align = \"right\">£22,554</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("Purple Tuesday Limited")."\">Purple Tuesday Limited</a></td>";
   echo "<td align = \"right\">£1,350</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("Solirius Ltd")."\">Solirius Ltd</a></td>";
   echo "<td align = \"right\">£44,275</td>";

   echo "<tr><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("SYMOLOGY LTD")."\">SYMOLOGY LTD</a></td>";
   echo "<td align = \"right\">£72,765</td>";
   echo "<tr class=\"alt\"><td><a href=\"$server/g-cloud_detail.php?type=Supplier&term=".urlencode($term)."&Supplier=".urlencode("Zendesk")."\">Zendesk</a></td>";
   echo "<td align = \"right\">£4,000</td>";

   echo "</tbody></table></div>";

?>
</ul>



<h3>Other items of note</h3>

<p>Total spend on the framework rose by £8.8m to £53.5m after the spend figures for May, July and August were revised:</p>
<ul>
<li>May - from £3.58, to £4.17m</li>
<li>July - from £7.1m to £7.9m</li>
<li>August - from £5.1m to £6.8m</li>
</ul>
<?php include ("footer.php"); ?>
</body>
</html>