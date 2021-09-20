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

// カートに商品がなければ？
// カートに商品があれば、カート内の商品を削除する
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  // CART_URLに移動する
  redirect_to(CART_URL);
} 

// 合計金額の計算
$total_price = sum_carts($carts);

// テンプレートファイル読み込み
include_once '../view/finish_view.php';