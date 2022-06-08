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
  
  $_SESSION['boxvar'] = $_REQUEST['txt_box'];
  $material_var = "";
  if ($_POST['txt_mat1'] <> "") { $material_var = $_POST['txt_mat1'] . "% " . $_POST['select_material']; }
  if ($_POST['txt_mat2'] <> "") { $material_var = $material_var . ", " . $_POST['txt_mat2'] . "% " . $_POST['select_mat2']; }
  if ($_POST['txt_mat3'] <> "") { $material_var = $material_var . ", " . $_POST['txt_mat3'] . "% " . $_POST['select_mat3']; }  
  if ($_POST['txt_mat4'] <> "") { $material_var = $material_var . ", " . $_POST['txt_mat4'] . "% " . $_POST['select_mat4']; }
  
  $insertSQL = sprintf("INSERT INTO tbl_inv_simple (invoice_id, box, modelo, des_id, qty, marca_id, ori_id, material, precio, costo, pvp, user_id, pieces) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['hidden_inv'], "int"),
                       GetSQLValueString($_POST['txt_box'], "text"),
                       GetSQLValueString($_POST['txt_modelo'], "text"),
                       GetSQLValueString($_POST['select_descripcion'], "int"),
                       GetSQLValueString($_POST['txt_qty'], "int"),
                       GetSQLValueString($_POST['select_marca'], "int"),
                       GetSQLValueString($_POST['select_origen'], "int"),
                       GetSQLValueString($material_var, "text"),
                       GetSQLValueString($_POST['txt_precio'], "double"),
                       GetSQLValueString($_POST['text_costo'], "double"),
					   GetSQLValueString($_POST['txt_pvp'], "double"),
                       GetSQLValueString($_POST['hidden_user'], "int"),
                       GetSQLValueString($_POST['text_pieces'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
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
if (isset($_GET['invoice_id'])) {
  $colname_invoice = $_GET['invoice_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_invoice = sprintf("SELECT inv.*, cos.cos_names, cos.cos_corpname, cos.cos_corpname2, cos.cos_ruc FROM tbl_invoice inv, tbl_costumers cos WHERE inv.cos_id=cos.cos_id and inv.invoice_id = %s", GetSQLValueString($colname_invoice, "int"));
$invoice = mysql_query($query_invoice, $labels_con) or die(mysql_error());
$row_invoice = mysql_fetch_assoc($invoice);
$totalRows_invoice = mysql_num_rows($invoice);

$colname_inv_simple = "-1";
if (isset($_GET['invoice_id'])) {
  $colname_inv_simple = $_GET['invoice_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_inv_simple = sprintf("SELECT inv.*, des.des_text, mar.marca_text, ori.ori_name FROM tbl_inv_simple inv, tbl_description des, tbl_marcas mar, tbl_origin ori WHERE inv.marca_id=mar.marca_id and inv.des_id=des.des_id and inv.ori_id=ori.ori_id and inv.invoice_id = %s ORDER BY inv.inv_id DESC LIMIT 50 ", GetSQLValueString($colname_inv_simple, "int"));
$inv_simple = mysql_query($query_inv_simple, $labels_con) or die(mysql_error());
$row_inv_simple = mysql_fetch_assoc($inv_simple);
$totalRows_inv_simple = mysql_num_rows($inv_simple);

mysql_select_db($database_labels_con, $labels_con);
$query_marcas = "SELECT mar.* FROM tbl_marcas mar, tbl_invoice inv, tbl_costumers cos  WHERE inv.invoice_id = 1 AND mar.cos_id = inv.cos_id  ORDER BY mar.marca_text ASC";
$marcas = mysql_query($query_marcas, $labels_con) or die(mysql_error());
$row_marcas = mysql_fetch_assoc($marcas);
$totalRows_marcas = mysql_num_rows($marcas);

mysql_select_db($database_labels_con, $labels_con);
$query_description = "SELECT * FROM tbl_description ORDER BY des_text ASC";
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

$colname_status = "-1";
if (isset($_GET['invoice_id'])) {
  $colname_status = $_GET['invoice_id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_status = sprintf("SELECT * FROM tbl_invoice WHERE status=0 AND invoice_id = %s", GetSQLValueString($colname_status, "int"));
$status = mysql_query($query_status, $labels_con) or die(mysql_error());
$row_status = mysql_fetch_assoc($status);
$totalRows_status = mysql_num_rows($status);

mysql_select_db($database_labels_con, $labels_con);
$query_etiqtotal = "SELECT SUM(`qty` * `pieces`) FROM `tbl_inv_simple` WHERE invoice_id =" . $_GET['invoice_id'] . "";
$etiqtotal = mysql_query($query_etiqtotal, $labels_con) or die(mysql_error());
$row_etiqtotal = mysql_fetch_assoc($etiqtotal);
$totalRows_etiqtotal = mysql_num_rows($etiqtotal);
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
          <td width="531" align="center" class="Titulo">Creacion de invoice simple</td>
        </tr>
      </table>
      <br />
      <table width="521" border="1" align="center">
        <tr>
          <td width="119" bgcolor="#333333" class="cabecera">Invoice No.</td>
          <td colspan="3"><?php echo str_pad((int) ($row_invoice['invoice_id']),6,"0",STR_PAD_LEFT); ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Costumer:</td>
          <td colspan="3"><?php echo $row_invoice['cos_names']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Company:</td>
          <td colspan="3"><?php echo $row_invoice['cos_corpname']; ?>  <?php echo $row_invoice['cos_corpname2']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#333333" class="cabecera">Etiquetas</td>
          <td width="123"><?php echo $row_etiqtotal['SUM(`qty` * `pieces`)']; ?></td>
          <td width="131" bgcolor="#333333" class="cabecera">Total facturar</td>
          <td width="120"><?php echo ($row_etiqtotal['SUM(`qty` * `pieces`)']) * $row_invoice['precio']; ?></td>
        </tr>
      </table>
      <br />
      <table width="628" border="0" align="center">
        <?php if ($totalRows_status == 0) { // Show if recordset empty ?>
  <tr>
    <td align="center">Esta factura esta cerrada y no se pueden agregar mas items.</td>
  </tr>
  <?php } // Show if recordset empty ?>
      </table>
      <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
        <?php if ($totalRows_status > 0) { // Show if recordset not empty ?>
  <table width="929" border="1" align="center">
    <tr>
      <td width="83">Box:
        <input name="hidden_inv" type="hidden" id="hidden_inv" value="<?php echo $row_invoice['invoice_id']; ?>" /></td>
      <td width="73"><label>
        <input name="txt_box" type="text" class="cajagrande" id="txt_box" value="<?php echo $_SESSION['boxvar']; ?>" size="4" maxlength="3" />
        </label></td>
      <td width="76">Model/style:</td>
      <td colspan="2"><input name="txt_modelo" type="text" class="cajagrande" id="txt_modelo" size="13" maxlength="30" /></td>
      <td colspan="2">Qty:
        <input name="txt_qty" type="text" class="cajagrande" id="txt_qty" size="4" maxlength="4" /></td>
      <td width="177">Pieces: 
        <label>
          <input name="text_pieces" type="text" class="cajagrande" id="text_pieces" value="1" size="5" maxlength="1" />
          </label></td>
      </tr>
    <tr>
      <td>Descripcion:</td>
      <td><select name="select_descripcion" class="cajagrande" id="select_descripcion">
        <?php
do {  
?>
        <option value="<?php echo $row_description['des_id']?>"><?php echo $row_description['des_text']?></option>
        <?php
} while ($row_description = mysql_fetch_assoc($description));
  $rows = mysql_num_rows($description);
  if($rows > 0) {
      mysql_data_seek($description, 0);
	  $row_description = mysql_fetch_assoc($description);
  }
?>
        </select></td>
      <td>Origin:</td>
      <td colspan="3"><select name="select_origen" class="cajagrande" id="select_origen">
        <?php
do {  
?>
        <option value="<?php echo $row_origen['ori_id']?>"><?php echo $row_origen['ori_name']?></option>
        <?php
} while ($row_origen = mysql_fetch_assoc($origen));
  $rows = mysql_num_rows($origen);
  if($rows > 0) {
      mysql_data_seek($origen, 0);
	  $row_origen = mysql_fetch_assoc($origen);
  }
?>
        </select></td>
      <td width="120" align="center"><a href="/labels/inv_simple2.php?invoice_id=<?php echo $row_invoice['invoice_id']; ?>&orden=inv.inv_id" target="_blank">Ver todo</a></td>
      <td align="center"><a href="/labels/prices_search.php?id=<?php echo $row_invoice['cos_id']; ?>" target="_blank">Prices</a></td>
      </tr>
    <tr>
      <td>Components:</td>
      <td colspan="7"><input name="txt_mat1" type="text" class="cajagrande" id="txt_mat1" size="15" maxlength="30" />
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
          <input name="txt_mat2" type="text" class="cajagrande" id="txt_mat2" size="15" maxlength="30" />
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
<input name="txt_mat3" type="text" class="cajagrande" id="txt_mat3" size="15" maxlength="30" />
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
          <input name="txt_mat4" type="text" class="cajagrande" id="txt_mat4" size="15" maxlength="30" />
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
          </label></td>
      </tr>
    <tr>
      <td>Brand:</td>
      <td colspan="2"><select name="select_marca" class="cajagrande" id="select_marca">
        <?php
do {  
?>
        <option value="<?php echo $row_marcas['marca_id']?>"><?php echo $row_marcas['marca_text']?></option>
        <?php
} while ($row_marcas = mysql_fetch_assoc($marcas));
  $rows = mysql_num_rows($marcas);
  if($rows > 0) {
      mysql_data_seek($marcas, 0);
	  $row_marcas = mysql_fetch_assoc($marcas);
  }
?>
        </select></td>
      <td width="238">Unit Price:              <input name="txt_precio" type="text" class="cajagrande" id="txt_precio" value="0" size="6" maxlength="6" /></td>
      <td colspan="2">Cost:
        <input name="text_costo" type="text" class="cajagrande" id="text_costo" value="0" size="6" maxlength="5" /></td>
      <td>PVP
        <label for="txt_pvp"></label>
        <input name="txt_pvp" type="text" class="cajagrande" id="txt_pvp" value="0" size="5" maxlength="5" /></td>
      <td><input type="submit" class="cajagrande" name="button" id="button" value="    Grabar    " />
        <input name="hidden_user" type="hidden" id="hidden_user" value="<?php echo $row_Recordset1['user_id']; ?>" /></td>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?>
<input type="hidden" name="MM_insert" value="form1" />
      </form>
      <br />
      <br />
      <table width="1153" border="1" align="center">
        <?php if ($totalRows_inv_simple > 0) { // Show if recordset not empty ?>
  <tr bgcolor="#333333" class="cabecera">
    <td width="34">Box</td>
    <td width="119">Brand</td>
    <td width="94">Model/Style</td>
    <td width="151">Description</td>
    <td width="37">Qty</td>
    <td width="37">Pieces</td>
    <td width="107">Origin</td>
    <td width="195">Components</td>
    <td width="69">Precio</td>
    <td width="76">Costo</td>
    <td width="79">Edit</td>
    <td width="79">Eliminar</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_inv_simple['box']; ?></td>
      <td><?php echo $row_inv_simple['marca_text']; ?></td>
      <td><?php echo $row_inv_simple['modelo']; ?></td>
      <td align="center"><?php echo $row_inv_simple['des_text']; ?></td>
      <td align="center"><?php echo $row_inv_simple['qty']; ?></td>
      <td align="center"><?php echo $row_inv_simple['pieces']; ?></td>
      <td><?php echo $row_inv_simple['ori_name']; ?></td>
      <td><?php echo $row_inv_simple['material']; ?></td>
      <td align="right"><?php echo $row_inv_simple['precio']; ?></td>
      <td align="right"><?php echo $row_inv_simple['costo']; ?></td>
      <td align="center"><a href="inv_simple_edit.php?inv_id=<?php echo $row_inv_simple['inv_id']; ?>&amp;invoice_id=<?php echo $row_inv_simple['invoice_id']; ?>"><img src="editi.png" width="16" height="16" border="0" /></a></td>
      <td align="center"><a href="inv_del_item.php?inv_id=<?php echo $row_inv_simple['inv_id']; ?>&amp;invoice_id=<?php echo $row_inv_simple['invoice_id']; ?>&amp;cliente=<?php echo $row_invoice['cos_names']; ?>"><img src="delete.png" width="16" height="16" border="0" /></a></td>
    </tr>
    <?php } while ($row_inv_simple = mysql_fetch_assoc($inv_simple)); ?>
<?php } // Show if recordset not empty ?>
      </table>
<br />
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
document.getElementById("txt_modelo").focus();
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

mysql_free_result($invoice);

mysql_free_result($inv_simple);

mysql_free_result($marcas);

mysql_free_result($description);

mysql_free_result($origen);

mysql_free_result($material);

mysql_free_result($status);

mysql_free_result($etiqtotal);
?>
