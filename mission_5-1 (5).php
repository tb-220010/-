<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "utf-8">
<title>mission_5-1</title>
</head>

<body>
<form action = "" method = "post" >
	<p>
	<input type = "text" placeholder = "名前" name = "name" value = "<?php if(isset($PostEdit1)) {echo $PostEdit1;} ?>"> </p>
	<p>
	<textarea name = "textarea" placeholder = "コメント" rows = "5"><?php if(isset($PostEdit2)) {echo $PostEdit2;} ?></textarea></p>
	<p>
	<input tupe = "password" placeholder = "パスワード" name = "pass" value = "<?php if(isset($PostEdit3)) {echo $PostEdit3;} ?>">
	<input type = "submit" value = "送信"> </p>
	<p><input type = "hidden" name = "editnumber" value = "<?php if(isset($aaa)) {echo $aaa;} ?>"></p>
</form>

<form action = "" method = "post">
	<p>
	<input type = "text" placeholder = "削除対象番号" name = "delete"> </p>
	<p>
	<input type =  "password" placeholder = "パスワード" name = "deletepass">
	<input type = "submit" value = "削除"></p>
</form>

<form action = "" method = "post">
	<p>
	<input type = "text" placeholder = "編集対象番号" name = "edit"> </p>
	<p>
	<input type = "password" placeholder = "パスワード" name = "editpass">
	<input type = "submit" value = "編集"></p>
</form>
</body>
</html>

<?php

// DB接続設定
	$dsn='データベース名';
	$user='ユーザー名';
	$password='パスワード';
	$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
	//「log」という名前のテーブルが存在しないとき、テーブルを新たに作成。
	$sql = "CREATE TABLE IF NOT EXISTS log1"
	."("
	."id INT AUTO_INCREMENT PRIMARY KEY,"
	."name char(32),"
	."comment TEXT,"
	."date DATETIME,"
	."pass char(18)"
	.");";
	$stmt = $pdo->query($sql);

	if(!empty($_POST["name"]) and !empty($_POST["textarea"]) and !empty($_POST["pass"]))
	{
		if(!empty($_POST["editnumber"]))
		{
			$bbb = $_POST["editnumber"];

			//ユーザーからの入力を受け付ける
			$sql = $pdo -> prepare("update log1 set name = :name,comment = :comment,date = :date,pass = :pass where id = $bbb");

			//SQL文の一部を変数にする。
			$sql -> bindParam(':name', $name , PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':date', $date, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

			//変数はどこから？
			$name = $_POST["name"];
			$comment = $_POST["textarea"];
			$date = date("Y-m-d H:i:s");
			$pass = $_POST["pass"];

			//準備された文を実行する
			$sql -> execute();
		}
		else
		{
			//ユーザーからの入力を受け付ける
			$sql = $pdo -> prepare("INSERT INTO log1(name, comment,date, pass)  VALUES(:name,:comment,:date,:pass)");

			//SQL文の一部を変数にする
			$sql -> bindParam(':name', $name , PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':date', $date, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$name = $_POST['name'];
			$comment = $_POST['textarea'];
			$date = date("Y-m-d H:i:s");
			$pass = $_POST['pass'];

			//準備された文を実行する
			$sql -> execute();
		}
	}

	elseif(!empty($_POST["delete"]) and !empty($_POST["deletepass"]))
	{
		$id = $_POST["delete"];
		$deletepass = $_POST["deletepass"];

		$sql = $pdo ->prepare( "SELECT * FROM log1 WHERE id = $id");

		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
		$sql -> execute();

		//一つのレコードを取り出す
		$result = $sql -> fetch();
		$Truepass = $result['pass'];

		if($deletepass == $Truepass)
		{
			$sql = "delete from log1 where id =:id";
			$stmt = $pdo -> prepare($sql);
			$stmt ->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt ->execute();
		}
		else
		{
			echo "パスワードが違います。";
			echo "<br>";
		}
	}

	elseif(!empty($_POST["edit"]) and !empty($_POST["editpass"]))
	{
		$aaa = $_POST["edit"];
		$editpass = $_POST["editpass"];

		$sql = $pdo ->prepare( "SELECT * FROM log1 WHERE id = $aaa");

		$sql -> bindParam(':name', $name , PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

		$sql -> execute();

		$result = $sql -> fetch();
		$Truepass = $result['pass'];

		if($editpass == $Truepass)
		{
			$PostEdit1 = $result['name'];
			$PostEdit2 = $result['comment'];
			$PostEdit3 = $Truepass;

		}
		else
		{
			echo "パスワードが違います。";
			echo "<br>";
		}
	}

		
	$sql = 'SELECT * FROM log1';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row)
	{
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
		echo "<hr>";
	}


?>