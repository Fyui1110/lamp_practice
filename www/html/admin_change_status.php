<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

// セッションの開始
session_start();

/*
$nameがセットされていれば$nameを返す
  function get_session($name){
    if(isset($_SESSION[$name]) === true){
      return $_SESSION[$name];
    };
    return '';
  }
*/

// user_idが空白でなければLOGIN_URLに移動する
if(is_logined() === false){
/*
　function is_logined(){
    return get_session('user_id') !== '';
  }
*/
  redirect_to(LOGIN_URL);
/*
  function redirect_to($url){
    header('Location: ' . $url);
    exit;
  }
*/
}

// データベースに接続
$db = get_db_connect();

// ログインしているユーザーIDの取得
$user = get_login_user($db);
/*
  function get_login_user($db){
    $login_user_id = get_session('user_id');
    return get_user($db, $login_user_id);
  }
*/

// $userがUSER_TYPE_ADMINでなければLOGIN_URLに移動する
if(is_admin($user) === false){
/*
  function is_admin($user){
    return $user['type'] === USER_TYPE_ADMIN;
  }
*/
  redirect_to(LOGIN_URL);
}

// item_idがセットされていれば$item_idに代入する
$item_id = get_post('item_id');

// changes_toがセットされていれば$changes_toに代入する
$changes_to = get_post('changes_to');
/*
  function get_post($name){
    if(isset($_POST[$name]) === true){
      return $_POST[$name];
    };
    return '';
  }
*/

// changes_toがopenであればステータスをITEM_STATUS_OPENに変更する
// changes_toがcloseであればステータスをITEM_STATUS_CLOSEに変更する
// ？LIMIT 1
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}

// ADMIN_URLに移動する
redirect_to(ADMIN_URL);
/*
  function redirect_to($url){
    header('Location: ' . $url);
    exit;
  }
*/
}
