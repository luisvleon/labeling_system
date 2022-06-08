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
  $updateSQL = sprintf("UPDATE tbl_order SET ord_status=%s WHERE ord_id=%s",
                       GetSQLValueString($_POST['select_status'], "int"),
                       GetSQLValueString($_POST['hidden_ord_id'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());

  $updateGoTo = "orden_etiq.php?id=" . $_POST['hidden_invoice'] . "";
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

mysql_select_db($database_labels_con, $labels_con);
$query_status = "SELECT * FROM tlb_status";
$status = mysql_query($query_status, $labels_con) or die(mysql_error());
$row_status = mysql_fetch_assoc($status);
$totalRows_status = mysql_num_rows($status);

$colname_orden = "-1";
if (isset($_GET['ord_id'])) {
  $colname_orden = $_GET['ord_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_orden = sprintf("SELECT ord_id, invoice_id, ord_status FROM tbl_order WHERE ord_id = %s", GetSQLValueString($colname_orden, "int"));
$orden = mysql_query($query_orden, $labels_con) or die(mysql_error());
$row_orden = mysql_fetch_assoc($orden);
$totalRows_orden = mysql_num_rows($orden);
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
          <td width="531" align="center" class="Titulo">Edicion de status de impresion</td>
        </tr>
      </table>
      <p><br />
      </p>
      <table width="575" border="0" align="center">
        <tr>
          <td align="center"><p><strong>Desea editar el status de este grupo de etiquetas ?</strong></p>
          <p><?php echo $row_orden['ord_id']; ?></p></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="200" border="0" align="center">
          <tr>
            <td align="center"><p>
              <label>
                <select name="select_status" id="select_status">
                  <?php
do {  
?>
                  <option value="<?php echo $row_status['sta_id']?>"<?php if (!(strcmp($row_status['sta_id'], $row_orden['ord_status']))) {echo "selected=\"selected\"";} ?>><?php echo $row_status['sta_label']?></option>
                  <?php
} while ($row_status = mysql_fetch_assoc($status));
  $rows = mysql_num_rows($status);
  if($rows > 0) {
      mysql_data_seek($status, 0);
	  $row_status = mysql_fetch_assoc($status);
  }
?>
                </select>
              </label>
              </p>
              <p>
                <input name="hidden_ord_id" type="hidden" id="hidden_ord_id" value="<?php echo $row_orden['ord_id']; ?>" />
                <input name="hidden_invoice" type="hidden" id="hidden_invoice" value="<?php echo $row_orden['invoice_id']; ?>" />
                <label>
                  <input type="submit" name="button" id="button" value="     Grabar     " />
                </label>
            </p></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
      </form>
      <p>&nbsp;</p>
      <p>        <br />
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

mysql_free_result($status);

mysql_free_result($orden);
?>
