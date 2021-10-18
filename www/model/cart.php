<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// ユーザー別のカート内の商品データを取得
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
      carts.user_id = :user_id
  ";
  // SQL文の実行
  return fetch_all_query($db, $sql, array(':user_id' => $user_id));
}

//？
// カート内の商品データを取得($user_idと$item_idが一致するもの)
function get_user_cart($db, $user_id, $item_id){
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
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";

  return fetch_query($db, $sql, array(':user_id' => $user_id, ':item_id' => $item_id));

}

// ？ $cart === false = $cartのSQL文が実行できなかった場合？
// カートに追加する
// $cart === falseであれば商品を新規追加する
// $cart === falseでなければ個数を増やす
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

// カートに商品を新規追加する
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";

  return execute_query($db, $sql, array(':item_id' => $item_id, ':user_id' => $user_id, ':amount' => $amount));
}

// カートの商品個数を変更する
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  return execute_query($db, $sql, array(':amount' => $amount, ':cart_id' => $cart_id));
}

// カート内を削除する
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  return execute_query($db, $sql, array(':cart_id' => $cart_id));
}

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

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";

  execute_query($db, $sql, array(':user_id' => $user_id));
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  // カートが空の場合、エラーメッセージを追加しfalseを返す
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    // 未公開の商品がある場合、エラーメッセージを追加しfalseを返す
    if(is_open($cart) === false){ //is_openの戻り値はtrueかfalse
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 在庫数が購入数より少ない場合、エラーメッセージを追加しfalseを返す
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  // エラーが0個でない場合はfalseを返す
  // isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
  if(has_error() === true){
    return false;
  }
  // 上記以外はtrueを返す
  return true;
}

// 購入履歴テーブルに追加
function add_history($db, $user_id) {
  $sql = "
    INSERT INTO
      history(
        user_id
      )
    VALUES(:user_id)
  ";
  
  return execute_query($db, $sql, array(':user_id' => $user_id));
}

// 明細テーブルへ追加するSQL文
function add_detail($db, $order_id, $item_id, $amount, $order_price) {
  $sql = "
    INSERT INTO
      detail(
        order_id,
        item_id,
        amount,
        order_price
      )
    VALUES(:order_id, :item_id, :amount, :order_price)
  ";
  return execute_query($db, $sql, array(':order_id' => $order_id, ':item_id' =>$item_id, ':amount' => $amount, ':order_price' => $order_price));
    }

// 明細テーブルへ追加
// foreachで繰り返し処理する
function add_details($db, $order_id, $carts) {
  foreach ($carts as $value) {
    if(add_detail(
        $db,
        $order_id,
        $value['item_id'],
        $value['amount'],
        $value['price']
      ) === false) {
      return false;
    }
  }
  return true;
}
