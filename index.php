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
      
      <p> <a href="news.php" class=tv_button>23 Jun 2015 <br>G-Cloud news</a></p>
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
<!--      <p> <a href="index.php" class=tv_button>Not yet working</a></p> -->
      <p style = "color:#3366ff; font-size:8pt">&nbsp</p>
    <a href="https://twitter.com/GovSpendOrgUK" class="twitter-follow-button" data-show-count="false">Follow @GovSpendOrgUK</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
      </center>
    </nav>

    <p style = "color:#3366ff; font-size:8pt">&nbsp</p>

    <div id="navfooter">
      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">Summary</p>

       <p style = "color:#3366ff; font-size:10pt"><br><b>G-Cloud Spend</b> </p>

      <?php
		$for_month = cz_get('select max( for_month ) from `g-cloud`');
//		echo $for_month;
        $sql = "SELECT `For_Month` , sum(`Total_Charge`)/10000  FROM `g-cloud` group by `For_Month` desc";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - ".date("M Y", strtotime($for_month))." : ".str_repeat("&nbsp",16)."£".number_format(intval($spend[1])/100)."m</p>";

        $sql = "SELECT sum(`Total_Charge`)/1000000  FROM `g-cloud` WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - To date: ".str_repeat("&nbsp",18)."£".number_format(intval($spend[0]))."m</p>";
		$grand_total_spend = $spend[0];
		
        $sql = "SELECT sum(`Total_Charge`)/1000000  FROM `g-cloud` WHERE SME = 'sme'";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $percent = intval(($spend[0]/$grand_total_spend)*100);
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - SME spend : ".str_repeat("&nbsp",10)."£".number_format(intval($spend[0]))."m</p>";
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - SME % ".str_repeat("&nbsp",23).$percent."%</p>";


        $sql = "SELECT `message` FROM `sys_messages` WHERE (`key_id` = 'g-cloud import')";
        $result=mysqli_query($cxn,$sql);
        $import_date = mysqli_fetch_row($result);
        echo "<p style = \"color:#3366ff; font-size:10pt\">Data to end ".date("M Y", strtotime($for_month))." </p>";

      ?>

      <p style = "color:#3366ff; font-size:10pt"><br><b>Spend Pipeline</b> </p>

      <?php

        $target_date = Date("Y-m-d");
		$spend[0] = 0;

//        $sql = "SELECT sum(`SpendFinancial2014_15`)/1000000 as 'FY14_15' FROM pipeline WHERE 1";
//        $result=mysqli_query($cxn,$sql);
//        $spend = mysqli_fetch_row($result);
//        $total_spend += $spend[0];
//        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2014: ".str_repeat("&nbsp",16)." £".number_format(intval($spend[0]))."m</p>";

        $sql = "SELECT sum(`SpendFinancial2015_16`)/1000000 as 'FY15_16' FROM pipeline WHERE 1";
        $result=mysqli_query($cxn,$sql);
        $spend = mysqli_fetch_row($result);
        $total_spend += $spend[0];
        echo "<p style = \"color:#3366ff; font-size:10pt\">  - 2015: ".str_repeat("&nbsp",16)." £".number_format(intval($spend[0]))."m</p>";

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
      <p style = "color:#3366ff; font-size:10pt"><br>Jun 23, 2015 - G-Cloud May spend added.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>May 22, 2015 - G-Cloud Apr spend added.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Apr 30, 2015 - G-Cloud Mar spend added.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Mar 23, 2015 - G-Cloud Feb spend added and pipeline data updated.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Feb 28, 2015 - G-Cloud Jan spend added.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Feb 7, 2015 - G-Cloud December spend added.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Feb 7, 2015 - Spend Pipeline updated.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Dec 16, 2014 - G-Cloud November spend added.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Nov 21, 2014 - G-Cloud October spend added.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Oct 23, 2014 - G-Cloud September spend added.</p>      

      <p style = "color:#3366ff; font-size:10pt"><br>Aug 3, 2014 - Spend Pipeline data updated including 2019 forecast.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Sep 25, 2014 - G-Cloud August spend added.</p>      
      <p style = "color:#3366ff; font-size:10pt"><br>Aug 26, 2014 - G-Cloud July spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Jul 29, 2014 - G-Cloud June spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Jun 13, 2014 - G-Cloud May spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>May 16, 2014 - G-Cloud April spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Apr 16, 2014 - G-Cloud March spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Mar 21, 2014 - G-Cloud Jan / Feb spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Feb 07, 2014 - G-Cloud December spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Jan 13, 2014 - Spend pipeline beta released.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Jan 8, 2014 - Spend pipeline data updated.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Dec 20, 2013 - G-Cloud November spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Nov 27, 2013 - G-Cloud October spend added.</p>
      <p style = "color:#3366ff; font-size:10pt"><br>Oct 18, 2013 - G-Cloud September spend added.</p>

      	<p style = "color:#3366ff; font-size:10pt"><br>Follow us on twitter to get news directly:</p> <p>&nbsp</p>

    <a href="https://twitter.com/GovSpendOrgUK" class="twitter-follow-button" data-show-count="false">Follow @GovSpendOrgUK</a>
      
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

      <h2>Knowledge and insight</h2>
      <p>GovSpend.Org.UK helps organisations to win business with the Public Sector by providing data on historic and future spend.</p>
