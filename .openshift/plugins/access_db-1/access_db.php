<?php
/*
Plugin Name: access db
Initialize mysql database connection
*/
require_once( $_SERVER['DOCUMENT_ROOT'] . '/gm-config.php' );
//include '/gm-config.php';
//Setup DB access
$ip_addr = 'localhost';
//$username = 'cquic';
$username = 'cquic_gm320gjd';
//$db_password = '&$@~!tW)mAEO';
$db_password = '9Kr2K@#Hgqc$';
$dbname = 'cquic_group_meetings';
//$username = 'DB_GM_USER';
//$db_password = 'DB_GM_PASSWORD';
//$dbname = 'DB_GM_NAME';
//Connect to DB
$lnk = mysql_connect($ip_addr, $username, $db_password)
                   or die ('Not connected: ' . mysql_error());
// make $dbname the current db
mysql_select_db($dbname,$lnk) or die ("Can't use DB " . $dbname . ": " . mysql_error());

/***************************************************************************************
Do MySQL queries in a standard way.
***************************************************************************************/
function doQuery($query) {
        $handle = mysql_query($query) or die ('<p><strong>MySQL Error:</strong> ' . mysql_error() . "</p>\n<pre>$query</pre>");
        return $handle;
}// end function doQuery
?>