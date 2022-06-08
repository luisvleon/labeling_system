<?php require_once('Connections/labels_con.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$var_inv = $_COOKIE["inv_id"];
$var_mat1 = $_COOKIE["mat1"];
$var_mat1b = $_COOKIE["mat1b"];
$var_mat2 = $_COOKIE["mat2"];
$var_mat2b = $_COOKIE["mat2b"];
$var_mat3 = $_COOKIE["mat3"];
$var_mat3b = $_COOKIE["mat3b"];
$var_mat4 = $_COOKIE["mat4"];
$var_mat4b = $_COOKIE["mat4b"];

$var_size = $_COOKIE["size"];
$var_qty = $_COOKIE["qty"];
$var_ori_id = $_COOKIE["ori_id"];


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
$statusvar=0; //variable de status de impresion
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
  
  if (strlen($_POST['txt_forro']) == "") {
    
 $insertSQL = sprintf("INSERT INTO tbl_order (invoice_id, simple_id, cos_id, ori_id, size_id, id_lavado, id_blan, id_sec, id_plan, id_pro, ord_date, cantidad, forro, ord_status, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($var_inv, "int"),
					   GetSQLValueString($_COOKIE["inv_num"], "int"),
                       GetSQLValueString($_POST['hidden_cos'], "int"),
                       GetSQLValueString($_POST['hidden_ori'], "int"),
                       GetSQLValueString($_POST['hidden_size'], "int"),
					   GetSQLValueString($_POST['select_ins'], "int"),
					   GetSQLValueString($_POST['select_blan'], "int"),
					   GetSQLValueString($_POST['select_sec'], "int"),
					   GetSQLValueString($_POST['select_plan'], "int"),
					   GetSQLValueString($_POST['select_pro'], "int"),
                       GetSQLValueString(date("Y-m-d"), "date"),
                       GetSQLValueString($_POST['txt_qty'], "int"),
                       GetSQLValueString(" ", "text"),
					   GetSQLValueString($statusvar, "int"),
                       GetSQLValueString($_POST['hiddenuser'], "int"));
					   //echo $insertSQL;
					   
					   
} else {
	
	if (strlen($_POST['txt_forro2']) == "") {$forro = $_POST['txt_forro'] . "% " . $_POST['select_forro'];} else {$forro = $_POST['txt_forro'] . "% " . $_POST['select_forro'] . ", " . $_POST['txt_forro2'] . "% " . $_POST['select_forro2'];}
	
	 					$insertSQL = sprintf("INSERT INTO tbl_order (invoice_id, simple_id, cos_id, ori_id, size_id, id_lavado, id_blan, id_sec, id_plan, id_pro, ord_date, cantidad, forro, ord_status, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s,  %s, %s, %s, %s, %s)",
                       GetSQLValueString($var_inv, "int"),
                       GetSQLValueString($_COOKIE["inv_num"], "int"),
					   GetSQLValueString($_POST['hidden_cos'], "int"),
                       GetSQLValueString($_POST['hidden_ori'], "int"),
                       GetSQLValueString($_POST['hidden_size'], "int"),
					   GetSQLValueString($_POST['select_ins'], "int"),
					   GetSQLValueString($_POST['select_blan'], "int"),
					   GetSQLValueString($_POST['select_sec'], "int"),
					   GetSQLValueString($_POST['select_plan'], "int"),
					   GetSQLValueString($_POST['select_pro'], "int"),
                       GetSQLValueString(date("Y-m-d"), "date"),
                       GetSQLValueString($_POST['txt_qty'], "int"),
                       GetSQLValueString($forro, "text"),
					   GetSQLValueString($statusvar, "int"),
                       GetSQLValueString($_POST['hiddenuser'], "int"));
	  
}

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
  
  //Grabar las instrucciones
  $insertSQL = sprintf("INSERT INTO tbl_par_labels (ord_id, par_id) VALUES (%s, %s)",
                       GetSQLValueString($_COOKIE["order_num"], "text"),
                      GetSQLValueString($_POST['select_ins'], "int"));
					  //echo "instrucciones " . $insertSQL;
  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
  
  //Grabar materiales
  // primer material requerido
  $insertSQL_mat1 = sprintf("INSERT INTO tbl_mat_labels (ord_id, stu_id, stu_percen) VALUES (%s, %s, %s)",
                       GetSQLValueString($_COOKIE["order_num"], "text"),
                       GetSQLValueString($_POST['hidden_mat1'], "int"),
                       GetSQLValueString($var_mat1, "text"));
					   //echo "<br>mat1  debe ser " .$_POST['hidden_mat1'] ." SQL " . $insertSQL_mat1;
  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL_mat1, $labels_con) or die(mysql_error());
  
  // segundo material opcional
  if (strlen($_COOKIE["mat2"]) > 0) {
  $insertSQL_mat2 = sprintf("INSERT INTO tbl_mat_labels (ord_id, stu_id, stu_percen) VALUES (%s, %s, %s)",
                       GetSQLValueString($_COOKIE["order_num"], "text"),
                       GetSQLValueString($_POST['hidden_mat2'], "int"),
                       GetSQLValueString($var_mat2, "text"));
					  // echo "<br>mat2 " . $insertSQL_mat2;
  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL_mat2, $labels_con) or die(mysql_error());
  }
  // tercer material opcional
  if (strlen($_COOKIE["mat3"]) > 0) {
  $insertSQL_mat3 = sprintf("INSERT INTO tbl_mat_labels (ord_id, stu_id, stu_percen) VALUES (%s, %s, %s)",
                       GetSQLValueString($_COOKIE["order_num"], "text"),
                       GetSQLValueString($_POST['hidden_mat3'], "int"),
                       GetSQLValueString($var_mat3, "text"));
					   //echo "<br>mat3 " . $insertSQL_mat3;
  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL_mat3, $labels_con) or die(mysql_error());
  }
    // cuarto material opcional
   if (strlen($_COOKIE["mat4"]) > 0) {
  $insertSQL_mat4 = sprintf("INSERT INTO tbl_mat_labels (ord_id, stu_id, stu_percen) VALUES (%s, %s, %s)",
                       GetSQLValueString($_COOKIE["order_num"], "text"),
                       GetSQLValueString($_POST['hidden_mat4'], "int"),
                       GetSQLValueString($var_mat4, "text"));
					   //echo "<br>mat4 " . $insertSQL_mat4;
  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL_mat4, $labels_con) or die(mysql_error());
  }
  
  
  //duplicar las etiquetas
  
   for ($i = 1; $i <= $_POST['txt_qty']; $i++)      /* this line added to repeat the insert   */
{ 
  $insertSQL = sprintf("INSERT INTO tbl_duplicate (ord_id, rep_num) VALUES (%s, %s)",
                       GetSQLValueString($_COOKIE["order_num"], "text"),
                       GetSQLValueString($i, "int"));
					   //echo "<br>" . $insertSQL;

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
}  
  
  
  //unset cookies
    setcookie("mat1", "", time() -3600);
  setcookie("mat1b", "", time() -3600);
  
  setcookie("mat2", "", time() -3600);
  setcookie("mat2b", "", time() -3600);
  
  setcookie("mat3", "", time() -3600);
  setcookie("mat3b", "", time() -3600);
  
  setcookie("mat4", "", time() -3600);
  setcookie("mat4b", "", time() -3600);
  
  setcookie("ori_id", "", time() -3600);
  setcookie("size", "", time() -3600);
  setcookie("qty", "", time() -3600);
  
  setcookie("orden_num", "", time() -3600);
   setcookie("inv_num", "", time() -3600);
  
  
  
  $insertGoTo = "inv_simple.php";
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

