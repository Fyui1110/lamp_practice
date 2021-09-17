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

// ステータスがopen(=1)の商品データの取得
$items = get_all_items($db);

// テンプレートファイル読み込み
include_once VIEW_PATH . '/admin_view.php';
