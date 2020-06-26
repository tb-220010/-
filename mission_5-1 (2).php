<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
    </head>
    <body>
        <hr>
        <form action="mission_5-1.php" method="post">
            【投稿フォーム】<br>
            名前:<br>
        	<input type="text" name="name" size="50"
        	value = "<?php echo $old_submission[0];?>"><br>
            コメント:<br>
            <textarea name="comment" cols="50" rows="5">
            <?php if($old_submission[2] == 1){echo $old_submission[1];}?></textarea><br>
			<input type="hidden"  name="rewnum2" size="50"
			value = "<?php if(empty($_POST["edit_num"]) == FALSE && $old_submission[2] == 1){echo$_POST["edit_num"];}?>">
			パスワード:<br>
            <input type="text" name="compassword"  size="50" 
            value = "<?php {echo $old_submission[3];?>">
            <input type="submit" name="comsubmit" value="送信" size="20"><br><br>
        </form>
        <hr>
        <form action="mission_5-1.php" method="post">
            【削除フォーム】<br>
            削除対象番号:<br>
            <input type="text" name="delete"  size="3"><br>
            パスワード:</font><br>
            <input type="text" name="depassword" value="" size="50"/>
            <input type="submit" name="desubmit" value="削除"><br><br>
        </form>
        <hr>
        <form action="mission_5-1.php" method="post">
            【編集フォーム】<br>
            編集対象番号:<br>
            <input type="text" name="rewnum1"  size="3"><br>
            名前:<br>
            <input type="text" name="rename" size="50"><br>
            コメント:</font><br>
            <input type="text" name="recomment" size="50"><br>
            パスワード:<br>
            <input type="text" name="repassword" value="" size="50">
            <input type="submit" name="resubmit"value="編集"><br>
        </form>
       <hr>
                
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
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT"
	. "date DATE,"
    . "password TEXT"
	.");";
	$stmt = $pdo->query($sql);

/*テーブル一覧表示
    $sql="DROP　TABLE tbtest";
    $delete=$pdp->query($sql);
	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";*/

//コメント表示
    echo "<br>～投稿一覧～<br>";
    viewComments($pdo);
    function viewComments($pdo){
        $content = file_get_contents($pdo);   
    }

//入力
    if(!empty($_POST['comsubmit'])){
           if(!empty($_POST['name'])&&!empty($_POST['comment'])&&!empty($_POST['compassword'])){
                $password=$_POST["compassword"];
                $Originpassword="mari";
                if($password=$Originpassword){


        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment,date) VALUES (:name, :comment,:date)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment =$_POST["comment"];
        $date = date("Y/m/d H:i:s"); 
        $sql -> execute();
    }
    }
    }

//編集
    if(!empty($_POST['resubmit'])){
        if(!empty($_POST['rename'])&&!empty($_POST['recomment'])&&!empty($_POST['repassword'])){
             $password=$_POST["repassword"];
             $Originpassword="mari";
             if($password=$Originpassword){

                $id =  $_POST["rewnum1"]; //変更する投稿番号
                $name =  $_POST["rename"];
                $comment =$_POST["recomment"]; //変更したい名前、変更したいコメントは自分で決めること
                $sql = 'update tbtest set name=:name,comment=:comment where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
             }}
        }
        
//削除
        if(!empty($_POST['desubmit'])){
        if(!empty($_POST['delete'])&&!empty($_POST['depassword'])){
             $password=$_POST["depassword"];
             $Originpassword="mari";
             if($password=$Originpassword){

                $id = $_POST["delete"];
                $sql = 'delete from tbtest where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
             }}
            }
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
}
}

?>

    </body>
</html>