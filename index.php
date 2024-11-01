<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スコアボード</title>
    <style>
        /* ビデオの設定 */
        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* スコアボード全体を中央に配置 */
        .scoreboard {
            position: relative;
            height: 100vh;
        }

        /* スコアボードの背景 */
        .scoreboard-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 20px;
            padding: 40px 20px; /* 上部のpaddingを小さく、下部のpaddingを広く */
            z-index: 0;
            font-size: 1.2em;
            font-weight: bold;
            color: #FFF;
            flex: 1;
        }

        h1{
            margin: 0;
        }

        p{
            font-size: 2.2rem;
            text-align: center;
        }

        /* タイトルのスタイル */
        .title img {
            width: auto;
            height: auto;
            max-width: 100%; /* 元の比率を保ちながら最大幅を設定 */
            max-height: 60px; /* 最大高さを指定してサイズを調整 */
        }

        /* スコアアイテムのスタイル */
        .score-item {
            display: flex;
            align-items: center;
            background-color: #FFD54F;
            border-radius: 25px;
            margin: 10px 0;
            padding: 7px 25px;
            width: 400px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            font-family: Arial, sans-serif;
            z-index: 1;
        }

        /* 番号画像 */
        .score-item .rank-number img {
            width: auto;
            height: auto;
            max-width: 50px; /* 元の比率を保ちながら最大幅を設定 */
            max-height: 50px; /* 最大高さを指定してサイズを調整 */
            margin-right: 15px;
        }

        /* 名前とスコアのテキスト部分 */
        .score-item .name {
            font-size: 1.3em;
            font-weight: bold;
            color: #333;
            flex: 1;
        }

        .score-item .score {
            font-size: 1.6em;
            font-weight: bold;
            color: #333;
        }

        /* 色別のスタイル */
        .score-item.gold {
            background-color: #FFD54F;
        }

        .score-item.silver {
            background-color: #80DEEA;
        }

        .score-item.bronze {
            background-color: #EEEEEE;
        }
    </style>
</head>
<body>
    <!-- 背景ビデオ -->
    <video class="background-video" autoplay loop muted>
        <source src="videoplayback.mp4" type="video/mp4">
        お使いのブラウザは動画をサポートしていません。
    </video>

    <!-- スコアボードの裏に背景を追加 -->
    <div id="scoreboard">
        <div class="scoreboard-container">
            <!-- ルール説明 -->
            <h1>～ ルール ～</h1>
            <p>制限時間: 3分, 弾数: 10個<br>動く的は5点、動かない的は1点<br>ハイスコア目指して頑張ろう!!</p>
            <!-- タイトル画像 -->
            <div class="title">
                <img src="ranking-title.png" alt="ランキング">
            </div>
            <!-- ランキング -->
            <div id="ranking-list"></div>
        </div>
    </div>

    <script>
        // ランキングデータを取得して表示を更新する関数

        function fetchRanking() {
            fetch('get_ranking.php')
                .then(response => response.json())
                .then(data => {
                    // ランキングリストのHTMLを生成
                    let rankingList = '';
                    const rankClasses = ['gold', 'silver', 'bronze']; // 各順位に対応するクラス
                    for (let rank = 1; rank <= 3; rank++) {
                        if (data[rank]) {
                            const { name, score } = data[rank];
                            const rankClass = rankClasses[rank - 1];
                            rankingList += `<div class="score-item ${rankClass}"><div class="rank-number"><img src="rank${rank}.png" alt="${rank}"></div><div class="name">${name}</div><div class="score">${score} 点</div></div>`;
                        }
                    }
                    document.getElementById('ranking-list').innerHTML = rankingList;
                })
                .catch(error => console.error('Error fetching ranking:', error));
        }

        // ページロード時と、5秒ごとにfetchRankingを実行
        document.addEventListener('DOMContentLoaded', fetchRanking);
        setInterval(fetchRanking, 5000);
    </script>
    
</body>
</html>