mysql_select_db($database_labels_con, $labels_con);
$query_maximo = "SELECT MAX(ord_id) as sup FROM tbl_order";
$maximo = mysql_query($query_maximo, $labels_con) or die(mysql_error());
$row_maximo = mysql_fetch_assoc($maximo);
$totalRows_maximo = mysql_num_rows($maximo);

$vartemp = intval($row_maximo['sup'] + 1);
//echo $vartemp;
setcookie("order_num", $vartemp);


mysql_select_db($database_labels_con, $labels_con);
$query_maximo2 = "SELECT MAX(inv_id) as sup2 FROM tbl_inv_simple";
$maximo2 = mysql_query($query_maximo2, $labels_con) or die(mysql_error());
$row_maximo2 = mysql_fetch_assoc($maximo2);
$totalRows_maximo2 = mysql_num_rows($maximo2);

$vartemp2 = $row_maximo2['sup2'];
//echo $vartemp;
setcookie("inv_num", $vartemp2);


$colname_costu = "-1";
if (isset($var_inv)) {
  $colname_costu = $var_inv;
}
mysql_select_db($database_labels_con, $labels_con);
$query_costu = sprintf("SELECT inv.invoice_id, cos.cos_id, cos.cos_corpname, cos.cos_corpname2, cos.cos_ruc FROM tbl_costumers cos, tbl_invoice inv WHERE cos.cos_id=inv.cos_id AND inv.invoice_id = %s", GetSQLValueString($colname_costu, "int"));
$costu = mysql_query($query_costu, $labels_con) or die(mysql_error());
$row_costu = mysql_fetch_assoc($costu);
$totalRows_costu = mysql_num_rows($costu);

