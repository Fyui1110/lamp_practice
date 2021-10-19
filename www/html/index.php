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

// ランキングの表示
$ranking = item_ranking($db);

// ランキング順位
$ranking_no = 1;

// トークンの生成
$token = get_csrf_token();

// テンプレートファイル読み込み
include_once VIEW_PATH . 'index_view.php';