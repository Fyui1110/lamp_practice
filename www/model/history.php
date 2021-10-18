<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// 購入履歴情報の取得
// $db、$user_idを引数に渡す
//「注文番号(order_id)」「購入日時(order_dated)」「該当の注文の合計金額」
// historyテーブルとdetailテーブルを結合
// ？疑問点 $user_idごとにグルーピングし、合計金額を計算(amount * order_price)
function get_user_history($db, $user_id){
  $sql = "
    SELECT
        history.order_id,
        history.order_dated,
        SUM(detail.amount*detail.order_price) AS total
    FROM
        history
    JOIN
        detail
    ON
        history.order_id = detail.order_id
    WHERE
        history.user_id = :user_id
    GROUP BY
        detail.order_id
    ORDER BY
        order_dated DESC;
      ";
  return fetch_all_query($db, $sql, array(':user_id' => $user_id));
}

function get_all_history($db){
    $sql = "
      SELECT
          history.order_id,
          history.order_dated,
          SUM(detail.amount*detail.order_price) AS total
      FROM
          history
      JOIN
          detail
      ON
          history.order_id = detail.order_id
      GROUP BY
          detail.order_id
      ORDER BY
        order_dated DESC;
      ";
    return fetch_all_query($db, $sql);
  }