$colname_origen = "-1";
if (isset($var_ori_id)) {
  $colname_origen = $var_ori_id;
}
mysql_select_db($database_labels_con, $labels_con);
$query_origen = sprintf("SELECT * FROM tbl_origin WHERE ori_id = %s", GetSQLValueString($colname_origen, "int"));
$origen = mysql_query($query_origen, $labels_con) or die(mysql_error());
$row_origen = mysql_fetch_assoc($origen);
$totalRows_origen = mysql_num_rows($origen);

//Instrucciones de lavado
mysql_select_db($database_labels_con, $labels_con);
$query_ins = "SELECT * FROM tbl_params ORDER BY par_id ASC";
$ins = mysql_query($query_ins, $labels_con) or die(mysql_error());
$row_ins = mysql_fetch_assoc($ins);
$totalRows_ins = mysql_num_rows($ins);

//instrucciones de blanqueado
mysql_select_db($database_labels_con, $labels_con);
$query_blan = "SELECT * FROM tbl_params_blan ORDER BY id_param";
$blan = mysql_query($query_blan, $labels_con) or die(mysql_error());
$row_blan = mysql_fetch_assoc($blan);
$totalRows_blan = mysql_num_rows($blan);

//Instrucciones de secado
mysql_select_db($database_labels_con, $labels_con);
$query_sec = "SELECT * FROM tbl_params_sec ORDER BY id_param";
$sec = mysql_query($query_sec, $labels_con) or die(mysql_error());
$row_sec = mysql_fetch_assoc($sec);
$totalRows_sec = mysql_num_rows($sec);

//Instrucciones de plachado
mysql_select_db($database_labels_con, $labels_con);
$query_plan = "SELECT * FROM tbl_params_plan ORDER BY id_param";
$plan = mysql_query($query_plan, $labels_con) or die(mysql_error());
$row_plan = mysql_fetch_assoc($plan);
$totalRows_plan = mysql_num_rows($plan);

//Instrucciones de lavado profesional
mysql_select_db($database_labels_con, $labels_con);
$query_pro = "SELECT * FROM tbl_params_pro ORDER BY id_param";
$pro = mysql_query($query_pro, $labels_con) or die(mysql_error());
$row_pro = mysql_fetch_assoc($pro);
$totalRows_pro = mysql_num_rows($pro);

mysql_select_db($database_labels_con, $labels_con);
$query_mat = "SELECT * FROM tbl_stuff ORDER BY stu_text ASC";
$mat = mysql_query($query_mat, $labels_con) or die(mysql_error());
$row_mat = mysql_fetch_assoc($mat);
$totalRows_mat = mysql_num_rows($mat);

$colname_talla = "-1";
if (isset($var_size)) {
  $colname_talla = $var_size;
}
mysql_select_db($database_labels_con, $labels_con);
$query_talla = sprintf("SELECT * FROM tbl_sizes WHERE size_id = %s", GetSQLValueString($colname_talla, "int"));
$talla = mysql_query($query_talla, $labels_con) or die(mysql_error());
$row_talla = mysql_fetch_assoc($talla);
$totalRows_talla = mysql_num_rows($talla);

