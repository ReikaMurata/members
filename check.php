<?php 
session_start();
require('dbconnect.php');
require('hash.php');

//登録フォームからの遷移でなければ登録フォームにリダイレクト
if(!isset($_SESSION['join'])){
	header('Location: index.php');
	exit();
}

//登録処理
if( !empty($_POST) ){
    
    //名前
    $name = $_SESSION['join']['name'];
    //メールアドレス
    $email = $_SESSION['join']['email'];
    //パスワード
    $pass = $_SESSION['join']['pass'];
    //パスのハッシュ化
    $pass = get_hashedpass($pass);
    
	//SQL実行でエラーが起こった際にどう処理するかの指定。例外をスロー
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //プリペアドステートメントをPDO側で行わずDB側に処理させる。
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    //SQL文を変数に格納
    //VALUESにプレースホルダーを設定。後から該当するプレースホルダーに値を入れることでSQL文の使いまわしができる？
    $sql = "INSERT INTO users (name, pass, email) VALUES (:name, :pass, :email)";
    //挿入する値は空のまま、SQL実行の準備をする（この段階ではまだ値は入っていない）
    $stmt = $db->prepare($sql);
    //型を指定してパラメーターをバインド（この段階で値を挿入）
    //bindParamはbindValueと違いexecute実行のタイミングで変数を参照するので。execute前に何らかの処理を行うことで入る値が変わる可能性もある。また必ず変数で渡す必要がある。bindvalueは直接値を渡してもOK
    //PDO::PARAM_STR　型を明示的に指定（値を文字列に変換）
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    //挿入する値が入った変数をexecuteにセットしてSQLを実行
    $stmt->execute();
    
	unset($_SESSION['join']);
	
	header('Location: thanks.php');
	exit();
}
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>新規会員登録</title>
<link href="base.css" type="text/css" rel="stylesheet" media="all">
</head>
<body>
	<div id="input_form">
        <form action="" method="post">
            <dl class="clearfix">
                <dt>【お名前】</dt>
                <dd><?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES, 'UTF-8'); ?></dd>
                <dt>【メールアドレス】</dt>
                <dd><?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES, 'UTF-8'); ?></dd>
                <dt>【パスワード】</dt>
                <dd>●●●●●</dd>
            </dl>
            <div class="form_btn"><a href="index.php?action=rewrite">書き直す</a>　<input type="submit" name="submit" value="登録する"></div>
        </form>
    </div>
    
</body>
</html>