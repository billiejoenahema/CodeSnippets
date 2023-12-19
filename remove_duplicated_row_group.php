<?php

// 元のCSVファイルのパス
$originalFilePath = './concatenated.csv';

// 新しいCSVファイルのパス
$newFilePath = 'concatenated_' . date('YmdHis') . '.csv';

// 元のCSVファイルを読み込む
$originalCsv = array_map('str_getcsv', file($originalFilePath));

// グループごとの配列を初期化
$groups = array();

// グループごとに分割
$groupId = null;
foreach ($originalCsv as $row) {
    $currentGroupId = $row[0];

    // グループが変わったら新しいグループとして扱う
    if ($currentGroupId !== $groupId) {
        $groups[] = array();
        $groupId = $currentGroupId;
    }

    // 現在のグループに行を追加
    $groups[count($groups) - 1][] = $row;
}

// 既に新しいCSVに存在する1列目の値を保持する配列
$existingIds = array();

// 新しいCSVファイルに書き込む
foreach ($groups as $group) {
    $groupId = $group[0][0];

    // 新しいCSVに追加する前に、既に追加したグループの1行目の1列目に同じ値があるか確認
    $skipGroup = false;
    foreach ($existingIds as $existingId) {
        if ($existingId === $groupId) {
            $skipGroup = true;
            break;
        }
    }

    // 同じ1列目の値が含まれていない場合、新しいCSVに追加
    if (!$skipGroup) {
        $fp = fopen($newFilePath, 'a');
        foreach ($group as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);

        // 既に新しいCSVに存在する1列目の値を更新
        $existingIds[] = $groupId;
    }
}

echo "新しいCSVが作成されました: $newFilePath\n";
