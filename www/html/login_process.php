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


$user = login_as($db, $name, $password);
/*
  function login_as($db, $name, $password){
    $user = get_user_by_name($db, $name);
    if($user === false || $user['password'] !== $password){
      return false;
    }
    set_session('user_id', $user['user_id']);
    return $user;
  }

  function get_user_by_name($db, $name){
    $sql = "
      SELECT
        user_id, 
        name,
        password,
        type
      FROM
        users
      WHERE
        name = '{$name}'
      LIMIT 1
    ";

    return fetch_query($db, $sql);
  }
*/
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}

// HOME_URLに移動する
redirect_to(HOME_URL);