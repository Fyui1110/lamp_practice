<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';

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

// 購入履歴情報の取得
if (is_admin($user) === false){
  $histories = get_user_history($db, $user['user_id']);
}else{
  $histories = get_all_history($db);
}

// トークンの生成
$token = get_csrf_token();

// テンプレートファイル読み込み
include_once VIEW_PATH . 'history_view.php';

// order_id、$token、購入日時、合計金額をviewで送信