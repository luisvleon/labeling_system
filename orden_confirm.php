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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_invoice (cos_id, shipto_id, fecha_in, fecha_out, comments, precio, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hidden_cosid'], "int"),
                       GetSQLValueString($_POST['hidden_shiptoid'], "int"),
                       GetSQLValueString($_POST['fecha_in'], "text"),
                       GetSQLValueString($_POST['fecha_out'], "text"),
                       GetSQLValueString($_POST['txt_comments'], "text"),
					   GetSQLValueString($_POST['txt_precio'], "text"),
                       GetSQLValueString($_POST['hidden_user'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
// agregar inv_id al final de la siguiente linea or max_id
  $insertGoTo = "orden_activities.php?inv_id=" . ($row_maximo['MAX(invoice_id)'] +1) . "";
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

$colname_Recordset2 = "-1";
if (isset($_GET['cos_id'])) {
  $colname_Recordset2 = $_GET['cos_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_Recordset2 = sprintf("SELECT cos_id, cos_names, cos_corpname, cos_ruc, cos_adress FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $labels_con) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$colname_ship = "-1";
if (isset($_GET['shipto_id'])) {
  $colname_ship = $_GET['shipto_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_ship = sprintf("SELECT * FROM tbl_ship_to WHERE shipto_id = %s", GetSQLValueString($colname_ship, "int"));
$ship = mysql_query($query_ship, $labels_con) or die(mysql_error());
$row_ship = mysql_fetch_assoc($ship);
$totalRows_ship = mysql_num_rows($ship);

mysql_select_db($database_labels_con, $labels_con);
$query_maximo = "SELECT MAX(invoice_id) FROM tbl_invoice";
$maximo = mysql_query($query_maximo, $labels_con) or die(mysql_error());
$row_maximo = mysql_fetch_assoc($maximo);
$totalRows_maximo = mysql_num_rows($maximo);
$max_var = ($row_maximo['MAX(invoice_id)'] +1);
/* echo $max_var; */
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
          <td width="531" align="center" class="Titulo">Confirmar la orden de trabajo e invoice</td>
        </tr>
      </table>
      <br />
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="454" border="1" align="center">
          <tr>
            <td width="127" align="right" bgcolor="#333333" class="cabecera">Cliente:</td>
            <td width="311" align="left"><?php echo $row_Recordset2['cos_names']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Razon Social:</td>
            <td><?php echo $row_Recordset2['cos_corpname']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">RUC:</td>
            <td><?php echo $row_Recordset2['cos_ruc']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Dirección:</td>
            <td><?php echo $row_Recordset2['cos_adress']; ?></td>
          </tr>
          <tr>
            <td align="right" bgcolor="#333333" class="cabecera">Ship to:</td>
            <td align="right">&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Nombre / Courier:</td>
            <td><?php echo $row_ship['shipto_name']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Dirección:</td>
            <td><?php echo $row_ship['shipto_address']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Telefonos:</td>
            <td><?php echo $row_ship['shipto_phono']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Instrucciones:</td>
            <td><label>
              <textarea name="txt_comments" id="txt_comments" cols="45" rows="5"></textarea>
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Fecha ingresa:</td>
            <td><label>
              <input name="fecha_in" type="text" id="fecha_in" value="<?php echo $dt1; ?>" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Fecha de entrega:</td>
            <td><input type="text" name="fecha_out" id="fecha_out" /></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Precio general</td>
            <td><label for="txt_precio"></label>
            <input type="text" name="txt_precio" id="txt_precio" /></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera"><input name="hidden_cosid" type="hidden" id="hidden_cosid" value="<?php echo $row_Recordset2['cos_id']; ?>" />
            <input name="hidden_shiptoid" type="hidden" id="hidden_shiptoid" value="<?php echo $row_ship['shipto_id']; ?>" />
            <input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_Recordset1['user_id']; ?>" />
            <input name="hidden_max" type="hidden" id="hidden_max" value="<?php echo $row_maximo['MAX(invoice_id)']; ?>" /></td>
            <td><label><?php echo $row_maximo['MAX(invoice_id)']; ?>
              <input type="submit" name="button" id="button" value="   Confirmar   " />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
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
<?php
mysql_free_result($Recordset1);

mysql_free_result($Recordset2);

mysql_free_result($ship);

mysql_free_result($maximo);
?>
