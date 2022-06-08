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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tbl_costumers SET cos_names=%s, cos_corpname=%s, cos_corpname2=%s, cos_ruc=%s, cos_email=%s, cos_phone1=%s, cos_phone2=%s, cos_fax=%s, pais_label=%s, ciudad_label=%s, cos_adress=%s, cos_comments=%s, cos_userid=%s WHERE cos_id=%s",
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
                       GetSQLValueString($_POST['hidden_user'], "int"),
                       GetSQLValueString($_POST['hidden_id'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());

  $updateGoTo = "costu_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_Recordset1 = sprintf("SELECT user_id, user_names FROM tbl_users WHERE user_username = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $labels_con) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_costumer = "-1";
if (isset($_REQUEST['cos_id'])) {
  $colname_costumer = $_REQUEST['cos_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costumer = sprintf("SELECT * FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_costumer, "int"));
$costumer = mysql_query($query_costumer, $labels_con) or die(mysql_error());
$row_costumer = mysql_fetch_assoc($costumer);
$totalRows_costumer = mysql_num_rows($costumer);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Untitled Document</title>
<!-- InstanceEndEditable -->
<link href="css/labels.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
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
<!-- InstanceEndEditable -->
</head>
<body>
<br />
<table width="651" border="0" align="center">
  <tr>
    <td width="610" align="center"><script type="text/javascript" src="labels_menu.js"></script></td>
  </tr>
</table>
<table width="980" border="0" align="center">
  <tr>
    <td width="814" height="652" valign="top"><!-- InstanceBeginEditable name="edit1" -->
      <table width="537" border="0" align="center">
        <tr>
          <td width="531" align="center" class="Titulo">Editar cliente</td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="579" border="1" align="center">
          <tr>
            <td width="94" bgcolor="#333333" class="cabecera"><strong>Nombres:</strong></td>
            <td width="469"><label>
              <input name="hidden_id" type="hidden" id="hidden_id" value="<?php echo $row_costumer['cos_id']; ?>" />
              <input name="txt_names" type="text" id="txt_names" onblur="MM_validateForm('txt_names','','R');return document.MM_returnValue" value="<?php echo $row_costumer['cos_names']; ?>" size="50" maxlength="40" />
              <span class="Asterisco"> *</span></label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>Razon Soc:</strong></td>
            <td><label>
              <input name="txt_razon" type="text" id="txt_razon" value="<?php echo $row_costumer['cos_corpname']; ?>" size="50" maxlength="40" />
              <span class="Asterisco">*</span></label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Razon Soc 2:</td>
            <td><input name="txt_razon2" type="text" id="txt_razon2" value="<?php echo $row_costumer['cos_corpname2']; ?>" size="50" maxlength="40" /></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>Ruc:</strong></td>
            <td><label>
              <input name="txt_ruc" type="text" id="txt_ruc" onblur="MM_validateForm('txt_names','','R','txt_razon','','R','txt_ruc','','RisNum','txt_email','','NisEmail','txt_phone1','','NisNum','txt_phone2','','NisNum','txt_fax','','NisNum');return document.MM_returnValue" value="<?php echo $row_costumer['cos_ruc']; ?>" size="20" maxlength="13" />
              <span class="Asterisco">*</span></label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Pais:</td>
            <td><label>
              <input name="txt_pais" type="text" id="txt_pais" value="<?php echo $row_costumer['pais_label']; ?>" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Ciudad:</td>
            <td><label>
              <input name="txt_ciudad" type="text" id="txt_ciudad" value="<?php echo $row_costumer['ciudad_label']; ?>" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>Dirección:</strong></td>
            <td><label>
              <input name="txt_adress" type="text" id="txt_adress" value="<?php echo $row_costumer['cos_adress']; ?>" size="60" maxlength="60" />
              <span class="Asterisco">*</span></label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>E-mail:</strong></td>
            <td><label>
              <input name="txt_email" type="text" id="txt_email" value="<?php echo $row_costumer['cos_email']; ?>" size="50" maxlength="40" />
              <span class="Asterisco"> *</span></label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>Telefono:</strong></td>
            <td><label>
              <input name="txt_phone1" type="text" id="txt_phone1" value="<?php echo $row_costumer['cos_phone1']; ?>" size="30" maxlength="20" />
              <span class="Asterisco"> *</span></label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>Celular:</strong></td>
            <td><label>
              <input name="txt_phone2" type="text" id="txt_phone2" value="<?php echo $row_costumer['cos_phone2']; ?>" size="30" maxlength="20" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>Fax:</strong></td>
            <td><label>
              <input name="txt_fax" type="text" id="txt_fax" value="<?php echo $row_costumer['cos_fax']; ?>" size="30" maxlength="20" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>Comentarios:</strong></td>
            <td><label>
              <textarea name="txt_comments" id="txt_comments" cols="60" rows="3"><?php echo $row_costumer['cos_comments']; ?></textarea>
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><strong>Usuario:</strong></td>
            <td><input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_Recordset1['user_id']; ?>" />
              <?php echo $row_Recordset1['user_names']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">&nbsp;</td>
            <td><label>
              <input type="submit" name="button" id="button" value="Submit" />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
      </form>
      <p><br />
        <br />
      </p>
    <!-- InstanceEndEditable --></td>
  </tr>
</table>
<br />
<table width="335" border="0" align="right">
  <tr>
    <td width="329">Usuario: <?php echo $row_Recordset1['user_names']; ?></td>
  </tr>
</table>
</body>
<!-- InstanceEnd -->
<?php
mysql_free_result($Recordset1);

mysql_free_result($costumer);
?>
