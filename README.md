# ポートフォリオ管理アプリ（Honda Collections Site）

## 概要

**Web アプリをポートフォリオとして整理・保存・共有**できる Laravel 製のアプリです。  
タイトル・画像・URL・解説・タグなどを登録し、**視覚的に見やすい形で一覧や詳細の表示**が可能です。  
また、画像のドラッグ＆ドロップ並び替え・セッション保持など、日常的な更新作業をスムーズにする工夫を取り入れています。

---

## サイト

🔗 アプリ  
  <https://akkun1114.com/>  

---

## 目次

- [概要](#概要)
- [サイト](#サイト)
- [使用技術](#使用技術)
- [主な機能](#主な機能)
- [セットアップに必要な環境](#セットアップに必要な環境)
- [セットアップ手順](#セットアップ手順)
- [ディレクトリ構成](#ディレクトリ構成)
- [本番環境の注意点](#本番環境の注意点)
  
---

## 使用技術

- **フロントエンド**：HTML / JavaScript / Tailwind CSS / jQuery（Select2）
- **バックエンド**：PHP 8.2 / Laravel 9.x  
- **データベース**：MySQL 8.0（開発: MAMP） / MariaDB 10.5（本番: Xserver、MySQL互換）  
- **インフラ・環境**：MAMP / macOS Sequoia 15.3.1 / Xserver  
- **ビルド環境**：Node.js 22.17.0（開発） / Node.js 16.20.2（本番: Xserver に nodebrew で導入） / Composer 2.x  
- **開発ツール**：VSCode / Git / GitHub / phpMyAdmin  
  
※ ローカル開発環境は、 Node.js 22.x を使用してビルドを実行しています。  
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
| ポートフォリオの一覧・詳細表示（公開ページ）      | 公開ページ    | ●                | -      |
| 「技術 × 主な機能タグ」によるポートフォリオ検索   | 公開ページ   | ●                | -      |
| ポートフォリオの一覧・詳細表示（管理者ページ）    | 管理者ページ | -                 | ●      |
| 「公開種別 × 表示優先度」によるポートフォリオ検索 | 管理者ページ  | -                 | ●      |
| ポートフォリオの作成・編集・削除                | 管理者ページ  | -                 | ●      |
| 技術・主な機能タグの CRUD                     | 管理者ページ  | -                 | ●      |
| 「フリーワード × 種類」によるタグ検索            | 管理者ページ | -                 | ●      |
| プロフィール編集                              | 管理者ページ | -                  | ●      |

---

## セットアップに必要な環境

- PHP 8.2 以上
- Composer 2.x
- DB：MySQL 8.0 もしくは MariaDB 10.5（MySQL互換）
- Node.js (Tailwind をビルド)
- Git（クローンする場合）

.env の `DB_` 各項目などは、Xserver または開発の環境に応じて適宜変更してください。  

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
```

---

## セットアップ手順

1. リポジトリをクローン
```bash
git clone https://github.com/HondaAkihito/collections.git
cd collections
```
2. 環境変数を設定
```bash
cp .env.example .env
```
3. PHPパッケージをインストール
```bash
composer install
```
4. アプリケーションキーを生成
```bash
php artisan key:generate
```
5. DBマイグレーション & 初期データ投入
```bash
php artisan migrate --seed
```
6. フロントエンドビルド (Tailwind/Vite 使用時)
```bash
npm install
npm run dev  # 開発環境用
npm run build  # 本番環境用
```
7. サーバー起動 (ローカル開発用)
```bash
php artisan serve
```

---

## ディレクトリ構成

```txt
collections/
├── app/                     # アプリケーションロジック（モデル、コントローラ、サービスなど）
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── PublicSite/
│   │   ├── Middleware/
│   │   ├── Requests/
│   ├── Models/
│   ├── Providers/
│   └── Service/
├── bootstrap/
│   └── cache/
├── config/                  # 各種設定ファイル
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── index.php            # エントリーポイント
├── resources/
│   ├── css/                 # Tailwind CSS
│   ├── js/                  # JavaScript / サービス処理
│   └── views/               # Bladeテンプレート（auth / admin / public_site など）
├── routes/
│   └── web.php              # ルーティング設定
├── storage/                 # ログ・セッション・画像アップロード等の保存先
├── .env.example             # 環境変数テンプレート
├── composer.json            # PHPパッケージ管理ファイル
├── package.json             # Node.jsパッケージ管理ファイル
├── vite.config.js           # Vite 設定
├── tailwind.config.js       # Tailwind CSS 設定
└── README.md
```

---

## 本番環境の注意点

Xserver 上で Laravel アプリを本番公開する際の詳細な手順 (SSH 接続、`.env` 設定、`.htaccess` 配置、`index.php` 修正、ビルドファイルの配置など) は、以下の記事にまとめています：

- メインドメインの場合  
  https://qiita.com/honaki/items/bf82986954c7db568094

- サブドメインの場合  
  https://qiita.com/honaki/items/a9c01bb8ae753ed67add
