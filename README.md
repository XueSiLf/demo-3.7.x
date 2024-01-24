# EASYSWOOLE 3.7.x DEMO

## 关于具体 demo 在哪

`demo-3.7.x/main` 分支对应了 `EasySwoole 3.7.x` 版本的 `demo`，`main` 主要是 `EasySwoole` 基础使用的例子，其他使用请看其他对应的分支。

## `DEMO` 运行环境要求

- php >= 8.1.0
- ext-swoole >= 4.4.23

### 安装 EasySwoole Demo

#### Linux/MacOS/Ubuntu 环境

Linux/MacOS/Ubuntu 环境可使用如下步骤进行安装 EasySwoole Demo DEMO。

```bash
git clone https://github.com/XueSiLf/demo-3.7.x.git
cd demo
composer install
php vendor/easyswoole/easyswoole/bin/easyswoole.php install
composer dump-autoload
```

#### Docker 环境

```bash
cd /tmp/easyswoole
git clone https://github.com/XueSiLf/demo-3.7.x.git

docker run --name=easyswoole-demo -v /tmp/easyswoole/demo-3.7.x:/var/www -p 9501:9501 -it --entrypoint /bin/sh easyswoolexuesi2021/easyswoole:php8.1.22-alpine3.16-swoole5.1.1

docker exec -it easyswoole-demo sh

composer install
php vendor/easyswoole/easyswoole/bin/easyswoole.php install
composer dump-autoload
```

## 如何运行 `DEMO`

安装项目时请不要覆盖默认的配置文件（`dev.php` / `produce.php`）以及 `EasySwooleEvent` 事件注册文件（`EasySwooleEvent.php`）

### 配置项目数据库

创建数据库 `easyswoole_demo`，设置字符集为 `utf8mb4`，排序规则为 `utf8mb4_general_ci`，导入 `Doc/easyswoole_demo.sql` sql文件。

在 `Config/DATABASE.php` 中的 `DATABASE.MYSQL` 配置项中修改 `name` 为 `default` 的 `MYSQL` 配置 `host`、`port`、`user`、`password`
、`database`，修改其为项目正确可用的配置。

> 如果您使用的 `swoole` 版本是 `5.x` 版本，请修改 `Config/DATABASE.php` 中的 `DATABASE.MYSQL.default.useMysqli` 配置项为 `true`。否则会出如下废弃警告：```[2024-01-24 19:59:57][trigger][notice]:[Method Swoole\Coroutine\MySQL::__construct() is deprecated at file:/tmp/easyswoole/demo-3.7.x/vendor/easyswoole/mysqli/src/Client.php line:160]
```。

### 启动项目

```
php easyswoole.php server start
```

## 访问接口

### Admin 管理员模块

#### auth

- login 登录
  http://localhost:9501/api/admin/auth/login?account=easyswoole&password=123456

- logout
  http://localhost:9501/api/admin/auth/logout

- getInfo
  http://localhost:9501/api/admin/auth/getInfo

#### user manager

- get all user
  http://localhost:9501/api/admin/user/getAll
  http://localhost:9501/api/admin/user/getAll?page=1&limit=2
  http://localhost:9501/api/admin/user/getAll?keyword=easyswoole

- get one user
  http://localhost:9501/api/admin/user/getOne?userId=1

- add user
  http://localhost:9501/api/admin/user/add?userName=EasySwoole1&userAccount=easyswoole1&userPassword=123456

- update user
  http://localhost:9501/api/admin/user/update?userId=1&userPassword=456789&userName=easyswoole&state=0&phone=18888888889

- delete user
  http://localhost:9501/api/admin/user/delete?userId=2

### Common 公共模块

#### banner

- get one banner 读取一条banner
  http://localhost:9501/api/common/banner/getOne?bannerId=1

- get all banner
  http://localhost:9501/api/common/banner/getAll
  http://localhost:9501/api/common/banner/getAll?page=1&limit=2
  http://localhost:9501/api/common/banner/getAll?keyword=easyswoole

### User 会员模块

- user login
  http://localhost:9501/api/user/auth/login?userAccount=easyswoole&userPassword=456789

- get user info
  http://localhost:9501/api/user/auth/getInfo

- logout
  http://localhost:9501/api/user/auth/logout

## 请先认真阅读手册 再进行体验

- [EASYSWOOLE 在线手册](https://www.easyswoole.com)
- QQ 交流群
    - VIP 群 579434607 （本群需要付费599元）
    - EasySwoole 官方一群 633921431(已满)
    - EasySwoole 官方二群 709134628(已满)
    - EasySwoole 官方三群 932625047(已满)
    - EasySwoole 官方四群 779897753(已满)
    - EasySwoole 官方五群 853946743(已满)
    - EasySwoole 官方六群 524475224

- 商业支持：
    - QQ 291323003
    - EMAIL admin@fosuss.com    
