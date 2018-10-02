<?php
//データベースへの接続、変数指定
$dsn='dataname';
$user='username';
$password='password';
$pdo=new PDO($dsn,$user,$password);

//テーブルの作成
$sql="create table mission_4(
	id int not null auto_increment primary key,
	name char(32),
	comment text,
	date datetime,
	pass char(32)
	)";

$stml=$pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
</head>
<body>

<?php
//データ入力・編集
$name=$_POST['name'];
$comment=$_POST['comment'];
$time=date("Y/m/d H:i:s");
$pass=$_POST['pass'];

// $sql = "DROP TABLE mission_4";
// $result = $pdo -> query($sql);

if(!empty($name) and !empty($comment) and !empty($pass)){
	//入力
	if(empty($_POST['hi'])){	
	$sql=$pdo->prepare("INSERT INTO mission_4 (id,name,comment,date,pass) VALUES(:id,:name,:comment,:date,:pass)" );
	$sql->bindParam(':id',$id,PDO::PARAM_STR);
	$sql->bindParam(':name',$name,PDO::PARAM_STR);
	$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
	$sql->bindParam(':date',$time,PDO::PARAM_STR);
	$sql->bindParam(':pass',$pass,PDO::PARAM_STR);
	$sql->execute();

	}
	//編集
	else{
		$hi=$_POST['hi'];
		$sql="update mission_4 set name='$name',comment='$comment',date='$time',pass='$pass'where id=$hi";
		$name=$_POST['name'];
		$comment=$_POST['comment'];
		$time=time("Y/m/d H:i:s");
		$pass=$_POST['pass'];
		$result=$pdo->query($sql);
	}
}

// $sql = "TRUNCATE TABLE mission_4";
// $result = $pdo -> query($sql);

//データ消去
$delnumber=$_POST['del'];
$dpass=$_POST['dpass'];
if(ctype_digit($delnumber) and !empty($dpass)){
	$sql="select*from mission_4 where id=(select max(id) from mission_4)";
	$lines=$pdo->query($sql);
	foreach($lines as $row){
		$lastid=$row['id'];
	if($dpass==$row['pass']){
		if($lastid=$delnumber){
		$sql="delete from mission_4 where id=$delnumber";
		$result=$pdo->query($sql);
		$sql="alter table mission_4 AUTO_INCREMENT=$delnumber";
		$result=$pdo->query($sql);
		}
	}
		else{
			echo"パスワードが間違っています！";
		}
	}
}


//投稿編集
$renumber=$_POST['re'];
$epass=$_POST['epass'];
if(ctype_digit($renumber) and !empty($epass)){
	$sql="select*from mission_4 where id=$renumber";
	$lines=$pdo->query($sql);
	$result=$lines->fetch();
	if($epass==$result['pass']){
		$editnb=$result['id'];
		$rename=$result['name'];
		$recomment=$result['comment'];
	}
	
			else{
				echo"パスワードが間違っています！";
			}
		}
				
?>


<form action="mission_4-1.php" method="POST">
<input type="text" name="name" placeholder="名前" value="<?php echo $rename; ?>" ><br/>
<input type="text" name="comment" placeholder="コメント" value="<?php echo $recomment; ?>"><br/>
<input type="password" name="pass" placeholder="パスワード" >
<input type="hidden" name="hi" value="<?php echo $editnb; ?>" >
<input type="submit" value="送信">
<br/>
<br />


<input type="text" name="del" placeholder="削除対象番号"><br />

<input type="password" name="dpass" placeholder="パスワード" >
<input type="submit" value="削除">
<br/>
<br />

<input type="text" name="re" placeholder="編集対象番号"><br />

<input type="password" name="epass" placeholder="パスワード" >
<input type="submit" value="編集">

</form>
<?php
$sql='select*from mission_4  ORDER BY id ASC';
$results=$pdo->query($sql);
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].'<br>';
}
	
?>
</body>
</html>



