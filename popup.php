<?php
    // ------------------------------------------------------------自身へのPOSTかつ、ボタン毎の処理分岐
    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // ポスト通信があったかを判別
        if (isset($_POST['command'])) {         // ボタンタグに付随するinputタグのname=""の部分　キー名自分で指定
            switch ($_POST['command']) {                 // ここからボタン毎の処理分岐
                case 'bt-A':
                    break;
                case 'bt-B':
                    break;
                case 'bt-C':
                    break;
                case 'bt-D':
                    break;
                default:
                    break;
                }
        }
        // POST変数の中身を削除するなどの操作は特別必要ない多分
    }

    // ------------------------------------------------------------GET
    // $target_url = "https://floor02.sakura.ne.jp/";
    ob_start();
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['r'])) {
            switch ($_GET['r']) {
                case 'cal':
                    header("location:https://script.google.com/macros/s/AKfycby-wO1PTraJU2DUzgNVDlRxJsKLL0OhcDQ8uwrqrU5uDTDlRGyEhwRRQ2wr1Tqrzot2/exec");
                    exit();
                    break;
                default:
                    break;
            }
        }
    }
    ob_end_flush();

    ?>

        <!DOCTYPE html>
        <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="icon" href="./img/icon.png">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=DotGothic16&display=swap" rel="stylesheet"></head>
            <title>中継地点</title><!-- ブラウザタブに表示される文字 -->
            <!-- 240705_1805 -->
            <style>
                body {
                    background-color: #dfdfdf;
                    font-family: 'DotGothic16', sans-serif;
                    margin: 0;
                }
                p {
                    margin: 0;
                }
                a { /* スマホでボタンをタップした時に青くなるのを防ぐ */
                    -webkit-tap-highlight-color: rgba(0, 0, 0, 0); /* 透明にする */
                }


                .button-area {    /* ボタンの見た目 */
                    position: relative;
                    width: 300px;
                    height: 200px;
                    background-color: #fff;
                    border: 2px solid #000000;
                    /* display: flex;
                    justify-content: center; */
                    align-items: center;
                    overflow: hidden;   /* 子要素がはみ出さないようにする */
                }
                .button-area button{     /* ボタンそのもののスタイル */
                    position: absolute;
                    top: 0;
                    left: 0;
                    height: 100%;
                    width: 100%;
                    /* buttonのスタイル削除 */
                    background-color: transparent;
                    border: none;
                    cursor: pointer;
                    outline: none;
                    padding: 0;
                    appearance: none;
                }
                .button-area:active{     /* ボタンが押されている間のスタイル */
                    background-color: #e5c0cb;
                    border: 2px solid #e53368;
                }
                .button-area:active > .title-area{
                    background-color: #e53368;
                }
                .img-area > img {
                    object-fit: cover;  /* 画像を、div要素に空白ができないように収める。縦横比を維持。 */
                    transition: transform 0.15s;    /* 画像拡大にかかる時間 */
                    width: 100%;
                    height: 100%;
                }
                .img-area:hover > img {
                    transform: scale(1.05);
                }


                /* スマホで開いたとき */
                @media screen and (max-width: 767px) and (orientation: portrait) {
                    .all {  /* 幅400pxでスマホでもみやすい（@mediaを使わないときに） */
                        width: 400px;
                        margin-left: calc((100% - 400px) / 2);
                    }
                    .container {
                        flex-direction: column;
                        width: 100%;
                        padding-inline: 0;
                    }
                    .button-area {
                        margin-left: calc((100% - (300px)) / 2);
                    }
                }
                /* PCで開いたとき */
                @media screen and (min-width: 767px) {
                    .all {
                    }
                    .container {
                        margin:auto;
                        justify-content: center;
                        align-content: center;
                        width: 80%;
                    }
                    .button-area {
                        /* margin-top: 10px;
                        margin-left: 10px; */
                    }
                }


            </style>
        </head>
        <body>
            <!--
            - div要素全体をボタンにする方法
                - div > input, buttonの順に要素を配置
                - 親→div、子→buttonととらえ、
                - 親要素にはボタンの見た目を。子要素はpoisition:absoluteとスタイルの削除を。

            - 自身index.phpにPOSTで送り、複数あるボタン毎に別の処理を行う方法
                - ↑のボタン複数個を1つのformタグで囲う。（formタグは１つでOK、divごと）
                - inputのtypeはhidden
                - inputのnameはPOST変数のキー名
                - inputのvalueはPOST変数のキーに格納する値
                - buttonのtypeはsubmit　で、他のオプションは不要
                キーと値でどう処理を分けるか
                - name（キー）は1つで十分　例）command　←処理の 種類 で分けるのも良いかもしれない
                - value（値）をボタン（操作したい事柄）毎に用意し、ここで処理を分岐させる。例）start, stop, sort, ...
                - ボタンが押されたとき、設定したinputのnameで指定したキー command に、
                　そのボタンのvalueが文字列として、格納されていく。配列。
                - phpで次をチェックし処理を分岐する。
                    - ポスト通信が行われたかどうか
                    - ポスト変数の中にキー名が1つ以上登録されているかどうか
                    - そのキーの中の値が何か。
                    - ★↑これらはこのスクリプト上部のコード参照
            -->

            <!-- 
            - 自身index.phpにGETで送り、複数あるボタン毎に別の処理を行う方法
              - POSTでは、header遷移が何故か使えなかった
              - POSTとの違う点は、formタグをボタン毎に用意すること。
                - 一つにしてしまうと、rというキーにvalurに入れているすべてのパラメータ(bkr, kuji, ...)が送信されてしまう。
            -->
        <div class="all">
            <form action="index.php" method="GET">
                <div class="button-area">
                    <p style="text-align: center; margin: 0; padding-top: 6px;">スプラ3用ブキルーレット</p>
                </div>
            </form>

            <form action="index.php" method="GET">
                <div class="button-area">
                    <p style="text-align: center; margin: 0; padding-top: 6px;">スプラ3用ブキルーレット</p>
                </div>
            </form>
        </div>

        </body>
        </html>
