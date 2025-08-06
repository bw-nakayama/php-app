<?php
require_once('config.php');

// PDOクラスのインスタンス化
function connectPdo()
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);  //例外発生時はPDOExceptionをスローする
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}
//仲介役。とはいえ、常につながっているわけではなくメソッドを使用するたびにつないでくれてる
//コンストラクタでどのような処理がされているか
//指定されたデータベースに接続し、接続に失敗した場合は例外をスローする
//それとも、単純に指定されたデータベースへの接続を表すPDOインスタンスを生成、でいいのか

//例外と通常のエラーの違い
//通常のエラーはどうあれ該当の箇所が実行されるとエラーになる コードは正しいが環境要因によっておこる＝＞例外
//例外は該当箇所が特定の状態になるーーたとえば、特定の値が入ってきたときーーと発生するエラー
//try文の中でthrowが実行されるとその時点でcatch文が動き始める。
//PDOクラスのコンストラクタ内にthrowが入っている。　throwはさすがに言語構造では？

// 新規作成処理
function createTodoData($todoText)
{
    $dbh = connectPdo();
    $sql = 'INSERT INTO todos (content) VALUES (:todoText)'; //編集
    $stmt = $dbh->prepare($sql); //追記
    $stmt->bindValue(':todoText', $todoText, PDO::PARAM_STR); //追記
    $stmt->execute(); //追記
}
//todosテーブルにcontentカラムが$todoTextであるレコードをinsertを使用して追加している

// 更新処理
function updateTodoData($post)
{
    $dbh = connectPdo();
    $sql = 'UPDATE todos SET content = :todoText WHERE id = :id'; //編集
    $stmt = $dbh->prepare($sql); //編集
    $stmt->bindValue(':todoText', $post['content'], PDO::PARAM_STR); //編集
    $stmt->bindValue(':id', (int)$post['id'], PDO::PARAM_INT); //編集
    $stmt->execute(); //編集
}

function getTodoTextById($id)
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL AND id = :id';//編集
    $stmt = $dbh->prepare($sql); //編集
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT); //編集
    // var_dump($stmt->execute());
    // exit();
    $stmt->execute();
    $data = $stmt->fetch(); //こっちは一行だけ取っているから連想配列
    return $data['content'];
}

//削除処理
function deleteTodoData($id)
{
    $dbh = connectPdo();
    $now = date('Y-m-d H:i:s');
    $sql = 'UPDATE todos SET deleted_at = "' . $now . '" WHERE id = :id';
    $stmt = $dbh->prepare($sql); //編集
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT); //編集
    $stmt->execute();
}
//$stmtはpdostatementクラスのインスタンスが格納された変数。
//Updateのqueryメソッドにも戻り値としてPDOStatementクラスのインスタンスがあるが
//それの保持する結果セットってなんだ？　ないらしい（真偽不明）

//データの取得処理
function getAllRecords()
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
    return $dbh->query($sql)->fetchAll();
}
//DBから取ってきた結果を配列の形で返している　2次元連想配列かしら

//ところでいちいちインスタンス化する必要はあるのだろうか。　一度インスタンス化したものをとっておければいいような気もする
//まあ、その分場所をとるから一概にどちらがどうとは言えないのだけれども
//ページ遷移で消滅する？　まあ、それはそう。