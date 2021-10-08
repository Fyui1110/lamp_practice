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

// トークンのチェック
$token  = get_post('token');
if (is_valid_csrf_token($token) === false){
  redirect_to(LOGIN_URL);
}

// トークンの破棄
unset($_SESSION['csrf_token']);

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

//トランザクション開始
$db->beginTransaction();

//購入履歴テーブルに追加(add_history関数) 引数に$db、$user_id($user['user_id'])を渡す
//戻り値はtrue(追加成功)、false(書き込みエラー)
$user_id = $user['user_id'];
if (add_history($db, $user_id) === false) {
  set_error('購入履歴が追加できませんでした');
  //もし失敗したらロールバック
  $db->rollback();
  //set_errorにエラーメッセージを追加してCART_URLに移動する
  redirect_to(CART_URL);
}
  
//成功したら$order_idの取得(PDO::lastInsertId)
$order_id = $db -> lastInsertId('order_id');
  
//明細テーブルへ追加(add_detail) 引数に$db、$order_id、$cartsを渡す
//戻り値はtrue(追加成功)、false(書き込みエラー)
if (add_details($db, $order_id, $carts) === false) {
  //set_errorにエラーメッセージを追加してCART_URLに移動する
  set_error('購入明細が追加できませんでした');
  // ロールバック処理
  $db->rollback();
  redirect_to(CART_URL);
}

//購入履歴テーブルと明細テーブルに追加が出来ていればコミット処理
$db->commit();


// トークンの生成
$token = get_csrf_token();

// テンプレートファイル読み込み
include_once '../view/finish_view.php';