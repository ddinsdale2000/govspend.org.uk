
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - Spend Pipeline - helping you find more business in Government</title>
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
    include('menu_pipeline_search.php');
    include("init.php");  
    $cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
    $cxn2 = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); // used for looking up cpv details
    $term = $_GET['term'] ; // search term
    $analyse = $_GET['analyse'] ; // Primary dimension - one of organisation / title / pipeline_type
    if ($debug > 0 ) {echo "<p>Parameter is: ".$analyse."</p>";}
    if (strlen($analyse) == 0) {$analyse = "pipeline_type"; }
    $name = $_GET['name'] ; // search term
    if (strlen($name) == 0) {$name = "all"; }

    echo "<h2>Search Spend Pipeline</h1>";
    echo "<p style = \"color:#3366ff; font-size:10pt\">This week (13th - 17th Jan 2014) we are testing this capability.  
    All is working fine but if you spot any issues (or have ideas) please send to : <a href= \"mailto:support@tgovspend.org.uk\" style= \"display:inline\">support@govspend.org.uk</a></p>";
    if (strlen($term) == 0 ) {echo "<h2>Searching for - All records</h2>";}
    else {echo "<h2>Searching for - $term</h2>";}
    
    $sql = "select ' ', 
            sum(`SpendFinancial2013_14`)/1000000 as 'FY13_14',
            sum(`SpendFinancial2014_15`)/1000000 as 'FY14_15',
            sum(`SpendFinancial2015_16`)/1000000 as 'FY15_16',
            sum(`SpendFinancial2016_17`)/1000000 as 'FY16_17',
            sum(`SpendFinancial2017_18`)/1000000 as 'FY17_18',
            sum(`SpendFinancial2018_19`)/1000000 as 'FY18_19',
            sum(`TotalCapitalCost`)/1000000 as 'TotaCapitalCost'
          FROM `pipeline` as t1 LEFT JOIN `cpv-codes` as t2 on left(t1.`CPVCodes`,8) = t2.cpv_code where ";
     if ($name != "all")
     {if ($analyse == "pipeline_type") {$sql = $sql . "( `PipelineType` = \"$name\")  ";}
      elseif ($analyse == "organisation") {$sql = $sql . "NoticeOrganisationName = \"$name\" ";}
      elseif ($analyse == "cpv_code") {$sql = $sql . "left(cpvcodes,2) = left(\"$name\",2) ";}
     }     
     if (strlen($term)>0) { 
        if ($name != "all") {$sql = $sql . " and ";}
        $sql = $sql . "     ((NoticeTitle like '%$term%') or (cpv_description like '%$term%') or";
        $sql = $sql . "      (NoticeOrganisationName like '%$term%'))";}
     if (($name == "all") and  (strlen($term)==0)) {$sql = $sql . " 1";}       
     if ($debug > 0 ) {echo "<p>".$sql."</p>";}

     $result=mysqli_query($cxn,$sql);
     $spend = mysqli_fetch_row($result);
     echo "<div class=\"datagrid\"><table>";
     echo "<thead> <tr>";
     echo "<th>".str_repeat("&nbsp",25)."</th>";
