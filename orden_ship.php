<?php require_once('Connections/labels_con.php'); ?>
<?php require_once('Connections/labels_con.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO tbl_ship_to (cos_id, shipto_name, shipto_address, shipto_phono, user_id) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hidden_cos_id'], "int"),
                       GetSQLValueString($_POST['txt_name'], "text"),
                       GetSQLValueString($_POST['txt_direccion'], "text"),
                       GetSQLValueString($_POST['txt_phonos'], "text"),
                       GetSQLValueString($_POST['hidden_user'], "int"));

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

$colname_Recordset2 = "-1";
if (isset($_GET['cos_id'])) {
  $colname_Recordset2 = $_GET['cos_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_Recordset2 = sprintf("SELECT cos_id, cos_names, cos_corpname, cos_ruc FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $labels_con) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$colname_shipto = "-1";
if (isset($_GET['cos_id'])) {
  $colname_shipto = $_GET['cos_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_shipto = sprintf("SELECT * FROM tbl_ship_to WHERE cos_id = %s ORDER BY shipto_name ASC", GetSQLValueString($colname_shipto, "int"));
$shipto = mysql_query($query_shipto, $labels_con) or die(mysql_error());
$row_shipto = mysql_fetch_assoc($shipto);
$totalRows_shipto = mysql_num_rows($shipto);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema de etiquetado</title>
<!-- InstanceEndEditable -->
<link href="css/labels.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
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
    <td width="814" height="652" valign="top"><!-- InstanceBeginEditable name="edit1" --><br />
      <table width="537" border="0" align="center">
        <tr>
          <td width="531" align="center" class="Titulo">Seleccione dirección para distribución</td>
        </tr>
      </table>
      <br />
      <table width="685" border="1" align="center">
        <tr>
          <td width="171"><?php echo $row_Recordset2['cos_names']; ?></td>
          <td width="355"><?php echo $row_Recordset2['cos_corpname']; ?></td>
          <td width="137"><?php echo $row_Recordset2['cos_ruc']; ?></td>
        </tr>
      </table>
      <br />
      <br />
      <table width="861" border="1">
        <tr bgcolor="#333333" class="cabecera">
          <td width="145">Nombre / Currier:</td>
          <td width="404">Dirección:</td>
          <td width="156">Telefonos:</td>
          <td width="81" align="center">Sel</td>
        </tr>
        <?php do { ?>
          <?php if ($totalRows_shipto > 0) { // Show if recordset not empty ?>
  <tr>
    <td><?php echo $row_shipto['shipto_name']; ?></td>
    <td><?php echo $row_shipto['shipto_address']; ?></td>
    <td><?php echo $row_shipto['shipto_phono']; ?></td>
    <td align="center"><a href="orden_confirm.php?cos_id=<?php echo $row_Recordset2['cos_id']; ?>&amp;shipto_id=<?php echo $row_shipto['shipto_id']; ?>"><img src="detalle.png" width="16" height="16" border="0" /></a></td>
  </tr>
  <?php } // Show if recordset not empty ?>
          <?php } while ($row_shipto = mysql_fetch_assoc($shipto)); ?>
      </table>
      <br />
      <table width="559" border="0" align="center">
        <tr>
          <td width="553" align="center">Si aún no ha registrado una dirección, puede hacerlo debajo.</td>
        </tr>
      </table>
      <br />
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="460" border="1" align="center">
          <tr>
            <td width="95" bgcolor="#333333" class="cabecera">Nombres:</td>
            <td width="287"><label>
              <input name="txt_name" type="text" id="txt_name" size="50" maxlength="40" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Dirección:</td>
            <td><label>
              <input name="txt_direccion" type="text" id="txt_direccion" size="60" maxlength="60" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Telefonos:</td>
            <td><label>
              <input name="txt_phonos" type="text" id="txt_phonos" size="50" maxlength="40" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><input name="hidden_cos_id" type="hidden" id="hidden_cos_id" value="<?php echo $row_Recordset2['cos_id']; ?>" />
            <input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_Recordset1['user_id']; ?>" /></td>
            <td><label>
              <input type="submit" name="button" id="button" value="   Grabar   " />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
      <p><br />
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

mysql_free_result($Recordset2);

mysql_free_result($shipto);
?>
