<?php
// 元ファイルのパス
// $dataFilePath = '/directory/' . $argv[1] .  '/data_concatenated_org.csv';
// 処理後ファイルのパス
// $newFilePath = '/directory/' . $argv[1] .  '/data.csv';
$dataFilePath = './concatenated.csv';
$newFilePath = './concatenated_after.csv';
$storeIndexFilePath = './store_index.csv';

// 元ファイルの存在チェック
if (!file_exists($dataFilePath)) {
    die("Error: ファイルが見つかりません。\n" . $dataFilePath . "\n");
}

// store_index.csvの存在チェック
if (!file_exists($storeIndexPath)) {
    die("Error: ファイルが見つかりません。\n" . $storeIndexPath . "\n");
}

// 元ファイルを読み込む
$dataCsv = array_map('str_getcsv', file($dataFilePath));

// 元ファイルがCSVかどうかをチェック
if (!is_array($dataCsv)) {
    die("Error: ファイルがCSV形式ではありません。" . $dataFilePath . "\n");
}
// 元ファイルが空であるかをチェック
if (empty($dataCsv)) {
    die("Error: ファイルが空です。" . $dataFilePath . "\n");
}

// 処理後のファイルがすでに存在する場合はファイルを削除する
if (file_exists($newFilePath)) {
    unlink($newFilePath);
}

// store_index.csvを読み込む
$storeIndexCsv = array_map('str_getcsv', file($storeIndexFilePath));

// store_index.csvがCSVかどうかをチェック
if (!is_array($storeIndexCsv)) {
    die("Error: ファイルがCSV形式ではありません。" . $storeIndexCsv . "\n");
}

// store_index.csvが空であるかをチェック
if (empty($storeIndexCsv)) {
    die("Error: ファイルが空です。" . $storeIndexCsv . "\n");
}

// 元ファイルの行数
$dataRowCount = count($dataCsv);

// 1列目の値が空の行がある場合の処理
// TODO

// data.csvから

// グループごとの配列を初期化
$groups = array();

// 1列目に同じ値が連続している行のかたまりを1グループと定義する
// グループごとに分割
$groupId = null;
foreach ($dataCsv as $row) {
    $currentGroupId = $row[0];

    // 1列目の値の連続性が途切れたら新しいグループとして扱う
    if ($currentGroupId !== $groupId) {
        $groups[] = array();
        $groupId = $currentGroupId;
    }

    // 現在のグループに行を追加
    $groups[count($groups) - 1][] = $row;
}

// すでに追加したグループに存在する1列目の値を保持する配列
$existingIds = [];

// 新しいCSVファイルに追加しなかったグループの1行目の1列目の値を保持する配列
$removedIds = [];

// 新しいCSVファイルに書き込む
$fp = fopen($newFilePath, 'a');

foreach ($groups as $group) {
    $groupId = $group[0][0];

    // 新しいCSVに追加する前に、既に追加したグループの1行目の1列目に同じ値があるか確認
    $skipGroup = in_array($groupId, $existingIds);

    // 同じ1列目の値が含まれていない場合、新しいCSVに追加
    if (!$skipGroup) {
        foreach ($group as $row) {
            fputcsv($fp, $row);
        }

        // 既に新しいCSVに存在する1列目の値を更新
        $existingIds[] = $groupId;
    } else {
        $removedIds[] = $groupId;
    }
}

fclose($fp);

// 新しいCSVを元ファイルに上書きする
// 新しいCSVファイルを読み込む
$newCsv = array_map('str_getcsv', file($newFilePath));

echo "削除したグループのID: \n";
echo implode("\n", $removedIds) . "\n";
echo "処理前の行数: " . count($dataCsv) . "\n";
echo "処理後の行数: " . count($newCsv) . "\n";

// 重複したグループのIDと削除したグループのIDが一致していなければ処理を終了させる
if (implode("\n", $removedIds) !== implode("\n", $existingIds)) {
    die("Error: 重複したグループのIDと削除したグループのIDが一致していません。\n");
}

echo "store_index.csvから重複している店舗を削除します。";

//
// store_index.csvを読み込む
$storeIndexCsv = array_map('str_getcsv', file($storeIndexFilePath));

// store_index.csvから重複している店舗を削除
$newStoreIndexCsv = array_filter($storeIndexCsv, function ($row) use ($removedIds) {
    return !in_array($row[0], $removedIds);
});

// 新しいstore_index.csvに書き込む
$fp = fopen($storeIndexFilePath, 'w');
foreach ($newStoreIndexCsv as $row) {
    fputcsv($fp, $row);
}
fclose($fp);
echo "完了しました。\n";

