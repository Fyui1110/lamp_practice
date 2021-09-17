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
/*
  function get_user_carts($db, $user_id){
    $sql = "
      SELECT
        items.item_id,
        items.name,
        items.price,
        items.stock,
        items.status,
        items.image,
        carts.cart_id,
        carts.user_id,
        carts.amount
      FROM
        carts
      JOIN
        items
      ON
        carts.item_id = items.item_id
      WHERE
        carts.user_id = {$user_id}
    ";
    return fetch_all_query($db, $sql);
  }
*/

// 合計金額の計算
$total_price = sum_carts($carts);
/*
  function sum_carts($carts){
    $total_price = 0;
    foreach($carts as $cart){
      $total_price += $cart['price'] * $cart['amount'];
    }
    return $total_price;
  }
*/

// テンプレートファイル読み込み
include_once VIEW_PATH . 'cart_view.php';