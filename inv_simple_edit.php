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
  $updateSQL = sprintf("UPDATE tbl_inv_simple SET box=%s, modelo=%s, des_id=%s, qty=%s,  verificado=%s, pieces=%s, marca_id=%s, ori_id=%s, material=%s, precio=%s, costo=%s, pvp=%s, user_id=%s WHERE inv_id=%s",
                       GetSQLValueString($_POST['txt_box'], "text"),
                       GetSQLValueString(strtoupper($_POST['txt_modelo']), "text"),
                       GetSQLValueString($_POST['select_descripcion'], "int"),
                       GetSQLValueString($_POST['txt_qty'], "int"),
					   GetSQLValueString($_POST['txt_verif'], "int"),
					   GetSQLValueString($_POST['txt_pieces'], "int"),
                       GetSQLValueString($_POST['select_marca'], "int"),
                       GetSQLValueString($_POST['select_origen'], "int"),
                       GetSQLValueString($_POST['txt_mat1'], "text"),
                       GetSQLValueString($_POST['txt_precio'], "double"),
					   GetSQLValueString($_POST['txt_costo'], "double"),
					   GetSQLValueString($_POST['txt_pvp'], "double"),
                       GetSQLValueString($_POST['hidden_user'], "int"),
                       GetSQLValueString($_POST['hidden_inv'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());

  $updateGoTo = "inv_simple.php";
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
$query_description = "SELECT des.* FROM tbl_description des, tbl_invoice inv WHERE inv.activo =1 AND des.cos_id = inv.cos_id ORDER BY des_text ASC";
$description = mysql_query($query_description, $labels_con) or die(mysql_error());
$row_description = mysql_fetch_assoc($description);
$totalRows_description = mysql_num_rows($description);

mysql_select_db($database_labels_con, $labels_con);
$query_origen = "SELECT * FROM tbl_origin ORDER BY ori_name ASC";
$origen = mysql_query($query_origen, $labels_con) or die(mysql_error());
$row_origen = mysql_fetch_assoc($origen);
$totalRows_origen = mysql_num_rows($origen);

mysql_select_db($database_labels_con, $labels_con);
$query_material = "SELECT * FROM tbl_stuff ORDER BY stu_text ASC";
$material = mysql_query($query_material, $labels_con) or die(mysql_error());
$row_material = mysql_fetch_assoc($material);
$totalRows_material = mysql_num_rows($material);

mysql_select_db($database_labels_con, $labels_con);
$query_marcas = "SELECT mar.* FROM tbl_marcas mar, tbl_invoice inv WHERE inv.activo = 1 AND mar.cos_id = inv.cos_id ORDER BY mar.marca_text ASC";
$marcas = mysql_query($query_marcas, $labels_con) or die(mysql_error());
$row_marcas = mysql_fetch_assoc($marcas);
$totalRows_marcas = mysql_num_rows($marcas);

$colname_item = "-1";
if (isset($_GET['inv_id'])) {
  $colname_item = $_GET['inv_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_item = sprintf("SELECT * FROM tbl_inv_simple WHERE inv_id = %s", GetSQLValueString($colname_item, "int"));
$item = mysql_query($query_item, $labels_con) or die(mysql_error());
$row_item = mysql_fetch_assoc($item);
$totalRows_item = mysql_num_rows($item);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema de etiquetado</title>
<!-- InstanceEndEditable -->
<link href="css/labels.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body onload="setFocus();">
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
          <td width="531" align="center" class="Titulo">Editar Item</td>
        </tr>
      </table>
      <br />
      <br />
      <br />
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="919" border="1" align="center" cellpadding="3" cellspacing="3">
          <tr>
            <td colspan="2">Box:
              <input name="hidden_inv" type="hidden" id="hidden_inv" value="<?php echo $row_item['inv_id']; ?>" />
              <input name="txt_box" type="text" class="cajagrande" id="txt_box" value="<?php echo $row_item['box']; ?>" size="10" maxlength="3" /></td>
            <td>Qty:
              <input name="txt_qty" type="text" class="cajagrande" id="txt_qty" value="<?php echo $row_item['qty']; ?>" size="7" maxlength="4" /></td>
            <td>Verificado</td>
            <td><label for="txt_verif"></label>
            <input name="txt_verif" type="text" class="cajagrande" id="txt_verif" value="<?php echo $row_item['verificado']; ?>" size="5" maxlength="3" /></td>
            <td>Model/style:</td>
            <td><input name="txt_modelo" type="text" class="cajagrande" id="txt_modelo" value="<?php echo $row_item['modelo']; ?>" size="12" maxlength="20" /></td>
          </tr>
          <tr>
            <td width="82">&nbsp;</td>
            <td width="51">Pieces:
            <label>
              <input name="txt_pieces" type="text" class="cajagrande" id="txt_pieces" value="<?php echo $row_item['pieces']; ?>" size="3" maxlength="1" />
            </label></td>
            <td width="75">Descripcion:</td>
            <td colspan="2"><select name="select_descripcion" id="select_descripcion">
              <?php
do {  
?>
              <option value="<?php echo $row_description['des_id']?>"<?php if (!(strcmp($row_description['des_id'], $row_item['des_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_description['des_text']?></option>
              <?php
} while ($row_description = mysql_fetch_assoc($description));
  $rows = mysql_num_rows($description);
  if($rows > 0) {
      mysql_data_seek($description, 0);
	  $row_description = mysql_fetch_assoc($description);
  }
?>
            </select></td>
            <td width="185">Origin:</td>
            <td width="237"><select name="select_origen" class="cajagrande" id="select_origen">
              <?php
do {  
?>
              <option value="<?php echo $row_origen['ori_id']?>"<?php if (!(strcmp($row_origen['ori_id'], $row_item['ori_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_origen['ori_name']?></option>
              <?php
} while ($row_origen = mysql_fetch_assoc($origen));
  $rows = mysql_num_rows($origen);
  if($rows > 0) {
      mysql_data_seek($origen, 0);
	  $row_origen = mysql_fetch_assoc($origen);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td>Components:</td>
            <td colspan="6"><input name="txt_mat1" type="text" id="txt_mat1" value="<?php echo $row_item['material']; ?>" size="80" maxlength="150" />
              *Debe escribir a mano los componentes</td>
          </tr>
          <tr>
            <td>Unit Price:</td>
            <td colspan="2"><input name="txt_precio" type="text" id="txt_precio" value="<?php echo $row_item['precio']; ?>" size="10" maxlength="6" /></td>
            <td width="100">Cost:
            <label>
              <input name="txt_costo" type="text" id="txt_costo" value="<?php echo $row_item['costo']; ?>" size="10" maxlength="6" />
            </label></td>
            <td width="107">PVP 
              <label for="txt_pvp"></label>
            <input name="txt_pvp" type="text" id="txt_pvp" value="<?php echo $row_item['pvp']; ?>" size="10" maxlength="6" /></td>
            <td>Brand:
              <select name="select_marca" id="select_marca">
                <?php
do {  
?>
                <option value="<?php echo $row_marcas['marca_id']?>"<?php if (!(strcmp($row_marcas['marca_id'], $row_item['marca_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_marcas['marca_text']?></option>
                <?php
} while ($row_marcas = mysql_fetch_assoc($marcas));
  $rows = mysql_num_rows($marcas);
  if($rows > 0) {
      mysql_data_seek($marcas, 0);
	  $row_marcas = mysql_fetch_assoc($marcas);
  }
?>
            </select></td>
            <td><input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_Recordset1['user_id']; ?>" />
              <input type="submit" name="button" id="button" value="    Grabar    " /></td>
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
<script type="text/javascript">
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
document.getElementById("txt_verif").focus();
}
</script>
</body>
<!-- InstanceEnd -->
<?php
mysql_free_result($description);

mysql_free_result($origen);

mysql_free_result($material);

mysql_free_result($marcas);

mysql_free_result($Recordset1);

mysql_free_result($item);
?>
