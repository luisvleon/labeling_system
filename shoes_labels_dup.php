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
  $insertSQL = sprintf("INSERT INTO tbl_shoes_labels (invoice_id, costumer, estilo, `size`, marca, capellada_text, forro_text, plantilla_text, zuela_text, origin, `user`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hidden_inv'], "int"),
                       GetSQLValueString($_POST['hidden_cos'], "int"),
                       GetSQLValueString($_POST['txt_estilo'], "text"),
                       GetSQLValueString($_POST['txt_size'], "text"),
                       GetSQLValueString($_POST['select_marca'], "int"),
                       GetSQLValueString($_POST['txt_capellada'], "text"),
                       GetSQLValueString($_POST['txt_forro'], "text"),
                       GetSQLValueString($_POST['txt_plantilla'], "text"),
                       GetSQLValueString($_POST['txt_suela'], "text"),
                       GetSQLValueString($_POST['select_origin'], "int"),
                       GetSQLValueString($_POST['hidden_user'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());

  $insertGoTo = "shoes_label_qty.php?id=" . $row_label['id_shoe_label'] . "&inv=" . $row_label['invoice_id'] . "";
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
$query_maximo = "SELECT MAX(id_shoe_label) maximo FROM tbl_shoes_labels";
$maximo = mysql_query($query_maximo, $labels_con) or die(mysql_error());
$row_maximo = mysql_fetch_assoc($maximo);
$totalRows_maximo = mysql_num_rows($maximo);

$colname_costu = "-1";
if (isset($_REQUEST['cos'])) {
  $colname_costu = $_REQUEST['cos'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costu = sprintf("SELECT cos_id, cos_names FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_costu, "int"));
$costu = mysql_query($query_costu, $labels_con) or die(mysql_error());
$row_costu = mysql_fetch_assoc($costu);
$totalRows_costu = mysql_num_rows($costu);

mysql_select_db($database_labels_con, $labels_con);
$query_ori = "SELECT * FROM tbl_origin ORDER BY ori_name ASC";
$ori = mysql_query($query_ori, $labels_con) or die(mysql_error());
$row_ori = mysql_fetch_assoc($ori);
$totalRows_ori = mysql_num_rows($ori);

mysql_select_db($database_labels_con, $labels_con);
$query_cape = "SELECT id_mat, mat_text FROM tbl_shoes_mat_cape ORDER BY mat_text ASC";
$cape = mysql_query($query_cape, $labels_con) or die(mysql_error());
$row_cape = mysql_fetch_assoc($cape);
$totalRows_cape = mysql_num_rows($cape);

mysql_select_db($database_labels_con, $labels_con);
$query_forro = "SELECT * FROM tbl_shoes_mat_forro ORDER BY text_forro ASC";
$forro = mysql_query($query_forro, $labels_con) or die(mysql_error());
$row_forro = mysql_fetch_assoc($forro);
$totalRows_forro = mysql_num_rows($forro);

mysql_select_db($database_labels_con, $labels_con);
$query_plan = "SELECT * FROM tbl_shoes_mat_plan ORDER BY text_plan ASC";
$plan = mysql_query($query_plan, $labels_con) or die(mysql_error());
$row_plan = mysql_fetch_assoc($plan);
$totalRows_plan = mysql_num_rows($plan);

mysql_select_db($database_labels_con, $labels_con);
$query_zuela = "SELECT * FROM tbl_shoes_mat_zuela ORDER BY text_zuela ASC";
$zuela = mysql_query($query_zuela, $labels_con) or die(mysql_error());
$row_zuela = mysql_fetch_assoc($zuela);
$totalRows_zuela = mysql_num_rows($zuela);

$colname_label = "-1";
if (isset($_GET['lab'])) {
  $colname_label = $_GET['lab'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_label = sprintf("SELECT * FROM tbl_shoes_labels WHERE id_shoe_label = %s", GetSQLValueString($colname_label, "int"));
$label = mysql_query($query_label, $labels_con) or die(mysql_error());
$row_label = mysql_fetch_assoc($label);
$totalRows_label = mysql_num_rows($label);

mysql_select_db($database_labels_con, $labels_con);
$query_marcas = "SELECT * FROM tbl_marcas ORDER BY marca_text ASC";
$marcas = mysql_query($query_marcas, $labels_con) or die(mysql_error());
$row_marcas = mysql_fetch_assoc($marcas);
$totalRows_marcas = mysql_num_rows($marcas);
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
    <td width="814" height="652" valign="top"><!-- InstanceBeginEditable name="edit1" -->
      <table width="537" border="0" align="center">
        <tr>
          <td width="531" align="center" class="Titulo"><br />
          Duplicate shoes labels<br />
          <br /></td>
        </tr>
      </table>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="708" border="1" align="center">
          <tr>
            <td width="123" bgcolor="#333333" class="cabecera">Costumer:</td>
            <td colspan="3" class="cajagrande"><?php echo $row_costu['cos_names']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Origin:</td>
            <td colspan="3"><label>
              <select name="select_origin" class="cajagrande" id="select_origin">
                <?php
do {  
?>
                <option value="<?php echo $row_ori['ori_id']?>"<?php if (!(strcmp($row_ori['ori_id'], $row_label['origin']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ori['ori_name']?></option>
                <?php
} while ($row_ori = mysql_fetch_assoc($ori));
  $rows = mysql_num_rows($ori);
  if($rows > 0) {
      mysql_data_seek($ori, 0);
	  $row_ori = mysql_fetch_assoc($ori);
  }
?>
              </select>
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Estilo:</td>
            <td width="421"><label>
              <input name="txt_estilo" type="text" class="cajagrande" id="txt_estilo" value="<?php echo $row_label['estilo']; ?>" />
            </label>
              <label for="txt_size2"></label>
              <label> </label></td>
            <td width="49"><span class="cabecera">Size:</span></td>
            <td width="219"><input name="txt_size" type="text" class="cajagrandeSIN" id="txt_size2" value="<?php echo $row_label['size']; ?>" size="8" maxlength="20" /></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Marca:</td>
            <td colspan="3"><label for="select_marca"></label>
              <select name="select_marca" class="cajagrande" id="select_marca">
                <?php
do {  
?>
                <option value="<?php echo $row_marcas['marca_id']?>"<?php if (!(strcmp($row_marcas['marca_id'], $row_label['marca']))) {echo "selected=\"selected\"";} ?>><?php echo $row_marcas['marca_text']?></option>
                <?php
} while ($row_marcas = mysql_fetch_assoc($marcas));
  $rows = mysql_num_rows($marcas);
  if($rows > 0) {
      mysql_data_seek($marcas, 0);
	  $row_marcas = mysql_fetch_assoc($marcas);
  }
?>
              </select></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Capellada:</td>
            <td colspan="3"><label>
              <input name="txt_capellada" type="text" class="cajagrande" id="txt_capellada" value="<?php echo $row_label['capellada_text']; ?>" size="35" maxlength="50" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Forro:</td>
            <td colspan="3"><label>
              <input name="txt_forro" type="text" class="cajagrande" id="txt_forro" value="<?php echo $row_label['forro_text']; ?>" size="35" maxlength="50" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Plantilla:</td>
            <td colspan="3"><label>
              <input name="txt_plantilla" type="text" class="cajagrande" id="txt_plantilla" value="<?php echo $row_label['plantilla_text']; ?>" size="35" maxlength="50" />
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Zuela:</td>
            <td colspan="3"><label>
              <input name="txt_suela" type="text" class="cajagrande" id="txt_suela" value="<?php echo $row_label['zuela_text']; ?>" size="35" maxlength="50" />
            </label></td>
          </tr>
          <tr>
            <td height="46" bgcolor="#333333" class="cabecera">Show WasH&gt;</td>
            <td colspan="3"><label for="select_show"></label>
              <select name="select_show" class="cajagrande" id="select_show">
                <option value="0">No</option>
                <option value="1">Yes</option>
              </select></td>
          </tr>
          <tr>
            <td height="46" bgcolor="#333333" class="cabecera"><input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_Recordset1['user_id']; ?>" />
              <input name="hidden_maximo" type="hidden" id="hidden_maximo" value="<?php echo ($row_maximo['MAX(id_shoe_label)'] +1); ?>" />
              <input name="hidden_cos" type="hidden" id="hidden_cos" value="<?php echo $row_costu['cos_id']; ?>" />
              <input name="hidden_inv" type="hidden" id="hidden_inv" value="<?php echo $row_label['invoice_id']; ?>" /></td>
            <td colspan="3"><label>
              <input name="button" type="submit" class="cajagrande" id="button" value="Submit" />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
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

mysql_free_result($maximo);

mysql_free_result($costu);

mysql_free_result($ori);

mysql_free_result($cape);

mysql_free_result($forro);

mysql_free_result($plan);

mysql_free_result($zuela);

mysql_free_result($label);

mysql_free_result($marcas);
?>
