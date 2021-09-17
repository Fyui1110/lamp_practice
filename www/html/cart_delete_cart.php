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

// cart_idがセットされていれば$cart_idに代入する
$cart_id = get_post('cart_id');

// カートの中身を削除する
if(delete_cart($db, $cart_id)){
/*
  function delete_cart($db, $cart_id){
    $sql = "
      DELETE FROM
        carts
      WHERE
        cart_id = {$cart_id}
      LIMIT 1
    ";

    return execute_query($db, $sql);
  }
*/
  set_message('カートを削除しました。');
} else {
  set_error('カートの削除に失敗しました。');
}

// CART_URLに移動する
redirect_to(CART_URL);