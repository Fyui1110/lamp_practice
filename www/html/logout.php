<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// セッションの開始
session_start();

// 変数の初期化
$_SESSION = array();

// sessionに関連する設定を取得
$params = session_get_cookie_params();

// sessionに利用しているクッキーの有効期限を過去に設定することで無効化
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
// セッションIDを無効化
session_destroy();

// LOGIN_URLに移動する
redirect_to(LOGIN_URL);

