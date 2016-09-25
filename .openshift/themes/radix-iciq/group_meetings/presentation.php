<?php
//PRESENTATION details for CQuIC Group Meeting - dynamically generated from gmid (group_meetings ID)
$basehref = (strstr($_SERVER['HTTP_HOST'],"physics.unm.edu")) ? "http://physics.unm.edu/CQuIC/" : "http://www.cquic.org/" ;
$path2root = "/nfs/user/p/physics/Sites/physics.unm.edu/CQuIC/";


include $path2root."common/app.php";

if(!isset($_GET['gmid'])) die("Missing parameter: gmid");
else $gmid = $_GET['gmid'];

dbOpen('physics.unm.edu');
$result = doQuery("SELECT * FROM group_meetings WHERE gm_id = '" . mysql_real_escape_string($gmid) . "' ");
if (mysql_num_rows($result) == 0) die ("Invalid parameter: gmid");
else $res = mysql_fetch_array($result,MYSQL_ASSOC);
dbClose();

foreach ($res as $key=>$value) {
   $vn = str_replace("_","",$key);
   $$vn = $value;
   }//end foreach



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Group Meeting - <?php echo date("d M Y", $gmts); ?></title>
<base href="<?php echo $basehref; ?>" />
<link rel="stylesheet" href="css/arxiv.css" type="text/css" media="screen" />
</head>
<body>

<div style="text-align: center; margin: 10px 0 0 0;"><h1>Group Meeting Presentation</h1>
<h2><?php echo date("d M Y", $gmts); ?></h2></div>

<div style="text-align: center; margin: 20px 0 0 0;"><strong>Presentation:</strong>
<?php echo htmlspecialchars($gmpresentation); ?><br /><br /><strong>Title:</strong>
<?php echo htmlspecialchars($gmprestitle); ?><br /><br /><strong>Abstract:</strong>
</div>
<div style="width: 500px; text-align: left; margin: 20px auto 0 auto;"><?php echo nl2br($gmpresabs); ?>
<br /><br /></div>


<div style="text-align: center;">
<input type="button" value="print" onclick="window.print();">&nbsp;
<input type="button" value="close" onclick="window.close();">
</div>

</body>
</html>