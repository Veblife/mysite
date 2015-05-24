<?php

$host='localhost'; // имя хоста (уточняется у провайдера)
$database='mysite'; // имя базы данных, которую вы должны создать
$user='rostik'; // заданное вами имя пользователя, либо определенное провайдером
$pswd='123456'; // заданный вами пароль
 
$dbh = mysql_connect($host, $user, $pswd) or die("Не могу соединиться с MySQL.");
mysql_select_db($database) or die("Не могу подключиться к базе данных $database");


//$query = "SELECT * FROM `my_sql_table`";
//$res = mysql_query($query);
//while($row = mysql_fetch_array($res))
//{
//echo "Номер: ".$row['id']."<br>\n";
//echo "Имя: ".$row['firstname']."<br>\n";
//echo "Фамилия: ".$row['surname']."<br><hr>\n";
//}



