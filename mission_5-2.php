<?php
//データベースに接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo= new PDO($dsn,$user,$password,
array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

//DB内にテーブルを作成
$sql="CREATE TABLE IF NOT EXISTS mission5_1"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."date DATETIME,"
."password char(10)"
.");";
$stmt=$pdo->query($sql);

//入力されたら(投稿機能)
if(empty($_POST["editnum"]) && !empty($_POST["name"] && $_POST["comment"] && $_POST["pass"])){
    
//データを入力	
$sql = $pdo -> prepare("INSERT INTO mission5_1 (name,comment,date,password) VALUES(:name,:comment,:date,:password)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> bindParam(':password', $pass, PDO::PARAM_STR);
	$name=$_POST["name"];
	$comment=$_POST["comment"];
	$date=date("Y/m/d H:i:s");
	$pass=$_POST["pass"];
	$sql -> execute();
}

//削除機能
if(!empty($_POST["deletenum"]) && empty($_POST["deletepass"])){
    echo "パスワードを入力してください。";
}
elseif(empty($_POST["deletenum"]) && !empty($_POST["deletepass"])){
    echo "番号を入力してください。";
}
//番号とパスワードが両方が入力されたら
else{
    $sql='delete from mission5_1 WHERE id=:id AND password=:password';//where文で条件を指定
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':id',$deletenum,PDO::PARAM_INT);
    $stmt->bindParam(':password',$deletepass,PDO::PARAM_STR);
    $deletenum=$_POST["deletenum"];
    $deletepass=$_POST["deletepass"];
    $stmt->execute();
}

//編集機能
if(!empty($_POST["edit"])&& empty($_POST["editpass"])){
    echo "パスワードを入力してください。";
}
elseif(empty($_POST["edit"]) && !empty($_POST["editpass"])){
    echo "編集番号を入力してください。";
}
else{//番号とパスワードが両方入力されたら
    //データを抽出
    $sql='SELECT *FROM mission5_1 where id=:id';//idが同じならば抽出
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':id',$edit,PDO::PARAM_INT);//editに入力された番号が一致するか
    $edit=$_POST["edit"];
    $stmt->execute();
    
    //データを取得し、格納
    $results=$stmt->fetchAll();
    foreach($results as $row){
      $editnum=$row['id'];
      $editname=$row['name'];
      $editcomment=$row['comment'];
      $pass=$row['password'];
    }
}
//update文で編集
//入力フォームのすべての項目に入力があったら
    if(!empty($_POST["editnum"] && $_POST["name"] && $_POST["comment"] && $_POST["pass"])){
        //updateするのはset以下、where文で実施する条件を指定
    $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment WHERE id=:id AND password=:password';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	//データベース内の情報と一致するかみるため
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->bindParam(':password',$password,PDO::PARAM_STR);
	$name=$_POST["name"];
	$comment=$_POST["comment"];
	$id=$_POST["editnum"];
	$password=$_POST["pass"];
	$stmt->execute();
}

?>
<!doctype html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <h1>ひとこと掲示板</h1>
        <h2>楽しかった思い出を入力してください。</h2>
        <form action="" method="post">
            <input type="hidden" name="editnum"
            value="<?php if($pass==$_POST["editpass"]){echo $editnum;}?>">
            名前：<input type="text" name="name"
            value="<?php if($pass==$_POST["editpass"]){echo $editname;}?>"><br><br>
            コメント：<input type="comment" name="comment"
            value="<?php if($pass==$_POST["editpass"]){echo $editcomment;}?>"><br><br>
            パスワード：<input type="text" name="pass">
            <input type="submit" name="submit">
        </form>
        <h2>削除機能</h2>
        <form action="" method="post">
            削除対象番号：<input type="text" name="deletenum"><br><br>
            パスワード：<input type="text" name="deletepass">
            <input type="submit" name="deletego" value="削除">
            </form>
        <h2>編集機能</h2>
        <form action="" method="post">
            編集対象番号：<input type="text" name="edit"><br><br>
            パスワード：<input type="text" name="editpass">
            <input type="submit" name="editgo" value="編集"><br>
        </form>
        <h2>投稿一覧</h2>
    <?php
    //$sql='SELECT count(*) FROM mission5_1';
    //$stmt=$pdo->query($sql);
    //$count=$stmt->fetchColumn();
    
     $select = 'SELECT*FROM mission5_1';
	$stmt = $pdo->query($select);
	$results = $stmt->fetchAll();
	
	//if($count==0){
	  //  echo "まだ投稿はありません<br>";
	//}
    //else{
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].",";
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
            }
	?>
    </body>    
    
</html>