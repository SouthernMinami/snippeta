# READMEテンプレ

# スニペタ

会員登録なしで、誰でもコードスニペットを共有できるWebサイトです。

💻サイトURL: [https://snippeta.kano.wiki/](https://snippeta.kano.wiki/)

## ✨Features

## トップページ（Playground ページ）

| 機能 | 内容 |
| --- | --- |
| Monacoエディタ | 投稿するコードを書き込むためのエディタスペース。主要なプログラミング言語とplain textをドロップダウンから選択できます。 |
| ソースファイルダウンロードボタン | エディタに記載されているコードを、選択した言語の拡張子ファイルとしてダウンロードできます。 |
| コピーボタン | エディタの内容をクリップボードにコピーできます。 |

## 投稿閲覧ページ

投稿された全てのスニペットに、一覧ページからアクセスできます。

（投稿時に設定されたExpiration Dateを過ぎた投稿は閲覧不可になります。）

| 機能 | 内容 |
| --- | --- |
| スニペット情報 | 投稿スニペットの情報（タイトル、言語、閲覧期限、投稿日時）が表示されます。 |
| コード内容 | コードスニペットが記載されたエディタスペースが表示されます。ここでコードを書き換えてページを離れても、データ内容は変更されません。 |
| ソースファイルダウンロードボタン | エディタに記載されているコードを、選択した言語の拡張子ファイルとしてダウンロードできます。 |
| コピーボタン | エディタの内容をクリップボードにコピーできます。 |

## ⚒️Skills

| カテゴリ | 技術 |
| --- | --- |
| フロントエンド | HTML / CSS / Javascript / Monaco Editor ライブラリ |
| バックエンド | PHP |
| 本番環境 | AWS EC2 |
| デプロイ | Nginx |
| 暗号化 | Certbot |

## 🔥Motivations

開発のモチベーション

- 初学者用に、他のプログラマーがよく使っているコードスニペットを共有・閲覧できるサイトがあると便利そうだと感じた（Githubなどよりも手軽に）。
- 自分用のよく使うスニペットのメモ場所としても制作。

## 👀Emphasis

こだわった点

- フォームにタイトルと閲覧期限が入力されてない状態で投稿しようとした場合に、投稿者に見える形で知らせるためにバリデーションを追加しました
- Vercel等を使うのではなく、EC2インスタンスを本番環境としてNGINXとPHP-FPMを介してデプロイ設定を行いました。
- Laravelのようなバックエンドフレームワークで扱えるコマンドが内部で何をしているのかを大まかに理解するために
    - マイグレーションファイルなどのコード自動生成コマンド
    - マイグレーションコマンド
    - テーブルへのデータ挿入コマンド
    
    上記のコマンドと実行用のファイルを作成しました。
    

## 📜Log

[作業ログ](https://github.com/SouthernMinami/weekly-report/blob/main/logs/snippeta.md)

## ➡️TBA

- レイアウトの改善
