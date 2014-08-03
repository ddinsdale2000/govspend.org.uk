<?php session_start(); 
//if (isset($_SESSION["username"])){  header("location:main.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="keywords" content="Government spend public sector contracts finder g-cloud gcloud data contracts-finder">
    <meta name="author" content="Creative Space Learning">
    <title>Welcome to GovSpend.Org.UK - home</title>
    <link rel="stylesheet" href="main.css" type="text/css" />
    <link rel="stylesheet" href="button.css" type="text/css" />

</head>

<body>
<?php 
  include_once("analyticstracking.php"); 
  include('header_main.php'); 
  include('menu1.php');
  include("init.php");  
  $cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
?>
<div id ="page" style = "float:left; width:100%; "  >

<p style = "font-size : 6pt"> &nbsp </p>
  </header>
  
  <div id="navborder" >
    <nav id="left_nav"  >
 
      <p style="background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">
        News
      </p>
      <center>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
      <p> <a href="news.php" class=tv_button>28 Jul 2014 <br>G-Cloud news</a></p>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
<!--      <p> <a href="index.php" class=tv_button>Not yet working</a></p> -->
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
      </center>
    </nav>
    <p style = "color:#3366ff; font-size:8pt">&nbsp</p>

    <div id="navfooter">
      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">Summary</p>

       <p style = "color:#3366ff; font-size:10pt"><br><b>G-Cloud Spend</b> </p>

      <?php
        $sql = "SELECT `For_Month` , sum(`Total_Charge`)/10000  FROM `g-cloud` group by `For_Month` desc";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - June 2014 : ".str_repeat("&nbsp",14)."£".number_format(intval($spend[1])/100)."m</p>";

        $sql = "SELECT sum(`Total_Charge`)/1000000  FROM `g-cloud` WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - To date: ".str_repeat("&nbsp",18)."£".number_format(intval($spend[0]))."m</p>";

        $sql = "SELECT `message` FROM `sys_messages` WHERE (`key_id` = 'g-cloud import')";
        $result=mysqli_query($cxn,$sql);
        $import_date = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">Data as at $import_date[0] </p>";

      ?>

      <p style = "color:#3366ff; font-size:10pt"><br><b>Spend Pipeline</b> </p>

      <?php

        $target_date = Date("Y-m-d");
		$spend[0] = 0;

        $sql = "SELECT sum(`SpendFinancial2014_15`)/1000000 as 'FY14_15' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend += $spend[0];
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2014: ".str_repeat("&nbsp",16)." £".number_format(intval($spend[0]))."m</p>";

        $sql = "SELECT sum(`SpendFinancial2015_16`)/1000000 as 'FY15_16' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend += $spend[0];
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2015: ".str_repeat("&nbsp",14)." £".number_format(intval($spend[0]))."m</p>";

        $sql = "SELECT sum(`SpendFinancial2016_17`)/1000000 as 'FY16_17' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend += $spend[0];
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2016: ".str_repeat("&nbsp",14)." £".number_format(intval($spend[0]))."m</p>";

        $sql = "SELECT sum(`SpendFinancial2017_18`)/1000000 as 'FY17_18' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend += $spend[0];
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2017: ".str_repeat("&nbsp",14)." £".number_format(intval($spend[0]))."m</p>";

        $sql = "SELECT sum(`SpendFinancial2018_19`)/1000000 as 'FY18_19' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend += $spend[0];
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2018: ".str_repeat("&nbsp",15)."£".number_format(intval($spend[0]))."m</p>";

        $sql = "SELECT sum(`SpendFinancial2019_20`)/1000000 as 'FY19_20' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend += $spend[0];
        //mysqli_close($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2019: ".str_repeat("&nbsp",15)."£".number_format(intval($spend[0]))."m</p>";

