<?php

ini_set("display_errors",1); //TEMP

//TEST MODE
$testmode = 0;
if($testmode>0) {
   print_r($_POST); exit;
   }

//config
include "/nfs/user/p/physics/Sites/physics.unm.edu/CQuIC/groupmeet/submission_config.php";

//db functions
include "/nfs/user/p/physics/Sites/physics.unm.edu/CQuIC/common/app.php";

// INIT
$cmd = (isset($_POST['cmd'])) ? $_POST['cmd'] : "";
$gmid = (isset($_REQUEST['gmid']) && !empty($_REQUEST['gmid'])) ? $_REQUEST['gmid'] : '';
$gmprestitle = (isset($_POST['gmprestitle'])) ? $_POST['gmprestitle'] : '';
$gmpresabs = (isset($_POST['gmpresabs'])) ? $_POST['gmpresabs'] : '';
$output = "";

//SCRIPT INCLUDE PATH to siteroot ($path2root);
$path2root = "/nfs/user/p/physics/Sites/physics.unm.edu/CQuIC/";

dbOpen('physics.unm.edu'); //OPEN DB

//SAVE ACTION
if($cmd == "save") {
   $query = "UPDATE group_meetings SET gm_pres_title = '".addslashes($gmprestitle)."', 
      gm_pres_abs = '".addslashes($gmpresabs)."' 
      WHERE gm_id = '$gmid' ";
   doQuery($query);
   }//end if cmd = save

//QUERY
$query = "SELECT gm_ts, gm_presentation, gm_pres_title, gm_pres_abs FROM group_meetings WHERE gm_id = '$gmid' ";
$result = doQuery($query);
if(mysql_num_rows($result)>0) {
   $res = mysql_fetch_array($result,MYSQL_ASSOC);
   foreach ($res as $key=>$value) {
      $vn = str_replace("_","",$key);
      $$vn = $value;
      }//end foreach
   }//end if has rows

//////////////////////////////////////////////////////  F O R M  /////////////////////////////////////////////////////////

$output .= '<div style="width: 100%; text-align: center;"><h2>Presentation Submission Form</h2>
</div><div>
<span class="fixed">Date:</span><span>'.date("d M Y", $gmts).'</span><br />
<span class="fixed">Presentation:</span><span>'.$gmpresentation.'</span><br />
<form name="presdata" method="post" action="'.$_SERVER['PHP_SELF'].'">
<input type="hidden" name="gmid" value="'.$gmid.'">
<input type="hidden" name="cmd" value="save">
<span class="fixed">Title:</span><input type="text" name="gmprestitle" value="'.htmlspecialchars($gmprestitle).'" style="width: 370px;"><br />
<span class="fixed">Abstract:</span><br />
<textarea name="gmpresabs" class="abs">'.htmlspecialchars($gmpresabs).'</textarea><br />
<input type="submit" value="save changes"><input type="button" value="never mind" onclick="window.close();">
</form>
';

///////////////////////////////////////// H T M L /////////////////////////////////////////////
?><html>
<head>
<style type="text/css">
span.fixed { display: inline-block; width: 100px; height: 30px; }
textarea.abs { width: 470px; height: 260px; }
</style>
</head>
<body style="font-family: arial;">

<?php echo $output; ?>

</body>
</html>
