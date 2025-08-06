<?php
require_once('functions.php');
setToken();
$todo = getSelectedTodo($_GET['id']);//url/?id=n の n を引数に入れている
// var_dump($_GET);
// exit();
//スーパーグローバル変数はどこで定義されているか
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>編集</title>
</head>
<body>
  <?php if (!empty($_SESSION['err'])): ?>
    <p><?= $_SESSION['err']; ?></p>
  <?php endif; ?>
  <form action="store.php" method="post">
    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
    <input type="hidden" name="id" value="<?= e($_GET['id']); ?>">
    <input type="text" name="content" value="<?= e($todo); ?>">
    <input type="submit" value="更新">
  </form>
  <div>
    <a href="index.php">一覧へ戻る</a>
  </div>
  <?php unsetError(); ?>
</body>
</html>