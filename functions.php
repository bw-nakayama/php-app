<?php
require_once('connection.php');
session_start();    //セッションをスタートさせる　PHP組み込み関数
//セッションはデータを一時的に保存するサーバ側の領域。

function getSelectedTodo($id)
{
    return getTodoTextById($id); 
}

function savePostedData($post)
{
    checkToken($post['token']); // 追記
    $path = getRefererPath();
    switch ($path) {
        case '/new.php':
            createTodoData($post['content']);
            break;
        case '/edit.php':
            updateTodoData($post);
            break;
        case '/index.php':
            deleteTodoData($post['id']);
            break;
        default:
            break;
    }
}

//まず、$_POSTとか$_SERVER=>スーパーグローバル変数（すべてのスコープで使用可能）
/*$_SERVERはサーバの情報などが連想配列として入っている
$_SERVER['HTTP_REFERER']は遷移元のページのアドレスが格納される
引数を1つのみ指定したparse_urlはurlを情報ごとに分け、連想配列の形で返す。
pathキーに対応する値がurlのパス。それを返している。　文字列型。
*/
function getRefererPath() //こいつの説
{
    $urlArray = parse_url($_SERVER['HTTP_REFERER']);
    // var_dump($urlArray);
    // exit();
    return $urlArray['path'];
} //クエリメソッドの返り値　PDOStatementクラスのインスタンス　fetchAllはPDOStatementクラスのメソッド


function getTodoList()
{
    return getAllRecords();
}

//エスケープ処理
//定義しなおすことで省略しているし攻撃者側もどこでエスケープ処理を行っているかが分かりづらくなる
//XSSとは動的Webページ（アクセス時に内容が生成される）の生成処理内に任意のスクリプトを紛れ込ませ
//攻撃方法。　クロスサイトスクリプティング。
//cookie情報の盗難などが発生する。
//ユーザのブラウザに保存されるWebサイトの情報でログイン情報などが含まれる
//htmlspecialcharsはhtmlにとってspecialなcharsを第二引数の内容に応じて変換する。
//"",'',<>が変換される。
function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// SESSIONにtokenを格納する
/*openssl_random_pseudo_bytesの戻り値はstring
bin2hexもstringを引数にstringを返す。
16桁の2進数を4桁の16進数に変換して格納している。
*/
function setToken()
{
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
}

// SESSIONに格納されたtokenのチェックを行い、SESSIONにエラー文を格納する
function checkToken($token)
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
    }
}

function unsetError()
{
    $_SESSION['err'] = '';
}

//遷移元のページに戻し実行を止める
function redirectToPostedPage()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
//トークンは第三者のページが確認することができないもの。
//ページに入ってから出るまでしか持続しないので
//今回のswitch文と組み合わせることではじける？