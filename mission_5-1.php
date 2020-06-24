<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
    </head>
    <body>
        <form action="mission_5-1.php" method="post">
            <input type="hidden" name="new_edit_num" value = "<?php if(empty($_POST["edit_num"]) == FALSE && $old_submission[2] == 1){echo$_POST["edit_num"];}?>"><br>
            【投稿フォーム】<br>
            名前:<br>
            <input type="text" name="name" size="50" value = "<?php if($old_submission[2] == 1){echo $old_submission[0];}?>"><br>
            コメント:<br>
            <textarea name="comment" cols="50" rows="5"><?php if($old_submission[2] == 1){echo $old_submission[1];}?></textarea><br>
            パスワード:<br>
            <input type="text" name = "password" size = "50" value = "<?php if($old_submission[2] == 1){echo $old_submission[3];}?>"><br>
            <input type="submit" value="送信" onclick="document.charset='UTF-8';">
        </form>
        <hr>
        <form action="mission_5-1.php" method="post">
            【削除フォーム】<br>
            投稿番号:
            <input type="text" name="delete_num" size="3"><br>
            パスワード:<br>
            <input type="text" name = "password_delete" size = "50"><br>
            <input type="submit" value="削除">
        </form>
        <hr>
        <form action="mission_5-1.php" method="post">
            【編集フォーム】<br>
            投稿番号:
            <input type="text" name="edit_num" size="3"><br>
            パスワード:<br>
            <input type="text" name = "password_edit" size = "50"><br>
            <input type="submit" value="編集" onclick="document.charset='UTF-8';">
        </form>
                
<?php

// DB接続設定
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$old_submission = array("", "", 0, ""); 
if(empty($_POST["edit_num"]) == FALSE){
    $old_submission = getOldComment($pdo, $_POST["edit_num"]);
    echo "$old_submission[0]の$old_submission[1]を編集します<br>";
}


//テーブルを作成
try{
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "datetime DATETIME,"
    . "password TEXT"
    . ");";
    $res = $pdo->query($sql);

	/*$sql ='SHOW TABLES';
	$res = $pdo -> query($sql);
	foreach ($res as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";*/

//実行モードの制御
    if(empty($_POST["new_edit_num"]) == FALSE){
        editComments($pdo, $_POST["new_edit_num"], $_POST["name"] ,$_POST["comment"], $_POST["password"]);
    }elseif(empty($_POST["name"]) == FALSE || empty($_POST["comment"]) == FALSE){
        submission($pdo, $_POST["name"], $_POST["comment"], $_POST["password"]);
    }elseif(empty($_POST["delete_num"]) == FALSE){
        deleteComments($pdo, $_POST["delete_num"]);        
    }
    viewComments($pdo);
    
}catch(PDOException $e) {
    echo $e->getMessage();
    die();
}
$pdo = null;

 
//入力    
   function submission($pdo, $name, $comment, $password){
    $sql = 'select count(*) from tbtest';
    $stmt = $pdo->query($sql);
    $count =  $stmt->fetchColumn();

//データベースへの書き込み
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO tbtest
    (name, comment, datetime, password) VALUES ('$name','$comment','$date', '$password')";
    $res = $pdo->query($sql);
}
	
//コメントを表示する関数
    function viewComments($pdo){
    $sql = 'SELECT * FROM tbtest';
    $res = $pdo->query($sql);
    foreach ($res as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].',';
        echo $row['password'].'<br>';
        echo "<hr>";
    }
    }

    
//投稿を受け付ける関数
    function deleteComments($pdo, $id){
    $sql = "SELECT * FROM tbtest WHERE id = '$id'";
    $res = $pdo->query($sql);
    foreach ($res as $row){
        $pass = $row["password"];
    }
    if($_POST["password_delete"] == $pass){
        $sql = "DELETE FROM tbtest WHERE id = '$id'";
        $res = $pdo->query($sql);
    }
}

//編集する際フォームに編集中の文字を取得する関数
    function getOldComment($pdo, $id){
    $flag = 0;
    $sql = "SELECT * FROM tbtest WHERE id = '$id'";
    $res = $pdo->query($sql);
    foreach( $res as $row ){
        $old_name = $row['name'];
        $old_comment = $row['comment'];
        $old_password = $row['password'];
    }

    if ($old_password == $_POST["password_edit"]){
        $flag = 1;
    }

    return array ($old_name, $old_comment, $flag, $old_password);
}


//編集機能で既存の投稿を新たな名前とコメントに置換する関数
    function editComments($pdo, $id, $new_name, $new_comment, $new_password){
    $date = date("Y/m/d H:i:s");  
     $sql = "UPDATE tbtest SET name = '$new_name' , comment = '$new_comment' , datetime = '$date', password = '$new_password' WHERE id = '$id'";
    $res = $pdo->query($sql);
}

?>

    </body>
</html>