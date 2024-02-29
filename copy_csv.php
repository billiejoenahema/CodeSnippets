<?php
// store_index.csvのパス
$originalFilePath = 'store_index.csv';

// 新しいファイル名を生成
$currentDateTime = date('Ymd_His');
$newFileName = 'store_index_' . $currentDateTime . '.csv';
$newFilePath = $newFileName;

// store_index.csvの更新日時を取得
$originalFileMtime = filemtime($originalFilePath);

// store_index.csvをコピーして新しいファイルを作成
if (copy($originalFilePath, $newFilePath)) {
    // 新しいファイルに元の更新日時を設定
    if (touch($newFilePath, $originalFileMtime)) {
        echo 'ファイルが正常にコピーされ、更新日時が設定されました。';
    } else {
        echo '新しいファイルの更新日時の設定中にエラーが発生しました。';
    }
} else {
    echo 'ファイルのコピー中にエラーが発生しました。';
}
