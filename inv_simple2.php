<?php require_once('Connections/labels_con.php'); ?>
<?php if (!isset($_SESSION)) {
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

$query_invoice = sprintf("SELECT inv.*, cos.cos_names, cos.cos_corpname, cos.cos_ruc FROM tbl_invoice inv, tbl_costumers cos  WHERE inv.cos_id=cos.cos_id and inv.invoice_id = %s", GetSQLValueString($colname_invoice, "int"));

$invoice = mysql_query($query_invoice, $labels_con) or die(mysql_error());

$row_invoice = mysql_fetch_assoc($invoice);

$totalRows_invoice = mysql_num_rows($invoice);



$colname_inv_simple = "-1";

if (isset($_GET['invoice_id'])) {

  $colname_inv_simple = $_GET['invoice_id'];

}

if ($_REQUEST['orden'] <> " ") { $orden = "inv.inv_id"; } $orden=$_REQUEST['orden'];



mysql_select_db($database_labels_con, $labels_con);

$query_inv_simple = sprintf("SELECT inv.*, des.des_text, mar.marca_text, ori.ori_name FROM tbl_inv_simple inv, tbl_description des, tbl_marcas mar, tbl_origin ori WHERE inv.marca_id=mar.marca_id and inv.des_id=des.des_id and inv.ori_id=ori.ori_id and inv.invoice_id = %s ORDER BY " . $orden . "", GetSQLValueString($colname_inv_simple, "int"));

$inv_simple = mysql_query($query_inv_simple, $labels_con) or die(mysql_error());

$row_inv_simple = mysql_fetch_assoc($inv_simple);

$totalRows_inv_simple = mysql_num_rows($inv_simple);

mysql_select_db($database_labels_con, $labels_con);

$query_marcas = "SELECT * FROM tbl_marcas ORDER BY marca_text ASC";

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

<style type="text/css">

<!--

a:link {

	color: #FFF;

}

a:visited {
	color: #FFF;
	font-family: Verdana, Geneva, sans-serif;
	text-decoration:none
}

-->

</style><!-- InstanceEndEditable -->

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

          <td width="531" align="center" class="Titulo">Listado completo de items</td>

        </tr>

      </table>

      <br />

      <table width="521" border="1" align="center">

        <tr>

          <td width="119" bgcolor="#333333" class="cabecera">Invoice No.</td>

          <td width="292"><?php echo str_pad((int) ($row_invoice['invoice_id']),6,"0",STR_PAD_LEFT); ?></td>

        </tr>

        <tr>

          <td bgcolor="#333333" class="cabecera">Costumer:</td>

          <td><?php echo $row_invoice['cos_names']; ?></td>

        </tr>

        <tr>

          <td bgcolor="#333333" class="cabecera">Company:</td>

          <td><?php echo $row_invoice['cos_corpname']; ?></td>

        </tr>

      </table>

      <br />

      <br />

      <table width="1185" border="1" align="center">

        <?php if ($totalRows_inv_simple > 0) { // Show if recordset not empty ?>

  <tr bgcolor="#333333" class="cabecera">

    <td width="34"><a href="inv_simple2.php?invoice_id=<?php echo $row_invoice['invoice_id']; ?>&amp;orden=inv.box">Box</a></td>

    <td width="119"><a href="inv_simple2.php?invoice_id=<?php echo $row_invoice['invoice_id']; ?>&amp;orden=mar.marca_text">Brand</a></td>

    <td width="94"><a href="inv_simple2.php?invoice_id=<?php echo $row_invoice['invoice_id']; ?>&amp;orden=inv.modelo">Model/Style</a></td>

    <td width="151"><a href="inv_simple2.php?invoice_id=<?php echo $row_invoice['invoice_id']; ?>&amp;orden=des.des_text">Description</a></td>

    <td width="37">Qty</td>

    <td width="107">Pieces</td>

    <td width="195"><a href="inv_simple2.php?invoice_id=<?php echo $row_invoice['invoice_id']; ?>&amp;orden=ori.ori_name">Origin</a></td>

    <td width="69"><a href="inv_simple2.php?invoice_id=<?php echo $row_invoice['invoice_id']; ?>&amp;orden=inv.material">Components</a></td>

    <td width="69">Unit price</td>

    <td width="76">Costo</td>

    <td width="79">Edit</td>

    <td width="79">Eliminar</td>

  </tr>

  <?php do { ?>

    <tr class="<?php 
	  
	  if ($row_inv_simple['qty'] == $row_inv_simple['verificado']) { echo "fodook";} else { }
	  
	   ?>">

      <td align="center"><?php echo $row_inv_simple['box']; ?></td>

      <td><?php echo $row_inv_simple['marca_text']; ?></td>

      <td align="center" bgcolor="#333333"><a href="inv_simple_clone.php?inv_id=<?php echo $row_inv_simple['inv_id']; ?>&amp;invoice_id=<?php echo $row_inv_simple['invoice_id']; ?>" target="_blank" class="cabecera"><?php echo $row_inv_simple['modelo']; ?></a></td>

      <td align="center"><?php echo $row_inv_simple['des_text']; ?></td>

      <td><?php echo $row_inv_simple['qty']; ?></td>

      <td><?php echo $row_inv_simple['pieces']; ?></td>

      <td><?php echo $row_inv_simple['ori_name']; ?></td>

      <td align="right"><?php echo $row_inv_simple['material']; ?></td>

      <td align="right"><?php echo $row_inv_simple['precio']; ?></td>

      <td align="right"><?php echo $row_inv_simple['costo']; ?></td>

      <td align="center"><a href="inv_simple_edit.php?inv_id=<?php echo $row_inv_simple['inv_id']; ?>&amp;invoice_id=<?php echo $row_inv_simple['invoice_id']; ?>" target="_blank"><img src="editi.png" width="16" height="16" border="0" /></a></td>

      <td align="center"><a href="inv_del_item.php?inv_id=<?php echo $row_inv_simple['inv_id']; ?>&amp;invoice_id=<?php echo $row_inv_simple['invoice_id']; ?>&amp;cliente=<?php echo $row_invoice['cos_names']; ?>"><img src="delete.png" width="16" height="16" border="0" /></a></td>

    </tr>

    <?php } while ($row_inv_simple = mysql_fetch_assoc($inv_simple)); ?>

<?php } // Show if recordset not empty ?>

      </table>

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



mysql_free_result($invoice);



mysql_free_result($inv_simple);



mysql_free_result($marcas);



mysql_free_result($description);



mysql_free_result($origen);



mysql_free_result($material);

?>