// Not sure whether this figure is useful
//        $sql = "SELECT sum(`TotalCapitalCost`)/1000000 as 'TotalCapitalCost' FROM pipeline WHERE 1";
//        $result=mysqli_query($cxn,$sql);
//        $spend = mysqli_fetch_row($result);
//        echo "<p style = \"color:#3366ff; font-size:10pt\">Total Capex:  £".number_format(intval($spend[0]))."m</p>";

        echo "<p style = \"color:#3366ff; font-size:10pt\">Total Pipeline: ".str_repeat("&nbsp",3)." £".number_format(intval($total_spend))."m</p>";

        $sql = "SELECT `message` FROM `sys_messages` WHERE (`key_id` = 'pipeline import')";
        $result=mysqli_query($cxn,$sql);
        $import_date = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">Data as at $import_date[0] </p>";

?>
<!--        <p style = "color:#3366ff; font-size:10pt"><br><b>Construction pipeline</b> </p> 
-->
      <?php
//        $target_date = Date("Y-m-d");
//        $total_spend =0;
//        $sql = "SELECT sum(`spend_financial2013_14`) as 'FY13_14' FROM construction WHERE 1";
//        $result=mysqli_query($cxn,$sql);
//        $spend = mysqli_fetch_row($result);
//        $total_spend = $spend[0];
//        //mysqli_close($result);
//        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2013: ".str_repeat("&nbsp",14)."£".number_format(intval($spend[0]))."m</p>";

//        $sql = "SELECT sum(`spend_financial2014_15`) as 'FY14_15' FROM construction WHERE 1";
//        $result=mysqli_query($cxn,$sql);
//        $spend = mysqli_fetch_row($result);
//        $total_spend = $total_spend+$spend[0];
//        //mysqli_close($result);
//        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2014: ".str_repeat("&nbsp",14)."£".number_format(intval($spend[0]))."m</p>";

//        $sql = "SELECT sum(`spend_financial2015_16`) as 'FY15_16' FROM construction WHERE 1";
//        $result=mysqli_query($cxn,$sql);
//        $spend = mysqli_fetch_row($result);
//        $total_spend = $total_spend+$spend[0];
//        //mysqli_close($result);
//        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2015: ".str_repeat("&nbsp",14)."£".number_format(intval($spend[0]))."m</p>";

//        $sql = "SELECT sum(`total2016_20`) as 'FY16_20' FROM construction WHERE 1";
//        $result=mysqli_query($cxn,$sql);
//        $spend = mysqli_fetch_row($result);
//        $total_spend = $total_spend+$spend[0];
//        //mysqli_close($result);
//        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 16-20: ".str_repeat("&nbsp",13)."£".number_format(intval($spend[0]))."m</p>";

//        $sql = "SELECT sum(`total2020_beyond`) as 'FY20' FROM construction WHERE 1";
//        $result=mysqli_query($cxn,$sql);
//        $spend = mysqli_fetch_row($result);
//        $total_spend = $total_spend+$spend[0];
//        //mysqli_close($result);
//        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2020+ ".str_repeat("&nbsp",13)."£".number_format(intval($spend[0]))."m</p>";

//        echo "<p style = \"color:#3366ff; font-size:10pt\">Total Pipeline: ".str_repeat("&nbsp",1)." £".number_format(intval($total_spend))."m</p>";
?>
<!--        <p style = "color:#3366ff; font-size:10pt">Data as at 7th Oct 2013 </p> 
-->


      <p>&nbsp</p>
      
      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">Updates</p>
      <p style = "color:#3366ff; font-size:10pt"><br><b>Recent updates</b> </p>
      
      <p style = "color:#3366ff; font-size:10pt"><br>Jul 29, 2014 - G-Cloud June spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Jun 13, 2014 - G-Cloud May spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>May 16, 2014 - G-Cloud April spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Jan 31, 2014 - GDS publish G-Cloud spend graphs on performance platform.
      <a href="https://www.gov.uk/performance/g-cloud" alt = "G-Cloud Sales Information on Cabinet Office web site" target="_blank">click here to see</a>
      </p>
