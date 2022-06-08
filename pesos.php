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
  $insertSQL = sprintf("INSERT INTO tbl_pesos (inv_id, peso, numero_caja) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['hidden_id'], "int"),
                       GetSQLValueString($_POST['txt_peso'], "double"),
                       GetSQLValueString($_POST['txt_numero'], "text"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
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

$colname_pesos = "-1";
if (isset($_GET['inv_id'])) {
  $colname_pesos = $_GET['inv_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_pesos = sprintf("SELECT * FROM tbl_pesos WHERE inv_id = %s ORDER BY numero_caja ASC", GetSQLValueString($colname_pesos, "int"));
$pesos = mysql_query($query_pesos, $labels_con) or die(mysql_error());
$row_pesos = mysql_fetch_assoc($pesos);
$totalRows_pesos = mysql_num_rows($pesos);

$colname_orden = "-1";
if (isset($_GET['inv_id'])) {
  $colname_orden = $_GET['inv_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_orden = sprintf("SELECT cos.cos_names, inv.invoice_id FROM tbl_invoice inv, tbl_costumers cos WHERE inv.cos_id = cos.cos_id AND inv.invoice_id = %s", GetSQLValueString($colname_orden, "int"));
$orden = mysql_query($query_orden, $labels_con) or die(mysql_error());
$row_orden = mysql_fetch_assoc($orden);
$totalRows_orden = mysql_num_rows($orden);

$colname_total = "-1";
if (isset($_GET['inv_id'])) {
  $colname_total = $_GET['inv_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_total = sprintf("SELECT SUM(peso) as tota FROM tbl_pesos WHERE inv_id = %s", GetSQLValueString($colname_total, "int"));
$total = mysql_query($query_total, $labels_con) or die(mysql_error());
$row_total = mysql_fetch_assoc($total);
$totalRows_total = mysql_num_rows($total);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="/labels/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema de etiquetado</title>
<!-- InstanceEndEditable -->
<link href="/labels/css/labels.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body onload="setFocus();">
<br />
<table width="651" border="0" align="center">
  <tr>
    <td width="610" align="center"><script type="text/javascript" src="/labels/labels_menu.js"></script></td>
  </tr>
</table>
<table width="980" border="0" align="center">
  <tr>
    <td width="814" height="652" valign="top"><!-- InstanceBeginEditable name="edit1" -->
      <table width="537" border="0" align="center">
        <tr>
          <td width="531" align="center" class="Titulo">Pesos de cajas y/o cartones</td>
        </tr>
      </table>
      <br />
      <table width="400" border="0" align="center">
        <tr>
          <td align="center" class="Asterisco"><?php echo $row_orden['cos_names']; ?> - <?php echo $row_orden['invoice_id']; ?></td>
        </tr>
      </table>
      <br />
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="400" border="1" align="center">
          <tr>
            <td width="142" bgcolor="#333333" class="cabecera">Numero de caja:
            <input name="hidden_id" type="hidden" id="hidden_id" value="<?php echo $row_orden['invoice_id']; ?>" /></td>
            <td width="248"><label>
              <input name="txt_numero" type="text" class="cajagrande" id="txt_numero" size="5" maxlength="3" zorder="0" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Peso:</td>
            <td><label>
              <input name="txt_peso" type="text" class="cajagrande" id="txt_peso" size="8" maxlength="6" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">&nbsp;</td>
            <td><label>
              <input type="submit" name="button" id="button" value="Submit" />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
      <table width="290" border="0" align="center">
        <tr>
          <td align="right">Peso total: <span class="cajagrande"><?php echo $row_total['tota']; ?></span></td>
        </tr>
      </table>
      <table width="400" border="1" align="center">
        <tr bgcolor="#333333" class="cabecera">
          <td width="159" align="center">Caja #</td>
          <td width="171" align="center">Peso:</td>
          <td width="48" align="center">Borrar:</td>
        </tr>
        <?php do { ?>
          <tr>
            <td align="center"><?php echo $row_pesos['numero_caja']; ?></td>
            <td align="center"><?php echo $row_pesos['peso']; ?></td>
            <td align="center"><img src="/labels/delete.png" width="16" height="16" border="0" /></td>
          </tr>
          <?php } while ($row_pesos = mysql_fetch_assoc($pesos)); ?>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
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
<script type="text/javascript">
<!--
var formObj = document.getElementById('form1');
var inputArr = formObj.getElementsByTagName("input");
for (i=0; i<inputArr.length-1; i++)
{
inputArr[i].onfocus = function()
{
this.style.backgroundColor = "yellow";
};

inputArr[i].onblur = function()
{
this.style.backgroundColor = "";
};
}

-->
</script>
<script type="text/javascript">
function setFocus(){
document.getElementById("txt_numero").focus();
}
</script><?php

mysql_free_result($Recordset1);

mysql_free_result($pesos);

mysql_free_result($orden);

mysql_free_result($total);
?>
