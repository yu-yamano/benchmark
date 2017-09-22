# benchmark
各言語のパフォーマンス確認  

access_logをparseして最初と最後のデータを入れ替えCSV出力

テストで使用したaccess_logは425377アクセスのもの

## result
| lang   | version |result   |
|--------|---------|---------|
| php    | 7.0     |9.5863秒 |
| golang | 1.9     |7.4828秒 |
| scala  | 2.12    |19.858秒 |
