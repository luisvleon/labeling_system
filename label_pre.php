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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
  for ($i = 1; $i <= $_POST['txt_cantidad']; $i++)      /* this line added to repeat the insert   */
{ 
  $insertSQL = sprintf("INSERT INTO tbl_duplicate (ord_id, rep_num) VALUES (%s, %s)",
                       GetSQLValueString($_POST['hidden_ordid'], "int"),
                       GetSQLValueString($i, "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
}
/*  para grabar la cantidad de etiquetas en la tabla orden  */

if (strlen($_POST['txt_forro']) >0){
$updateSQL = sprintf("UPDATE tbl_order SET cantidad = %s, forro = %s WHERE ord_id =%s",
                       GetSQLValueString($_POST['txt_cantidad'], "text"),
					   GetSQLValueString($_POST['txt_forro'] . "% " . $_POST['select_mat'], "text"),
                       GetSQLValueString($_POST['hidden_ordid'], "int"));
} else {
	$updateSQL = sprintf("UPDATE tbl_order SET cantidad = %s, forro = %s WHERE ord_id =%s",
                       GetSQLValueString($_POST['txt_cantidad'], "text"),
					   GetSQLValueString("", "text"),
                       GetSQLValueString($_POST['hidden_ordid'], "int"));
	
}


  mysql_select_db($database_labels_con, $labels_con);
  $Result2 = mysql_query($updateSQL, $labels_con) or die(mysql_error());
  
  /*  hasta aqui insert cantidad  */
  
  
  $insertGoTo = "eti_end.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

$colname_costumer = "-1";
if (isset($_COOKIE["maxi"])) {
  $colname_costumer = $_COOKIE["maxi"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costumer = sprintf("SELECT cos.cos_id, cos.cos_corpname, cos.cos_ruc, siz.size_text, siz.size_label, ori.ori_name  FROM tbl_costumers cos, tbl_order ord, tbl_sizes siz, tbl_origin ori  WHERE ord.cos_id=cos.cos_id and ord.size_id=siz.size_id and ord.ori_id=ori.ori_id and ord.ord_id=%s", GetSQLValueString($colname_costumer, "int"));
$costumer = mysql_query($query_costumer, $labels_con) or die(mysql_error());
$row_costumer = mysql_fetch_assoc($costumer);
$totalRows_costumer = mysql_num_rows($costumer);

$colname_materiales = "-1";
if (isset($_COOKIE["maxi"])) {
  $colname_materiales = $_COOKIE["maxi"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_materiales = sprintf("SELECT stu.stu_text, mat.stu_percen  FROM tbl_mat_labels mat, tbl_stuff stu WHERE mat.stu_id=stu.stu_id and ord_id = %s", GetSQLValueString($colname_materiales, "int"));
$materiales = mysql_query($query_materiales, $labels_con) or die(mysql_error());
$row_materiales = mysql_fetch_assoc($materiales);
$totalRows_materiales = mysql_num_rows($materiales);

$colname_instrucciones = "-1";
if (isset($_COOKIE["maxi"])) {
  $colname_instrucciones = $_COOKIE["maxi"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_instrucciones = sprintf("SELECT per.par_label FROM tbl_par_labels par, tbl_params per WHERE par.par_id=per.par_id and ord_id = %s", GetSQLValueString($colname_instrucciones, "int"));
$instrucciones = mysql_query($query_instrucciones, $labels_con) or die(mysql_error());
$row_instrucciones = mysql_fetch_assoc($instrucciones);
$totalRows_instrucciones = mysql_num_rows($instrucciones);

mysql_select_db($database_labels_con, $labels_con);
$query_MAT = "SELECT * FROM tbl_stuff ORDER BY stu_text ASC";
$MAT = mysql_query($query_MAT, $labels_con) or die(mysql_error());
$row_MAT = mysql_fetch_assoc($MAT);
$totalRows_MAT = mysql_num_rows($MAT);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Untitled Document</title>
<!-- InstanceEndEditable -->
<link href="css/labels.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body OnLoad="document.form1.txt_cantidad.focus();">
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
          <td width="531" align="center" class="Titulo">Etiqueta a ser impresa</td>
        </tr>
      </table>
      <br />
      <table width="200" border="0" align="center">
        <tr>
          <td align="center">Importado por:<br />
            <strong><?php echo $row_costumer['cos_corpname']; ?></strong></td>
        </tr>
        <tr>
          <td align="center"><strong><?php echo $row_costumer['cos_ruc']; ?></strong></td>
        </tr>
      </table>
      <table width="290" border="0" align="center">
        <tr>
          <td height="40" align="center">Talla: <?php echo $row_costumer['size_label']; ?></td>
        </tr>
      </table>
      <table width="241" border="0" align="center">
        <?php do { ?>
          <tr>
            <td width="235" align="center"><?php echo $row_materiales['stu_percen']; ?>% <?php echo $row_materiales['stu_text']; ?></td>
          </tr>
          <?php } while ($row_materiales = mysql_fetch_assoc($materiales)); ?>
      </table>
      <table width="200" border="0" align="center">
        <?php do { ?>
          <tr>
            <td align="center"><?php echo $row_instrucciones['par_label']; ?></td>
          </tr>
          <?php } while ($row_instrucciones = mysql_fetch_assoc($instrucciones)); ?>
      </table>
      <br />
      <table width="261" border="0" align="center">
        <tr>
          <td width="255" align="center">Hecho en <?php echo $row_costumer['ori_name']; ?></td>
        </tr>
      </table>
      <br />
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="447" border="1" align="center">
    <tr>
      <td width="67" bgcolor="#333333" class="cabecera">Cantidad:
        <input type="hidden" name="hidden_ordid" id="hidden_ordid" value="<?php echo $_COOKIE["maxi"]; ?>" />
        <input name="hidden_cos" type="hidden" id="hidden_cos" value="<?php echo $row_costumer['cos_id']; ?>" /></td>
      <td width="364" align="left"><label>
        <input name="txt_cantidad" type="text" class="cajagrande" id="txt_cantidad" value="1" size="6" maxlength="3" />
      </label></td>
      </tr>
    <tr>
      <td bgcolor="#333333" class="cabecera">Forro:</td>
      <td align="left"><label for="txt_forro"></label>
        <input name="txt_forro" type="text" class="cajagrandeSIN" id="txt_forro" size="6" /> <label for="select_mat"></label>
         <select name="select_mat" class="cajagrandeSIN" id="select_mat">
           <?php
do {  
?>
           <option value="<?php echo $row_MAT['stu_text']?>"><?php echo $row_MAT['stu_text']?></option>
           <?php
} while ($row_MAT = mysql_fetch_assoc($MAT));
  $rows = mysql_num_rows($MAT);
  if($rows > 0) {
      mysql_data_seek($MAT, 0);
	  $row_MAT = mysql_fetch_assoc($MAT);
  }
?>
         </select></td>
      </tr>
    <tr>
      <td bgcolor="#333333" class="cabecera">&nbsp;</td>
      <td align="left"><input type="submit" name="button" id="button" value="Submit" /></td>
      </tr>
  </table>
  <p>
    <input type="hidden" name="MM_insert" value="form1" />
  </p>
</form>
      <p>&nbsp; </p>
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

mysql_free_result($MAT);
?>
