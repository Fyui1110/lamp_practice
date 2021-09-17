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

// item_idがセットされていれば$item_idに代入する
$item_id = get_post('item_id');

if(add_cart($db,$user['user_id'], $item_id)){
/*
  function add_cart($db, $user_id, $item_id ) {
    $cart = get_user_cart($db, $user_id, $item_id);
    if($cart === false){
      return insert_cart($db, $user_id, $item_id);
    }
    return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
  }

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

  function insert_cart($db, $user_id, $item_id, $amount = 1){
    $sql = "
      INSERT INTO
        carts(
          item_id,
          user_id,
          amount
        )
      VALUES({$item_id}, {$user_id}, {$amount})
    ";

    return execute_query($db, $sql);
  }

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
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

// HOME_URLに移動する
redirect_to(HOME_URL);