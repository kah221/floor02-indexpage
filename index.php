<?php
    // ------------------------------------------------------------
    // 現在時刻取得
    date_default_timezone_set('Asia/Tokyo');
    $dt = new DateTime('now');
    $now = $dt -> format("ymdHi");
    $dt_str = $dt -> format("ymd_Hi");

    // ------------------------------------------------------------アクセスログ
    $acfile = fopen('a-count.dat', 'r+b');    // アクセスカウンタファイルを開く
    flock($acfile, LOCK_EX);
    $a_count = fgets($acfile);

    $wflag = false;
    session_start();
    if (!isset($_SESSION['memory'])) {   // セッション変数がなかった場合（おはつor久しぶり）
        $_SESSION['memory'] = $now; // 用意
        $a_count++;   // .datファイルの中を更新！
        $wflag = true;    // timestamp書き込みのフラグを立てる
    } elseif ($_SESSION['memory'] != $now){    // 訪問して1分経過してからF5 →
        $_SESSION['memory'] = $now;
        $a_count++;
        $wflag = true;    // timestamp書き込みのフラグを立てる
    }
    // echo "変数now".$now."<br>";
    // echo "セッション変数".$_SESSION['memory']."<br>";
    // echo $a_count;

    // ------------------------------------------------------------アクセス時刻ログ
    $tmfile = fopen('a-time.dat', 'r+b');    // アクセス時刻ログファイルを開く
    flock($tmfile, LOCK_EX);
    // timestampファイルを1行ずつ読み込み、最後の行を変数へ代入
    if($tmfile){
        while (!feof($tmfile)) {
            $latest = fgets($tmfile);
        }
    }
    // ここで最終訪問時刻を更新してファイルを閉じる
    if ($wflag) {
        fwrite($tmfile, "\n".$dt_str);
    }
    fclose($tmfile);


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
                case 'bkr':
                    header("Location:./room/buki-roulette/");
                    exit();
                    break; 
                case 'kuji':
                    header("Location:./room/kuji/");
                    exit();
                    break;

                // カレンダーだけは特別
                // ボタンが押されると、まずポップアップ画面を表示させる
                // ↓この処理は、ポップアップ画面の中の、「移動する」ボタンが押されたときに実行させる
                case 'cal':
                    // header("location:https://script.google.com/macros/s/AKfycby-wO1PTraJU2DUzgNVDlRxJsKLL0OhcDQ8uwrqrU5uDTDlRGyEhwRRQ2wr1Tqrzot2/exec");
                    // 240827_2251githubで公開するので環境変数対応後の
                    header("Location:https://script.google.com/macros/s/AKfycbzahTfwazUMzmwiOD3wzxXP3WLs7zOFB2_avFWo-VgeZHWjOCdaUJiwX5PbZKMAiQis/exec");
                    exit();
                    break;
                default:
                    break;
            }
        }
    }
    ob_end_flush();
    ?>
    <!-- ポップアップの処理 -->
    <style>
        #popup {
            /* display: none; */
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

                /* popup画面のボタンの見た目 */
                .popup-button-area {
                    position: relative;
                    width: 150px;
                    height: 30px;
                    background-color: #fff;
                    border: 1px solid #000000;
                    /* display: flex;
                    justify-content: center; */
                    align-items: center;
                    overflow: hidden;   /* 子要素がはみ出さないようにする */
                }
                .popup-button-area button{     /* ボタンそのもののスタイル */
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
                .popup-button-area:active{     /* ボタンが押されている間のスタイル */
                    background-color: #e5c0cb;
                    border: 1px solid #e53368;
                }
                .popup-button-area:active > .title-area{
                    background-color: #e53368;
                }

    </style>

    <script>
        var popup = document.getElementById('popup');
        var openBtn = document.getElementById('open-popup');    // 画像と一体になっているボタンのこと
        var exitBtn = document.getElementById('exit-popup');    // ポップアップ画面の右側のポップアップを閉じて元に戻るボタンのこと

        openBtn.onclick = function() {
            popup.style.display = "block";
        }

        exitBtn.onclick = function() {
            popup.style.display = "none";
        }

        // window？？
        window.onclick = function(event) {
            if (event.target == popup) {
                popup.style.display = 'none';
            }
        }

    </script>

        <!DOCTYPE html>
        <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="icon" href="./img/icon.png">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=DotGothic16&display=swap" rel="stylesheet"></head>
            <title>Index Page</title><!-- ブラウザタブに表示される文字 -->
            <!-- 240618_1032 -->
            <style>
                .header {
                    background-color: #0053da;
                    width: 100%;
                    height: 160px;
                }
                .footer {
                    background-color: #0053da;
                    width: 100%;
                    height: auto;
                }

                body {
                    background-color: #dfdfdf;
                    font-family: 'DotGothic16', sans-serif;
                    margin: 0;
                }
                h2 {
                    font-size: 20px;
                }
                p {
                    margin: 0;
                }
                a { /* スマホでボタンをタップした時に青くなるのを防ぐ */
                    -webkit-tap-highlight-color: rgba(0, 0, 0, 0); /* 透明にする */
                }

                .container {
                    padding: 20px;
                    display:flex;
                    flex-wrap: wrap;
                    gap: 20px 20px; 
                    width: 100%;
                    min-height: calc(100vh - 160px);
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
                .img-area {
                    width: 100%;
                    height: 160px;
                    overflow: hidden;   /* 子要素がはみ出さないようにする */
                }
                .title-area {
                    width: 100%;
                    height: 40px;
                    background-color: hsl(0, 0.00%, 77%);
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
        <div class="header">
            <p style="font-size: 30px; margin-left: 30px; padding-top: 20px; color: white;">Index</p>
            <p style="font-size: 20px; margin-left: 30px; color: white;">floor02.sakura.ne.jp/</p>
            <p style="font-size: 20px; margin-left: 30px; color: white;"><?php echo $dt_str;?></p>
            <p style="font-size: 20px; margin-left: 30px; color: white;"><?php echo $a_count;?></p>
        </div>
        <div class="all">

            <div class="container">

                <form action="index.php" method="GET">
                    <div class="button-area">
                        <div class="img-area">
                            <img src="./img/bkr-samu.png" alt="">
                            <input type="hidden" name="r" value="bkr">
                            <button type="submit"></button>
                        </div>
                        <div class="title-area">
                            <p style="text-align: center; margin: 0; padding-top: 6px;">スプラ3用ブキルーレット</p>
                        </div>
                    </div>
                </form>
                
                <div class="button-area">
                    <div class="img-area">
                        <img src="./img/cal-samu.png" alt="">
                        <button id="open-popup"></button>
                    </div>
                    <div class="title-area">
                        <p style="text-align: center; margin: 0; padding-top: 6px;">梶岡の週間予定カレンダー</p>
                    </div>
                </div>

                <form action="index.php" method="GET">
                    <div class="button-area">
                        <div class="img-area">
                            <img src="./img/kuji-samu.png" alt="">
                            <input type="hidden" name="r" value="kuji">
                            <button type="submit"></button>
                        </div>                        
                        <div class="title-area">
                            <p style="text-align: center; margin: 0; padding-top: 6px;">くじが引けるだけのページ</p>
                        </div>
                    </div>
                </form>

                <!-- <form action="index.php" method="GET">
                    <div class="button-area">
                        <div class="img-area">
                            <img src="./img/aaa.png" alt="">
                            <input type="hidden" name="r" value="">
                            <button type="submit"></button>
                        </div>                        
                        <div class="title-area">
                            <p style="text-align: center; margin: 0; padding-top: 6px;">***</p>
                        </div>
                    </div>
                </form> -->


            </div>

            <!-- ポップアップ画面の実態 -->
            <div id="popup">
                <!-- カレンダーのページへ遷移するボタン -->
                <form action="index.php" method="GET">
                    <div class="popup-button-area">
                        <input type="hidden" name="r" value="cal">
                        <button type="submit"></button>
                        <p style="text-align:center; margin:0; padding-top:0;">移動する</p>
                    </div>
                </form>
                <!-- ポップアップを消すボタン -->
                <div class="popup-button-area">
                    <button id="exit-popup"></button>
                    <p style="text-align:center; margin:0; padding-top:0;">やめる</p>
                </div>
            </div>

        </div>
        <div class="footer">
            <?php for($i=0; $i<10; $i++){echo "<br>";} ?>
            <p style="font-size: 20px; margin-left: 30px; color: white;">
                - 制作：kh<br>
                - mail：hbk.kajioka@gmail.com<br>
                - github：<a href="https://github.com/kah221">https://github.com/kah221</a><br>
                - 更新<br>
                　240622_1652：公開<br>
            </p>
            <?php for($i=0; $i<10; $i++){echo "<br>";} ?>
        </div>
        </body>
        </html>
        <?php
        // ファイルを閉じる
        rewind($acfile);
        fwrite($acfile, $a_count);
        flock($acfile, LOCK_UN);
        fclose($acfile);
        // $_SESSION = array();





        ?>