//     echo " <th align = \"right\">13/14</th>";
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
//     echo " <td align = \"right\">£".number_format(intval($spend[1]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[2]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[3]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[4]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[5]))."m</td>";
     echo " <td align = \"right\">£".number_format(intval($spend[6]))."m</td>";
     $total_spend = $spend[2]+$spend[3]+$spend[4]+$spend[5]+$spend[6];
     echo " <td align = \"right\"><b>£".number_format(intval($total_spend))."m</b></td>";
     echo " </tr>";
     echo "</tbody>";
     
     echo "</table>";
     echo "</div>";
     
     echo "<p><form name=\"input\" action=\"pipeline.php\" method=\"get\">";
     echo "<p style = \"color:#3366ff; font-size:10pt\">Search for: <input type=\"text\" name=\"term\" value=\"$term\">";
     echo "<input type=\"submit\" value=\"Search\">";
     echo "</form></p>";
     echo "<p style = \"color:#3366ff; font-size:10pt\">Currently searching organisation name, notice description & CPV Code description</p>";

     echo "<p style = \"color:#3366ff; font-size:10pt\">Group by &nbsp: ";
     echo "<a href=\"$server/pipeline.php?analyse=pipeline_type&term=$term\" class=tv_button>Pipeline type</a>&nbsp&nbsp";
     echo "<a href=\"$server/pipeline.php?analyse=organisation&term=$term\" class=tv_button>Organisation name</a>&nbsp&nbsp";  
     echo "<a href=\"$server/pipeline.php?analyse=cpv_code&term=$term\" class=tv_button>Primary CPV code</a>&nbsp&nbsp";  
     echo "<a href=\"$server/pipeline.php\" class=tv_button>Show all</a>&nbsp&nbsp";

  if  ($analyse == "pipeline_type") {echo "<h2>Summary by Pipeline Type - £m</h2>";}
  elseif  ($analyse == "organisation") {echo "<h2>Summary by Organisation - £m</h2>";}
  elseif  ($analyse == "cpv_code") {echo "<h2>Summary by CPV Division - £m</h2>";}
    

?> 
<div class="datagrid"><table>
  <colgroup>
    <col span="1" style="width: 20%;">
<!--    <col span="1" style="width: 6%;"> -->
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 10%;">
  </colgroup>
  <thead>
    <tr>
<?php   
      if ($analyse == "pipeline_type") {echo "<th>Pipeline Type</th>";}
      elseif ($analyse == "title") {echo "<th>Notice title</th>";}
      elseif ($analyse == "organisation") {echo "<th>Organisation</th>";}
      elseif ($analyse == "cpv_code") {echo "<th>CPV Division</th>";}
?>
<!--      <th align = "right">13/14</th> -->
      <th align = "right">14/15</th>
      <th align = "right">15/16</th>
      <th align = "right">16/17</th>
      <th align = "right">17/18</th>
      <th align = "right">18/19</th>
      <th align = "right">Total</th>
      </tr>
    </thead>
  <tbody>
<?php
  $sql = ""; 
  if ($analyse == "pipeline_type") {$sql = "SELECT `PipelineType`,";}
  elseif ($analyse == "title") {$sql = "SELECT `NoticeTitle`,";}
  elseif ($analyse == "organisation") {$sql ="SELECT `NoticeOrganisationName`,";}
