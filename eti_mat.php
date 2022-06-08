<?php require_once('Connections/labels_con.php'); ?>
<?php
$total ="";
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
  $insertSQL = sprintf("INSERT INTO tbl_mat_labels (ord_id, stu_id, label, stu_percen) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_COOKIE["maxi"], "int"),
                       GetSQLValueString($_POST['select_material'], "int"),
					   GetSQLValueString($_POST['select_pre'], "text"),
                       GetSQLValueString($_POST['txt_percen'], "text"));
  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
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

$colname_costumers = "-1";
if (isset($_COOKIE["cos_id"])) {
  $colname_costumers = $_COOKIE["cos_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costumers = sprintf("SELECT cos_id, cos_corpname, cos_corpname2  FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_costumers, "int"));
$costumers = mysql_query($query_costumers, $labels_con) or die(mysql_error());
$row_costumers = mysql_fetch_assoc($costumers);
$totalRows_costumers = mysql_num_rows($costumers);

$colname_origen = "-1";
if (isset($_COOKIE["ori_id"])) {
  $colname_origen = $_COOKIE["ori_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_origen = sprintf("SELECT * FROM tbl_origin WHERE ori_id = %s", GetSQLValueString($colname_origen, "int"));
$origen = mysql_query($query_origen, $labels_con) or die(mysql_error());
$row_origen = mysql_fetch_assoc($origen);
$totalRows_origen = mysql_num_rows($origen);

$colname_medidas = "-1";
if (isset($_COOKIE["size_id"])) {
  $colname_medidas = $_COOKIE["size_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_medidas = sprintf("SELECT * FROM tbl_sizes WHERE size_id = %s", GetSQLValueString($colname_medidas, "int"));
$medidas = mysql_query($query_medidas, $labels_con) or die(mysql_error());
$row_medidas = mysql_fetch_assoc($medidas);
$totalRows_medidas = mysql_num_rows($medidas);

mysql_select_db($database_labels_con, $labels_con);
$query_materiales = "SELECT * FROM tbl_stuff ORDER BY stu_text ASC";
$materiales = mysql_query($query_materiales, $labels_con) or die(mysql_error());
$row_materiales = mysql_fetch_assoc($materiales);
$totalRows_materiales = mysql_num_rows($materiales);

$colname_instrucciones = "-1";
if (isset($_COOKIE["maxi"])) {
  $colname_instrucciones = $_COOKIE["maxi"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_instrucciones = sprintf("SELECT per.par_label FROM tbl_par_labels par, tbl_params per WHERE par.par_id=per.par_id and par.ord_id = %s", GetSQLValueString($colname_instrucciones, "int"));
$instrucciones = mysql_query($query_instrucciones, $labels_con) or die(mysql_error());
$row_instrucciones = mysql_fetch_assoc($instrucciones);
$totalRows_instrucciones = mysql_num_rows($instrucciones);

$colname_materialessel = "-1";
if (isset($_COOKIE["maxi"])) {
  $colname_materialessel = $_COOKIE["maxi"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_materialessel = sprintf("SELECT stu.stu_text, mat.stu_percen FROM tbl_mat_labels mat, tbl_stuff stu WHERE mat.stu_id=stu.stu_id and mat.ord_id = %s", GetSQLValueString($colname_materialessel, "int"));
$materialessel = mysql_query($query_materialessel, $labels_con) or die(mysql_error());
$row_materialessel = mysql_fetch_assoc($materialessel);
$totalRows_materialessel = mysql_num_rows($materialessel);
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
<body onload="setFocus();">
<br />
<table width="651" border="0" align="center">
  <tr>
    <td width="610" align="center"><script type="text/javascript" src="labels_menu.js"></script></td>
  </tr>
</table>
<table width="980" border="0" align="center">
  <tr>
    <td width="814" height="652" valign="top"><!-- InstanceBeginEditable name="edit1" -->
      <p>&nbsp;</p>
      <table width="537" border="0" align="center">
        <tr>
          <td width="531" align="center" class="Titulo">Seleccionar materiales y %</td>
        </tr>
      </table>
      <table width="328" border="1" align="center">
        <tr>
          <td align="center">Grupo de etiquetas: <?php echo str_pad((int) ($_COOKIE["maxi"]),6,"0",STR_PAD_LEFT); ?></td>
        </tr>
        <tr>
          <td align="center"><?php echo $row_costumers['cos_corpname']; ?> <?php echo $row_costumers['cos_corpname2']; ?></td>
        </tr>
        <tr>
          <td align="center"><?php echo $row_origen['ori_name']; ?></td>
        </tr>
        <tr>
          <td align="center"><?php echo $row_medidas['size_text']; ?> / <?php echo $row_medidas['size_label']; ?></td>
        </tr>
      </table> 
      <br />
      <table width="312" border="1" align="center">
        <?php do { ?>
          <tr>
            <td width="302" align="center"><?php echo $row_instrucciones['par_label']; ?></td>
          </tr>
          <?php } while ($row_instrucciones = mysql_fetch_assoc($instrucciones)); ?>
      </table>
      <br />
      <table width="454" border="0" align="center">
        <tr>
          <td width="448" align="center">Seleccione los materiales y sus porcentajes</td>
        </tr>
      </table>
      <?php if ($totalRows_materialessel > 0) { // Show if recordset not empty ?>
  <table width="367" border="1" align="center">
    <?php do { ?>
      <tr>
        <td width="357" align="center"><?php echo $row_materialessel['stu_percen']?>%&nbsp;<?php echo $row_materialessel['stu_text']?><?php
		
		
	$total = $total + $row_materialessel['stu_percen'];
	 ?> </td>
      </tr>
      <?php } while ($row_materialessel = mysql_fetch_assoc($materialessel)); ?>
  </table>
  <?php
  
  if ($total == "100") { ?>
   
   <!-- <script type="text/javascript">window.location = "label_pre.php"</script>-->

   <?php
} else {
    
}
?>
  
  <?php } // Show if recordset not empty ?>
<br />
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="680" border="1" align="center">
          <tr bgcolor="#333333" class="cabecera">
            <td width="106" align="center">Prefix</td>
            <td width="106" align="center">%</td>
            <td width="308">Material</td>
            <td width="132">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><label for="select_pre"></label>
              <select name="select_pre" id="select_pre">
                <option value=" "></option>
                <option value="Calzon: ">Calzon: </option>
                <option value="Blusa: ">Blusa: </option>
                <option value="Falda: ">Falda:</option>
                <option value="Forro: ">Forro: </option>
                <option value="Vestido: ">Vestido</option>
            </select></td>
            <td align="center"><label>
              <input type="hidden" name="hiddenField" id="hiddenField" value="<?php echo ($_COOKIE["maxi"]); ?>" />
              <input name="txt_percen" type="text" id="txt_percen" size="10" maxlength="4" />
            </label></td>
            <td><select name="select_material" id="select_material">
              <?php
do {  
?>
              <option value="<?php echo $row_materiales['stu_id']?>"><?php echo $row_materiales['stu_text']?></option>
              <?php
} while ($row_materiales = mysql_fetch_assoc($materiales));
  $rows = mysql_num_rows($materiales);
  if($rows > 0) {
      mysql_data_seek($materiales, 0);
	  $row_materiales = mysql_fetch_assoc($materiales);
  }
?>
            </select></td>
            <td><input type="submit" name="button" id="button" value="   Grabar   " /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
        <table width="200" border="0" align="center">
          <tr>
            <td align="center"><a href="label_pre.php?ord_id=<?php echo $etiq_var; ?>&maxi=<?php echo $max_var1; ?>&inv_id=<?php echo $_REQUEST['inv_id']; ?>">Ver etiqueta</a></td>
          </tr>
        </table>
      </form>
      <p>        <br />
        <br />
      </p>
      </script>
<script type="text/javascript">
function setFocus(){
document.getElementById("txt_percen").focus();
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

mysql_free_result($costumers);
?>
