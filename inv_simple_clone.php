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

$material_var = "";
  if ($_POST['txt_mat1'] <> "") { $material_var = $_POST['txt_mat1'] . "% " . $_POST['select_material']; }
  if ($_POST['txt_mat2'] <> "") { $material_var = $material_var . ", " . $_POST['txt_mat2'] . "% " . $_POST['select_mat2']; }
  if ($_POST['txt_mat3'] <> "") { $material_var = $material_var . ", " . $_POST['txt_mat3'] . "% " . $_POST['select_mat3']; }  
  if ($_POST['txt_mat4'] <> "") { $material_var = $material_var . ", " . $_POST['txt_mat4'] . "% " . $_POST['select_mat4']; }


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
  
  $insertSQL = sprintf("INSERT INTO tbl_inv_simple (invoice_id, box, modelo, des_id, qty, marca_id, ori_id, size_id, material, pieces, precio, costo, pvp, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hidden_inv'], "int"),
                       GetSQLValueString($_POST['txt_box'], "int"),
                       GetSQLValueString(strtoupper($_POST['txt_modelo']), "text"),
                       GetSQLValueString($_POST['select_descripcion'], "int"),
                       GetSQLValueString($_POST['txt_qty'], "int"),
                       GetSQLValueString($_POST['select_marca'], "int"),
                       GetSQLValueString($_POST['select_origen'], "int"),
                       GetSQLValueString($_POST['select_size'], "int"),
                       GetSQLValueString($material_var, "text"),
                       GetSQLValueString($_POST['txt_pieces'], "int"),
                       GetSQLValueString($_POST['txt_precio'], "double"),
                       GetSQLValueString($_POST['txt_costo'], "double"),
                       GetSQLValueString($_POST['txt_pvp'], "double"),
                       GetSQLValueString($_POST['hidden_user'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
  
  
 //pass variables
  setcookie("varbox", $_POST['txt_box']);
  setcookie("mat1", $_POST['txt_mat1']);
  setcookie("mat1b", $_POST['select_material']);
  
  setcookie("mat2", $_POST['txt_mat2']);
  setcookie("mat2b", $_POST['select_mat2']);
  
  setcookie("mat3", $_POST['txt_mat3']);
  setcookie("mat3b", $_POST['select_mat3']);
  
  setcookie("mat4", $_POST['txt_mat4']);
  setcookie("mat4b", $_POST['select_mat4']);
  
  setcookie("ori_id", $_POST['select_origen']);
  setcookie("size", $_POST['select_size']);
  setcookie("qty", $_POST['txt_qty'] * $_POST['txt_pieces']);
  
  setcookie("lav", $_POST['hidden_lav']);
  setcookie("blan", $_POST['hidden_blan']);
  setcookie("sec", $_POST['hidden_sec']);
  setcookie("plan", $_POST['hidden_plan']);
  setcookie("pro", $_POST['hidden_pro']);


  $insertGoTo = "create_labels.php";
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

mysql_select_db($database_labels_con, $labels_con);
$query_size = "SELECT * FROM tbl_sizes ORDER BY size_label ASC";
$size = mysql_query($query_size, $labels_con) or die(mysql_error());
$row_size = mysql_fetch_assoc($size);
$totalRows_size = mysql_num_rows($size);

$colname_instruc = "-1";
if (isset($_GET['inv_id'])) {
  $colname_instruc = $_GET['inv_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_instruc = sprintf("SELECT simple_id, id_lavado, id_blan, id_sec, id_plan, id_pro FROM tbl_order WHERE simple_id = %s", GetSQLValueString($colname_instruc, "int"));
$instruc = mysql_query($query_instruc, $labels_con) or die(mysql_error());
$row_instruc = mysql_fetch_assoc($instruc);
$totalRows_instruc = mysql_num_rows($instruc);
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
          <td width="531" align="center" class="Titulo">Item clone</td>
        </tr>
      </table>
      <br />
      <br />
      <br />
      <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
        <table width="919" border="1" align="center" cellpadding="3" cellspacing="3">
          <tr>
            <td colspan="2">Box:
              <input name="hidden_inv" type="hidden" id="hidden_inv" value="<?php echo $row_item['invoice_id']; ?>" />
            <input name="txt_box" type="text" class="cajagrande" id="txt_box" value="<?php echo $_COOKIE["varbox"]; ?>" size="10" maxlength="3" /><?php echo $_SESSION['boxvar']; ?></td>
            <td>Qty:
              <input name="txt_qty" type="text" class="cajagrande" id="txt_qty" value="<?php echo $row_item['qty']; ?>" size="7" maxlength="4" /></td>
            <td>Talla</td>
            <td><label for="select_size"></label>
              <select name="select_size" class="cajagrande" id="select_size">
                <?php
do {  
?>
                <option value="<?php echo $row_size['size_id']?>"<?php if (!(strcmp($row_size['size_id'], $row_item['size_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_size['size_label']?></option>
                <?php
} while ($row_size = mysql_fetch_assoc($size));
  $rows = mysql_num_rows($size);
  if($rows > 0) {
      mysql_data_seek($size, 0);
	  $row_size = mysql_fetch_assoc($size);
  }
?>
              </select>              
            <label for="txt_verif"></label></td>
            <td>Model/style:</td>
            <td><input name="txt_modelo" type="text" class="cajagrande" id="txt_modelo" value="<?php echo $row_item['modelo']; ?>" size="16" maxlength="20" /></td>
          </tr>
          <tr>
            <td width="100"><input name="hidden_lav" type="hidden" id="hidden_lav" value="<?php echo $row_instruc['id_lavado']; ?>" />
            <input name="hidden_blan" type="hidden" id="hidden_blan" value="<?php echo $row_instruc['id_blan']; ?>" />
            <input name="hidden_sec" type="hidden" id="hidden_sec" value="<?php echo $row_instruc['id_sec']; ?>" />
            <input name="hidden_plan" type="hidden" id="hidden_plan" value="<?php echo $row_instruc['id_plan']; ?>" />
            <input name="hidden_pro" type="hidden" id="hidden_pro" value="<?php echo $row_instruc['id_pro']; ?>" /></td>
            <td width="61">Pieces:
            <label>
              <input name="txt_pieces" type="text" class="cajagrande" id="txt_pieces" value="<?php echo $row_item['pieces']; ?>" size="3" maxlength="1" />
            </label></td>
            <td width="118">Descripcion:</td>
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
            <td width="119">Origin:</td>
            <td width="218"><select name="select_origen" class="cajagrande" id="select_origen">
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
            <td height="99">Components:</td>
            <td colspan="6"><p><span class="cajagrande"><?php echo $row_item['material']; ?></span>&nbsp;
                <input name="hidden_material" type="hidden" id="hidden_material" value="<?php echo $row_item['material']; ?>" />
            </p>
              <p>
                <input name="txt_mat1" type="text" class="cajagrande" id="txt_mat1" size="6" maxlength="30" autocomplete="off"/>
              %
              <select name="select_material" class="cajagrande" id="select_material">
                <?php
do {  
?>
                <option value="<?php echo $row_material['stu_text']?>"><?php echo $row_material['stu_text']?></option>
                <?php
} while ($row_material = mysql_fetch_assoc($material));
  $rows = mysql_num_rows($material);
  if($rows > 0) {
      mysql_data_seek($material, 0);
	  $row_material = mysql_fetch_assoc($material);
  }
?>
              </select>
              -
  <label>
    <input name="txt_mat2" type="text" class="cajagrande" id="txt_mat2" size="6" maxlength="30" autocomplete="off" />
    %
    <select name="select_mat2" class="cajagrande" id="select_mat2">
      <?php
do {  
?>
      <option value="<?php echo $row_material['stu_text']?>"><?php echo $row_material['stu_text']?></option>
      <?php
} while ($row_material = mysql_fetch_assoc($material));
  $rows = mysql_num_rows($material);
  if($rows > 0) {
      mysql_data_seek($material, 0);
	  $row_material = mysql_fetch_assoc($material);
  }
?>
      </select>
    <br />
    <input name="txt_mat3" type="text" class="cajagrande" id="txt_mat3" size="6" maxlength="30" autocomplete="off" />
    %
    <select name="select_mat3" class="cajagrande" id="select_mat3">
      <?php
do {  
?>
      <option value="<?php echo $row_material['stu_text']?>"><?php echo $row_material['stu_text']?></option>
      <?php
} while ($row_material = mysql_fetch_assoc($material));
  $rows = mysql_num_rows($material);
  if($rows > 0) {
      mysql_data_seek($material, 0);
	  $row_material = mysql_fetch_assoc($material);
  }
?>
      </select>
    -
    <input name="txt_mat4" type="text" class="cajagrande" id="txt_mat4" size="6" maxlength="30" autocomplete="off" />
    %
    <select name="select_mat4" class="cajagrande" id="select_mat4">
      <?php
do {  
?>
      <option value="<?php echo $row_material['stu_text']?>"><?php echo $row_material['stu_text']?></option>
      <?php
} while ($row_material = mysql_fetch_assoc($material));
  $rows = mysql_num_rows($material);
  if($rows > 0) {
      mysql_data_seek($material, 0);
	  $row_material = mysql_fetch_assoc($material);
  }
?>
      </select>
  </label>
            <input type="submit" name="button" id="button" value="    Grabar    " />
            </p></td>
          </tr>
          <tr>
            <td>Unit Price:</td>
            <td colspan="2"><input name="txt_precio" type="text" id="txt_precio" value="<?php echo $row_item['precio']; ?>" size="10" maxlength="6" /></td>
            <td width="87">Cost:
            <label>
              <input name="txt_costo" type="text" id="txt_costo" value="<?php echo $row_item['costo']; ?>" size="10" maxlength="6" />
            </label></td>
            <td width="134">PVP 
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
            <td><input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_Recordset1['user_id']; ?>" /></td>
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
document.getElementById("txt_qty").focus();
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

mysql_free_result($size);

mysql_free_result($instruc);
?>
