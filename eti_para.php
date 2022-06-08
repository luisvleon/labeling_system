<?php require_once('Connections/labels_con.php'); ?>
<?php

$dt1=date("Y-m-d"); 
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
$statusvar=0; //variable de status de impresion
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_order (cos_id, invoice_id, ori_id, size_id, ord_date, ord_status, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hidden_cosid'], "int"),
					   GetSQLValueString($_COOKIE["inv_id"], "int"),
                       GetSQLValueString($_POST['hidden_origen'], "int"),
                       GetSQLValueString($_POST['hidden_size'], "int"),
                       GetSQLValueString($_POST['hidden_date'], "date"),
                       GetSQLValueString($statusvar, "int"),
                       GetSQLValueString($_POST['hidden_user'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());


  $insertGoTo = "eti_parasel.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
if (isset($_COOKIE["cos_id"])) {
  $colname_costumer = $_COOKIE["cos_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costumer = sprintf("SELECT cos_id, cos_corpname, cos_ruc FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_costumer, "int"));
$costumer = mysql_query($query_costumer, $labels_con) or die(mysql_error());
$row_costumer = mysql_fetch_assoc($costumer);
$totalRows_costumer = mysql_num_rows($costumer);

$colname_origen = "-1";
if (isset($_COOKIE["ori_id"])) {
  $colname_origen = $_COOKIE["ori_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_origen = sprintf("SELECT * FROM tbl_origin WHERE ori_id = %s", GetSQLValueString($colname_origen, "int"));
$origen = mysql_query($query_origen, $labels_con) or die(mysql_error());
$row_origen = mysql_fetch_assoc($origen);
$totalRows_origen = mysql_num_rows($origen);

$colname_size = "-1";
if (isset($_GET['size_id'])) {
  $colname_size = $_GET['size_id'];
  setcookie("size_id", $_GET['size_id']);
}
mysql_select_db($database_labels_con, $labels_con);
$query_size = sprintf("SELECT * FROM tbl_sizes WHERE size_id = %s", GetSQLValueString($colname_size, "int"));
$size = mysql_query($query_size, $labels_con) or die(mysql_error());
$row_size = mysql_fetch_assoc($size);
$totalRows_size = mysql_num_rows($size);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Labels</title>
<!-- InstanceEndEditable -->
<link href="css/labels.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body OnLoad="document.form1.button.focus();">
<br />
<table width="651" border="0" align="center">
  <tr>
    <td width="610" align="center"><script type="text/javascript" src="labels_menu.js"></script></td>
  </tr>
</table>
<table width="980" border="0" align="center">
  <tr>
    <td width="814" height="652" valign="top"><!-- InstanceBeginEditable name="edit1" -->
      <p>&nbsp;</p>
      <table width="537" border="0" align="center">
        <tr>
          <td width="531" align="center" class="Titulo">Confirmar datos para las etiquetas</td>
        </tr>
      </table>
      <br />
      <br />
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="606" border="1" align="center">
          <tr align="center">
            <td align="left" bgcolor="#333333" class="cabecera">Grupo de etiquetas:
              <input name="next_orid" type="hidden" id="next_orid" value="<?php echo ($_COOKIE["maxi"]); ?>" /></td>
            <td align="left"><?php echo str_pad((int) ($_COOKIE["maxi"]),6,"0",STR_PAD_LEFT); ?></td>
          </tr>
          <tr align="center">
            <td width="154" align="left" bgcolor="#333333" class="cabecera">Razon social:            </td>
            <td width="421" align="left"><?php echo $row_costumer['cos_corpname']; ?>
            <input name="hidden_cosid" type="hidden" id="hidden_cosid" value="<?php echo $row_costumer['cos_id']; ?>" /></td>
          </tr>
          <tr align="center">
            <td align="left" bgcolor="#333333" class="cabecera">Ruc:</td>
            <td align="left"><?php echo $row_costumer['cos_ruc']; ?></td>
          </tr>
          <tr align="center">
            <td align="left" bgcolor="#333333" class="cabecera">Origen:</td>
            <td align="left"><?php echo $row_origen['ori_name']; ?><input name="hidden_origen" type="hidden" id="hidden_origen" value="<?php echo $_REQUEST['ori_id']; ?>" /></td>
          </tr>
          <tr align="center">
            <td align="left" bgcolor="#333333" class="cabecera">Size / Talla</td>
            <td align="left"><?php echo $row_size['size_label']; ?>
            <input name="hidden_size" type="hidden" id="hidden_size" value="<?php echo $row_size['size_id']; ?>" /></td>
          </tr>
          <tr align="center">
            <td align="left" bgcolor="#333333" class="cabecera"><input type="hidden" name="hidden_date" id="hidden_date" value="<?php echo $dt1; ?>" />              <input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_Recordset1['user_id']; ?>" />
            <input name="hidden_maxvar" type="hidden" id="hidden_maxvar" value="<?php echo $_COOKIE["maxi"]; ?>" /></td>
            <td align="left"><label>
              <input type="submit" name="button" id="button" value="   Grabar   " />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
      <br />
      <br />
      <br />
<br />
<br />
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