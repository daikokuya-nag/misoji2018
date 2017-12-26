<?php
	session_start();
	ini_set('display_errors' ,1);

		/***** MySQLへ接続 *****/

	define('LOCATIONURL' ,'202.232.39.229');			//202.232.39.229		localhost		dotgets161.senkosha.jp	127.0.0.1
	define('USER'        ,'misoji');
	define('DBPASS'      ,'6xph1zoe');			//6xph1zoe					5yfpru9a		xa51emeo
	define('DBNAME'      ,'misoji');

/*
	define('LOCATIONURL' ,'202.232.39.229');
	define('USER'        ,'tosima_jp');
	define('DBPASS'      ,'5ar81yiq');
	define('DBNAME'      ,'tosima_jp');
*/



		$dbName   = 'misoji';
		$host     = '202.232.39.229';
		$user     = 'misoji';
		$password = '6xph1zoe';

		$dsn      = 'mysql:dbname=' . $dbName . ';host=' . $host;

		$link = new PDO($dsn, $user, $password);






//		$connectID = mysql_connect(LOCATIONURL ,USER ,DBPASS ,false ,65536);
//
//
//		/***** データベースを開く *****/
//		$seleID = mysql_select_db(DBNAME ,$connectID);




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
