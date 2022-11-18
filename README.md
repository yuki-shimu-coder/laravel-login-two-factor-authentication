# laravel-login-two-factor-authentication

laravelで2段階認証を実装する

## はじめに
以下のツールを事前にインストールしてください。

- Docker

## 開発の手順

laravel-projectディレクトリ内で、.envファイルを作成してください

```
.env.example .env  
```

続いて、ルートディレクトリで下記のコマンドを実行してください

```
docker compose build
```

ビルドが完了したら、下記コマンドでDockerコンテナを起動してください

```
docker compose up -d
```

コンテナを起動したら、下記コマンドを実行してアプリの依存関係をインストールしてください

```
docker compose exec app composer install
```

続いて、データベースを準備します。

下記コマンドを実行して、DBコンテナにアクセスし、DBを作成してください

```
docker composer exec db bash
mysql -u  root -p
パスワードはrootです
CREATE DATABASE `laravel`;
SHOW DATABASES;
```

SHOW DATABASES;でlaravelが出来上がっていることを確認してください。

続いて、テーブルを用意します。

下記コマンドを実行して、appコンテナにアクセスし、マイグレーションを実行してください

```
docker compose exec app bash
cd laravel-project
php artisan migrate
```

最後に、下記コマンドを実行して、アプリをビルドしてください。（コマンド実行は、appコンテナのlaravel-projectディレクトリ内）

```
npm install
npm run build
```

http://localhost:8000/ にアクセスし、laravelのwelcomeページが表示されるかを確認してください。

## 二段階認証の確認手順

http://localhost:8000/ にアクセスし、画面右上のRegisterをクリック。任意のメールアドレスを登録してください。

http://localhost:8000/two_factor_auth/login_form にアクセスし、登録したメールアドレスとパスワードを入力し送信

http://localhost:8025/ にアクセスし、送信されたメールを開き4桁のパスワードをコピー

http://localhost:8000/two_factor_auth/login_form にアクセスし、先程コピーしたパスワードをペーストして送信


You're logged in!と画面に表示されていれば、ログインできています。