//  elseif ($analyse == "cpv_code") {$sql ="SELECT cpv_description as cpv_summary,";}
  elseif ($analyse == "cpv_code") {$sql ="SELECT left(`CPVCodes`,2) as cpv_summary,";}
  $sql = $sql . " 
            sum(`SpendFinancial2013_14`)/1000000 as 'FY13_14',
            sum(`SpendFinancial2014_15`)/1000000 as 'FY14_15',
            sum(`SpendFinancial2015_16`)/1000000 as 'FY15_16',
            sum(`SpendFinancial2016_17`)/1000000 as 'FY16_17',
            sum(`SpendFinancial2017_18`)/1000000 as 'FY17_18',
            sum(`SpendFinancial2018_19`)/1000000 as 'FY18_19',
            sum(`TotalCapitalCost`)/1000000 as 'TotaCapitalCost'
          FROM `pipeline` as t1 LEFT JOIN `cpv-codes` as t2 on left(t1.`CPVCodes`,8) = t2.cpv_code where ";
  if ($name != "all")
    {if ($analyse == "pipeline_type") {$sql = $sql . "( `PipelineType` = \"$name\") and" ;}
     elseif ($analyse == "organisation") {$sql = $sql . "NoticeOrganisationName = \"$name\" and";}
     elseif ($analyse == "cpv_code") {$sql = $sql ."left(cpvcodes,2) = left(\"$name\",2) and";}
    }     
  if (strlen($term)>0) { 
    $sql = $sql . "  ((NoticeTitle like '%$term%') or (cpv_description like '%$term%') or";
    $sql = $sql . "      (NoticeOrganisationName like '%$term%'))";
    $sql = $sql . " group by ";}
  else {$sql = $sql . " 1 group by "; }
  
  if ($analyse == "pipeline_type") {$sql = $sql. " `PipelineType`";}
  elseif ($analyse == "title") {$sql = $sql. " `NoticeTitle`";}
  elseif ($analyse == "organisation") {$sql = $sql. " `NoticeOrganisationName`";}
  elseif ($analyse == "cpv_code") {$sql = $sql. " `cpv_summary`";}
  if ($debug > 0 ) {echo "<p>".$sql."</p>";}
  $result=mysqli_query($cxn,$sql);
  $colour = 0;
  $grand_total = array(0,0,0,0,0,0,0,0,0);
  while ($spend = mysqli_fetch_row($result))
        
    {       
      if ((strlen($spend[0])>0) and ($spend[0] != "Contracts Finder - Current pipelines")) // This removes empty rows 
        { if ($colour == 0) {echo "<tr><td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td>";$colour = 0;} 
          if ($analyse == "cpv_code") // look up cpv_description for root nore of hierarchy to print out
            { $sql2 = "select * from `cpv-codes` where cpv_code = '".$spend[0]."000000'";
              $result2=mysqli_query($cxn2,$sql2);
              $spend2 = mysqli_fetch_row($result2);
              $spend[0] = $spend[0]." - ".$spend2[1];
            }
          echo "<a href=\"$server/pipeline.php?analyse=$analyse&name=".urlencode($spend[0])."\">".$spend[0]."</a></td>";
//          echo "<td align = \"right\"> £".number_format(intval($spend[1]))."m</td>";
//          $grand_total[1] += $spend[1];
          echo "<td align = \"right\"> £".number_format(intval($spend[2]))."m</td>";
          $grand_total[2] += $spend[2];
          echo "<td align = \"right\"> £".number_format(intval($spend[3]))."m</td>";
          $grand_total[3] += $spend[3];
          echo "<td align = \"right\"> £".number_format(intval($spend[4]))."m</td>";
          $grand_total[4] += $spend[4];          
          echo "<td align = \"right\"> £".number_format(intval($spend[5]))."m</td>";
          $grand_total[5] += $spend[5];          
          echo "<td align = \"right\"> £".number_format(intval($spend[6]))."m</td>";
          $grand_total[6] += $spend[6];
          $total_spend = $spend[2]+$spend[3]+$spend[4]+$spend[5]+$spend[6];
          $grand_total[7] += $total_spend;       
          echo "<td align = \"right\"><b>£".number_format(intval($total_spend))."m</b></td>";
          echo "</tr>";
          
        } // end if (str_len($spend[0])>0)
    }
          if ($colour == 0) {echo "<tr><td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td>";$colour = 0;} 
          echo "<p style = \"color:#3366ff; font-size:10pt\"><b>Grand Total</b></p></td>";
//        echo "<td align = \"right\"> £".number_format(intval($grand_total[1]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[2]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[3]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[4]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[5]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[6]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[7]))."m</td>";
          echo "</tr>";
   
?>
</tbody>
</table></div>

