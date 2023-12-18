#!/bin/bash

# 実行権限を付与
# chmod +x /file_path/remove_old_files.sh

# 実行
# /bin/sh /file_path/remove_old_files.sh

# 毎週水曜8:00にこのシェルスクリプトをrootユーザーで実行するcronジョブ
# 0 8 * * 3 root /bin/sh /file_path/remove_old_files.sh >> /var/log/remove_old_files.log 2>&1

# 対象ディレクトリのパス
target_directory="/directory_path"

# 移動先ディレクトリのパス
destination_directory="/tmp"

# 最終更新日が90日より古いファイルを検索して移動
find "$target_directory" -type f -mtime +90 -exec mv {} "$destination_directory" \;
