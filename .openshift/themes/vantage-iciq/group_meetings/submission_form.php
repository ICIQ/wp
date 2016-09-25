<?php

ini_set("display_errors",1); //TEMP

//TEST MODE
$testmode = 0;
if($testmode>0) {
   print_r($_POST); exit;
   }

// captcha
 $cryptinstall="../captcha/cryptographp.fct.php";
 include $cryptinstall;

//config
include "/nfs/user/p/physics/Sites/physics.unm.edu/CQuIC/groupmeet/submission_config.php";

//db functions
include "/nfs/user/p/physics/Sites/physics.unm.edu/CQuIC/common/app.php";

// INIT
$docid = (isset($_POST['docid'])) ? $_POST['docid'] : "" ; //defaults to blank if no incoming post
$confirmed = (isset($_POST['confirmed'])) ? $_POST['confirmed'] : "no"; //defaults to not confirmed
$submitter = (isset($_POST['submitter'])) ? $_POST['submitter'] : "" ; //defaults to blank if no incoming post
$report = (isset($_POST['report'])) ? $_POST['report'] : "Brief" ; //defaults to "Brief" if no incoming post
$addcomment = (isset($_POST['addcomment'])) ? $_POST['addcomment'] : "" ; //defaults to blank if no incoming post
$relevantlinks = (isset($_POST['relevantlinks'])) ? $_POST['relevantlinks'] : "" ; //defaults to blank if no incoming post
$gmid = (isset($_REQUEST['gmid']) && !empty($_REQUEST['gmid'])) ? $_REQUEST['gmid'] : '';  //may be incoming from login admin area
$dupid = (isset($_REQUEST['dupid']) && !empty($_REQUEST['dupid'])) ? $_REQUEST['dupid'] : '';  //may be incoming from login admin area
$armcaptcha = (isset($_POST['armcaptcha'])) ? $_POST['armcaptcha'] : "0" ; //defaults to 0 if init read meaning no captcha code to check

$caperror = "";
if ($armcaptcha > 0) {
    $captcha_code = $_POST['captcha_code'];
    $captcha_state = chk_crypt($captcha_code);
    if (! $captcha_state) $caperror = "<font color='#A00000'><b>Wrong Verification Code Entered</b></font><br>";
} else {
    $captcha_state = 1;
} 

$output = "";
$title = "";
$authors = "";
$meta = "";

//SCRIPT INCLUDE PATH to siteroot ($path2root);
$path2root = "/nfs/user/p/physics/Sites/physics.unm.edu/CQuIC/";

//FUNCTIONS
function getRemoteFile($url) { //function for reading remote page content

   // get the host name and url path
   $parsedUrl = parse_url($url);
   $host = $parsedUrl['host'];
   if (isset($parsedUrl['path'])) {
      $path = $parsedUrl['path'];
   } else {
      // the url is pointing to the host like http://www.mysite.com
      $path = '/';
   }

   if (isset($parsedUrl['query'])) {
      $path .= '?' . $parsedUrl['query'];
   }

   if (isset($parsedUrl['port'])) {
      $port = $parsedUrl['port'];
   } else {
      // most sites use port 80
      $port = '80';
   }

   $timeout = 10;
   $response = '';

   // connect to the remote server
   $fp = @fsockopen($host, '80', $errno, $errstr, $timeout );

   if( !$fp ) {
      echo "Cannot retrieve $url";
   } else {
      // send the necessary headers to get the file
      fputs($fp, "GET $path HTTP/1.0\r\n" .
                 "Host: $host\r\n" .
                 "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.19) Gecko/2010031422 Firefox/3.0.19\r\n" .
                 "Accept: */*\r\n" .
                 "Accept-Language: en-us,en;q=0.5\r\n" .
                 "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
                 "Keep-Alive: 300\r\n" .
                 "Connection: keep-alive\r\n" .
                 "Referer: http://$host\r\n\r\n");

      // retrieve the response from the remote server
      while ( $line = fread( $fp, 4096 ) ) {
         $response .= $line;
      }

      fclose( $fp );

      // strip the headers
      $pos      = strpos($response, "\r\n\r\n");
      $response = substr($response, $pos + 4);
   }

   // return the file content
   return $response;
}


