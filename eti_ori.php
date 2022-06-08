<?php require_once('Connections/labels_con.php'); ?>
<?php
$max_var = $_COOKIE["maxi"];
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
//anadido para pasar variable e ir a sizes

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_POST['select_pais'])) {
  //$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
//}

setcookie("ori_id", $_POST['select_pais']);


  $insertGoTo = "eti_size.php";
 
  
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


$colname_costumers = "-1";
if (isset($_COOKIE["cos_id"])) {
  $colname_costumers = $_COOKIE["cos_id"];
}
mysql_select_db($database_labels_con, $labels_con);
$query_costumers = sprintf("SELECT cos_id, cos_corpname, cos_corpname2  FROM tbl_costumers WHERE cos_id = %s", GetSQLValueString($colname_costumers, "int"));
$costumers = mysql_query($query_costumers, $labels_con) or die(mysql_error());
$row_costumers = mysql_fetch_assoc($costumers);
$totalRows_costumers = mysql_num_rows($costumers);

mysql_select_db($database_labels_con, $labels_con);
$query_origen = "SELECT * FROM tbl_origin ORDER BY ori_name ASC";
$origen = mysql_query($query_origen, $labels_con) or die(mysql_error());
$row_origen = mysql_fetch_assoc($origen);
$totalRows_origen = mysql_num_rows($origen);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Origen</title>
<!-- InstanceEndEditable -->
<link href="css/labels.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body onLoad="document.form1.select_pais.focus()">
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
          <td width="531" align="center" class="Titulo">Selecci�n del or�gen</td>
        </tr>
      </table>
      <br />
      <br />
      <table width="493" border="1" align="center">
        <tr>
          <td width="483" align="center"><?php echo $row_costumers['cos_corpname']; ?> <?php echo $row_costumers['cos_corpname2'] . " - " . $_COOKIE["maxi"]; ?></td>
        </tr>
      </table>
      <br />
      <table width="501" border="0" align="center">
        <tr>
          <td width="495" align="center">Por favor selecciones el origen de las prendas a ser etiquetadas</td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="396" border="1" align="center">
          <tr>
            <td width="277"><label for="select_pais"></label>
              <select name="select_pais" class="cajagrande" id="select_pais">
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
            <td width="109"><input type="submit" name="button" id="button" value="Submit" /></td>
          </tr>
        </table>
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

mysql_free_result($costumers);

mysql_free_result($origen);
?>
