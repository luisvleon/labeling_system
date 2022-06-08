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

$colname_etiquetas = "-1";
if (isset($_COOKIE["inv_id"])) {
  $colname_etiquetas = $_COOKIE["inv_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_etiquetas = sprintf("SELECT ord.*, simple.modelo, siz.*, ori.ori_name, mat.stu_percen, stu.stu_text, sta.sta_label FROM tbl_order ord, tbl_sizes siz, tbl_origin ori, tbl_mat_labels mat, tbl_stuff stu, tlb_status sta, tbl_inv_simple simple WHERE ord.simple_id = simple.inv_id AND ord.size_id=siz.size_id AND ord.ori_id=ori.ori_id AND mat.ord_id=ord.ord_id AND mat.stu_id=stu.stu_id AND ord.ord_status=sta.sta_id AND ord.invoice_id = %s ORDER BY ord.ord_id DESC LIMIT 100", GetSQLValueString($colname_etiquetas, "int"));
$etiquetas = mysql_query($query_etiquetas, $labels_con) or die(mysql_error());
$row_etiquetas = mysql_fetch_assoc($etiquetas);
$totalRows_etiquetas = mysql_num_rows($etiquetas);

$colname_cliente = "-1";
if (isset($_COOKIE["inv_id"])) {
  $colname_cliente = $_COOKIE["inv_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_cliente = sprintf("SELECT inv.invoice_id, cos.cos_names, cos.cos_id, cos.cos_corpname, cos.cos_corpname2,  cos.cos_ruc, inv.fecha_in, inv.fecha_out FROM tbl_invoice inv, tbl_costumers cos WHERE inv.cos_id=cos.cos_id AND inv.invoice_id = %s", GetSQLValueString($colname_cliente, "int"));
$cliente = mysql_query($query_cliente, $labels_con) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$colname_total = "-1";
if (isset($_COOKIE["inv_id"])) {
  $colname_total = $_COOKIE["inv_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_total = sprintf("SELECT SUM(cantidad) total FROM tbl_order WHERE ord_status=0 and invoice_id = %s", GetSQLValueString($colname_total, "int"));
$total = mysql_query($query_total, $labels_con) or die(mysql_error());
$row_total = mysql_fetch_assoc($total);
$totalRows_total = mysql_num_rows($total);

mysql_select_db($database_labels_con, $labels_con);
$query_maximo = "SELECT MAX(ord_id) AS maxi FROM tbl_order";
$maximo = mysql_query($query_maximo, $labels_con) or die(mysql_error());
$row_maximo = mysql_fetch_assoc($maximo);
$totalRows_maximo = mysql_num_rows($maximo);
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
          <td width="531" align="center" class="Titulo">Listado de etiquetas</td>
        </tr>
      </table>
      <br />
      <table width="431" border="1" align="center">
        <tr>
          <td width="98" align="left" bgcolor="#333333" class="cabecera">Razón social:</td>
          <td width="317" align="left"><?php echo $row_cliente['cos_corpname']; ?> <?php echo $row_cliente['cos_corpname2']; ?> </td>
        </tr>
      </table>
      <br />
      <table width="388" border="0" align="center">
        <?php if ($totalRows_etiquetas == 0) { // Show if recordset empty ?>
          <tr>
            <td align="center">No se han definido etiquetas para esta orden, <a href="/labels/eti_ori.php">Crear etiqueta</a></td>
          </tr>
          <?php } // Show if recordset empty ?>
      </table>
      <table width="1214" border="1" align="center">
        <?php if ($totalRows_etiquetas > 0) { // Show if recordset not empty ?>
        <br><center>Total a imprimir: <?php echo $row_total['total']; ?>&nbsp;&nbsp;&nbsp; <a href="/labels/eti_ori.php">Crear etiqueta</a>&nbsp;&nbsp;&nbsp; <a href="reset_labels.php?id=<?php echo $row_etiquetas['invoice_id']; ?>">Resetear etiquetas</a>&nbsp;&nbsp;&nbsp; <a href="orden_activities.php?inv_id=<?php echo $row_etiquetas['invoice_id']; ?>">Ir a invoice</a></center><p/>
          <tr bgcolor="#333333" class="cabecera">
            <td width="112" align="center">ID #</td>
            <td width="232" align="center">Estilo</td>
            <td width="232" align="center">Origen</td>
            <td width="230" align="center">Componentes</td>
            <td width="124" align="center">Cantidad</td>
            <td width="177" align="center">Status</td>
            <td width="61" align="center">Editar</td>
          </tr>
          <?php do { ?>
            <tr>
              <td align="center"><?php echo $row_etiquetas['ord_id']; ?></td>
              <td align="center"><?php echo $row_etiquetas['modelo']; ?></td>
              <td align="center"><?php echo $row_etiquetas['ori_name']; ?></td>
              <td align="center"><?php echo $row_etiquetas['stu_percen']; ?>% <?php echo $row_etiquetas['stu_text']; ?></td>
              <td align="center"><?php echo $row_etiquetas['cantidad']; ?></td>
              <td align="center"><?php echo $row_etiquetas['sta_label']; ?></td>
              <td align="center"><a href="/labels/print_status.php?ord_id=<?php echo $row_etiquetas['ord_id']; ?>"><img src="editi.png" alt="" width="16" height="16" border="0" /></a></td>
            </tr>
            <?php } while ($row_etiquetas = mysql_fetch_assoc($etiquetas)); ?>
          <?php } // Show if recordset not empty ?>
      </table>
      
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

mysql_free_result($etiquetas);

mysql_free_result($cliente);

mysql_free_result($total);

mysql_free_result($maximo);
?>
