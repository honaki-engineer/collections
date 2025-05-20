# ポートフォリオ管理アプリ

自分が作成した **Web アプリをポートフォリオとして整理・保存・共有できる** Laravel 製のアプリです。  
タイトル・画像・URL・解説・タグなどを登録し、**視覚的に見やすい形で一覧表示**できます。  
また、画像の並び替え・サムネイル選択・セッション保持・バリデーションなど、使いやすさにも配慮した設計となっています。

---

## サイト

🔗 https://akkun1114.com/

---

## 目次

- [サイト](#サイト)
- [使用技術](#使用技術)
- [主な機能](#主な機能)
- [セットアップ前に必要なもの](#セットアップ前に必要なもの)
- [セットアップ手順](#セットアップ手順)
- [ディレクトリ構成](#ディレクトリ構成)
- [開発環境](#開発環境)
- [本番環境の注意点](#本番環境の注意点)
  
---

## 使用技術

- **フロントエンド**：HTML / JavaScript / Tailwind CSS / Vite / jQuery (Select2)
- **バックエンド**：PHP 8.2 / Laravel 9.x  
- **データベース**：MySQL (Xserver)  
- **インフラ・環境**：MAMP / macOS Sequoia 15.3.1 / Xserver  
- **ビルド環境**：Node.js 22.x (ローカル開発用) / Node.js 16.20.2 (本番環境 / Xserver に nodebrew で導入) / Composer 2.x  
- **開発ツール**：VSCode / Git / GitHub / phpMyAdmin  

---

## 主な機能

- ユーザー認証 (ログイン / ログアウト / 新規登録 / パスワード再発行)
- ゲストログイン (ワンクリック)
- 学習記録の CRUD (作成 / 編集 / 削除 / 一覧表示)
- バリデーション + 入力保持 (old関数) + エラーメッセージ表示
- ページネーション
- 一覧データの検索機能
- ステータス自動ラベル変換 (数値 → 日本語)
- 学習時間のフォーマット変換 (秒 → 時間 / 分)
- 学習進捗の可視化 (残り時間 / 達成率)
- エラーページ対応 (400〜503)

---

## セットアップ前に必要なもの

- PHP 8.2 以上
- Composer 2.x
- MySQL (ローカル or Xserver上で構成)
- Node.js (Tailwindをビルド)
- Git（クローンする場合）

.env の `DB_` 各項目などは、Xserver またはローカルの環境に応じて適宜変更してください。

### .env 設定例（ローカル開発用）

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ten_thousand_hours_record
DB_USERNAME=root
DB_PASSWORD=
```

---

## セットアップ手順

1. リポジトリをクローン
```bash
git clone https://github.com/HondaAkihito/ten-thousand-hours-record.git
cd ten-thousand-hours-record
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
npm run dev  # 本番では npm run build
```
7. サーバー起動 (ローカル開発用)
```bash
php artisan serve
```

---

## ディレクトリ構成

```txt
ten-thousand-hours-record/
├── app/                     # アプリケーションロジック (モデル、サービスなど)
├── config/                  # 各種設定ファイル
├── database/
│   ├── migrations/          # マイグレーションファイル
│   └── seeders/             # 初期データ投入用
├── public/
│   └── index.php            # エントリーポイント
├── resources/
│   ├── views/               # Bladeテンプレート
│   ├── css/                 # カスタムCSS
│   └── js/                  # カスタムJS
├── routes/
│   └── web.php              # ルーティング設定
├── .env.example             # 環境変数のテンプレート
├── composer.json            # PHPパッケージ管理
├── package.json             # Node.js用パッケージ管理 (Tailwind/Viteなど)
├── vite.config.js           # Vite設定
├── tailwind.config.js       # Tailwind CSSの設定
└── README.md
```

---

## 開発環境

- PHP 8.2
- Laravel 9.x
- Composer 2.x
- Node.js 22.x (ローカル開発)
- Xserver (本番環境 / Node.js 16.20.2 ※ nodebrew にて導入)

※ ローカル開発環境は、 Node.js 22.x を使用してビルドを実行しています。  
本番環境 (Xserver) は、nodebrew を利用して Node.js 16.20.2 を導入し、ビルドを行っています。  
なお、 Xserver では Node.js の標準提供は行われていないため、サーバー内ビルドは公式サポート対象外の構成となります。  
必要に応じて、ローカルビルド済みのファイルをアップロードする運用をおすすめいたします。

---

## 本番環境の注意点

Xserver 上で Laravel アプリを本番公開する際の詳細な手順 (SSH 接続、`.env` 設定、`.htaccess` 配置、`index.php` 修正、ビルドファイルの配置など) は、以下の記事にまとめています：

- メインドメインの場合  
  https://qiita.com/honaki/items/bf82986954c7db568094

- サブドメインの場合  
  https://qiita.com/honaki/items/a9c01bb8ae753ed67add
