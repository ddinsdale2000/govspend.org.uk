<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>GovSpend.org.uk - Address Demo</title>
    <link rel="stylesheet" href="main.css" type="text/css" />
    <link rel="stylesheet" href="button.css" type="text/css" />
</head>
<body>
<?php 
	include('header_main.php'); 
	$address = $_GET['address']; 
   	if (strlen($address)==0){$address = "1 Triton Square, Regents Place, London, NW1 3HG";}

    echo "<h1>Address demo - http://www.govspend.org.uk/map.php </h1>"; 
    echo "<p><form name=\"input\" action=\"map.php\" method=\"get\">";
    echo "Address : <input type=\"text\" name=\"address\" value=\"$address\" style=\"width: 65%\"><br>";
	echo "<input type=\"submit\" class = \"tv_button\"value=\"Draw Map\">";

	echo "<iframe width=\"100%\"  height=\"450\" frameborder=\"0\" style=\"border:0\"";
	echo "src=\"https://www.google.com/maps/embed/v1/place?key=AIzaSyCfpsUXj2rIlHFbIYa-OiXQB6PG9OOHIEY";
	echo "&q=".urlencode($address) ;
	echo "\"></iframe>";

?>
</body>
</html>