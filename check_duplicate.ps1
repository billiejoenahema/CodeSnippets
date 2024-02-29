# ディレクトリを移動
# カレントディレクトリにファイルがそろっているかチェック
# data.csv
# post_data.csv
# store_index.csv

# data.csvをコピーしてdata_org.csvを作成する
# data.csvにpost_data.csvを連結する

# data_org.csv、post_data.data.csv の行数を取得
# data_org.csv の行数と post_data.csv の行数の合計が data.csv の行数と一致しているか確認
# 一致していなければ処理を終了する

# data.csvに重複しているIDで始まる行のグループがあるかチェック
# check_duplicated_row_group.php を実行、引数にyyyymmddを渡す
# 重複しているグループIDを表示

# 重複しているグループがあるか？
# あれば以下の処理を実行、なければ終了

# data.csvをコピーしてdata_concatenated_org.csvを作成する
# data.csvから重複しているIDで始まる行のグループの重複分を削除
# remove_duplicated_row_group.php を実行、引数にyyyymmddを渡す
# 削除したグループIDを表示
# 処理前後の行数を表示

# 重複しているグループIDと削除したグループIDが一致していなかったら処理を終了

# store_index.csvをコピーしてstore_index_org.csvを作成する
# 削除したIDと同じ値を1列目に持つ行をstore_index.csv から削除する

# store_index.csvから削除した

#
