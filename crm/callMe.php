<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Thank You</title>
</head>

<body>
<img src="http://prop.favistat.com/img/logo_new.png" width="100" height="41" alt="#" />

<h2>Call: 1800 2121 000</h2>
<?php
require_once("crm_integration.php");
define("CRM_WSDL_URL","http://bo.favista.in/favista/service/v4/soap.php?wsdl");
$referer_url=$_REQUEST['referer_url']; 
$landing_url=$_REQUEST['landing_url'];
$leadpage_url=$_REQUEST['leadpage_url']; 


$leadfollowerno = $_REQUEST['sales_mobile_no'];
$comments=$_REQUEST['comments'];
$venid=$_REQUEST['venid'];
$CustEmail = $_REQUEST['CustEmail'];
$CustName = $_REQUEST['CustName'];
$CustContact = $_REQUEST['CustContact'];
$Prname=$_REQUEST['project_name'];
$Rcomment=$_REQUEST['comment'];
$CustBestTimeCall=$_REQUEST['CustBestTimeCall'];
$CustPreferDtTime=$_REQUEST['CustPreferDtTime'];
$comment=$_REQUEST['comment'];
$Ccode=$_REQUEST['ccode'];
$Status=$_REQUEST['status'];
$Sourcename=$_REQUEST['source_name'];
$projID= intval($_REQUEST['proj_id']);
$UserID= intval($_REQUEST['user_id']);
$visit=$_REQUEST['visit'];
$priority=$_REQUEST['priority'];
if(empty($priority))
{$priority=1;
if(!empty($Sourcename)){
if(in_array($Sourcename,array('99acres','99acres Basic','Makaan','Affiliate Emails','Affiliate Partner','Magic Bricks','IndiaProperty','Commonfloor','Multimicro')))
{
$priority=2;
}}
}
function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
$link = mysql_connect('bo.favista.in', 'queueinsert', 'IKMUJN');
if (!$link) {
    $link = mysql_connect('bp.favista.in', 'queueinsert', 'IKMUJN');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
        }

}
// Make my_db the current database
$db_selected = mysql_select_db('favista', $link);

echo mysql_error ($link);
$Done=0;

if(isset($CustContact) && !startsWith($CustContact,'1408')) {

	/*code added by me*/


/*$mysql_query = mysql_query("INSERT INTO fv_leadsqueue(ProjID, UserID, Salesno, Comments, Emailchoice, CustEmail, CustName, CustContact, Projectname, Comment, Ccode , CustBestTimeCall, CustPreferDtTime, Visit, Sourcename, Status, Done, Timein,priority ) VALUES ('$projID','$UserID','$leadfollowerno','$comments','$venid','$CustEmail','$CustName','$CustContact','$Prname','$comment','$Ccode','$CustBestTimeCall','$CustPreferDtTime', '$visit', '$Sourcename','$Status','$Done',now(),'$priority')",$link);*/

$mysql_query = mysql_query("INSERT INTO fv_leadsqueue(ProjID, UserID, Salesno, Comments, Emailchoice, CustEmail, CustName, CustContact, Projectname, Comment, Ccode , CustBestTimeCall, CustPreferDtTime, Visit, Sourcename, Status, Done, Timein,priority,referer_url,landing_url,leadpage_url ) VALUES ('$projID','$UserID','$leadfollowerno','$comments','$venid','$CustEmail','$CustName','$CustContact','$Prname','$comment','$Ccode','$CustBestTimeCall','$CustPreferDtTime', '$visit', '$Sourcename','$Status','$Done',now(),'$priority','$referer_url','$landing_url','$leadpage_url')",$link);

/* modifiy the above query accordingly  */

echo mysql_error ($link);
if($mysql_query){
header("Location: ../thankyou.php");
?>
<!--<p style="font-size:24px;">Thank You for your Interest!<br />
We have received your requirement.<br />
Expect a call shortly from our Sales Expert with details!</p>-->
<?php
}
}
$count = mysql_query("SELECT COUNT(*) FROM  fv_leadsqueue where done = '0'",$link); 
$result = mysql_fetch_assoc($count);

mysql_close($link);
?>

</body>
</html>
