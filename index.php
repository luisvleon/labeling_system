<?php require_once('Connections/labels_con.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['txt_username'])) {
  $loginUsername=$_POST['txt_username'];
  $password=$_POST['txt_password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "menu.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_labels_con, $labels_con);
  
  $LoginRS__query=sprintf("SELECT user_username, user_password FROM tbl_users WHERE user_username=%s AND user_password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $labels_con) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de etiquetado</title>
</head>

<body onload="setFocus();">
<table width="622" height="328" border="0" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
      <br />
      <br />
      <br />
      <table width="200" border="1" align="center">
        <tr>
          <td align="center"><strong>Sistema de etiquetado 1.0</strong></td>
        </tr>
      </table>
      <br />
      <br />
      <br />
<br />
      <br />
      <table width="330" border="1" align="center">
        <tr>
          <td width="109">Username:</td>
          <td width="205"><label>
            <input type="text" name="txt_username" id="txt_username" />
          </label></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><label>
            <input type="password" name="txt_password" id="txt_password" />
          </label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><label>
            <input type="submit" name="button" id="button" value="Submit" />
          </label></td>
        </tr>
      </table>
    </form></td>
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
document.getElementById("txt_username").focus();
}
</script>
</body>
</html>