<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>新規作成</title>
  </head>
  <body>
    <h1>あなたのcookie情報</h1>
    <ul>
      <?php foreach ($_GET as $item => $content): ?>
        <li>
          <?= $item ?><span>: </span><?= $content ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </body>
</html>