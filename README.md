govspend.org.uk
===============
The site is develiped using the Linux / Apache / MySql / PHP (LAMP) stack.  Getting a version of this site up and running so you can co-develop is pretty easy.

Assuming you have LAMP installed the steps are as follows:

1. Copy the files into the directory you use for developing.  We use htdocs/govspend
2. in PHPMyAdmin, run the following scripts:
3.   - govspend.sql - this will build an empty version of the database
4.   - govspend_cpv.sql - this will populate the cpv-code table with reference data
5.   - Note 1st August - the import_gcloud.php routine is being updated to deal with a change in the format published by
6.     Cabinet Office.  To get the latest data into the g-cloud table, you will need to import the datafile
7.     G-Cloud-Spend-14-07-14-v2-For-import.csv.  To do this, use PHPMyAdmin and the import settings:
8.       - rows to skip = 1
9.       - fields separated by = ,
5. To import the pipeline data, just run the web page import_pipeline.php.  YOU MUST BE CONNECTED TO THE INTERNET.  The import
6. routine will automatically open the data source online and read the data into your database.
7. .
8. That's it.  Any help or ideas, please email support@govspend.org.uk
9. Have fun!
10. 




