<?php require_once('Connections/labels_con.php'); ?>
<?php require_once('Connections/labels_con.php');
$txt1 =  $_REQUEST['txt_1'];
$txt2 =  $_REQUEST['txt_2'];
$txt3 =  $_REQUEST['txt_3'];
$repetition =  $_REQUEST['txt_howmany'];
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
  
  
  $insertSQL = sprintf("INSERT INTO tbl_test (tes_1, tes_2, test_3) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['hid_1'], "text"),
                       GetSQLValueString($_POST['hid_2'], "text"),
                       GetSQLValueString($_POST['hid_3'], "text"));

 mysql_select_db($database_labels_con, $labels_con); 
    for ($i = 1; $i <= $_POST['hid_4']; $i++)      /* this line added to repeat the insert   */
{ 
  $Result1 = mysql_query($insertSQL, $labels_con) or die(mysql_error());
}													/* this braket was added also */
  $insertGoTo = "menu.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
  <table width="370" border="1">
    <tr>
      <td width="92">text1</td>
      <td width="262"><?php echo $_REQUEST['txt_1']; ?>
        <label>
          <input type="text" name="hid_1" id="hid_1" value="<?php echo $_REQUEST['txt_1']; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>text2</td>
      <td><?php echo $_REQUEST['txt_2']; ?>
        <label>
          <input type="text" name="hid_2" id="hid_2" value="<?php echo $_REQUEST['txt_2']; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>text3</td>
      <td><?php echo $_REQUEST['txt_3']; ?>
        <label>
          <input type="text" name="hid_3" id="hid_3" value="<?php echo $_REQUEST['txt_3']; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>how many</td>
      <td><?php echo $repetition ?>
        <label>
          <input type="text" name="hid_4" id="hid_4" value="<?php echo $repetition; ?>" />
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="button" id="button" value="Submit" />
      </label></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>