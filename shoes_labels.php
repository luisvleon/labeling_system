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

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_Recordset1 = sprintf("SELECT user_id, user_names FROM tbl_users WHERE user_username = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $labels_con) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_labels = "-1";
if (isset($_GET['cos'])) {
  $colname_labels = $_GET['cos'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_labels = sprintf("SELECT ori.ori_name, lab.cantidad, sta.sta_label, lab.size, lab.id_shoe_label, lab.estilo FROM tbl_shoes_labels lab, tbl_origin ori, tlb_status sta WHERE lab.costumer = %s AND lab.origin = ori.ori_id AND lab.status = sta.sta_id ORDER BY lab.id_shoe_label DESC", GetSQLValueString($colname_labels, "int"));
$labels = mysql_query($query_labels, $labels_con) or die(mysql_error());
$row_labels = mysql_fetch_assoc($labels);
$totalRows_labels = mysql_num_rows($labels);

$colname_costumer = "-1";
if (isset($_GET['cos'])) {
  $colname_costumer = $_GET['cos'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costumer = sprintf("SELECT cos_id, cos_corpname, cos_corpname2 FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_costumer, "int"));
$costumer = mysql_query($query_costumer, $labels_con) or die(mysql_error());
$row_costumer = mysql_fetch_assoc($costumer);
$totalRows_costumer = mysql_num_rows($costumer);

$colname_invoice = "-1";
if (isset($_GET['inv'])) {
  $colname_invoice = $_GET['inv'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_invoice = sprintf("SELECT invoice_id FROM tbl_invoice WHERE invoice_id = %s", GetSQLValueString($colname_invoice, "int"));
$invoice = mysql_query($query_invoice, $labels_con) or die(mysql_error());
$row_invoice = mysql_fetch_assoc($invoice);
$totalRows_invoice = mysql_num_rows($invoice);
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
<body>
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
          <td width="531" align="center" class="Titulo">Shoes labels for <?php echo $row_costumer['cos_corpname']; ?> <?php echo $row_costumer['cos_corpname2']; ?></td>
        </tr>
      </table>
      <br />
      <table width="200" border="0" align="center">
        <tr>
          <td align="center"><a href="/labels/shoes_label_order.php?cos=<?php echo $row_costumer['cos_id'];?>&inv=<?php echo $row_invoice['invoice_id']; ?>">Crear nueva</a></td>
          <td align="center"><a href="/labels/shoes_reset_labels.php?inv=<?php echo $row_invoice['invoice_id'];?>&cos=<?php echo $row_costumer['cos_id']; ?>">Resetear</a></td>
        </tr>
      </table>
      <table width="716" border="1" align="center">
        <tr bgcolor="#333333" class="cabecera">
          <td width="109">Origen</td>
          <td width="171" style="text-align: center">Estilo</td>
          <td width="106" style="text-align: center">Cantidad</td>
          <td width="80" style="text-align: center">TALLA</td>
          <td width="119">Status</td>
          <td width="91" align="center">Duplicate</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_labels['ori_name']; ?></td>
            <td style="text-align: center"><?php echo $row_labels['estilo']; ?></td>
            <td style="text-align: center"><?php echo $row_labels['cantidad']; ?></td>
            <td style="text-align: center"><?php echo $row_labels['size']; ?></td>
            <td><?php echo $row_labels['sta_label']; ?></td>
            <td align="center"><a href="shoes_labels_dup.php?lab=<?php echo $row_labels['id_shoe_label']; ?>&amp;cos=<?php echo $row_costumer['cos_id']; ?>"><img src="editi.png" width="16" height="16" border="0" /></a></td>
          </tr>
          <?php } while ($row_labels = mysql_fetch_assoc($labels)); ?>
      </table>
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

mysql_free_result($labels);

mysql_free_result($costumer);

mysql_free_result($invoice);
?>
