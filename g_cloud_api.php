<?php session_start(); 
//if (isset($_SESSION["username"])){  header("location:main.php");}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="keywords" content="Government spend public sector contracts finder g-cloud gcloud data contracts-finder api">
    <meta name="author" content="Creative Space Learning">
    <title>Welcome to GovSpend.Org.UK - G-Cloud API</title>
    <link rel="stylesheet" href="main.css" type="text/css" />
    <link rel="stylesheet" href="button.css" type="text/css" />

</head>

<body>
<?php 
  include_once("analyticstracking.php"); 
  include('header_main.php'); 
  include('menu_g_cloud1.php');
  include("init.php");  
  $cxn = mysqli_connect("$dbh", "$dbu", "$dbp", "govspend")or die("cannot connect"); 
?>
<div id ="page" style = "float:left; width:100%; "  >

<p style = "font-size : 6pt"> &nbsp </p>
  </header>
  
  <div id="navborder" >

    <div id="navfooter">
      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">Parameters</p>

       <p style = "color:#3366ff; font-size:10pt"><br><b>format</b> (html / csv / xml) </p>
       <p style = "color:#3366ff; font-size:10pt"> - default is html</p>
      <p>&nbsp</p>
       <p style = "color:#3366ff; font-size:10pt"><b>framework</b> (text)</p>
      <p>&nbsp</p>
       <p style = "color:#3366ff; font-size:10pt"><b>lot</b> (text)</p>
      <p>&nbsp</p>
       <p style = "color:#3366ff; font-size:10pt"><b>customer</b> (text)</p>
      <p>&nbsp</p>
       <p style = "color:#3366ff; font-size:10pt"><b>supplier</b> (text)</p>
      <p>&nbsp</p>
       <p style = "color:#3366ff; font-size:10pt"><b>date_from</b> (date)</p>
       <p style = "color:#3366ff; font-size:10pt"> - format YYYY-MM-DD</p>
       <p style = "color:#3366ff; font-size:10pt"><b>date_to</b> (date)</p>
       <p style = "color:#3366ff; font-size:10pt"> - format YYYY-MM-DD</p>
      <p>&nbsp</p>
       <p style = "color:#3366ff; font-size:10pt"><b>product</b> (text)</p>
      <p>&nbsp</p>
       <p style = "color:#3366ff; font-size:10pt"><b>spend_from</b> (number)</p>
       <p style = "color:#3366ff; font-size:10pt"><b>spend_to</b> (number)</p>

      <p>&nbsp</p>
      
      <p style="size:10pt; background-color:#3366FF; color:#ffffff; border-radius:1em; text-align:center; padding:2px;">Notes</p>
      <p style = "color:#3366ff; font-size:10pt"><br><b>Text searches</b><br> - use contains operator</p>
      <p style = "color:#3366ff; font-size:10pt"><br><b>Value and date searches</b><br> - use >= and <= operators</p>
      

    </div>
  </div>
  
  <div id="articleborder" >
    <article id="articlecontent" >

      <h2>G-Cloud Spend API - Public Beta</h2>

      <h3>What can you do with this API?</h3>
      <p>The API will let you query government's published G-Cloud spend data from within a programming language or application (e.g. Microsoft Excel)</p>
      
      <p>The API is simple to call using a URL with parameters.  For example: </p>
      <p>All purchases by Cabinet Office since 1st Jan 2013</p>
             <ul>
             <li><a href="http://www.govspend.org.uk/api.php?format=html&customer=Cabinet+Office&date_from=2013-01-01" alt = "Cabinet Office spend" target="_blank">http://www.govspend.org.uk/api.php?format=html&customer=Cabinet+Office&date_from=2013-01-01</a></li>
             </ul> 
      <p>Please note that when you click the link, the results may look unformatted.  That is because the API is returning the data.  It is up to the calling program to format it.</p>

	  <p>The following link takes you to a sample web page to test the API.  Please note there is no branding on the web page.</p>
             <ul>
             <li><a href="http://www.govspend.org.uk/api_test.php" alt = "G-Cloud spend api test page" target="_blank">http://www.govspend.org.uk/api_test.php</a></li>
             </ul> 

	  <p>The following links will take you to example programs that call the API, one in PHP code (the code that generates the page in the link above) and one as an excel spreadsheet</p>
             <ul>
             <li><a href="http://www.govspend.org.uk/api_test.txt" alt = "G-Cloud spend api source code" target="_blank">http://www.govspend.org.uk/api_test.txt</a></li>
             <li><a href="http://www.govspend.org.uk/api_test.xlsm" alt = "G-Cloud spend api excel spreadsheet" target="_blank">http://www.govspend.org.uk/api_test.xlsm</a></li>
             </ul> 

      <h3>Thank you</h3>
      <p>Thank you for having a look at the GovSpend.Org.UK API.  We welcome your suggestions on how we may improve the service.  Choose Contact Us from the home page 
      to get in touch.</p>   
         
    </article>
  </div>
</div>
  
<?php include ("footer.php"); ?>
 

</body>
</html>