$colname_mat1 = "-1";
if (isset($_COOKIE["mat1b"])) {

  $colname_mat1 = $_COOKIE["mat1b"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_mat1 = sprintf("SELECT * FROM tbl_stuff WHERE stu_text = %s", GetSQLValueString($colname_mat1, "text"));
$mat1 = mysql_query($query_mat1, $labels_con) or die(mysql_error());
$row_mat1 = mysql_fetch_assoc($mat1);
$totalRows_mat1 = mysql_num_rows($mat1);

$colname_mat2 = "-1";
if (isset($_COOKIE["mat2b"])) {
  $colname_mat2 = $_COOKIE["mat2b"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_mat2 = sprintf("SELECT * FROM tbl_stuff WHERE stu_text = %s", GetSQLValueString($colname_mat2, "text"));
$mat2 = mysql_query($query_mat2, $labels_con) or die(mysql_error());
$row_mat2 = mysql_fetch_assoc($mat2);
$totalRows_mat2 = mysql_num_rows($mat2);

$colname_mat3 = "-1";
if (isset($_COOKIE["mat3b"])) {
  $colname_mat3 = $_COOKIE["mat3b"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_mat3 = sprintf("SELECT * FROM tbl_stuff WHERE stu_text = %s", GetSQLValueString($colname_mat3, "text"));
$mat3 = mysql_query($query_mat3, $labels_con) or die(mysql_error());
$row_mat3 = mysql_fetch_assoc($mat3);
$totalRows_mat3 = mysql_num_rows($mat3);

$colname_mat4 = "-1";
if (isset($_COOKIE["mat4b"])) {
  $colname_mat4 = $_COOKIE["mat4b"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_mat4 = sprintf("SELECT * FROM tbl_stuff WHERE stu_text = %s", GetSQLValueString($colname_mat4, "text"));
$mat4 = mysql_query($query_mat4, $labels_con) or die(mysql_error());
$row_mat4 = mysql_fetch_assoc($mat4);
$totalRows_mat4 = mysql_num_rows($mat4);
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
<body  onload="setFocus();">
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
          <td width="531" align="center" class="Titulo">Create labels</td>
        </tr>
      </table>
      <table width="300" border="0" align="center">
        <tr>
          <td align="center">Desea crear estas etiquetas? <?php echo $_COOKIE["order_num"]; ?></td>
        </tr>
      </table>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <input name="hidden_max" type="hidden" id="hidden_max" value="<?php echo $order_num; ?>" />
        <input name="hidden_date" type="hidden" id="hidden_date" value="<?php echo date("m.d.y"); ?>" />
        <table width="775" border="1" align="center">
          <tr>
            <td width="139" height="42" bgcolor="#666666" class="cabecera">Importado por:
              <input name="hidden_invoice" type="hidden" id="hidden_invoice" value="<?php echo $row_costu['invoice_id']; ?>" />              <input type="hidden" name="hidden_cos" id="hidden_cos" value="<?php echo $row_costu['cos_id']; ?>" /></td>
            <td width="620" align="center"><?php echo $row_costu['cos_corpname']; ?> <?php echo $row_costu['cos_corpname2']; ?></td>
          </tr>
          <tr>
            <td height="27" bgcolor="#666666" class="cabecera">Origen:
              <input type="hidden" name="hidden_ori" id="hidden_ori" value="<?php echo $row_origen['ori_id']; ?>" /></td>
            <td align="center"><?php echo $row_origen['ori_name']; ?></td>
          </tr>
          <tr>
            <td height="31" bgcolor="#666666" class="cabecera">Fibras:
            <input name="hidden_mat1" type="hidden" id="hidden_mat1" value="<?php echo $row_mat1['stu_id']; ?>" />
            <input name="hidden_mat2" type="hidden" id="hidden_mat2" value="<?php echo $row_mat2['stu_id']; ?>" />
            <input name="hidden_mat3" type="hidden" id="hidden_mat3" value="<?php echo $row_mat3['stu_id']; ?>" />
            <input name="hidden_mat4" type="hidden" id="hidden_mat4" value="<?php echo $row_mat4['stu_id']; ?>" /></td>
            <td align="center" valign="middle"><?php echo $_COOKIE["mat1"]; ?><?php echo "% " . $var_mat1b;
			if (strlen($var_mat2) > 0) {echo "<br>" . $var_mat2 . "% " . $var_mat2b;}
			if (strlen($var_mat3) > 0) {echo "<br>" . $var_mat3 . "% " . $var_mat3b;}
			if (strlen($var_mat4) > 0) {echo "<br>" . $var_mat4 . "% " . $var_mat4b;}
			 ?></td>
          </tr>
          <tr>
            <td height="41" bgcolor="#666666" class="cabecera">Forro:</td>
            <td align="center"><label for="txt_forro"></label>
            <input name="txt_forro" type="text" class="cajagrande" id="txt_forro" size="6" /> 
            % 
            <label for="select_forro"></label>
            <select name="select_forro" class="cajagrande" id="select_forro">
              <?php
do {  
?>
              <option value="<?php echo $row_mat['stu_text']?>"><?php echo $row_mat['stu_text']?></option>
              <?php
} while ($row_mat = mysql_fetch_assoc($mat));
  $rows = mysql_num_rows($mat);
  if($rows > 0) {
      mysql_data_seek($mat, 0);
	  $row_mat = mysql_fetch_assoc($mat);
  }
?>
            </select> <br />
            <input name="txt_forro2" type="text" class="cajagrande" id="txt_forro2" size="6" />
%
<label for="select_forro2"></label>
<select name="select_forro2" class="cajagrande" id="select_forro2">
  <?php
do {  
?>
  <option value="<?php echo $row_mat['stu_text']?>"><?php echo $row_mat['stu_text']?></option>
  <?php
} while ($row_mat = mysql_fetch_assoc($mat));
  $rows = mysql_num_rows($mat);
  if($rows > 0) {
      mysql_data_seek($mat, 0);
	  $row_mat = mysql_fetch_assoc($mat);
  }
?>
</select></td>
          </tr>
          <tr>
            <td height="33" bgcolor="#666666" class="cabecera">Talla:
              <input name="hidden_size" type="hidden" id="hidden_size" value="<?php echo $row_talla['size_id']; ?>" /></td>
            <td align="center"><?php echo $row_talla['size_label']; ?></td>
          </tr>
          <tr>
            <td height="31" bgcolor="#666666" class="cabecera">Lavado:</td>
            <td align="center"><?php
			
			if ($_COOKIE["lav"] <> "") { $var_lav = $_COOKIE["lav"];} else { $var_lav = 1;}
			
			echo $var_lav; ?><select name="select_ins" class="cajagrande_mediana" id="select_ins">
              <?php
do {  
?>
              <option value="<?php  echo $row_ins['par_id']?>"<?php if (!(strcmp($row_ins['par_id'], $var_lav))) {echo "selected=\"selected\"";} ?>><?php echo $row_ins['label']?></option>
              <?php
} while ($row_ins = mysql_fetch_assoc($ins));
  $rows = mysql_num_rows($ins);
  if($rows > 0) {
      mysql_data_seek($ins, 0);
	  $row_ins = mysql_fetch_assoc($ins);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td bgcolor="#666666" class="cabecera">Blanqueado:</td>
            <td align="center"><?php if ($_COOKIE["blan"] <> "") { $var_blan = $_COOKIE["blan"];} else { $var_blan = 2;}
			echo $var_blan;
			 ?><select name="select_blan" class="cajagrande_mediana" id="select_blan">
              <?php
do {  
?>
              <option value="<?php echo $row_blan['id_param']?>"<?php if (!(strcmp($row_blan['id_param'], $var_blan))) {echo "selected=\"selected\"";} ?>><?php echo $row_blan['label']?></option>
              <?php
} while ($row_blan = mysql_fetch_assoc($blan));
  $rows = mysql_num_rows($blan);
  if($rows > 0) {
      mysql_data_seek($blan, 0);
	  $row_blan = mysql_fetch_assoc($blan);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td bgcolor="#666666" class="cabecera">Secado:</td>
            <td align="center"><?php if ($_COOKIE["sec"] <> "") { $var_sec = $_COOKIE["sec"];} else { $var_sec = 1;} echo $var_sec; ?><select name="select_sec" class="cajagrande_mediana" id="select_sec">
              <?php
do {  
?>
              <option value="<?php echo $row_sec['id_param']?>"<?php if (!(strcmp($row_sec['id_param'], $var_sec))) {echo "selected=\"selected\"";} ?>><?php echo $row_sec['label']?></option>
              <?php
} while ($row_sec = mysql_fetch_assoc($sec));
  $rows = mysql_num_rows($sec);
  if($rows > 0) {
      mysql_data_seek($sec, 0);
	  $row_sec = mysql_fetch_assoc($sec);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td bgcolor="#666666" class="cabecera">Planchado:</td>
            <td align="center"><?php if ($_COOKIE["plan"] <> "") { $var_plan = $_COOKIE["plan"];} else { $var_plan = 1;} echo $var_plan; ?><select name="select_plan" class="cajagrande_mediana" id="select_plan">
              <?php
do {  
?>
              <option value="<?php echo $row_plan['id_param']?>"<?php if (!(strcmp($row_plan['id_param'], $var_plan))) {echo "selected=\"selected\"";} ?>><?php echo $row_plan['label']?></option>
              <?php
} while ($row_plan = mysql_fetch_assoc($plan));
  $rows = mysql_num_rows($plan);
  if($rows > 0) {
      mysql_data_seek($plan, 0);
	  $row_plan = mysql_fetch_assoc($plan);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td bgcolor="#666666" class="cabecera">Lavado profesional:</td>
            <td align="center"><?php if ($_COOKIE["pro"] <> "") { $var_pro = $_COOKIE["pro"];} else { $var_pro = 2;} echo $var_pro; ?><select name="select_pro" class="cajagrande_mediana" id="select_pro">
              <?php
do {  
?>
              <option value="<?php echo $row_pro['id_param']?>"<?php if (!(strcmp($row_pro['id_param'], $var_pro))) {echo "selected=\"selected\"";} ?>><?php echo $row_pro['label']?></option>
              <?php
} while ($row_pro = mysql_fetch_assoc($pro));
  $rows = mysql_num_rows($pro);
  if($rows > 0) {
      mysql_data_seek($pro, 0);
	  $row_pro = mysql_fetch_assoc($pro);
  }
?>
            </select></td>
          </tr>
        </table>
        <p>
          <input name="hiddenuser" type="hidden" id="hiddenuser" value="<?php echo $row_Recordset1['user_id']; ?>" />
        </p>
        <table width="211" border="0" align="center">
          <tr>
            <td width="205" align="center" class="cajagrande"><label for="txt_qty"></label>
              <input name="txt_qty" type="text" class="cajagrande" id="txt_qty" value="<?php echo $_COOKIE["qty"]; ?>" size="4" maxlength="3" /></td>
          </tr>
        </table>
        <br />
        <table width="300" border="0" align="center">
          <tr>
            <td align="center"><input type="submit" name="button" id="button" value="     Submit     " /></td>
            <td align="center"><input type="submit" name="button2" id="button2" value="     Cancel     " /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
      <p>&nbsp;</p>
      <p><br />
        <br />
      </p><script type="text/javascript">
<!--
var formObj = document.getElementById('form1');
var inputArr = formObj.getElementsByTagName("input");
for (i=0; i<inputArr.length-1; i++)
{
inputArr[i].onfocus = function()
{
this.style.backgroundColor = "yellow";
};

inputArr[i].onblur = function()
{
this.style.backgroundColor = "";
};
}

-->
</script>

<script type="text/javascript">
function setFocus(){
document.getElementById("txt_forro").focus();
}
</script>
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

mysql_free_result($costu);

mysql_free_result($origen);

mysql_free_result($ins);

mysql_free_result($mat);

mysql_free_result($talla);

mysql_free_result($mat1);

mysql_free_result($mat2);

mysql_free_result($mat3);

mysql_free_result($mat4);

?>
