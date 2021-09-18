<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

// セッションの開始
session_start();

// user_idが空白でなければLOGIN_URLに移動する
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// セットされていれば代入する
$name     = get_post('name');
$password = get_post('password');

// データベースに接続
$db = get_db_connect();

// ユーザーとパスワードが一致していなければログインしない
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

// $userがUSER_TYPE_ADMINであればADMIN_URLに移動する
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}

// HOME_URLに移動する
redirect_to(HOME_URL);