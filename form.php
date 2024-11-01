<?php
session_start();

// ランキングデータの初期化
if (!isset($_SESSION['ranking'])) {
    $_SESSION['ranking'] = [
        1 => ['name' => '', 'score' => 0],
        2 => ['name' => '', 'score' => 0],
        3 => ['name' => '', 'score' => 0]
    ];
}

// フォームが送信されたときの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        // リセットボタンが押された場合、ランキングデータを初期化
        $_SESSION['ranking'] = [
            1 => ['name' => '', 'score' => 0],
            2 => ['name' => '', 'score' => 0],
            3 => ['name' => '', 'score' => 0]
        ];
    } else {
        // 新しいエントリーの処理
        $name = $_POST['name'];
        $score = (int)$_POST['score'];

        // 現在のランキングを取得
        $ranking = $_SESSION['ranking'];

        // 新しいスコアをランキングに挿入する処理
        for ($i = 1; $i <= 3; $i++) {
            if ($score > $ranking[$i]['score']) {
                // 下位の順位をずらす
                for ($j = 3; $j > $i; $j--) {
                    $ranking[$j] = $ranking[$j - 1];
                }
                
                // 順位の更新（重複チェック）
                if ($score === $ranking[$i]['score'] && !empty($ranking[$i]['name'])) {
                    $ranking[$i]['name'] .= "<br>" . $name; // 同じ順位の場合、名前を連結
                } else {
                    $ranking[$i] = ['name' => $name, 'score' => $score]; // 新しい順位として設定
                }
                break;
            } elseif ($score === $ranking[$i]['score']) {
                // 同点で既にその順位の点数がある場合、名前を追加
                $ranking[$i]['name'] .= "<br>" . $name;
                break;
            }
        }

        // 更新したランキングをセッションに保存
        $_SESSION['ranking'] = $ranking;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スコア入力フォーム</title>
</head>
<body>
    <!-- 入力フォーム -->
    <h1>ランキングの追加</h1>
    <form method="post" action="form.php">
        <label for="name">名前:</label>
        <input type="text" name="name" id="name" required>
        <label for="score">点数:</label>
        <input type="number" name="score" id="score" required min="0">
        <button type="submit">追加</button>
    </form>

    <!-- リセットボタン -->
    <form method="post" action="form.php" style="margin-top: 20px;">
        <button type="submit" name="reset">ランキングをリセット</button>
    </form>

    <!-- ランキングの表示 -->
    <h2>現在のランキング</h2>
    <ol>
        <?php foreach ($_SESSION['ranking'] as $rank => $data): ?>
            <li>
                <strong><?= $rank ?>位:</strong> <?= htmlspecialchars(str_replace("<br>", ", ", $data['name'])) ?> - <?= $data['score'] ?>点
            </li>
        <?php endforeach; ?>
    </ol>

    <p style="color: red;">点数は半角英数字で入力してください。<br>更新には数秒かかるため、連続で追加しないでください。</p>
</body>
</html>
