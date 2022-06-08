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
	
// reset all the table
$updateSQL = "UPDATE tbl_invoice SET activo='0' WHERE activo='1'";

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());
  	
	
	// just active the selected invoice
	
  $updateSQL = sprintf("UPDATE tbl_invoice SET activo=%s WHERE invoice_id=%s",
                       GetSQLValueString($_POST['hidden_uno'], "int"),
                       GetSQLValueString($_POST['hidden_invoice'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());

  $updateGoTo = "/labels/orden_list.php";
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

$colname_invoice = "-1";
if (isset($_GET['inv_id'])) {
  $colname_invoice = $_GET['inv_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_invoice = sprintf("SELECT inv.invoice_id, inv.cos_id, inv.status, cos.cos_names FROM tbl_invoice inv, tbl_costumers cos WHERE cos.cos_id = inv.cos_id AND inv.invoice_id = %s", GetSQLValueString($colname_invoice, "int"));
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
          <td width="531" align="center" class="Titulo">Activar invoice</td>
        </tr>
      </table>
      <br />
      <table width="600" height="540" border="0" align="center">
        <tr>
          <td align="center"><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
            <table width="478" border="0">
              <tr>
                <td width="472" align="center" bgcolor="#FFFFFF"><p class="cabecera"><span class="cajagrande">Desea activar el invoice # <?php echo $row_invoice['invoice_id']; ?> de <?php echo $row_invoice['cos_names']; ?></span></p>
                  <p>
                    <input name="hidden_invoice" type="hidden" id="hidden_invoice" value="<?php echo $row_invoice['invoice_id']; ?>" />
                    <input name="hidden_uno" type="hidden" id="hidden_uno" value="1" />
                    <input name="button" type="submit" class="cajagrande" id="button" value="   Activar   " />
                    <br />
                    <br />
                  </p></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1" />
          </form></td>
        </tr>
      </table>
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
<?php
mysql_free_result($Recordset1);

mysql_free_result($invoice);
?>
