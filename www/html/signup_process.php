<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

// セッションの開始
session_start();

// user_idが空白でなければLOGIN_URLに移動する
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// セットされていれば代入する
$name     = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

// データベースに接続
$db = get_db_connect();

try{
  $result = regist_user($db, $name, $password, $password_confirmation);
  /*
    function regist_user($db, $name, $password, $password_confirmation) {
      if( is_valid_user($name, $password, $password_confirmation) === false){
        return false;
      }
      return insert_user($db, $name, $password);
    }

    function is_valid_user($name, $password, $password_confirmation){
      // 短絡評価を避けるため一旦代入。
      $is_valid_user_name = is_valid_user_name($name);
      $is_valid_password = is_valid_password($password, $password_confirmation);
      return $is_valid_user_name && $is_valid_password ;
    }

    function is_valid_password($password, $password_confirmation){
      $is_valid = true;
      if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
        set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
        $is_valid = false;
      }
      if(is_alphanumeric($password) === false){
        set_error('パスワードは半角英数字で入力してください。');
        $is_valid = false;
      }
      if($password !== $password_confirmation){
        set_error('パスワードがパスワード(確認用)と一致しません。');
        $is_valid = false;
      }
      return $is_valid;
    }

    function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
      $length = mb_strlen($string);
      return ($minimum_length <= $length) && ($length <= $maximum_length);
    }
*/
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}

set_message('ユーザー登録が完了しました。');

login_as($db, $name, $password);
/*
  function login_as($db, $name, $password){
    $user = get_user_by_name($db, $name);
    if($user === false || $user['password'] !== $password){
      return false;
    }
    set_session('user_id', $user['user_id']);
    return $user;
  }

  function get_user_by_name($db, $name){
    $sql = "
      SELECT
        user_id, 
        name,
        password,
        type
      FROM
        users
      WHERE
        name = '{$name}'
      LIMIT 1
    ";
    return fetch_query($db, $sql);
  }
*/

// HOME_URLに移動する
redirect_to(HOME_URL);