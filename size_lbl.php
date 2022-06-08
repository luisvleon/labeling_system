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
  $insertSQL = sprintf("INSERT INTO lbl_tallas (id_talla, cantidad) VALUES (%s, %s)",
                       GetSQLValueString($_POST['select_talla'], "int"),
                       GetSQLValueString($_POST['txt_cantidad'], "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
  ////////////////////////////////////////////
  
  mysql_select_db($database_labels_con, $labels_con);
$query_maximo = "SELECT max(id) FROM lbl_tallas";
$maximo = mysql_query($query_maximo, $labels_con) or die(mysql_error());
$row_maximo = mysql_fetch_assoc($maximo);
$totalRows_maximo = mysql_num_rows($maximo);

  
  for ($i = 1; $i <= $_POST['txt_cantidad']; $i++)      /* this line added to repeat the insert   */
{ 
  $insertSQL = sprintf("INSERT INTO lbl_tallas_duplicate (id_talla, duplicate) VALUES (%s, %s)",
                       GetSQLValueString($row_maximo['max(id)'], "int"),
                       GetSQLValueString($i, "int"));

  mysql_select_db($database_labels_con, $labels_con);
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
}

/////////////////////////////////////////////////////////
  
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
$query_tallas = "SELECT * FROM tbl_sizes ORDER BY size_label ASC";
$tallas = mysql_query($query_tallas, $labels_con) or die(mysql_error());
$row_tallas = mysql_fetch_assoc($tallas);
$totalRows_tallas = mysql_num_rows($tallas);

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
<body>
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
          <td width="531" align="center" class="Titulo">Etiquetas de talla</td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="600" height="125" border="0" align="center">
          <tr>
            <td><table width="393" border="1" align="center">
              <tr>
                <td width="141" bgcolor="#333333" class="cabecera">Seleccione la talla : </td>
                <td width="242"><label>
                  <select name="select_talla" id="select_talla">
                    <?php
do {  
?>
                    <option value="<?php echo $row_tallas['size_id']?>"><?php echo $row_tallas['size_label']?></option>
                    <?php
} while ($row_tallas = mysql_fetch_assoc($tallas));
  $rows = mysql_num_rows($tallas);
  if($rows > 0) {
      mysql_data_seek($tallas, 0);
	  $row_tallas = mysql_fetch_assoc($tallas);
  }
?>
                  </select>
                  <input name="hidden_max" type="hidden" id="hidden_max" value="<?php echo $row_maximo['max(id)']; ?>" />
                  <?php echo $row_maximo['max(id)']; ?></label></td>
              </tr>
            </table>
            <br />
            <table width="392" border="1" align="center">
              <tr>
                <td width="138" bgcolor="#333333" class="cabecera">Cantidad:</td>
                <td width="238"><label>
                  <input name="txt_cantidad" type="text" id="txt_cantidad" size="8" maxlength="3" />
                </label></td>
              </tr>
            </table>
            <br />
            <table width="319" border="0" align="center">
              <tr>
                <td align="center"><label>
                  <input type="submit" name="button" id="button" value="Submit" />
                </label></td>
              </tr>
            </table></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
      <p>&nbsp;</p>
      <p><br />
      </p>
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

mysql_free_result($tallas);

?>
