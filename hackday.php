
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - Hack Day</title>
  <link rel="stylesheet" href="main.css" type="text/css" />
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
?> 

<h2>Hack Day - 30th October</h2>
<p style = "color:#3366ff; font-size:10pt">Date: 30th October 2013</p>
<p style = "color:#3366ff; font-size:10pt">Time: 9:00am</p>
<p style = "color:#3366ff; font-size:10pt">Location: 1 Triton Square, Regents Place, London, NW1 3HG</p>
<center>
<img src="hackday.png" alt="Hackday image" width="685" height="303" >
</center>
<h3>Objectives</h3>
<p style = "color:#3366ff; font-size:10pt">To create one or more services that people can use to
   understand how the data in two procurement data feeds could lead to more business for their organisation.  The two data feeds are linked below.  It is <b>strongly recommended</b> that you use the .sql versions of the import files.  
   These files will both create the database tables as well as importing the data.  That means that everyone will have the same table and field names and code should be interoperable amongst the development teams.</p>
<h3>What you will need</h3>
<p style = "color:#3366ff; font-size:10pt">The tools of your trade.</p>
<p style = "color:#3366ff; font-size:10pt">For the day, we will provide wifi but any dev tools or platforms you will need to bring.</p>
<p style = "color:#3366ff; font-size:10pt">For hosting your app / service / thing, we will provide a Linux / Apache / MySQL / PHP platform.  
   If you need other tech, feel free to provide it yourself or please get in touch:</p><p> <a href= "mailto:support@tgovspend.org.uk" style = "color:#3366ff; font-size:10pt" >support@govspend.org.uk</a></p>
<p style = "color:#3366ff; font-size:10pt">If you want to create your own app and host it on your own platform, delighted for you to do that.</p>
<p style ="color:#3366ff; font-size:10pt"><b>FEED 1</b></p>
<p style ="color:#3366ff; font-size:10pt">See list_of_current_pipelines.xls at:</p>
<p style ="color:#3366ff; font-size:10pt"> <a href="https://online.contractsfinder.businesslink.gov.uk/data-feed.aspx" alt = "Contracts Finder data page">Spend Pipeline data (you may need to click twice)</a></p>
<p style = "color:#3366ff; font-size:10pt">For ease, a cleaned version of this file as a MySQL script is:</p>
<p style ="color:#3366ff; font-size:10pt"><a href="httP://www.govspend.org.uk/spend_pipeline_load.sql" alt = "Spend Pipeline MySQL Load File">spend_pipeline_load.sql</a></p>
<p style = "color:#3366ff; font-size:10pt">If you use the original file, watch out for commas and carriage returns in fields - makes processing the CSV tricky.</p>
<p style ="color:#3366ff; font-size:10pt"><b>FEED 2</b></p>
<p style ="color:#3366ff; font-size:10pt"><a href="http://data.gov.uk/dataset/government-construction-pipeline" alt = "Construction pipeline on data.gov">Construction Pipeline data</a></p>
<p style ="color:#3366ff; font-size:10pt">The clean SQL script will be provided in a few days.</p>
<p style ="color:#3366ff; font-size:10pt"><b>BONUS FEED</b></p>
<p style ="color:#3366ff; font-size:10pt">Not part of the hack day, but of interest is the G-Cloud spend feed:</p>
<p style ="color:#3366ff; font-size:10pt"><a href="http://gcloud.civilservice.gov.uk/about/sales-information/" alt = "G-Cloud Sales Information">G-Cloud Spend data</a></p>
<p style = "color:#3366ff; font-size:10pt">For ease, a cleaned version of this file as a MySQL script is:</p>
<p style ="color:#3366ff; font-size:10pt"><a href="httP://www.govspend.org.uk/g_cloud_load.sql" alt = "Spend Pipeline MySQL Load File">g_cloud_load.sql</a></p>
<p style = "color:#3366ff; font-size:10pt">If you use the original file, watch out for the format of the date field and currency and variable formatting in the spend field - makes processing the CSV tricky.</p>
<p style ="color:#3366ff; font-size:10pt"><b>IMPORT INSTRUCTIONS</b></p>
<p style ="color:#3366ff; font-size:10pt">If you are using MySQL, importing is an easy three step process:</p>
<p style ="color:#3366ff; font-size:10pt">  - 1. Log into phpMyAdmin</p>
<p style ="color:#3366ff; font-size:10pt">  - 2. Create a database - recommend using <b>govspend</b> as the name so that it will work with the code we are developing.</p>
<p style ="color:#3366ff; font-size:10pt">  - 3. From the phpMyAdmin menu, choose import with the following settings:</p>
<center>
<img src="php_import.png" alt="phpMyAdmin settings - use Format of imported File = SQL and it should work" width="512" height="320" >
</center>

<h3>Thank you</h3>
<p style = "color:#3366ff; font-size:10pt">Thank you for participating in the hack day</p>


<?php include ("footer.php"); ?>
</body>
</html>