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
$query_descrip = "SELECT des.* FROM tbl_description des, tbl_invoice inv WHERE inv.activo = 1 AND des.cos_id = inv.cos_id ORDER BY des.des_text ASC";
$descrip = mysql_query($query_descrip, $labels_con) or die(mysql_error());
$row_descrip = mysql_fetch_assoc($descrip);
$totalRows_descrip = mysql_num_rows($descrip);

mysql_select_db($database_labels_con, $labels_con);
$query_brands = "SELECT mar.* FROM tbl_marcas mar, tbl_invoice inv WHERE inv.activo = 1 AND mar.cos_id = inv.cos_id ORDER BY mar.marca_text ASC";
$brands = mysql_query($query_brands, $labels_con) or die(mysql_error());
$row_brands = mysql_fetch_assoc($brands);
$totalRows_brands = mysql_num_rows($brands);

if ($_POST['select_brand'] == "") {
} else {
$colname_prices = "-1";
if (isset($_GET['id'])) {
  $colname_prices = $_GET['id'];
}
mysql_select_db($database_labels_con, $labels_con);
$query_prices = sprintf("SELECT DISTINCT sim.inv_id, sim.precio, sim.costo, cos.cos_names, mar.marca_text, des.des_text, sim.material, sim.modelo  FROM tbl_inv_simple sim, tbl_marcas mar, tbl_description des, tbl_invoice inv, tbl_costumers cos WHERE inv.activo = 1 AND inv.cos_id = %s AND sim.marca_id=". $_POST['select_brand'] . " AND sim.marca_id = mar.marca_id AND sim.des_id=" . $_POST['select_descrip'] . " AND sim.des_id = des.des_id AND sim.invoice_id = inv.invoice_id AND inv.cos_id = cos.cos_id ORDER BY sim.modelo ASC", 
GetSQLValueString($colname_prices, "int"));
$prices = mysql_query($query_prices, $labels_con) or die(mysql_error());
$row_prices = mysql_fetch_assoc($prices);
$totalRows_prices = mysql_num_rows($prices);	
}
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
<body onload="setFocus()";>
<br />
<table width="651" border="0" align="center">
  <tr>
    <td width="610" align="center"><script type="text/javascript" src="/labels/labels_menu.js"></script></td>
  </tr>
</table>
<table width="980" border="0" align="center">
  <tr>
    <td width="814" height="652" valign="top"><!-- InstanceBeginEditable name="edit1" --><br />
      <table width="537" border="0" align="center">
        <tr>
          <td width="531" align="center" class="Titulo">Prices search for <?php echo $row_prices['cos_names']; ?></td>
        </tr>
      </table>
      <br />
      <form id="form1" name="form1" method="post" action="prices_search.php?id=<?php echo $_REQUEST['id']; ?>">
        <table width="407" border="1" align="center">
          <tr>
            <td width="138" bgcolor="#333333" class="cabecera">Description:
            <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $_REQUEST['id']; ?>" /></td>
            <td width="259"><label>
              <select name="select_descrip" id="select_descrip" >
                <?php
do {  
?>
                <option value="<?php echo $row_descrip['des_id']?>"><?php echo $row_descrip['des_text']?></option>
                <?php
} while ($row_descrip = mysql_fetch_assoc($descrip));
  $rows = mysql_num_rows($descrip);
  if($rows > 0) {
      mysql_data_seek($descrip, 0);
	  $row_descrip = mysql_fetch_assoc($descrip);
  }
?>
              </select>
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">Brand:</td>
            <td><label>
              <select name="select_brand" id="select_brand">
                <?php
do {  
?>
                <option value="<?php echo $row_brands['marca_id']?>"><?php echo $row_brands['marca_text']?></option>
                <?php
} while ($row_brands = mysql_fetch_assoc($brands));
  $rows = mysql_num_rows($brands);
  if($rows > 0) {
      mysql_data_seek($brands, 0);
	  $row_brands = mysql_fetch_assoc($brands);
  }
?>
              </select>
            </label></td>
          </tr>
          <tr>
            <td bgcolor="#333333" class="cabecera">&nbsp;</td>
            <td><label>
              <input type="submit" name="button" id="button" value="Submit" />
            </label></td>
          </tr>
        </table>
      </form>

      <br />
      <?php if ($totalRows_prices > 0) { // Show if recordset not empty ?>
  <table width="755" border="1" align="center">
    <tr bgcolor="#333333" class="cabecera">
      <td>Id</td>
      <td>Style</td>
      <td>Description:</td>
      <td>Material</td>
      <td>Brand:</td>
      <td>Price:</td>
      <td>Cost:</td>
      </tr>
    <?php do { ?>
      <tr>
        <td><a href="/labels/inv_simple_edit.php?inv_id=<?php echo $row_prices['inv_id'] . "&invoice_id=8" ; ?>" target="_blank"><?php echo $row_prices['inv_id']; ?></a></td>
        <td><?php echo $row_prices['modelo']; ?></td>
        <td><?php echo $row_prices['des_text']; ?></td>
        <td><?php echo $row_prices['material']; ?></td>
        <td><?php echo $row_prices['marca_text']; ?></td>
        <td><?php echo $row_prices['precio']; ?></td>
        <td><?php echo $row_prices['costo']; ?></td>
      </tr>
      <?php } while ($row_prices = mysql_fetch_assoc($prices)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
<br />
</script>
<script type="text/javascript">
function setFocus(){
document.getElementById("select_descrip").focus();
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

mysql_free_result($descrip);

mysql_free_result($brands);
if ($_POST['select_brand'] == "") {
} else {
mysql_free_result($prices);
}
?>