function makeMeetTS($id="") {
   global $meetDOW;
   dbOpen('panda.unm.edu');
   if(empty($id)) { //NO GMID SPECIFIED: query for "next" meeting in database -OR- use "next [$meetDOW]"
      $result = doQuery ("SELECT gm_ts FROM group_meetings 
         WHERE gm_ts >=  ".strtotime("today")."
            ORDER BY gm_url "); //looks for TODAY (midnight) or AFTER
      $nextInDb = (mysql_num_rows($result)>0) ? mysql_result($result,"0","gm_ts") : 0;
      dbClose();
      $nextByDow = strtotime($meetDOW);
      $ret = ($nextInDb > 0 && $nextInDb < $nextByDow) ? $nextInDb : $nextByDow ;
      return $ret ;
      }//end if empty id

      else { //GMID SPECIFIED: query for date of group meeting with incoming gm_id
      $result = doQuery("SELECT gm_url FROM group_meetings WHERE gm_id = '$id' ");
      $url = mysql_result($result,"0","gm_url");
      dbClose();
      $ret = strtotime ( substr($url,9,2).'/'.substr($url,11,2).'/20'.substr($url,7,2) );
      return $ret ;
      }//end else incoming id
   }//end function makeMeetTS


////////////////////////////////////////////////////// S T E P   O N E :   F O R M /////////////////////////////////////////////////////////

if ( empty($_POST['docid']) || ! $captcha_state) { // NO INCOMING $docid - OUTPUT FORM
#if ( empty($_POST['docid']) ) { // NO INCOMING $docid - OUTPUT FORM
$capstr = dsp_crypt(0,1);

$output .= '<div style="width: 100%; text-align: center;"><h2>arXiv &amp; 
Journal Review Submission Form</h2>
<h2>'.date("d M Y", makeMeetTS($gmid)).'</h2></div>';
$output .= $caperror;
$output .= '<span style="font-style: italic">Submissions must either be in arXiv (xxxx.xxxx) or DOI format.</span>
<br /><br />
<form name="datafetch" method="post" action="'.$_SERVER['PHP_SELF'].'">
<span class="fixed">name:</span><input type="text" name="submitter" value="'.$submitter.'" style="width: 300px;"><br />
<span class="fixed">arXiv ID / doi:</span><input type="text" name="docid" value="'.$docid.'" style="width: 300px;"><br />
<span class="fixed">report:</span><select name="report"><option 
   value="Brief"'.((strstr($report,"Brief")) ? ' selected="selected"' : '').'>brief<option 
   value="Detailed"'.((strstr($report,"Detailed")) ? ' selected="selected"' : '').'>detailed</select><br />
<span class="fixed">comment:</span><input type="text" name="addcomment" value="'.$addcomment.'" style="width: 480px;"><br />
<span>relevant links: (<em>format:</em> URL[required], link text[optional] with line break between links)<br /><textarea name="relevantlinks"
   style="width: 600px; height: 40px;">'.$relevantlinks.'</textarea><br />';

$output .= $capstr;

$output .= '<b>Verification - </b> <i>Please enter the verification code as shown in the box above:</i><br><input type="text" id="cap2" name="captcha_code" size="20"  maxlength="5" />';
$output .= '<input type="hidden" name="armcaptcha" value=1">';

$output .= '<input type="hidden" name="gmid" value="'.$gmid.'">
<input type="submit" value="Submit" style="margin-top: 10px;">
</form>
';
}

////////////////////////////////////////////////////// S T E P   T W O :   C O N F I R M //////////////////////////////////////////////////////////

else  { // PROCESS INCOMING $docid - OUTPUT CONFIRMATION PAGE

$output .= '<h3>'.$report.' Report Confirmation</h3>
';

//arXiv or doi?
$idtype = (strpos($docid,".") == 4 || strstr($docid,"quant-ph") ) ? "arXiv" : "doi" ;

//FETCH URL (for fetching abstract page content)
$fetchurl = ($idtype=="arXiv") ? $arxiv_fetchprefix.$docid : $doi_fetchprefix.$docid;
$pc = getRemoteFile($fetchurl) ;//get page content (data page)

// CHECK VALIDITY (set $idisvalid for SWITCH below)
if($idtype=="arXiv") { // arXiv: check page title tag
   $ptstart = strpos($pc,'<title>')+7;
   $ptend = strpos($pc,'<',$ptstart);
   $pagetitle = trim(substr($pc,$ptstart,$ptend-$ptstart));
   $idisvalid = ( stristr($pagetitle,$badarxiv_htmltitle) )  ? false : true ;
   }
   else { // doi: check xml error tag
   $idisvalid = ( stristr($pc, $baddoi_errortag) ) ? false : true;
   }


//CHECK FOR DUP SUBMISSION ($docid)
$meetts = makeMeetTS($gmid);//sets timestamp for NEXT meeting or INCOMING (gmid) meeting date
dbOpen('panda.unm.edu');
$query = "SELECT sf_id FROM form_submissions WHERE sf_docid = '$docid' AND sf_ts = '$meetts' ";
$result = doQuery($query);
$idisdup = (mysql_num_rows($result)>0) ? true : false ;
dbClose();

//SWITCH 
switch ($idisvalid) { 

   case false : //////////////////////// I N V A L I D   I D //////////////////////////////
      $output .= "\n<br /><span style=\"color: #d00; font-weight: bold;\">The ".$idtype." identifier submitted is not found (invalid ID).</span><br />\n";
   break;   

   
   case true:  /////////////////////////// V A L I D   I D /////////////////////////////////

   //Safety - $submitter exists
   if(empty($submitter)) 
      die('Name is a required field. [<a href="javascript: window.history.back();">&laquo;back</a>] [<a href="javascript: window.close();">never mind</a>]');

   //ABS URL (for accessing abstract)
   $absurl = ($idtype == "arXiv") ? $fetchurl : 'http://dx.doi.org/'.$docid ;
   $abslink = '<a href="'.$absurl.'" onclick="window.open(\''.$absurl.'\'); return false;">abs</a>' ;

   //TOP (id, name, abs link)
   $top = '<h3>'.$docid.' ['.$submitter.', <a href="'.$absurl.'" onclick="window.open(\''.$absurl.'\'); return false;">abs</a>]:</h3>
';
   $output .= $top; $entry = $top;//START ENTRY VAR for writing to html archive file
   //div indent
   $output .= '<div style="margin: 0 0 0 50px;">
';  
   $entry .= '<div style="margin: 0 0 0 50px;">
';    

   if($idtype == "arXiv") {      /////////////////////////////////////////// a r X i v /////////////////////////////////////////////

      //TITLE
      $titlestart = strpos($pc,'<span class="descriptor">Title:</span>')+38;
      $titleend = strpos($pc,'<',$titlestart);
      $title = trim(substr($pc,$titlestart,$titleend-$titlestart));
      $output .="<h3>".$title."</h3>\n"; //ADD TITLE TO OUTPUT
      $entry .="<h3>".$title."</h3>\n"; //ADD TITLE TO ENTRY

      //AUTHORS
      $authorsstart = strpos($pc,'<div class="authors"><span class="descriptor">Authors:</span>')+61;
      $authorsend = strpos($pc,'</div>',$authorsstart);
      $authors = trim(substr($pc,$authorsstart,$authorsend-$authorsstart));
      $authors = str_replace('href="/','href="http://arxiv.org/',$authors);//CORRECT LINK PATHS
      $output .= "Authors: ".$authors."<br />\n"; // ADD AUTHORS TO OUTPUT
      $entry .= "Authors: ".$authors."<br />\n"; // ADD AUTHORS TO ENTRY

      //ADDITIONAL METADATA (table)
      $mdtstart = strpos($pc,'<table summary="Additional metadata">');
      $mdtend = strpos($pc,'</table>')+8;
      $mdtlen = $mdtend-$mdtstart;
      $meta = substr($pc,$mdtstart,$mdtlen);
      $meta = str_replace('href="/','target = "_blank" href="http://arxiv.org/',$meta); //fix link paths; add target blank
      $output .= $meta; // ADD META TO OUTPUT
      $entry .= $meta; // ADD META TO ENTRY

      } //end if arXiv

      else {                                      ////////////////////////////// D O I //////////////////////////////////
       
      //TITLE
      $titlestart = strpos($pc,'<title>')+7;
      $titleend = strpos($pc,'</title>',$titlestart);
      $title = trim(substr($pc,$titlestart,$titleend-$titlestart));
      $output .="<h3>".$title."</h3>\n"; //ADD TITLE TO OUTPUT
      $entry .="<h3>".$title."</h3>\n"; //ADD TITLE TO ENTRY

      //AUTHORS
      $contributors_start = strpos($pc,'<contributors>')+14;
      $contributors_end = strpos($pc,'</contributors>',$contributors_start);
      $contributors = trim(substr($pc,$contributors_start,$contributors_end-$contributors_start));
      $auths = array();

      while ( strstr($contributors,"<given_name>") ) {
         $gnst = strpos($contributors,"<given_name>")+12;
         $gnend = strpos($contributors,"</given_name>",$gnst);
         $authgn = trim(substr($contributors,$gnst,$gnend-$gnst));
         $snst = strpos($contributors,"<surname>")+9;
         $snend = strpos($contributors,"</surname>",$snst);
         $authsn = trim(substr($contributors,$snst,$snend-$snst));
         $auths[] = $authgn." ".$authsn;
         $contributors = substr($contributors,$snend);
         }//end while

      $authors .= (count($auths)>0) ? "Authors: " : "";
      foreach($auths as $key=>$value) {
         $authors .= ($key>0) ? ", " : "";
         $authors .= $value;
         }

      $output .= $authors . ( (count($auths)>0) ? "\n<br />\n" : "" ); //ADD AUTHORS TO OUTPUT
      $entry .= $authors . ( (count($auths)>0) ? "\n<br />\n" : "" ); //ADD AUTHORS TO ENTRY

      //JOURNAL
      $journalstart = strpos($pc,'<full_title>')+12;
      $journalend = strpos($pc,'</full_title>',$journalstart);
      $journal = trim(substr($pc,$journalstart,$journalend-$journalstart));
      $meta .= 'Journal: '.$journal.'<br />
';

      //DOI
      $meta .= 'DOI: <a href="http://dx.doi.org/'.$docid.'" 
onclick="window.open(\'http://dx.doi.org/'.$docid.'\'); return false;">'.$docid.'</a><br />
';

      $output .= $meta; // ADD META TO OUTPUT
      $entry .= $meta; // ADD META TO ENTRY

      }//end else doi

   //ADD COMMENTS - regardless of arXiv -or- doi
   $cmnts = (!empty($addcomment)) ? '<em>'.$addcomment.'</em>'."\n" : '' ;
   $output .= $cmnts;
   $entry .= $cmnts;
 
   //ADD RELEVANT LINKS 
   $rellnks = "";
   if(isset($relevantlinks) && !empty($relevantlinks)) {

      $linklines = explode("\n",$relevantlinks);
      foreach($linklines as $lineno => $linkline) {
         $linkparts = explode(",",$linkline);
         $linkparts = array_reverse($linkparts); //puts URL at the end
         //NOTE: expressions below accomodate comma WITHIN the link text
         $linkurl = array_pop($linkparts);
         $linkparts = (!empty($linkparts)) ? array_reverse($linkparts) : $linkparts;
         $linktext = (!empty($linkparts)) ? implode(",",$linkparts) : $linkurl ; //in case there are extra commas within link text
         $rellnks .= ' [<a href="'.$linkurl.'" onclick="window.open(this.href); return false;">'.$linktext.'</a>]';  
         }//end foreach $linklines
      $output .= $rellnks;
      $entry .= $rellnks;
      }//end if

   //close indented div
   $output .= '</div>
';
   $entry .= '</div>

';//two line breaks here

//////////////////////////////////////////////////////////// A C T I O N ///////////////////////////////////////////////////////////////

   // CONFIRM -or- DATABASE and WRITE HTML ARCHIVE?
   $output .= '<div style="margin: 20px 0 0 100px;">
';
   if($confirmed == "no") { // show confirmation form (resubmits form values with "confirmed=yes")

////////////////////////////////////////////////////// Confirmation Only ////////////////////////////////////////////////////////////

      $output .= '<form name="confirm" method="post" action="'.$_SERVER['PHP_SELF'].'">
<input type="hidden" name="submitter" value="'.htmlspecialchars($submitter).'">
<input type="hidden" name="docid" value="'.$docid.'">
<input type="hidden" name="report" value="'.$report.'">
<!-- <input type="hidden" name="addcomment" value="'.htmlspecialchars($cmnts).htmlspecialchars($rellnks).'"> -->
<input type="hidden" name="confirmed" value="yes">
<input type="hidden" name="gmid" value="'.$gmid.'">
<textarea name="addcomment" style="display: none;">'.$cmnts.$rellnks.'</textarea>
';

//////////// DUPLICATE SUBMISSION????? //////////
      if(!$idisdup) {                                                            // NOT duplicate...
         $output .= '
<input type="submit" value="submit as shown above">
';
         } // end if not duplicate
         else {                                                                      //DUPLICATE...
         $output .= '
<input type="hidden" name="dupid" value="'.$docid.'">
<span style="color: #f00;">This paper has already been submitted for this meeting.</span><br />
<input type="submit" value="submit again">
';
         }//end else duplicate
/////////////////////////////////////////////////////////////////////////////////

      $output .= '
</form>
<input type="button" value="change" onclick="window.history.back();">
<input type="button" value="never mind" onclick="window.close();">
';
      }// end if NOT confirmed

      else { //CONFIRMED---database and write to archive file

///////////////////////////////////////////////////// Database / Write //////////////////////////////////////////////////////////////////////

      $meetts = makeMeetTS($gmid);//sets timestamp for NEXT meeting or INCOMING (gmid) meeting date
      $htmlfn ="archive". substr(date("Y",$meetts),2,2).date("m",$meetts).date("d",$meetts).".html";


      //DATABASE FORM SUBMISSON
      $query = "INSERT INTO form_submissions 
         (sf_id,sf_ts,sf_submitter,sf_docid,sf_report,sf_addcomment,sf_abs_url,sf_arch_url)
         VALUES (
         '', 
         '$meetts', 
         '".addslashes($submitter)."',
         '$docid',
         '$report',
         '".addslashes($addcomment)."',
         '$absurl',
         '$htmlfn' )";

//die("TESTING ONLY---if you get this message re-submit in a couple of minutes---thanks<br /><br />".$query);

      dbOpen('panda.unm.edu');
      doQuery($query);


      //DATABASE GROUP MEETING (if new)
      $query = "SELECT gm_url FROM group_meetings WHERE gm_url = '$htmlfn' ";
      $result = doQuery($query);
      if(mysql_num_rows($result)<1) {//group meeting record not exists...so insert here
         $anj_review = date("Y",$meetts).": ".date("m",$meetts)."/".date("d",$meetts);
         $query = "INSERT INTO group_meetings 
            (gm_id,gm_ts,gm_anj_review,gm_presentation,gm_url,gm_closed)
            VALUES
            ('','$meetts','$anj_review','','$htmlfn','0') ";
         $result = doQuery($query);
         }
      dbClose();


      //HTML WRITE
      $writefn = $path2root."groupmeet/archive/".$htmlfn ;
      $getfn = ( file_exists($writefn) ) ? $writefn : $path2root."groupmeet/template.html";
      $getfc = file_get_contents($getfn); //get contents of CURRENT FILE or TEMPLATE
      $dup_st = strpos($getfc,'<h3>'.$dupid.' [');
      if(!empty($dupid) && $dup_st > 0) {     ////////////////// DUPLICATE WRITE method:  inserts submitter and adds comments
         $find_st = $dup_st;
         $find_end = strpos($getfc,'<a',$find_st);
         $getfc = substr_replace($getfc,$submitter.', ',$find_end,0);
         if(!empty($addcomment)) {
            $find_end2 = strpos($getfc,'</div>',$find_end);
            $getfc = substr_replace($getfc,'| <em>'.$addcomment.'</em>'."\n",$find_end2,0);
            }
         $newfc = $getfc;
         }
      else {                                                   //////////////////// NON-DUPLICATE WRITE method - writes all to proper section
      $titledate = date("d M Y",$meetts);//like "19 May 2010"...for page title insertion
      $basehref = (strstr($_SERVER['HTTP_HOST'],"panda.unm.edu")) ? 
            "http://panda.unm.edu/CQuIC/" : "http://www.cquic.org/" ; //for page insertion (metatag);
      $getfc = str_replace("[%BASEHREF%]", $basehref, $getfc);//insert basehref (if template)
      $getfc = str_replace("[%TITLEDATE%]", $titledate, $getfc);//insert basehref (if template)
      $detailed_end = strpos($getfc,'<div style="text-align: center; margin: 30px 0 0 0;"><h2>Brief Reports</h2></div>');
      $brief_end = strpos($getfc,'<div id="footlinks" ');
      $detailed_str = substr($getfc,0,$detailed_end);
      $brief_str = substr($getfc,$detailed_end,$brief_end - $detailed_end);
      $bottom_str = substr($getfc,$brief_end);
      if($report == "Detailed")
         $detailed_str .= $entry; 
         else 
         $brief_str .= $entry;
      $newfc = $detailed_str.$brief_str.$bottom_str; // ASSEMBLE NEW CONTENTS
      }
      file_put_contents($writefn,$newfc); // WRITE TO FILE
//      chmod($writefn,0777); // SET WRITE PERMS

      //HTML OUTPUT MESSAGE
      $output .= '<span style="color: #f00;">Your submission as shown above has been accepted.</span>
<br /><br />
<input type="button" value="submit another" onclick="window.location=\''.$_SERVER['PHP_SELF'].'\'">
<input type="button" value="finished" onclick="window.close(); opener.location.reload();">
';
      }
      $output .= '</div>
';
   break; 

   }//end switch on idisvalid

}//end if process incoming


//////////////////////////////////////// H T M L /////////////////////////////////////////////
?><html>
<head>
<style type="text/css">
table { margin: 0; padding: 0; border-collapse: collapse; border: 0; }
td { margin: 0; padding: 0; border: 0; }
span.fixed { display: inline-block; width: 120px; height: 30px; }
</style>
</head>
<body style="font-family: arial;">

<?php echo $output; ?>

</body>
</html>
