
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - Spend Pipeline Introduction - helping you find more business in Government</title>
  <link rel="stylesheet" href="main.css" type="text/css" />
  <link rel="stylesheet" href="table.css" type="text/css" />
  <link rel="stylesheet" href="button.css" type="text/css" />

</head>
<body>
<?php 
/* Page Name:  	Spend Pipeline
 * Desc:        Allows analysis of spend pipeline by the following parameters   	
 *				NoticeOrganisationName
 *				NoticeTitle
 *				PipelineType
 */
    $debug = 0;
    include_once("analyticstracking.php");
    include('header_main.php'); 
    include('menu_pipeline.php');
    include("init.php");  
    $cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
    $term = $_GET['term'] ; // search term
    $analyse = $_GET['analyse'] ; // Primary dimension - one of organisation / title / pipeline_type
    if ($debug > 0 ) {echo "<p>Parameter is: ".$analyse."</p>";}
    if (strlen($analyse) == 0) {$analyse = "pipeline_type"; }
    $name = $_GET['name'] ; // search term
    if (strlen($name) == 0) {$name = "all"; }

    echo "<h2>Spend Pipeline Introduction</h1>";
    
     echo "<p><form name=\"input\" action=\"pipeline.php\" method=\"get\">";
     echo "<p style = \"color:#3366ff; font-size:10pt\">Search for: <input type=\"text\" name=\"term\" value=\"$term\">";
     echo "<input type=\"submit\" value=\"Search\">";
     echo "</form></p>";
     echo "<p style = \"color:#3366ff; font-size:10pt\">Currently searching organisation name and notice description</p>";

     echo "<p style = \"color:#3366ff; font-size:10pt\">Group by &nbsp: ";
     echo "<a href=\"$server/pipeline.php?analyse=pipeline_type&term=$term\" class=tv_button>Pipeline type</a>&nbsp&nbsp";
     echo "<a href=\"$server/pipeline.php?analyse=organisation&term=$term\" class=tv_button>Organisation name</a>&nbsp&nbsp";  
     echo "<a href=\"$server/pipeline.php\" class=tv_button>Show all</a>&nbsp&nbsp";

    echo "<p style = \"color:#3366ff; font-size:10pt\">This page is in test mode.  All should work but any issues / ideas please send to : <a href= \"mailto:support@tgovspend.org.uk\" >support@govspend.org.uk</a></p>";

    echo "<h2>Total of all records in the current spend pipeline</h2>";
    
    $sql = "select ' ', 
            sum(`SpendFinancial2013_14`)/1000000 as 'FY13_14',
            sum(`SpendFinancial2014_15`)/1000000 as 'FY14_15',
            sum(`SpendFinancial2015_16`)/1000000 as 'FY15_16',
            sum(`SpendFinancial2016_17`)/1000000 as 'FY16_17',
            sum(`SpendFinancial2017_18`)/1000000 as 'FY17_18',
            sum(`SpendFinancial2018_19`)/1000000 as 'FY18_19',
            sum(`TotalCapitalCost`)/1000000 as 'TotaCapitalCost'
          FROM `pipeline` where ";
     if ($name != "all")
     {if ($analyse == "pipeline_type") {$sql = $sql . "( `PipelineType` = \"$name\")  ";}
      elseif ($analyse == "organisation") {$sql = $sql . "NoticeOrganisationName = \"$name\" ";}
     }     
     if (strlen($term)>0) { 
        if ($name != "all") {$sql = $sql . " and ";}
        $sql = $sql . "     ((NoticeTitle like '%$term%') or ";
        $sql = $sql . "      (NoticeOrganisationName like '%$term%'))";}
     if (($name == "all") and  (strlen($term)==0)) {$sql = $sql . " 1";}       
     if ($debug > 0 ) {echo "<p>".$sql."</p>";}

     $result=mysqli_query($cxn,$sql);
     $spend = mysqli_fetch_row($result);
     echo "<div class=\"datagrid\"><table>";
     echo "<thead> <tr>";
     echo "<th>".str_repeat("&nbsp",25)."</th>";
     echo " <th align = \"right\">13/14</th>";
     echo " <th align = \"right\">14/15</th>";
     echo " <th align = \"right\">15/16</th>";
     echo " <th align = \"right\">16/17</th>";
     echo " <th align = \"right\">17/18</th>";
     echo " <th align = \"right\">18/19</th>";
     echo " <th align = \"right\">Total</th>";
     echo " </tr>";

     echo "</thead>";
     echo "<tbody><tr>";
     echo "<td>Grand Total</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[1]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[2]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[3]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[4]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[5]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[6]))."m</td>";
     $total_spend = $spend[1]+$spend[2]+$spend[3]+$spend[4]+$spend[5]+$spend[6];
     echo " <td align = \"right\"><b>£".number_format(intval($total_spend))."m</b></td>";
     echo " </tr>";
     echo "</tbody>";
     
     echo "</table>";
     echo "</div>";
     

?>
<h2>Notes</h2>
<p>Figures are in £m (£1m = £1,000,000). </p> 

<?php include ("footer.php"); ?>
</body>
</html>