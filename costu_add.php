<?php require_once('Connections/labels_con.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_costumers (cos_names, cos_corpname, cos_corpname2, cos_ruc, cos_email, cos_phone1, cos_phone2, cos_fax, pais_label, ciudad_label, cos_adress, cos_comments, cos_userid) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txt_names'], "text"),
                       GetSQLValueString($_POST['txt_razon'], "text"),
					   GetSQLValueString($_POST['txt_razon2'], "text"),
                       GetSQLValueString($_POST['txt_ruc'], "text"),
                       GetSQLValueString($_POST['txt_email'], "text"),
                       GetSQLValueString($_POST['txt_phone1'], "text"),
                       GetSQLValueString($_POST['txt_phone2'], "text"),
                       GetSQLValueString($_POST['txt_fax'], "text"),
                       GetSQLValueString($_POST['txt_pais'], "text"),
                       GetSQLValueString($_POST['txt_ciudad'], "text"),
                       GetSQLValueString($_POST['txt_adress'], "text"),
                       GetSQLValueString($_POST['txt_comments'], "text"),
                       GetSQLValueString($_POST['hidden_user'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());

  $insertGoTo = "menu.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_rs_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rs_user = $_SESSION['MM_Username'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_rs_user = sprintf("SELECT user_id FROM tbl_users WHERE user_username = %s", GetSQLValueString($colname_rs_user, "text"));
$rs_user = mysql_query($query_rs_user, $labels_con) or die(mysql_error());
$row_rs_user = mysql_fetch_assoc($rs_user);
$totalRows_rs_user = mysql_num_rows($rs_user);$colname_rs_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rs_user = $_SESSION['MM_Username'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_rs_user = sprintf("SELECT user_id, user_names FROM tbl_users WHERE user_username = %s", GetSQLValueString($colname_rs_user, "text"));
$rs_user = mysql_query($query_rs_user, $labels_con) or die(mysql_error());
$row_rs_user = mysql_fetch_assoc($rs_user);
$totalRows_rs_user = mysql_num_rows($rs_user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="stmenu.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>
<link href="css/labels.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="674" border="0" align="center">
  <tr>
    <td width="597" height="509"><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="555" border="0" align="center">
        <tr>
          <td align="center" valign="middle"><script type="text/javascript" src="labels_menu.js"></script></td>
        </tr>
      </table>
      <br />
      <br />
      <table width="579" border="1" align="center">
        <tr>
          <td width="94" bgcolor="#333333" class="cabecera"><strong>Nombres:</strong></td>
          <td width="469"><label>
            <input name="txt_names" type="text" id="txt_names" onblur="MM_validateForm('txt_names','','R');return document.MM_returnValue" size="50" maxlength="40" />
            <span class="Asterisco">          *</span></label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>Razon Soc:</strong></td>
          <td><label>
            <input name="txt_razon" type="text" id="txt_razon" size="50" maxlength="40" />
            <span class="Asterisco">*</span></label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Razon Soc 2:</td>
          <td><input name="txt_razon2" type="text" id="txt_razon2" size="50" maxlength="40" /></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>Ruc:</strong></td>
          <td><label>
            <input name="txt_ruc" type="text" id="txt_ruc" onblur="MM_validateForm('txt_names','','R','txt_razon','','R','txt_ruc','','RisNum','txt_email','','NisEmail','txt_phone1','','NisNum','txt_phone2','','NisNum','txt_fax','','NisNum');return document.MM_returnValue" size="20" maxlength="13" />
            <span class="Asterisco">*</span></label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Pais:</td>
          <td><label>
            <input type="text" name="txt_pais" id="txt_pais" />
          </label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Ciudad:</td>
          <td><label>
            <input type="text" name="txt_ciudad" id="txt_ciudad" />
          </label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>Dirección:</strong></td>
          <td><label>
            <input name="txt_adress" type="text" id="txt_adress" size="60" maxlength="60" />
            <span class="Asterisco">*</span></label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>E-mail:</strong></td>
          <td><label>
            <input name="txt_email" type="text" id="txt_email" size="50" maxlength="40" />
            <span class="Asterisco">          *</span></label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>Telefono:</strong></td>
          <td><label>
            <input name="txt_phone1" type="text" id="txt_phone1" size="30" maxlength="20" />
            <span class="Asterisco">          *</span></label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>Celular:</strong></td>
          <td><label>
            <input name="txt_phone2" type="text" id="txt_phone2" size="30" maxlength="20" />
          </label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>Fax:</strong></td>
          <td><label>
            <input name="txt_fax" type="text" id="txt_fax" size="30" maxlength="20" />
          </label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>Comentarios:</strong></td>
          <td><label>
            <textarea name="txt_comments" id="txt_comments" cols="60" rows="3"></textarea>
          </label></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera"><strong>Usuario:</strong></td>
          <td><input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_rs_user['user_id']; ?>" />
            <?php echo $row_rs_user['user_names']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">&nbsp;</td>
          <td><label>
            <input type="submit" name="button" id="button" value="Submit" />
          </label></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs_user);
?>
