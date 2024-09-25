<?php
// MySQLデータベースへの接続
$servername = "localhost";
$username = "root";  // 自分のDBユーザー名
$password = "";      // 自分のDBパスワード
$dbname = "bookmark_app";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラー確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ブックマークの追加処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $url = $conn->real_escape_string($_POST['url']);

    if (!empty($title) && !empty($url)) {
        $sql = "INSERT INTO bookmarks (title, url) VALUES ('$title', '$url')";
        if ($conn->query($sql) === TRUE) {
            echo "新しいブックマークが追加されました！";
        } else {
            echo "エラー: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "タイトルとURLを入力してください。";
    }
}

// ブックマークの一覧取得
$sql = "SELECT * FROM bookmarks ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブックマークアプリ</title>
    <link rel="stylesheet" href="style.css"> <!-- CSSを外部から読み込み -->
</head>
<body>
    <div class="container">
        <h1 class="title">ブックマークアプリ</h1>

        <!-- ブックマーク追加フォーム -->
        <form method="post" action="">
            <input type="text" name="title" placeholder="タイトル" required>
            <input type="url" name="url" placeholder="URL" required>
            <button type="submit">追加</button>
        </form>

        <!-- ブックマーク一覧表示 -->
        <ul class="bookmark-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li>
                        <a href="<?php echo $row['url']; ?>" target="_blank"><?php echo $row['title']; ?></a>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>まだブックマークがありません。</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>

<?php
$conn->close();
?>
