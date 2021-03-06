
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - Construction Pipeline - helping you find more business in Government</title>
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

?> 

<h1>Construction Pipeline - £m</h1>

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
      <th>Sector</th>
      <th align = "right">13/14</th>
      <th align = "right">14/15</th>
      <th align = "right">15/16</th>
      <th align = "right">Total 13/16</th>
      <th align = "right">Total 16/20</th>
      <th align = "right">Total post 20</th>
      <th align = "right">Capex Public</th>
      <th align = "right">Capex All</th>
      </tr>
    </thead>
  <tbody>
<?php
  $sql = "SELECT `Sector`, 
            sum(`spend_financial2013_14`) as 'FY12_13',
            sum(`spend_financial2014_15`) as 'FY13_14',
            sum(`spend_financial2015_16`) as 'FY14_15',
            sum(`total2013_16`)  as 'FY13_16',
            sum(`total2016_20`) as 'FY16_20',
            sum(`total2020_beyond`)  as 'FY20_beyond',
            sum(`total_capex_public`) as 'tota_capex_public',
            sum(`total_capex_all`) as 'total_capex_all'
          FROM `construction` WHERE 1 group by `Sector`";
  $result=mysqli_query($cxn,$sql);
  $colour = 0;
  $grand_total = array(0,0,0,0,0,0,0,0,0);
  while ($spend = mysqli_fetch_row($result))
        
    {       
      if ((strlen($spend[0])>0) and ($spend[0] != "Contracts Finder - Current pipelines")) // This removes empty rows 
        { if ($colour == 0) {echo "<tr><td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td>";$colour = 0;} 
          echo "<a href=\"$server/construction_detail.php?sector=".urlencode($spend[0])."\">".$spend[0]."</a></td>";
          echo "<td align = \"right\"> £".number_format(intval($spend[1]))."m</td>";
          $grand_total[1] += $spend[1];
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
          echo "<td align = \"right\"> £".number_format(intval($spend[7]))."m</td>";
          $grand_total[7] += $spend[7];         
          $total_spend = $spend[1]+$spend[2]+$spend[3]+$spend[4]+$spend[5]+$spend[6]+$spend[7];
          $grand_total[8] += $total_spend;       
          echo "<td align = \"right\"><b>£".number_format(intval($total_spend))."m</b></td>";
          echo "</tr>";
          
        } // end if (str_len($spend[0])>0)
    }
          if ($colour == 0) {echo "<tr><td>";$colour = 1;} else {echo "<tr  class=\"alt\"><td>";$colour = 0;} 
          echo "<p style = \"color:#3366ff; font-size:10pt\"><b>Grand Total</b></p></td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[1]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[2]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[3]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[4]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[5]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[6]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[7]))."m</td>";
          echo "<td align = \"right\"> £".number_format(intval($grand_total[8]))."m</td>";
          echo "</tr>";
   
?>
</tbody>
</table></div>


<h2>Notes</h2>
<p>Figures are in £m (£1m = £1,000,000).  If a total is £0, click the name of the organisation to see the detail.  The total may be less than £1m or the organisation may have not entered a value at this time.</p> 
<?php include ("footer.php"); ?>
</body>
</html>