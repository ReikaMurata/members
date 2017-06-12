<?php
//セッションスタート
session_start();
require('dbconnect.php');

//送信を確認
if( !empty($_POST) ){
    //「お名前」に入力があるかチェック
    if( $_POST['name'] == '' ){
        //エラーメッセージ追加
        $error['name'] = 'お名前が入力されていません';
    }
    //「メール」の入力チェック
    if( $_POST['email'] == '' ){
        //エラーメッセージ追加
        $error['email'] = 'メールアドレスが入力されていません';
    }else{
        //入力されたメールアドレスチェック
        if( !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email']) ){
            //エラーメッセージ追加
            $error['email'] = 'メールアドレスが正しくありません';
        }else{
            //メールアドレスの重複チェック
            $sql = "SELECT email FROM users";
            $stmt = $db->query($sql);
            while($items = $stmt->fetch(PDO::FETCH_ASSOC)){
                if($_POST['email'] == $items['email']){
                    $error['email'] = '既に登録済みのメールアドレスです';
                    break;
                }
            }
        }
    }
    if( $_POST['pass'] == "" ){
        $error['pass'] = 'パスワードが入力されていません';
    }else{
        if( strlen($_POST['pass']) > 16 ){
            $error['pass'] = 'パスワードは16文字以内にしてください';
        }
    }
    
    //エラーなし
	if( empty($error) ){
		$_SESSION['join'] = $_POST;
		header('location: check.php');
		exit();
	}
}

//書き直し
if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite' ){
	$_POST = $_SESSION['join'];
	$error['rewrite'] = true;
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
    <h1>会員情報登録</h1>
    <form action="" method="post" enctype="multipart/form-data">
    	<dl class="clearfix">
    		<dt>【お名前】</dt>
    		<dd>
            	<input type="text" name="name" value="<?php if( !empty($_POST) ){echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');} ?>">
            	<?php if( isset($error['name']) ){ echo '<p class="error">'.$error['name'].'</p>'; } ?>
            </dd>
    		<dt>【メールアドレス】</dt>
    		<dd>
            	<input type="mail" name="email" value="<?php if( !empty($_POST) ){echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');} ?>">
            	<?php if( isset($error['email']) ){ echo '<p class="error">'.$error['email'].'</p>'; } ?>
            </dd>
    		<dt>【パスワード】</dt>
    		<dd>
            	<input type="password" name="pass" value="<?php if( !empty($_POST) ){echo htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');} ?>">
                <?php if( isset($error['pass']) ){ echo '<p class="error">'.$error['pass'].'</p>'; } ?>
            </dd>
    	</dl>
        <div class="form_btn"><input type="submit" value="確認する"></div>
    </form>
    </div>
    
</body>
</html>