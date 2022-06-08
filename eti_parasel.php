<?php require_once('Connections/labels_con.php'); ?>
<?php
$var_maxi = $_COOKIE["maxi"];
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
<?php if (!function_exists("GetSQLValueString")) {
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
  $updateSQL = sprintf("UPDATE tbl_order SET id_lavado=%s, id_blan=%s, id_sec=%s, id_plan=%s, id_pro=%s WHERE ord_id=%s",
                       GetSQLValueString($_POST['select_lavado'], "int"),
                       GetSQLValueString($_POST['select_blan'], "int"),
                       GetSQLValueString($_POST['select_sec'], "int"),
                       GetSQLValueString($_POST['select_plan'], "int"),
                       GetSQLValueString($_POST['select_pro'], "int"),
                       GetSQLValueString($_POST['hidden_order'], "int"));
					   //echo $updateSQL;

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($updateSQL, $labels_con) or die(mysql_error());

  $updateGoTo = "eti_mat.php";
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

$colname_costumer = "-1";
if (isset($_COOKIE["cos_id"])) {
  $colname_costumer = $_COOKIE["cos_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costumer = sprintf("SELECT cos_id, cos_corpname FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_costumer, "int"));
$costumer = mysql_query($query_costumer, $labels_con) or die(mysql_error());
$row_costumer = mysql_fetch_assoc($costumer);
$totalRows_costumer = mysql_num_rows($costumer);

$colname_origen = "-1";
if (isset($_COOKIE["ori_id"])) {
  $colname_origen = $_COOKIE["ori_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_origen = sprintf("SELECT * FROM tbl_origin WHERE ori_id = %s", GetSQLValueString($colname_origen, "int"));
$origen = mysql_query($query_origen, $labels_con) or die(mysql_error());
$row_origen = mysql_fetch_assoc($origen);
$totalRows_origen = mysql_num_rows($origen);

$colname_medida = "-1";
if (isset($_COOKIE["size_id"])) {
  $colname_medida = $_COOKIE["size_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_medida = sprintf("SELECT * FROM tbl_sizes WHERE size_id = %s", GetSQLValueString($colname_medida, "int"));
$medida = mysql_query($query_medida, $labels_con) or die(mysql_error());
$row_medida = mysql_fetch_assoc($medida);
$totalRows_medida = mysql_num_rows($medida);

mysql_select_db($database_labels_con, $labels_con);
$query_Recordset2 = "SELECT * FROM tbl_params ORDER BY par_label ASC";
$Recordset2 = mysql_query($query_Recordset2, $labels_con) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_labels_con, $labels_con);
$query_labado = "SELECT * FROM tbl_params ORDER BY par_id";
$labado = mysql_query($query_labado, $labels_con) or die(mysql_error());
$row_labado = mysql_fetch_assoc($labado);
$totalRows_labado = mysql_num_rows($labado);

mysql_select_db($database_labels_con, $labels_con);
$query_blanqueado = "SELECT * FROM tbl_params_blan";
$blanqueado = mysql_query($query_blanqueado, $labels_con) or die(mysql_error());
$row_blanqueado = mysql_fetch_assoc($blanqueado);
$totalRows_blanqueado = mysql_num_rows($blanqueado);

mysql_select_db($database_labels_con, $labels_con);
$query_secado = "SELECT * FROM tbl_params_sec ORDER BY id_param";
$secado = mysql_query($query_secado, $labels_con) or die(mysql_error());
$row_secado = mysql_fetch_assoc($secado);
$totalRows_secado = mysql_num_rows($secado);

mysql_select_db($database_labels_con, $labels_con);
$query_planchado = "SELECT * FROM tbl_params_plan";
$planchado = mysql_query($query_planchado, $labels_con) or die(mysql_error());
$row_planchado = mysql_fetch_assoc($planchado);
$totalRows_planchado = mysql_num_rows($planchado);

mysql_select_db($database_labels_con, $labels_con);
$query_lav_pro = "SELECT * FROM tbl_params_pro";
$lav_pro = mysql_query($query_lav_pro, $labels_con) or die(mysql_error());
$row_lav_pro = mysql_fetch_assoc($lav_pro);
$totalRows_lav_pro = mysql_num_rows($lav_pro);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Labels</title>
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
          <td width="531" align="center" class="Titulo">Instrucciones de lavado</td>
        </tr>
      </table>
      <table width="314" border="1" align="center">
        <tr>
          <td width="304" align="center"><?php echo $row_costumer['cos_corpname'] . " "; ?><?php echo $_COOKIE["maxi"]; ?></td>
        </tr>
        <tr>
          <td align="center"><?php echo $row_origen['ori_name'] . " - "; ?><?php echo str_pad((int) ($_COOKIE["maxi"]),6,"0",STR_PAD_LEFT); ?></td>
        </tr>
      </table>
      <table width="435" border="0" align="center">
        <tr>
          <td align="center">Seleccione las instrucciones de lavado que correspondan.</td>
        </tr>
      </table>
      <br />
      
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <input name="hidden_order" type="hidden" id="hidden_order" value="<?php echo $_COOKIE[maxi]; ?>"/>
        <table width="400" border="1" align="center">
          <tr>
            <td width="102">Lavado:</td>
            <td width="282"><label for="select_lavado"></label>
              <select name="select_lavado" id="select_lavado">
                <?php
do {  
?>
                <option value="<?php echo $row_labado['par_id']?>"><?php echo $row_labado['label']?></option>
                <?php
} while ($row_labado = mysql_fetch_assoc($labado));
  $rows = mysql_num_rows($labado);
  if($rows > 0) {
      mysql_data_seek($labado, 0);
	  $row_labado = mysql_fetch_assoc($labado);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td>Blanqueado:</td>
            <td><select name="select_blan" id="select_blan">
              <?php
do {  
?>
              <option value="<?php echo $row_blanqueado['id_param']?>"<?php if (!(strcmp($row_blanqueado['id_param'], 2))) {echo "selected=\"selected\"";} ?>><?php echo $row_blanqueado['label']?></option>
              <?php
} while ($row_blanqueado = mysql_fetch_assoc($blanqueado));
  $rows = mysql_num_rows($blanqueado);
  if($rows > 0) {
      mysql_data_seek($blanqueado, 0);
	  $row_blanqueado = mysql_fetch_assoc($blanqueado);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td>Secado:</td>
            <td><select name="select_sec" id="select_sec">
              <?php
do {  
?>
              <option value="<?php echo $row_secado['id_param']?>"><?php echo $row_secado['label']?></option>
              <?php
} while ($row_secado = mysql_fetch_assoc($secado));
  $rows = mysql_num_rows($secado);
  if($rows > 0) {
      mysql_data_seek($secado, 0);
	  $row_secado = mysql_fetch_assoc($secado);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td>Planchado:</td>
            <td><select name="select_plan" id="select_plan">
              <?php
do {  
?>
              <option value="<?php echo $row_planchado['id_param']?>"><?php echo $row_planchado['label']?></option>
              <?php
} while ($row_planchado = mysql_fetch_assoc($planchado));
  $rows = mysql_num_rows($planchado);
  if($rows > 0) {
      mysql_data_seek($planchado, 0);
	  $row_planchado = mysql_fetch_assoc($planchado);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td>Lavado Pro:</td>
            <td><select name="select_pro" id="select_pro">
              <?php
do {  
?>
              <option value="<?php echo $row_lav_pro['id_param']?>"<?php if (!(strcmp($row_lav_pro['id_param'], 2))) {echo "selected=\"selected\"";} ?>><?php echo $row_lav_pro['label']?></option>
              <?php
} while ($row_lav_pro = mysql_fetch_assoc($lav_pro));
  $rows = mysql_num_rows($lav_pro);
  if($rows > 0) {
      mysql_data_seek($lav_pro, 0);
	  $row_lav_pro = mysql_fetch_assoc($lav_pro);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="button" id="button" value="Submit" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
        <input type="hidden" name="MM_update" value="form1" />
      </form>
      <p>&nbsp;</p>
      <p><br />
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

mysql_free_result($Recordset2);

mysql_free_result($labado);

mysql_free_result($blanqueado);

mysql_free_result($secado);

mysql_free_result($planchado);

mysql_free_result($lav_pro);
?>
