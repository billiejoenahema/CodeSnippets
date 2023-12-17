# 同じ1列目の値が連続している行のかたまりをグループと定義する
# 1列目の値が共通しているグループが複数ある場合は2番目以降のグループを削除する

import pandas as pd

# ファイルパス
input_file = './concatenated.csv'
backup_file = './concatenated_backup.csv'

# CSVファイル読み込み
df = pd.read_csv(input_file, header=None, names=['col1', 'col2', 'col3', 'col4', 'col5', 'col6'])

# コピー作成
df.to_csv(backup_file, index=False)

# 1列目が変わるたびにグループを作成し、重複するグループを除外
df['group'] = (df['col1'] != df['col1'].shift()).cumsum()
groups_to_keep = df.drop_duplicates(subset='col1', keep='first')['group']
df_filtered = df[df['group'].isin(groups_to_keep)].drop(columns=['group'])

# 結果をCSVファイルに保存
df_filtered.to_csv(input_file, header=False, index=False)

print("処理が完了しました。")
