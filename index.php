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
        Quick buttons
      </p>
      <center>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
      <p> <a href="news.php" class=tv_button>G-Cloud News</a></p>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
<!--      <p> <a href="index.php" class=tv_button>Not yet working</a></p> -->
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
      </center>
    </nav>
    <p style = "color:#3366ff; font-size:8pt">&nbsp</p>

    <div id="navfooter">
      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">Data Sources</p>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
      <p style ="color:#3366ff; font-size:10pt"><a href="https://online.contractsfinder.businesslink.gov.uk/data-feed.aspx" alt = "Contracts Finder data page">Spend Pipeline data<br>(you may need to click twice)</a></p>
      <p style ="color:#3366ff; font-size:10pt">&nbsp</p>
      <p style ="color:#3366ff; font-size:10pt"><a href="http://gcloud.civilservice.gov.uk/about/sales-information/" alt = "G-Cloud Sales Information">G-Cloud Spend data</a></p>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">Analytics</p>
      <p style = "color:#3366ff; font-size:10pt"><br><b>Pipeline (as at 7 Oct 13)</b> </p>

      <?php
        $target_date = Date("Y-m-d");
        $sql = "SELECT sum(`SpendFinancial2013_14`)/1000000 as 'FY13_14' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend = $spend[0];
        //mysqli_close($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2013: ".str_repeat("&nbsp",17)."£".number_format(intval($spend[0]))."m</p>";

        $sql = "SELECT sum(`SpendFinancial2014_15`)/1000000 as 'FY14_15' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend += $spend[0];
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2014: ".str_repeat("&nbsp",14)." £".number_format(intval($spend[0]))."m</p>";

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
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2018: ".str_repeat("&nbsp",17)."£".number_format(intval($spend[0]))."m</p>";

        echo "<p style = \"color:#3366ff; font-size:10pt\">Total Pipeline: ".str_repeat("&nbsp",3)." £".number_format(intval($total_spend))."m</p>";

// Not sure whether this figure is useful
//        $sql = "SELECT sum(`TotalCapitalCost`)/1000000 as 'TotalCapitalCost' FROM pipeline WHERE 1";
//        $result=mysqli_query($cxn,$sql);
//        $spend = mysqli_fetch_row($result);
//        echo "<p style = \"color:#3366ff; font-size:10pt\">Total Capex:  £".number_format(intval($spend[0]))."m</p>";

?>
       <p style = "color:#3366ff; font-size:10pt"><br><b>G-Cloud Spend</b> </p>
      <?php
        $sql = "SELECT `For_Month` , sum(`Total_Charge`)/10000  FROM `g-cloud` group by `For_Month` desc";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - This month: ".str_repeat("&nbsp",13)."£".number_format(intval($spend[1])/100)."m</p>";

        $sql = "SELECT sum(`Total_Charge`)/1000000  FROM `g-cloud` WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - To date: ".str_repeat("&nbsp",16)."£".number_format(intval($spend[0]))."m</p>";
      ?>

      <p>&nbsp</p>
      
      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">News</p>
      <p style = "color:#3366ff; font-size:10pt"><br><b>Recent updates</b> </p>
      <p style = "color:#3366ff; font-size:10pt"><br>Oct 18, 2013 - G-Cloud September spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Oct 11, 2013 - Follow us on twitter to get news directly - @GovSpendOrgUK.</p>
    </div>
  </div>
  
  <div id="articleborder" >
    <article id="articlecontent" >

      <h2>Helping you win business from the Public Sector</h2>
      <h3>What do we do?</h3>
      <p>GovSpend.Org.UK analyses published Government data that allows you to understand:</p>
      <p><ul>
      <li><b>Spend Pipeline</b> - What Government is planning to spend over the next six years - data includes contact details of the lead procurer and links back to the original notices posted on Government's Contracts Finder service.</li>
      <li><b>G-Cloud Spend</b> - What Government has spent, with whom and for how much - allowing you to understand the G-Cloud market for your products and services.
             For example, if you supply 'Agile' services, the following links will show you <ul>
             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Customer&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing customers">Who's buying 'Agile'</a></li>
             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Product&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing products">What's being bought</a></li>
             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Supplier&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing suppliers">Who's supplying 'Agile'</a></li></ul> 
             You can do your own searches for other products and services by selecting 'G-CLOUD SPEND' from the menu above.</li>
      <li><b>Other tools</b> are under development.</li>
      </p></ul>
      <p>This site is currently under development.  The data is correct and the site works but may be taken down at any time without notice.  The site will be launched at the end of November 2013.</p>   
      <h3>Thank you</h3>
      <p>Thank you for having a look at GovSpend.Org.UK.  We welcome your suggestions on how we may improve the service.  Choose Contact Us from the menu above
      to get in touch.</p>   
         
    </article>
  </div>
<?php include ("footer.php"); ?>
 
</div>

</body>
</html>
