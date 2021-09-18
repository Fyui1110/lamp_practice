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
$password_confirmation = get_post('password_confirmation');

// データベースに接続
$db = get_db_connect();

// ユーザーの登録
try{
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}
set_message('ユーザー登録が完了しました。');

// ？
login_as($db, $name, $password);

// HOME_URLに移動する
redirect_to(HOME_URL);