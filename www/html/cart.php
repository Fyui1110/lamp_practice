<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

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

// カート情報の取得
$carts = get_user_carts($db, $user['user_id']);

// 合計金額の計算
$total_price = sum_carts($carts);

// トークンの生成
$token = get_csrf_token();

// テンプレートファイル読み込み
include_once VIEW_PATH . 'cart_view.php';