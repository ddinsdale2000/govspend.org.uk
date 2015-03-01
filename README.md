govspend.org.uk
===============
The site is develiped using the Linux / Apache / MySql / PHP (LAMP) stack.  Getting a version of this site up and running so you can co-develop is pretty easy.

Assuming you have LAMP installed the steps are as follows:

1. Copy the files into the directory you use for developing.  We use htdocs/govspend
2. in PHPMyAdmin, run the following scripts:
3.   - govspend.sql - this will build an empty version of the database
4.   - govspend_cpv.sql - this will populate the cpv-code table with reference data
5.   - The import_g_cloud.php routine is being updated to deal with a change in the format published by
6.     Cabinet Office.  To get the latest data into the g-cloud table, you will need to import the latest datafile
7.     from https://digitalmarketplace.blog.gov.uk/sales-accreditation-information/ To do this, right click and copy the URL on that page and then paste
8.     into the input box on the import_g_cloud.php page.  You should also define a variable in init.php as the security code e.g. $security_code = "mysecretcode";
9. To import the pipeline data, just run the web page import_pipeline.php.  YOU MUST BE CONNECTED TO THE INTERNET.  The import
10. routine will automatically open the data source online and read the data into your database.
11. .
12. That's it.  Any help or ideas, please email support@govspend.org.uk
13. Have fun!
14. 




