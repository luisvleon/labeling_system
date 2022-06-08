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

$currentPage = $_SERVER["PHP_SELF"];

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_Recordset1 = sprintf("SELECT user_id, user_names FROM tbl_users WHERE user_username = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $labels_con) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$maxRows_invoices = 20;
$pageNum_invoices = 0;
if (isset($_GET['pageNum_invoices'])) {
  $pageNum_invoices = $_GET['pageNum_invoices'];
}
$startRow_invoices = $pageNum_invoices * $maxRows_invoices;

mysql_select_db($database_labels_con, $labels_con);
$query_invoices = "SELECT inv.invoice_id, inv.activo, inv.fecha_in, inv.fecha_out, cos.cos_names, cos.cos_corpname, cos.cos_ruc, shi.shipto_address FROM tbl_invoice inv, tbl_costumers cos, tbl_ship_to shi WHERE inv.cos_id=cos.cos_id AND inv.shipto_id=shi.shipto_id ORDER BY inv.invoice_id DESC";
$query_limit_invoices = sprintf("%s LIMIT %d, %d", $query_invoices, $startRow_invoices, $maxRows_invoices);
$invoices = mysql_query($query_limit_invoices, $labels_con) or die(mysql_error());
$row_invoices = mysql_fetch_assoc($invoices);

if (isset($_GET['totalRows_invoices'])) {
  $totalRows_invoices = $_GET['totalRows_invoices'];
} else {
  $all_invoices = mysql_query($query_invoices);
  $totalRows_invoices = mysql_num_rows($all_invoices);
}
$totalPages_invoices = ceil($totalRows_invoices/$maxRows_invoices)-1;

$queryString_invoices = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_invoices") == false && 
        stristr($param, "totalRows_invoices") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_invoices = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_invoices = sprintf("&totalRows_invoices=%d%s", $totalRows_invoices, $queryString_invoices);

$queryString_ordenes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ordenes") == false && 
        stristr($param, "totalRows_ordenes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ordenes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ordenes = sprintf("&totalRows_ordenes=%d%s", $totalRows_ordenes, $queryString_ordenes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Listado de ordenes</title>
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
          <td width="531" align="center" class="Titulo">Ordenes de trabajo</td>
        </tr>
      </table>
      <br />
      <br />
      <table width="1079" border="1" align="center">
        <tr bgcolor="#333333" class="cabecera">
          <td width="159">Cliente</td>
          <td width="185">Razon Social</td>
          <td width="118">Ruc</td>
          <td width="186">Enviado a</td>
          <td width="143">Fecha ingreso</td>
          <td width="126">Fecha de salida</td>
          <td width="55" align="center">Active</td>
          <td width="55" align="center">Sel</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_invoices['cos_names']; ?></td>
            <td><?php echo $row_invoices['cos_corpname']; ?></td>
            <td><?php echo $row_invoices['cos_ruc']; ?></td>
            <td><?php echo $row_invoices['shipto_address']; ?></td>
            <td><?php echo $row_invoices['fecha_in']; ?></td>
            <td><?php echo $row_invoices['fecha_out']; ?></td>
            <td align="center"><a href="/labels/order_activate.php?inv_id=<?php echo $row_invoices['invoice_id']; ?>"><?php
			
			if ($row_invoices['activo'] == 1) { echo "<img src=\"/labels/checkmark.png\" border=\"0\" width=\"25\" height=\"21\" />"; } else { echo "<img src=\"/labels/delete.png\" border=\"0\" width=\"25\" height=\"21\" />";}
			
			
			
			 ?></a></td>
            <td align="center"><a href="orden_activities.php?inv_id=<?php echo $row_invoices['invoice_id']; ?>"><img src="detalle.png" width="16" height="16" border="0" /></a></a></td>
          </tr>
          <?php } while ($row_invoices = mysql_fetch_assoc($invoices)); ?>
      </table>
      <br />
      <table width="316" border="0" align="center">
        <tr>
          <td width="159" align="center"><a href="<?php printf("%s?pageNum_invoices=%d%s", $currentPage, max(0, $pageNum_invoices - 1), $queryString_invoices); ?>">Previous</a></td>
          <td width="147" align="center"><a href="<?php printf("%s?pageNum_invoices=%d%s", $currentPage, min($totalPages_invoices, $pageNum_invoices + 1), $queryString_invoices); ?>">Next</a></td>
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

mysql_free_result($invoices);
?>
