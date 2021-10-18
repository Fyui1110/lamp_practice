<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// 購入明細情報の取得
// $db、order_idを引数に渡す
//「商品名(name)」「購入時の商品価格(order_price)」 「購入数(amount)」「小計(sumtotal)」
// detailテーブルとitemsテーブルを結合
function get_user_detail($db, $order_id){
  $sql = "
  SELECT
  items.name,
  detail.amount,
  detail.order_price,
  detail.amount* detail.order_price AS subtotal
FROM
  detail
JOIN
  items
ON
  detail.item_id = items.item_id
WHERE
 detail.order_id = :order_id
;
    ";
  return fetch_all_query($db, $sql, array(':order_id' => $order_id));
}

function check_user($db, $order_id){
  $sql = "
      SELECT
          user_id
      FROM
          history
      WHERE
          order_id = :order_id
      ";
  return fetch_query($db, $sql, array(':order_id' => $order_id));
}
