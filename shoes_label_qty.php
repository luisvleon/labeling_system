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
	
	
	 for ($i = 1; $i <= $_POST['txt_cantidad']; $i++)      /* this line added to repeat the insert   */
{ 
  $insertSQL = sprintf("INSERT INTO tbl_shoes_duplic (order_id, dupli) VALUES (%s, %s)",
                       GetSQLValueString($_POST['hidden_id'], "int"),
                       GetSQLValueString($i, "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
}
/*  para grabar la cantidad de etiquetas en la tabla orden  */


  $updateSQL = sprintf("UPDATE tbl_shoes_labels SET cantidad=%s WHERE id_shoe_label=%s",
                       GetSQLValueString($_POST['txt_cantidad'], "int"),
                       GetSQLValueString($_POST['hidden_id'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());

  $updateGoTo = "shoes_labels.php?cos=" . $row_label['costumer'] . "&inv=" . $row_label['invoice_id'] . "";
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
$query_maximo = "SELECT MAX(id_shoe_label) maximo FROM tbl_shoes_labels";
$maximo = mysql_query($query_maximo, $labels_con) or die(mysql_error());
$row_maximo = mysql_fetch_assoc($maximo);
$totalRows_maximo = mysql_num_rows($maximo);

$colname_label = $row_maximo['maximo'];
if (isset($row_maximo['maximo'])) {
  $colname_label = $row_maximo['maximo'];
  //echo $colname_label;
}
mysql_select_db($database_labels_con, $labels_con);
$query_label = sprintf("SELECT * FROM tbl_shoes_labels WHERE id_shoe_label = %s", GetSQLValueString($colname_label, "int"));
$label = mysql_query($query_label, $labels_con) or die(mysql_error());
$row_label = mysql_fetch_assoc($label);
$totalRows_label = mysql_num_rows($label);

$colname_costumer = "-1";
if (isset($row_maximo['maximo'])) {
  $colname_costumer = $row_maximo['maximo'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costumer = sprintf("SELECT cos.cos_corpname, cos.cos_corpname2 FROM tbl_shoes_labels lab, tbl_costumers cos WHERE lab.id_shoe_label = %s AND cos.cos_id=lab.costumer", GetSQLValueString($colname_costumer, "int"));
$costumer = mysql_query($query_costumer, $labels_con) or die(mysql_error());
$row_costumer = mysql_fetch_assoc($costumer);
$totalRows_costumer = mysql_num_rows($costumer);

$colname_origen = "-1";
if (isset($row_maximo['maximo'])) {
  $colname_origen = $row_maximo['maximo'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_origen = sprintf("SELECT ori.ori_name FROM tbl_shoes_labels lab, tbl_origin ori  WHERE lab.id_shoe_label = %s AND lab.origin = ori.ori_id", GetSQLValueString($colname_origen, "int"));
$origen = mysql_query($query_origen, $labels_con) or die(mysql_error());
$row_origen = mysql_fetch_assoc($origen);
$totalRows_origen = mysql_num_rows($origen);
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
<body onload="setFocus();">
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
          <td width="531" align="center" class="Titulo">Quantity shoes labels</td>
        </tr>
      </table>
      <br />
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="371" border="1" align="center">
          <tr>
            <td width="122" bgcolor="#333333" class="cabecera">Costumer:</td>
            <td width="233"><?php echo $row_costumer['cos_corpname']; ?> <?php echo $row_costumer['cos_corpname2']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Origin:</td>
            <td><?php echo $row_origen['ori_name']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Capellada:</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Forro:</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Plantilla:</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Zuela:</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Catidad:</td>
            <td><label>
              <input name="txt_cantidad" type="text" class="cajagrande" id="txt_cantidad" size="6" maxlength="3" />
            </label></td>
          </tr>
          <tr>
            <td height="56" bgcolor="#333333" class="cabecera"><input name="hidden_id" type="hidden" id="hidden_id" value="<?php echo $row_label['id_shoe_label']; ?>" /></td>
            <td><label>
              <input type="submit" name="button" id="button" value="Submit" />
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
</script>
<script type="text/javascript">
function setFocus(){
document.getElementById("txt_cantidad").focus();
}
</script>
<?php
mysql_free_result($Recordset1);
mysql_free_result($maximo);
mysql_free_result($label);

mysql_free_result($costumer);

mysql_free_result($origen);


?>
