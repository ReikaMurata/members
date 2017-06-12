<?php 
require('dbconnect.php');
require('hash.php');

session_start();

//ログイン処理
if(!empty($_POST)){
	//メールとパス未記入確認
	if($_POST['mail'] == ''){
		$error['blank_mail'] = '※メールアドレスが未記入です';
	}
	if($_POST['pass'] == ''){
		$error['blank_pass'] = '※パスワードが未記入です';
	}
	//DBと照合
	if($_POST['mail'] != '' && $_POST['pass'] != ''){
		$sql = sprintf(
			'SELECT * FROM members WHERE mail="%s" AND pass="%s"',
			mysqli_real_escape_string($db, $_POST['mail']),
			mysqli_real_escape_string($db, get_stretched_password($_POST['pass'], $_POST['mail']))
		);
		$record = mysqli_query($db, $sql) or die(mysqli_error($db));
		if($table = mysqli_fetch_assoc($record)){
			//ログイン成功
			header("Location: mypage.php");
			exit();
		}else{
			//ログイン失敗
			$error['login'] = '※アカウントが違います';
		}
	}
}
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログイン</title>
<link href="base.css" type="text/css" rel="stylesheet" media="all">
</head>
<body>
	<div id="input_form">
    	<h1 class="title">メールアドレスとパスワードを記入してログインしてください。</h1>
        <form action="" method="post">
            <dl class="clearfix">
                <dt>【メールアドレス】</dt>
                <dd>
                	<input type="email" name="mail"<?php if( isset($_POST['mail']) && $_POST['mail'] ){ echo ' value="'.htmlspecialchars($_POST['mail']).'"';} ?>>
                    <?php if(isset($error['blank_mail']) && $error['blank_mail']){ echo '<p class="error">'.$error['blank_mail'].'</p>'; } ?>
                </dd>
                <dt>【パスワード】</dt>
                <dd>
                	<input type="password" name="pass">
                    <?php if(isset($error['blank_pass']) && $error['blank_pass']){ echo '<p class="error">'.$error['blank_pass'].'</p>'; } ?>
                </dd>
            </dl>
            <div class="form_btn"><input type="submit" value="ログインする"></div>
        </form>
    </div>
    
</body>
</html>