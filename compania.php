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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tbl_company SET slogan=%s, ad1=%s, ad2=%s, phonos=%s, email=%s, web=%s WHERE razon=%s",
                       GetSQLValueString($_POST['txt_slogan'], "text"),
                       GetSQLValueString($_POST['txt_ad1'], "text"),
                       GetSQLValueString($_POST['txt_ad2'], "text"),
                       GetSQLValueString($_POST['txt_phonos'], "text"),
                       GetSQLValueString($_POST['txt_email'], "text"),
                       GetSQLValueString($_POST['txt_web'], "text"),
                       GetSQLValueString($_POST['txt_razon'], "text"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());

  $updateGoTo = "compania.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tbl_company SET razon=%s, slogan=%s, ad1=%s, ad2=%s, phonos=%s, email=%s, web=%s WHERE id=%s",
                       GetSQLValueString($_POST['txt_razon'], "text"),
                       GetSQLValueString($_POST['txt_slogan'], "text"),
                       GetSQLValueString($_POST['txt_ad1'], "text"),
                       GetSQLValueString($_POST['txt_ad2'], "text"),
                       GetSQLValueString($_POST['txt_phonos'], "text"),
                       GetSQLValueString($_POST['txt_email'], "text"),
                       GetSQLValueString($_POST['txt_web'], "text"),
                       GetSQLValueString($_POST['hiddenField'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());

  $updateGoTo = "compania.php";
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
$query_compania = "SELECT * FROM tbl_company";
$compania = mysql_query($query_compania, $labels_con) or die(mysql_error());
$row_compania = mysql_fetch_assoc($compania);
$totalRows_compania = mysql_num_rows($compania);
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
          <td width="531" align="center" class="Titulo">Datos de la compañia</td>
        </tr>
      </table>
      <br />
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="654" border="1" align="center">
          <tr>
            <td width="154" bgcolor="#333333" class="cabecera">Razón social:
            <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_compania['id']; ?>" /></td>
            <td width="484"><label>
              <input name="txt_razon" type="text" id="txt_razon" value="<?php echo $row_compania['razon']; ?>" size="40" maxlength="25" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Slogan:</td>
            <td><label>
              <input name="txt_slogan" type="text" id="txt_slogan" value="<?php echo $row_compania['slogan']; ?>" size="65" maxlength="50" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Dirección 1:</td>
            <td><label>
              <input name="txt_ad1" type="text" id="txt_ad1" value="<?php echo $row_compania['ad1']; ?>" size="70" maxlength="60" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Dirección 2:</td>
            <td><label>
              <input name="txt_ad2" type="text" id="txt_ad2" value="<?php echo $row_compania['ad2']; ?>" size="70" maxlength="60" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Teléfonos:</td>
            <td><label>
              <input name="txt_phonos" type="text" id="txt_phonos" value="<?php echo $row_compania['phonos']; ?>" size="60" maxlength="60" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">E-mail:</td>
            <td><label>
              <input name="txt_email" type="text" id="txt_email" value="<?php echo $row_compania['email']; ?>" size="50" maxlength="40" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Website:</td>
            <td><input name="txt_web" type="text" id="txt_web" value="<?php echo $row_compania['web']; ?>" size="50" maxlength="40" /></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">&nbsp;</td>
            <td><label>
              <input type="submit" name="button" id="button" value="   Actualizar   " />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
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

mysql_free_result($compania);

?>
