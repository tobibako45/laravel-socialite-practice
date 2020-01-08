# 概要
Laravel Socialiteを使って、ソーシャルログインをやってみる。

Laravel-Passportを使ってログインもさせたい。

- Laravel Framework 6.6.1
- PHP 7.3.12
- sqlite

# お試し

このリポジトリ([Laravel\-passport(リソースサーバー側)](https://github.com/tobibako45/laravel-passport-practice))とセットでお試し

あとからまとめます。

### Laravelでcloneしたらやること

SqliteでやったのでこちらをCODEBASE1期生の[@avocadoneko](https://twitter.com/avocadoneko)さんのこちらを参考

[【初心者向け】Laravel プロジェクトを clone してブラウザ表示するまで \- Qiita](https://qiita.com/avocadoneko/items/04d3c297064ba6e55a33)

こちらも
```
npm install
npm run dev
```

<br>

#### １、前提
[Laravel\-passport(リソースサーバー側)](https://github.com/tobibako45/laravel-passport-practice)が設定されていることが前提

<br>

#### ２、.envを記入

[Laravel\-passport(リソースサーバー側)](https://github.com/tobibako45/laravel-passport-practice)で作った、クライアントの情報をenvを記入。
```
PASSPORT_ID=<Laravel-passportで登録したクライアントID>
PASSPORT_SECRET=＜Laravel-passportで登録したクライアントSecret＞
```

<br>

#### ３、localhost:8000で起動
```
php artisan serve
```
で立ち上げて、
```
http://localhost:8000
```
にアクセス。

<br>

#### ４、Laravel-Passportで認証してログイン
「Laravel-Passportでログインする」ボタンから、ログインして認証。
初回だと新規登録される。次回はログインのみ。
