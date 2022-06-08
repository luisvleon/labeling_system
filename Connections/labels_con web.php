<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_labels_con = "localhost";
$database_labels_con = "orexport_labels";
$username_labels_con = "orexport_logan";
$password_labels_con = "Targus25";
$labels_con = mysql_pconnect($hostname_labels_con, $username_labels_con, $password_labels_con) or trigger_error(mysql_error(),E_USER_ERROR); 
?>