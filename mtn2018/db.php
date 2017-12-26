<?php
	session_start();
	ini_set('display_errors' ,1);

		/***** MySQLへ接続 *****/

		$dbName   = 'misoji2018';
		$host     = 'localhost';
		$user     = 'root';
		$password = 'henokappa';

		$dsn      = 'mysql:dbname=' . $dbName . ';host=' . $host;

		$link = new PDO($dsn, $user, $password);


?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>三十路 メンテ 2018年版</title>

</head>
<body>

</body>
</html>
