<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

// セッションの開始
session_start();

// user_idが空白でなければLOGIN_URLに移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ログインしているユーザーIDの取得
$user = get_login_user($db);

// $userがUSER_TYPE_ADMINでなければLOGIN_URLに移動する
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// セットされていれば代入する
$name   = get_post('name');
$price  = get_post('price');
$status = get_post('status');
$stock  = get_post('stock');
/*
  function get_post($name){
    if(isset($_POST[$name]) === true){
      return $_POST[$name];
    };
    return '';
  }
*/

// imageがセットされていれば$imageに代入する
$image = get_file('image');
/*
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}
*/

// ？
if(regist_item($db, $name, $price, $stock, $status, $image)){
/*
  function regist_item($db, $name, $price, $stock, $status, $image){
    $filename = get_upload_filename($image);
    if(validate_item($name, $price, $stock, $filename, $status) === false){
      return false;
    }
    return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
  }
*/
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}

// ADMIN_URLに移動する
redirect_to(ADMIN_URL);