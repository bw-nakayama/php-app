<?php
require_once('config.php');

// PDOクラスのインスタンス化
function connectPdo()
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}
//仲介役。

// 新規作成処理
function createTodoData($todoText)
{
    $dbh = connectPdo();
    $sql = 'INSERT INTO todos (content) VALUES ("' . $todoText . '")';
    $dbh->query($sql);
}
//todosテーブルにcontentカラムが$todoTextであるレコードをinsertを使用して追加している

// 更新処理
function updateTodoData($post)
{
    $dbh = connectPdo();
    $sql = 'UPDATE todos SET content = "' . $post['content'] . '" WHERE id = ' . $post['id'];
    $dbh->query($sql);
}

function getTodoTextById($id)
{
    $dbh = connectPdo();
    $sql = "SELECT * FROM todos WHERE deleted_at IS NULL AND id = $id";
    $data = $dbh->query($sql)->fetch();
    return $data['content'];
}

//削除処理
function deleteTodoData($id)
{
    $dbh = connectPdo();
    $now = date('Y-m-d H:i:s');
    $sql = 'UPDATE todos SET deleted_at = "' . $now . '" WHERE id = ' . $id;
    $dbh->query($sql);
}

//データの取得処理
function getAllRecords()
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
    return $dbh->query($sql)->fetchAll();
}
//DBから取ってきた結果を配列の形で返している

//ところでいちいちインスタンス化する必要はあるのだろうか。　一度インスタンス化したものをとっておければいいような気もする
//まあ、その分場所をとるから一概にどちらがどうとは言えないのだけれども