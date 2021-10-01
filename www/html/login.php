<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// セッションの開始
session_start();

// user_idが空白でなければLOGIN_URLに移動する
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// トークンの生成
$token = get_csrf_token();

// テンプレートファイル読み込み
include_once VIEW_PATH . 'login_view.php';