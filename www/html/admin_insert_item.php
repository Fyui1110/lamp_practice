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

// imageがセットされていれば$imageに代入する
$image = get_file('image');

// ？
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}

// ADMIN_URLに移動する
redirect_to(ADMIN_URL);