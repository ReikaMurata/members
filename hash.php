<?php 
//define('STRETCH_COUNT', 1000);

//文字列からSHA256のハッシュ値を獲得
/*function get_sha256($target){
	return hash('sha256', $target);
}*/

//ソルト+ストレッチングしたパスワードを取得
/*function get_stretched_password($pass, $mail){
	$salt = get_sha256($mail);
	$hash = '';
	for($i = 1; $i < STRETCH_COUNT; $i++){
		$hash = get_sha256($hash.$salt.$pass);
	}
	return $hash;
}*/
function get_hashedpass($pass){
    return password_hash($pass, PASSWORD_DEFAULT);
}
?>