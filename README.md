##建立來源

####複製專案：
<https://github.com/abcd1236542000/work-architecture-base>

```sh
git clone https://github.com/abcd1236542000/work-architecture-base
```
-------------

####Local端使用：

安裝相依套件
```sh
composer install
```
複製環境變數檔
```sh
cp .env.example .env
```
產生APP_KEY
```sh
php artisan key:generate
```
運行
```sh
php artisan serve --port=80
```
-------------

####heroku 設定：
此專案新增 `Procfile`檔案

####heroku dashboard 設定環境變數
指令產生key
```sh
php artisan key:generate --show
```
手動方式
Settings ->Config Vars 設定 APP_KEY

指令
```sh
heroku config:set APP_KEY={手動方式產生的APP_KEY}
```
