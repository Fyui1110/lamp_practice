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

// item_idがセットされていれば$item_idに代入する
$item_id = get_post('item_id');

// ？
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
} else {
  set_error('商品削除に失敗しました。');
}

// ADMIN_URLに移動する
redirect_to(ADMIN_URL);