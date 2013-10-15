
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
    $org = $_GET["NoticeOrganisationName"];
    if ($org == "all")
    {
      $sql = "SELECT `NoticeTitle`, 
            `SpendFinancial2012_13`/1000 as 'FY12_13',
            `SpendFinancial2013_14`/1000 as 'FY13_14',
            `SpendFinancial2014_15`/1000 as 'FY14_15',
            `SpendFinancial2015_16`/1000 as 'FY15_16',
            `SpendFinancial2016_17`/1000 as 'FY16_17',
            `SpendFinancial2017_18`/1000 as 'FY17_18',
            `SpendFinancial2018_19`/1000 as 'FY18_19',
            `TotalCapitalCost`/1000 as 'TotaCapitalCost',
            `URL`
          FROM `pipeline` ";
     } else
     { $sql = "SELECT `NoticeTitle`, 
            `SpendFinancial2012_13`/1000 as 'FY12_13',
            `SpendFinancial2013_14`/1000 as 'FY13_14',
            `SpendFinancial2014_15`/1000 as 'FY14_15',
            `SpendFinancial2015_16`/1000 as 'FY15_16',
            `SpendFinancial2016_17`/1000 as 'FY16_17',
            `SpendFinancial2017_18`/1000 as 'FY17_18',
            `SpendFinancial2018_19`/1000 as 'FY18_19',
            `TotalCapitalCost`/1000 as 'TotaCapitalCost',
           `URL`
          FROM `pipeline` WHERE NoticeOrganisationName = \"$org\"";

     }
?> 

<h1>Pipeline Details</h1>

<?php echo "<h2>".$org."</h2>";?>
<?php echo "<a href=\"$server/pipeline.php\" style = \"color:#3366ff; font-size:10pt\"><-- Back</a>";?>

<!-- Table code from http://tablestyler.com/#-->
<div class="datagrid"><table>
  <colgroup>
    <col span="1" style="width: 20%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 6%;">
    <col span="1" style="width: 10%;">
  </colgroup>
  <thead>
    <tr>
      <th>Notice Title</th>
      <th align = "right">12/13</th>
      <th align = "right">13/14</th>
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
      
  $result=mysqli_query($cxn,$sql);
  $colour = 0;
  $grand_total = array(0,0,0,0,0,0,0,0,0);
  while ($spend = mysqli_fetch_row($result))
        
    {       
      if ((strlen($spend[0])>0) and ($spend[0] != "Contracts Finder - Current pipelines")) // This removes empty rows 
        { if ($colour == 0) {echo "<tr><td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td>";$colour = 0;} 
          echo "<a href = \"".$spend[9]."\" target=\"_blank\">".$spend[0]."</a></td>";
          echo "<td align = \"right\"> £".number_format(intval($spend[1]))."k</td>";
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
          echo "<td align = \"right\"> £".number_format(intval($spend[7]))."k</td>";
          $grand_total[7] += $spend[7];         
          $total_spend = $spend[1]+$spend[2]+$spend[3]+$spend[4]+$spend[5]+$spend[6]+$spend[7];
          $grand_total[8] += $total_spend;       
          echo "<td align = \"right\"><b>£".number_format(intval($total_spend))."k</b></td>";
          echo "</tr>";
          
        } // end if (str_len($spend[0])>0)
    }
          if ($colour == 0) {echo "<tr><td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td>";$colour = 0;} 
          echo "<p style = \"color:#3366ff; font-size:10pt\"><b>Grand Total</b></p></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[1]))."k</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[2]))."k</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[3]))."k</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[4]))."k</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[5]))."k</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[6]))."k</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[7]))."k</b></td>";
          echo "<td align = \"right\"><b> £".number_format(intval($grand_total[8]))."k</b></td>";
          echo "</tr>";
   
?>
</tbody>
</table></div>
<?php echo "<a href=\"$server/pipeline.php\" style = \"color:#3366ff; font-size:10pt\"><-- Back</a>";?>

<h2>Notes</h2>
<p>Links will open a new tab or window and take you to that opportunity on Contracts Finder</p> 
<p>Figures are rounded down to the nearest £k (£1k = £1,000)</p> 
<?php include ("footer.php"); ?>
</body>
</html>