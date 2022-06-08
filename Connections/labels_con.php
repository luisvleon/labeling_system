<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_labels_con = "localhost";
$database_labels_con = "labels";
$username_labels_con = "root";
$password_labels_con = "targus25";
$labels_con = mysql_pconnect($hostname_labels_con, $username_labels_con, $password_labels_con) or trigger_error(mysql_error(),E_USER_ERROR); 
?>