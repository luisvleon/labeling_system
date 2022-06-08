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
<?php if (!function_exists("GetSQLValueString")) {
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

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_Recordset1 = sprintf("SELECT user_id, user_names FROM tbl_users WHERE user_username = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $labels_con) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_orden = "-1";
if (isset($_GET['inv_id'])) {
  $colname_orden = $_GET['inv_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_orden = sprintf("SELECT inv.*, cos.*, shi.*, shi.email AS emails, user.user_names FROM tbl_invoice inv, tbl_costumers cos, tbl_ship_to shi, tbl_users user WHERE inv.cos_id=cos.cos_id AND inv.shipto_id=shi.shipto_id AND inv.user_id=user.user_id AND inv.invoice_id = %s", GetSQLValueString($colname_orden, "int"));
$orden = mysql_query($query_orden, $labels_con) or die(mysql_error());
$row_orden = mysql_fetch_assoc($orden);
$totalRows_orden = mysql_num_rows($orden);

mysql_select_db($database_labels_con, $labels_con);
$query_maximo = "SELECT MAX(ord_id) AS maxi FROM tbl_order";
$maximo = mysql_query($query_maximo, $labels_con) or die(mysql_error());
$row_maximo = mysql_fetch_assoc($maximo);
$totalRows_maximo = mysql_num_rows($maximo);

mysql_select_db($database_labels_con, $labels_con);
$query_etiquetas = "SELECT SUM(`qty` * `pieces`) FROM `tbl_inv_simple` WHERE invoice_id =" . $_GET['inv_id'] . "";
$etiquetas = mysql_query($query_etiquetas, $labels_con) or die(mysql_error());
$row_etiquetas = mysql_fetch_assoc($etiquetas);
$totalRows_etiquetas = mysql_num_rows($etiquetas);

mysql_select_db($database_labels_con, $labels_con);
$query_items = "SELECT SUM(`qty`) FROM `tbl_inv_simple` WHERE invoice_id =" . $_GET['inv_id'] . "";
$items = mysql_query($query_items, $labels_con) or die(mysql_error());
$row_items = mysql_fetch_assoc($items);
$totalRows_items = mysql_num_rows($items);

		//SET PARAMETERS
		setcookie("cos_id",$row_orden['cos_id']);
		setcookie("inv_id",$row_orden['invoice_id']);
		setcookie("maxi", $row_maximo['maxi'] + 1);


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
          <td width="531" align="center" class="Titulo">Actividades con orden de trabajo</td>
        </tr>
      </table>
      <br />
      <table width="681" border="1" align="center">
        <tr>
          <td bgcolor="#333333" class="cabecera">Orden de trabajo:</td>
          <td width="270"><?php echo str_pad((int) ($_REQUEST['inv_id']),6,"0",STR_PAD_LEFT); ?></td>
          <td width="91" bgcolor="#333333" class="cabecera">Status:</td>
          <td colspan="3" align="center"><?php
		  if ($row_orden['status'] == 0) { echo "Invoice abierto";
		  } else { echo "Invoice cerrado";
		  }
		  ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Items:</td>
          <td><?php echo $row_items['SUM(`qty`)']; ?></td>
          <td bgcolor="#333333" class="cabecera">Etiquetas:</td>
          <td width="37"><?php echo $row_etiquetas['SUM(`qty` * `pieces`)']; ?></td>
          <td width="49" bgcolor="#333333" class="cabecera">Total:</td>
          <td width="60"><?php echo (($row_etiquetas['SUM(`qty` * `pieces`)']) * $row_orden['precio'] ); ?></td>
        </tr>
        <tr>
          <td colspan="6" class="cabecera">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="center" bgcolor="#333333" class="cabecera">Cliente</td>
        </tr>
        <tr>
          <td width="134" bgcolor="#333333" class="cabecera">Nombres:</td>
          <td colspan="5"><?php echo $row_orden['cos_names']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Razon Social:</td>
          <td colspan="5"><?php echo $row_orden['cos_corpname']; ?> <?php echo $row_orden['cos_corpname2']; ?> </td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Ruc:</td>
          <td><?php echo $row_orden['cos_ruc']; ?></td>
          <td bgcolor="#333333"><span class="cabecera">Email:</span></td>
          <td colspan="3"><a href="mailto:<?php echo $row_orden['cos_email']; ?>"><?php echo $row_orden['cos_email']; ?></a></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Telefonos:</td>
          <td><?php echo $row_orden['cos_phone1']; ?> - <?php echo $row_orden['cos_phone2']; ?></td>
          <td bgcolor="#333333" class="cabecera">Fax:</td>
          <td colspan="3"><?php echo $row_orden['cos_fax']; ?></td>
        </tr>
        <tr>
          <td colspan="6" align="center" bgcolor="#333333" class="cabecera">Destino</td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Nombres:</td>
          <td colspan="5"><?php echo $row_orden['shipto_name']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Dirección:</td>
          <td colspan="5"><?php echo $row_orden['shipto_address']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Email:</td>
          <td colspan="5"><?php echo $row_orden['emails']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Telefonos:</td>
          <td colspan="5"><?php echo $row_orden['shipto_phono']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Fecha de ingreso:</td>
          <td colspan="5"><?php echo $row_orden['fecha_in']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Fecha de entrega:</td>
          <td colspan="5"><?php echo $row_orden['fecha_out']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Comentarios:</td>
          <td colspan="5"><?php echo $row_orden['comments']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Usuario:</td>
          <td colspan="5"><?php echo $row_orden['user_names']; ?></td>
        </tr>
      </table>
      <br />
      <br />
      <table width="652" border="1" align="center">
        <tr>
          <td width="235" height="51" align="center" valign="middle"><a href="eti_ori.php">Generar etiquetas</a></td>
          <td width="201" align="center" valign="middle"><a href="orden_etiq.php?id=<?php echo $row_orden['invoice_id']; ?>">Ver etiquetas</a></td>
          <td width="194" align="center" valign="middle"><a href="inv_simple.php?invoice_id=<?php echo $row_orden['invoice_id']; ?>">Invoice & Pack List</a></td>
        </tr>
        <tr>
          <td height="62" align="center" valign="middle"><a href="pesos.php?inv_id=<?php echo $row_orden['invoice_id']; ?>">Pesos</a></td>
          <td height="62" align="center" valign="middle"><a href="/labels/shoes_labels.php?cos=<?php echo $row_orden['cos_id'];?>&inv=<?php echo $row_orden['invoice_id']; ?>">Etiquetas calzado</a></td>
          <td align="center" valign="middle"><a href="/labels/close.php?inv_id=<?php echo $row_orden['invoice_id']; ?>">Cerrar factura</a></td>
        </tr>
      </table>
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

mysql_free_result($orden);

mysql_free_result($maximo);

mysql_free_result($etiquetas);

mysql_free_result($items);

?>