<?php
$suppliers = cz_get("SELECT count(distinct `Supplier`) FROM `g-cloud` WHERE 1 "); 
$sme_suppliers = cz_get("SELECT count(distinct `Supplier`) FROM `g-cloud` WHERE `SME` = 'sme'"); 
$total_spend = cz_get("SELECT sum(`Total_Charge`) FROM `g-cloud` WHERE 1");
$sme_spend = cz_get("SELECT sum(`Total_Charge`) FROM `g-cloud` WHERE `SME` = 'sme'");
$sme_percent = intval((100 * ($sme_spend/$total_spend)));      
echo "      <p> To date, $sme_suppliers of the $suppliers companies to win business via G-Cloud are Small and Medium sized Enterprises (SMEs).  Together, those SMEs have supplied over £".intval($sme_spend/1000000)."m (".$sme_percent."%) of services via G-Cloud.</p>"
?>
      <p align = "center">' if we can see companies like us winning business with Government, we will be motivated to get involved. '  </p>
      <p>  We hope that the knowledge and insight provided by this service will encourage more organisations to become suppliers to the Public Sector.</p>
	  <div align = "center">
		<iframe width="560" height="315" src="//www.youtube.com/embed/IRiqORaqt-Q" frameborder="0" allowfullscreen></iframe>
	  <p style ="color:#3366ff; font-size:10pt" ><i>Dev note - The video above doesn't yet include the latest features that we released last month.  We will update the video shortly.  Let us know what you think - <a href= "mailto:support@govspend.org.uk" >support@govspend.org.uk</a></i></p>
	  </div>
	  <p>The two data sets that GovSpend analyses are published monthly by UK Government; G-Cloud spend (the past); and Government's send pipeline (future planned tenders).</p><p>In more detail:</p>      
      <p><b>G-Cloud spend data</b> - What Government has spent, with whom, on what and how much - allowing you to understand the G-Cloud market for your products and services.
             For example, if you want to see what Cabinet Office is buying, the following link will show you </p>
             <ul>
<?php
//             For example, if you supply 'Agile' services, the following links will show you </p>
//             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Customer&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing customers">Who's buying 'Agile'</a></li>
//             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Product&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing products">What's being bought</a></li>
//             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Supplier&rank=total&scope=all&term=Agile" alt = "G-Cloud Agile Sales - showing suppliers">Who's supplying 'Agile'</a></li>
?>
             <li><a href="http://www.govspend.org.uk/g-cloud.php?type=Product&rank=total&scope=all&search_client=cabinet+office" alt = "G-Cloud Agile Sales - showing suppliers">Cabinet Office - show total spend and products bought.</a></li>

             </ul> 
             <p>You can do your own searches for other products and services by selecting 'G-CLOUD SPEND' from the menu above.</p>
      <p><b>Spend pipeline data</b> - What Government is planning to spend over the next few years - data includes contact details of the lead procurer and links back to the original notices posted on Government's Contracts Finder service.</p>
<!--      <li><b>Construction Pipeline</b> - Government planned spend on construction.</li>
      <li><b>Other tools</b> are under development.</li>
-->
  		<p>You can find links to the original data in the Data Sources section (bottom left of this page). </p>  
      <h3>Who is using this service?</h3>
      
 	  	<div align = "center">
     	<img src="govspend_users.png" alt="map of users across the world" width="75%" height="75%">
		</div>
		<p>We get about 800 people using the service each month with about 1,200 visits. People are mostly UK based and use the service for an average of 5 minutes each time they visit.</p>

 	  	<div align = "center">
     	<img src="govspend_jan_15_u.png" alt="map of users across the world" width="75%" height="75%">
		</div>
		<p>  It’s great to see that the people who use the service have an enthusiasm for life.  Are you a lover, a buff or a junkie?  Don't you just love Google Analytics!</p>
      
      <h3>Thank you</h3>
      <p>Thank you for having a look at GovSpend.Org.UK.  We welcome your suggestions on how we may improve the service.  Choose Contact Us from the menu above
      to get in touch.</p>   
	  <p>You are welcome to use and copy any information on this site (including the presentations) as long as you attribute the source as GovSpend.Org.UK (and the source of the data as Cabinet Office).</p>

      <h3>Other services that analyse G-Cloud spend</h3>
      <p>You may also be interested in the following services that explore and analyse Government spend data</p>
		<ul>
			<li>	
   		  		<a href="https://www.gov.uk/performance/g-cloud" alt = "G-Cloud Sales Information on Cabinet Office web site" target="_blank" style = "display:inline">G-Cloud Spend Graphs</a> 
		      	published by Government Digital Service
      		</li>
 			<li><a href="https://public.tableausoftware.com/profile/danosirra#!/vizhome/G-Cloud/D-Customers" alt = "G-Cloud Sales Information puyblished by Methods Consulting Ltd" target="_blank" style = "display:inline">G-Cloud Spend Tables</a> 
      			published by Methods Consulting Ltd</p>
      		</li>
      	</ul> 
    <p></p>     
    </article>
  </div>
</div>
  
<?php include ("footer.php"); ?>
<?php

function cz_get($sql) // executes a SQL statement and returns the results - returns 'none' if no result returned
{	// used to count things - pass in the sql e.g. 'select count(*) from members'
	include("init.php");
	$cz_cxn1 = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend") or die("cannot connect"); 
	if ($result=mysqli_query($cz_cxn1,$sql))
	{ 	if ($row= mysqli_fetch_row($result))
      		{return $row[0];}
    	else 
    		{return "none";}
    }
    else
		{return "none";}
}

?> 

</body>
</html>
