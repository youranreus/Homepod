# Homepod
自用小框架，不断学习，不断完善



### 运行(测试)环境

PHP 7.2.19

MYSQL 5.7

### 食用指南

想个办法从github上把源码搞下来，推荐使用`git clone`。在项目根目录下执行

```bash
composer install
```

提示找不到指令的可以安装一下composer，国内的用户推荐使用阿里的composer镜像。使用`homepod.sql`初始化你的数据库。

然后在根目录的`.env`文件中配置你的数据库信息，在`nginx`中添加以下配置

```nginx
rewrite ^/(.*)/$ /$1 redirect;

if (!-e $request_filename){
	rewrite ^(.*)$ /index.php break;
}
```

如果你使用的是`apache`，那么在你的配置文件中添加

```htaccess
RewriteEngine On
RewriteBase /

# Allow any files or directories that exist to be displayed directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^(.*)$ index.php?$1 [QSA,L]
```

大功告成。更多详细配置可以查看[Wiki](https://github.com/youranreus/Homepod/wiki)

