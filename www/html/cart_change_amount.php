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

// セットされていれば代入する
$cart_id = get_post('cart_id');
$amount  = get_post('amount');

// カート内の商品の数量を変更
if(update_cart_amount($db, $cart_id, $amount)){
/*
  function update_cart_amount($db, $cart_id, $amount){
    $sql = "
      UPDATE
        carts
      SET
        amount = {$amount}
      WHERE
        cart_id = {$cart_id}
      LIMIT 1
    ";
    return execute_query($db, $sql);
  }
*/
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}

// CART_URLに移動する
redirect_to(CART_URL);