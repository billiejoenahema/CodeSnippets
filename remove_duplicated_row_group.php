<?php

// 処理前にバックアップファイルファイルをリネームしておく
// concatenated.csv
// ↓
// concatenated_backup.csv

// 元ファイルのパス
// $originalFilePath = '/directory/' . $argv[1] .  '/concatenated_backup.csv';
// 処理後ファイルのパス
// $newFilePath = '/directory/' . $argv[1] .  '/concatenated.csv';
$originalFilePath = './concatenated.csv';
$newFilePath = './concatenated_after.csv';

// 元ファイルの存在チェック
if (!file_exists($originalFilePath)) {
    die("Error: ファイルが見つかりません。\n" . $originalFilePath . "\n");
}

// 元ファイルを読み込む
$originalCsv = array_map('str_getcsv', file($originalFilePath));

// 処理後のファイルがすでに存在する場合はファイルを削除する
if (file_exists($newFilePath)) {
    unlink($newFilePath);
}

// 元ファイルがCSVかどうかをチェック
if (!is_array($originalCsv)) {
    die("Error: ファイルがCSV形式ではありません。\n");
}
// 元ファイルが空であるかをチェック
if (empty($originalCsv)) {
    die("Error: ファイルが空です。\n");
}

// 元ファイルの行数
$originalRowCount = count($originalCsv);

// 1列目の値が空の行がある場合の処理


// グループごとの配列を初期化
$groups = array();

// 1列目に同じ値が連続している行のかたまりを1グループと定義する
// グループごとに分割
$groupId = null;
foreach ($originalCsv as $row) {
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

// 元ファイルを読み込む
$newCsv = array_map('str_getcsv', file($newFilePath));

echo "削除したグループのID: \n";
echo implode("\n", $removedIds) . "\n";
echo "処理前の行数: " . count($originalCsv) . "\n";
echo "処理後の行数: " . count($newCsv) . "\n";