<?php
$sql = "";
if (($name != "all") or (strlen($term) > 0)) // Output the detail table if there is a query 
{

    if ($name == "all")
    {  

//            `SpendFinancial2012_13`/1000 as 'FY12_13',

       if ($analyse == "pipeline_type") {$sql = " SELECT  `NoticeOrganisationName`,  `NoticeTitle`,";}
       elseif ($analyse == "title") {$sql = " SELECT `PipelineType`,`NoticeOrganisationName`,";}
       elseif ($analyse == "organisation") {$sql = " SELECT `PipelineType`, `NoticeTitle`, ";}
       elseif ($analyse == "cpv_code") {$sql = " SELECT `CPVCodes`,  `NoticeTitle`,";}
       $sql = $sql . "
            `SpendFinancial2014_15`/1000 as 'FY14_15',
            `SpendFinancial2015_16`/1000 as 'FY15_16',
            `SpendFinancial2016_17`/1000 as 'FY16_17',
            `SpendFinancial2017_18`/1000 as 'FY17_18',
            `SpendFinancial2018_19`/1000 as 'FY18_19',
            `TotalCapitalCost`/1000 as 'TotaCapitalCost',
            `URL`
          FROM `pipeline` as t1 LEFT JOIN `cpv-codes` as t2 on left(t1.`CPVCodes`,8) = t2.cpv_code where ";
          if (strlen($term)>0) { 
              $sql = $sql . "   ((NoticeTitle like '%$term%') or (cpv_description like '%$term%') or";
              $sql = $sql . "      (NoticeOrganisationName like '%$term%'))";}

     } else
    { 
       if ($analyse == "pipeline_type") {$sql = "SELECT  `NoticeOrganisationName`,`NoticeTitle`,";}
       elseif ($analyse == "title") {$sql = "SELECT `PipelineType`,`NoticeOrganisationName`,";}
       elseif ($analyse == "organisation") {$sql = "SELECT  `PipelineType`,`NoticeTitle`, ";}
       elseif ($analyse == "cpv_code") {$sql = " SELECT `CPVCodes`,  `NoticeTitle`,";}
       $sql = $sql . "
            `SpendFinancial2014_15`/1000 as 'FY14_15',
            `SpendFinancial2015_16`/1000 as 'FY15_16',
            `SpendFinancial2016_17`/1000 as 'FY16_17',
            `SpendFinancial2017_18`/1000 as 'FY17_18',
            `SpendFinancial2018_19`/1000 as 'FY18_19',
            `TotalCapitalCost`/1000 as 'TotaCapitalCost',
           `URL`
          FROM `pipeline` as t1 LEFT JOIN `cpv-codes` as t2 on left(t1.`CPVCodes`,8) = t2.cpv_code where ";

       if (strlen($term)>0) { 
       $sql = $sql . "     ((NoticeTitle like '%$term%') or (cpv_description like '%$term%') or";
       $sql = $sql . "      (NoticeOrganisationName like '%$term%')) and ";}

       if ($analyse == "pipeline_type") {$sql = $sql . "`PipelineType` = \"$name\" order by `NoticeOrganisationName`, `NoticeTitle`";}
       elseif ($analyse == "title") {$sql = $sql . " `NoticeTitle` = \"$name\" order by `PipelineType`,`NoticeOrganisationName`";}
       elseif ($analyse == "organisation") {$sql = $sql . " NoticeOrganisationName = \"$name\" order by `PipelineType`,`NoticeTitle`";}
       elseif ($analyse == "cpv_code") {$sql = $sql . "left(cpvcodes,2) = left(\"$name\",2) order by `CPVCodes`, `NoticeTitle`";}

     }

echo "<h2>Details of individual opportunities</h2>";
echo "<p style = \"color:#3366ff; font-size:10pt\">Click the Opportunity Title to view the opportunity on Contracts Finder</p>";

    if ($debug > 0 ) {echo "<p>".$sql."</p>";}
    if ($debug > 0 ) {echo "<p>".$analyse."</p>";}

//<!-- Table code from http://tablestyler.com/#-->
  echo "<div class=\"datagrid\"><table>";
  echo "<colgroup>";
  echo "  <col span=\"1\" style=\"width: 6%;\">";
  echo "  <col span=\"1\" style=\"width: 20%;\">";
  echo "  <col span=\"1\" style=\"width: 20%;\">";
  echo "  <col span=\"1\" style=\"width: 6%;\">";
  echo "  <col span=\"1\" style=\"width: 6%;\">";
  echo "  <col span=\"1\" style=\"width: 6%;\">";
  echo "  <col span=\"1\" style=\"width: 6%;\">";
  echo "  <col span=\"1\" style=\"width: 6%;\">";
  echo "  <col span=\"1\" style=\"width: 10%;\">";
  echo "</colgroup>"; 
  echo "<thead>";
  echo "  <tr>";
  echo "  <th align = \"left\"></th>";

  if ($analyse == "pipeline_type") { echo "<th>Organisation </th> <th>Opportunity Title</th>";}
  elseif ($analyse == "title") { echo "<th>Pipeline Type</th> <th>Organisation</th>";}
  elseif ($analyse == "organisation") {echo "<th>Pipeline Type</th><th>Opportunity Title</th>";}
  elseif ($analyse == "cpv_code") { echo "<th>Primary CPV Code</th> <th>Opportunity Title</th>";}
    
  echo "    <th align = \"right\">14/15</th>";
  echo "    <th align = \"right\">15/16</th>";
  echo "    <th align = \"right\">16/17</th>";
  echo "    <th align = \"right\">17/18</th>";
  echo "    <th align = \"right\">18/19</th>";
  echo "    <th align = \"right\">Total</th>";
  echo "    </tr>";
  echo "   </thead>";
  echo " <tbody>";
      
  $result=mysqli_query($cxn,$sql);
  $colour = 0;
  $grand_total = array(0,0,0,0,0,0,0,0,0);
  $count = 1;
  while ($spend = mysqli_fetch_row($result))
        
    {     
      if ((strlen($spend[0])>0) and ($spend[0] != "Contracts Finder - Current pipelines")) // This removes empty rows 
        { if ($analyse == "cpv_code") // look up cpv_description for root nore of hierarchy to print out
            { $sql2 = "select * from `cpv-codes` where cpv_code =  left('".$spend[0]."',8)";
              $result2=mysqli_query($cxn2,$sql2);
              $spend2 = mysqli_fetch_row($result2);
              $spend[0] = $spend2[0]." - ".$spend2[1];
            }

          if ($colour == 0) {echo "<tr><td>$count</td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td>$count</td>";$colour = 0;} 
          echo "<td align = \"left\"> $spend[0]</td>";
          echo "<td><a href = \"".$spend[8]."\" target=\"_blank\">".$spend[1]."</a></td>";
          $grand_total[1] += $spend[1];
          echo "<td align = \"right\"> £".number_format(intval($spend[2]))."k</td>";
          $grand_total[2] += $spend[2];
          echo "<td align = \"right\"> £".number_format(intval($spend[3]))."k</td>";
          $grand_total[3] += $spend[3];
          echo "<td align = \"right\"> £".number_format(intval($spend[4]))."k</td>";
          $grand_total[4] += $spend[4];          
          echo "<td align = \"right\"> £".number_format(intval($spend[5]))."k</td>";
          $grand_total[5] += $spend[5];          
          echo "<td align = \"right\"> £".number_format(intval($spend[6]))."k</td>";
          $grand_total[6] += $spend[6];
          $total_spend = $spend[1]+$spend[2]+$spend[3]+$spend[4]+$spend[5]+$spend[6];
          $grand_total[7] += $total_spend;       
          echo "<td align = \"right\"><b>£".number_format(intval($total_spend))."k</b></td>";
          echo "</tr>";
          $count += 1;
        } // end if (str_len($spend[0])>0)
    }
        
          if ($colour == 0) {echo "<tr><td></td><td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td></td><td>";$colour = 0;} 
          echo "<p style = \"color:#3366ff; font-size:10pt\"><b>Grand Total</b></p></td>";
          echo "<td align = \"right\"><b> </b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[2]/1000))."m</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[3]/1000))."m</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[4]/1000))."m</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[5]/1000))."m</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[6]/1000))."m</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[7]/1000))."m</b></td>";
 //         echo "<td align = \"right\"><b> £".number_format(intval($grand_total[8]))."k</b></td>";
          echo "</tr>";
          echo "</tbody>";
          echo "</table></div>";
}

?>

<h2>Notes</h2>
<p>Total Figures are in £m (£1m = £1,000,000), individual opportunities are in £k (£1k - £1,000). Figures are always rounded down. 
If a total is £0, click the links (e.g. Pipeline Type, Organisation Name, Opportunity Title etc.) to see the detail.  
It is likely that the total is £0 because the organisation providing the data has not entered a value at this time.</p> 
<p>Totals on this screen may not match totals on the Introduction screen as the timeframe on this screen 
is reduced to 5 years (2014 - 2019).  The pipeline contains forecasts for more than 5 years.</p>

<?php include ("footer.php"); ?>
</body>
</html>