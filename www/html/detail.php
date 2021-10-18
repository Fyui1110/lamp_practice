<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'detail.php';

// セッションの開始
session_start();

// user_idが空白でなければLOGIN_URLに移動する
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// トークンのチェック
$token  = get_post('token');
if (is_valid_csrf_token($token) === false){
  redirect_to(LOGIN_URL);
}

// トークンの破棄
unset($_SESSION['csrf_token']);

// データベースに接続
$db = get_db_connect();

// ログインしているユーザーIDの取得
$user = get_login_user($db);

// 送信されてきたデータの取得
// order_id、購入日時、合計金額
$order_id    = get_post('order_id');
$order_dated = get_post('order_dated');
$total = get_post('total');

$check_user = check_user($db, $order_id);

if (is_admin($user) === false && $check_user['user_id'] !== $user['user_id']){
  set_error("不正な処理が行われました");
  redirect_to(LOGIN_URL);
}

// 購入明細情報の取得
// $db、order_idを引数に渡す
//「商品名」「購入時の商品価格」 「購入数」「小計」
$details = get_user_detail($db, $order_id);

// テンプレートファイル読み込み
include_once VIEW_PATH . 'detail_view.php';