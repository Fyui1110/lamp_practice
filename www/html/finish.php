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
/*
  function purchase_carts($db, $carts){
    if(validate_cart_purchase($carts) === false){
      return false;
    }
    foreach($carts as $cart){
      if(update_item_stock(
          $db, 
          $cart['item_id'], 
          $cart['stock'] - $cart['amount']
        ) === false){
        set_error($cart['name'] . 'の購入に失敗しました。');
      }
    }
    
    delete_user_carts($db, $carts[0]['user_id']);
  }

  function validate_cart_purchase($carts){
    if(count($carts) === 0){
      set_error('カートに商品が入っていません。');
      return false;
    }
    foreach($carts as $cart){
      if(is_open($cart) === false){
        set_error($cart['name'] . 'は現在購入できません。');
      }
      if($cart['stock'] - $cart['amount'] < 0){
        set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
      }
    }
    if(has_error() === true){
      return false;
    }
    return true;
  }

  function update_item_stock($db, $item_id, $stock){
    $sql = "
      UPDATE
        items
      SET
        stock = {$stock}
      WHERE
        item_id = {$item_id}
      LIMIT 1
    ";
    
    return execute_query($db, $sql);
  }
*/
  set_error('商品が購入できませんでした。');
  // CART_URLに移動する
  redirect_to(CART_URL);
} 

// 合計金額の計算
$total_price = sum_carts($carts);

// テンプレートファイル読み込み
include_once '../view/finish_view.php';