<?php
//      <p style = "color:#3366ff; font-size:10pt"><br>Apr 16, 2014 - G-Cloud March spend added.</p>
//      <p style = "color:#3366ff; font-size:10pt"><br>Mar 21, 2014 - G-Cloud Jan / Feb spend added.</p>
//      <p style = "color:#3366ff; font-size:10pt"><br>Feb 07, 2014 - G-Cloud December spend added.</p>
//      <p style = "color:#3366ff; font-size:10pt"><br>Jan 13, 2014 - Spend pipeline beta released.</p>
//      <p style = "color:#3366ff; font-size:10pt"><br>Jan 8, 2014 - Spend pipeline data updated.</p>
//      <p style = "color:#3366ff; font-size:10pt"><br>Dec 20, 2013 - G-Cloud November spend added.</p>
 //     <p style = "color:#3366ff; font-size:10pt"><br>Nov 27, 2013 - G-Cloud October spend added.</p>
//      <p style = "color:#3366ff; font-size:10pt"><br>Oct 18, 2013 - G-Cloud September spend added.</p>
?>
      <p style = "color:#3366ff; font-size:10pt"><br>Follow us on twitter to get news directly - @GovSpendOrgUK.</p>

      <p>&nbsp</p>

      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">Data Sources</p>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
      <p style ="color:#3366ff; font-size:10pt"><a href="http://gcloud.civilservice.gov.uk/about/sales-information/" alt = "G-Cloud Sales Information" target="_blank">G-Cloud Spend data</a></p>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
      <p style ="color:#3366ff; font-size:10pt"><a href="https://online.contractsfinder.businesslink.gov.uk/data-feed.aspx" alt = "Contracts Finder data page" target="_blank">Spend Pipeline data<br>(you may need to click twice)</a></p>
      <p style ="color:#3366ff; font-size:10pt">&nbsp</p>
      <p style ="color:#3366ff; font-size:10pt"><a href="http://data.gov.uk/dataset/government-construction-pipeline" alt = "Construction pipeline on data.gov" target="_blank">Construction Pipeline data</a></p>
      <p style ="color:#3366ff; font-size:10pt">&nbsp</p>
      <p style ="color:#3366ff; font-size:10pt"><a href="http://simap.europa.eu/codes-and-nomenclatures/codes-cpv/codes-cpv_en.htm" alt = "link to cpv codes master list on simap.europa.eu" target="_blank">CPV Codes master list</a></p>
      <p style ="color:#3366ff; font-size:10pt">&nbsp</p>

    </div>
  </div>
  
  <div id="articleborder" >
    <article id="articlecontent" >

      <h2>Helping you win business from the Public Sector</h2>

      <h3>What do we do?</h3>
      <p>GovSpend.Org.UK analyses published Government data that allows you to understand:</p>
      
      <p><b>G-Cloud Spend</b> - What Government has spent, with whom and for how much - allowing you to understand the G-Cloud market for your products and services.
             For example, if you supply want to see what Cabinet Office is buying, the following link will show you </p>
             <ul>
<?php
//             For example, if you supply 'Agile' services, the following links will show you </p>
//             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Customer&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing customers">Who's buying 'Agile'</a></li>
//             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Product&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing products">What's being bought</a></li>
//             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Supplier&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing suppliers">Who's supplying 'Agile'</a></li>
?>
             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Supplier&rank=total&scope=all&search_client=cabinet+office" alt = "G-Cloud Agile Sales - showing suppliers">Cabinet Office</a></li>

             </ul> 
             <p>You can do your own searches for other products and services by selecting 'G-CLOUD SPEND' from the menu above.</p>
      <p><b>Spend Pipeline</b> - What Government is planning to spend over the next few years - data includes contact details of the lead procurer and links back to the original notices posted on Government's Contracts Finder service.</p>
<!--      <li><b>Construction Pipeline</b> - Government planned spend on construction.</li>
      <li><b>Other tools</b> are under development.</li>
-->
    
      <h3>Thank you</h3>
      <p>Thank you for having a look at GovSpend.Org.UK.  We welcome your suggestions on how we may improve the service.  Choose Contact Us from the menu above
      to get in touch.</p>   
         
    </article>
  </div>
</div>
  
<?php include ("footer.php"); ?>
 

</body>
</html>
