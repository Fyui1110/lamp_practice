<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

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

// 商品一覧の取得
$items = get_open_items($db);
/*
  function get_open_items($db){
    return get_items($db, true);
  }

  function get_item($db, $item_id){
    $sql = "
      SELECT
        item_id, 
        name,
        stock,
        price,
        image,
        status
      FROM
        items
      WHERE
        item_id = {$item_id}
    ";

    return fetch_query($db, $sql);
  }
*/

// テンプレートファイル読み込み
include_once VIEW_PATH . 'index_view.php';