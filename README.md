# ポートフォリオ管理アプリ（Honda Collections Site）

## 概要

**Web アプリをポートフォリオとして整理・保存・共有**できる Laravel 製のアプリです。  
タイトル・画像・URL・解説・タグなどを登録し、**視覚的に見やすい形で一覧や詳細の表示**が可能です。  
また、画像のドラッグ＆ドロップで並び替え・セッション保持など、日常的な更新作業をスムーズにする工夫を取り入れています。

---

## サイト

- アプリ  
  <https://akkun1114.com/>  

---

## 目次

- [概要](#概要)
- [サイト](#サイト)
- [使用技術](#使用技術)
- [主な機能](#主な機能)
- [セットアップ手順](#セットアップ手順)
- [ディレクトリ構成](#ディレクトリ構成)
- [本番環境の注意点](#本番環境の注意点)
  
---

## 使用技術

- **フロントエンド**：HTML / JavaScript / Tailwind CSS / jQuery（Select2）
- **バックエンド**：PHP 8.x（開発: 8.2.27 / 本番: 8.2.28） / Laravel 9.52.20  
- **データベース**：MySQL 8.0（開発） / MariaDB 10.5（本番・MySQL互換）  
- **インフラ・環境**：MAMP / Xserver / macOS Sequoia 15.3.1  
- **ビルド環境**：Node.js 24.4.0（開発） / Node.js 16.20.2（本番: Xserver に nodebrew で導入） / Composer 2.8.x（開発: 2.8.4 / 本番: 2.8.5）  
- **開発ツール**：VSCode / Git / GitHub / phpMyAdmin  
  
※ ローカル開発環境は、Node.js 24.4.0 を使用してビルドを実行しています。  
本番環境（Xserver）は、nodebrew を利用して Node.js 16.20.2 を導入し、ビルドを行っています。  
なお、Xserver では Node.js の標準提供は行われていないため、サーバー内ビルドは公式サポート対象外の構成となります。  
必要に応じて、ローカルビルド済みのファイルをアップロードする運用をおすすめいたします。

---

## 主な機能
### 開発者目線

- **認証/認可**：Breeze、全ルート `auth` / 取得は本人スコープ固定  
- **ポートフォリオ管理**：CRUD / 検索  
- **画像**：複数画像対応 / 並び替え / 重複禁止 / サムネイル＆プレビュー表示  
- **タグ**：複数選択対応 / Select2 による検索支援 / 並び替え  
- **400〜503**：カスタムエラーページ  
- **その他**：バリデーション / 入力保持（old関数＆セッション） / バリデーションエラーメッセージ表示 / ページネーション  

### ユーザー目線
#### 区分別 機能対応表

| 機能                                       | ページ分類   | 非ログインユーザー | 管理ユーザー |
| ----------------------------------------- | ----------- | --------------- | ------ |
| ログイン                                    | 管理者ページ  | -               | ●      |
| パスワード再設定                             | 管理者ページ  | -                | ●      |
| ポートフォリオの一覧・詳細表示                  | 公開ページ    | ●                | -      |
| 「技術 × 主な機能タグ」によるポートフォリオ検索   | 公開ページ   | ●                | -      |
| ポートフォリオの一覧・詳細表示                  | 管理者ページ | -                 | ●      |
| 「公開種別 × 表示優先度」によるポートフォリオ検索 | 管理者ページ  | -                 | ●      |
| ポートフォリオの作成・編集・削除                | 管理者ページ  | -                 | ●      |
| 技術・主な機能タグの CRUD                     | 管理者ページ  | -                 | ●      |
| 「フリーワード × 種類」によるタグ検索            | 管理者ページ | -                 | ●      |
| プロフィール編集                              | 管理者ページ | -                  | ●      |

---

## セットアップ手順

1. リポジトリをクローン
```bash
git clone https://github.com/honaki-engineer/collections.git
cd collections
```
2. 環境変数を設定
```bash
cp .env.example .env
```
.env の `DB_` 各項目などは、Xserver またはローカルの環境に応じて適宜変更してください。  
- [.env 設定例（開発用）](#env-設定例開発用)
- [.env 設定例（本番用）](#env-設定例本番用)
3. PHPパッケージをインストール
```bash
# 開発
composer install

# 本番
composer install --no-dev --optimize-autoloader
```
4. 画像圧縮の要件（Intervention Image v3）
- このプロジェクトは画像のリサイズ/圧縮に Intervention Image v3（GDドライバ） を使用します。  
  追加導入は不要（composer install に同梱済み）。
- サーバ側で PHP GD（WebP対応） が有効である必要があります。未対応だと WebP 変換が失敗します。
- 確認コマンド（CLI）：
```bash
php -m | grep -i gd        # → 'gd' が表示されればOK
php -i | grep -i webp      # → 'WebP Support => enabled' などが出ればOK
# もし表示されない場合は、GD拡張を有効化（php.ini）またはインストールしてください。
```
5. アプリケーションキーを生成
```bash
php artisan key:generate
```
6. DBマイグレーション & 初期データ投入
```bash
php artisan migrate --seed
```
7. フロントエンドビルド（Tailwind/Vite 使用時）

```bash
npm install

# 開発
npm run dev

# 本番
npm run build
```
8. ストレージリンク作成（画像表示のため必須）
```bash
php artisan storage:link
```
9. サーバー起動（開発時）
```bash
php artisan serve
```

---

### .env 設定例（開発用）

```env
APP_NAME='Honda Collections Site'
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=collections
DB_USERNAME=root
DB_PASSWORD=root

# Mailpit を使う場合
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# 画像保存に使うディスク
MEDIA_DISK=public
```

### .env 設定例（本番用）

```env
APP_NAME='Honda Collections Site'
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=（本番用 データベース）
DB_USERNAME=（本番用 ユーザー）
DB_PASSWORD=（本番用 DBuser パスワード）

# Gmail の場合
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=（使用するメールアドレス）
MAIL_PASSWORD=（16桁のアプリパスワード）
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=（使用するメールアドレス）
MAIL_FROM_NAME="${APP_NAME}"

# 画像保存に使うディスク
MEDIA_DISK=public
```

---

## ディレクトリ構成

```txt
collections/
├── app/
│   ├── Http/
│   │   ├── Controllers/                  # コントローラ（公開用、ログイン後用）
│   │   └── Requests/                     # 入力バリデーション
│   ├── Models/                           # Eloquent モデル
│   ├── Notifications/                    # カスタム通知（パスワード再設定メールのカスタム）
│   └── Service/                          # サービスクラス
├── config/                               # 各種設定ファイル
├── database/
│   ├── migrations/                       # マイグレーションファイル
│   └── seeders/                          # 初期データ投入用
├── lang/
│   └── ja/                               # バリデーションエラーの日本語化など
├── public/
│   ├── index.php                         # エントリーポイント
│   ├── image/                            # 初期画像投入用
│   └── storage -> ../storage/app/public  # storage:link のシンボリックリンク
├── resources/
│   ├── css/                              # Tailwind CSS
│   ├── js/                               # JavaScript / サービス処理
│   └── views/                            # Bladeテンプレート（auth / admin / public_site など）
├── routes/
│   └── web.php                           # ルーティング設定
├── storage/
│   └── app/public/
│       ├── collection_images/            # 画像アップロード先（公開される）
│       └── tmp/                          # 一時保存場所
├── .env.example                          # 環境変数テンプレート
├── composer.json                         # PHPパッケージ管理ファイル
├── package.json                          # Node.jsパッケージ管理ファイル
├── vite.config.js                        # Vite 設定
├── tailwind.config.js                    # Tailwind CSS 設定
└── README.md
```

---

## 本番環境の注意点

Xserver 上で Laravel アプリを本番公開する際の詳細な手順（SSH 接続、`.env` 設定、`.htaccess` 配置、`index.php` 修正、ビルドファイルの配置など）は、以下の記事にまとめています：

- メインドメインの場合  
  https://qiita.com/honaki/items/bf82986954c7db568094

- サブドメインの場合  
  https://qiita.com/honaki/items/a9c01bb8ae753ed67add
