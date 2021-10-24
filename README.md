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



大功告成。



### 一些配置

1.你可以在`App/Conf/Conf.php`中对错误提示及需要监控的网站进行配置，例如

```php
static $key = '1234567';//敏感操作的访问密钥
static $websites = [
    array("博客","https://gundam.exia.xyz",true),
    array("博客新手村","https://imouto.tech",true),
    array("个人主页","https://xn--18su5j71q.space",true),
    array("番剧仓库","https://od.imouto.tech",false),
    array("aria","https://aria.xn--pn1aul.tech",false)
];
```

2.你可以在`App/Conf/profile.json`中配置`homepod_page`中的个人信息。



### 模组结构

所有模组被存放在`App/Module`文件夹中，每个模块文件夹名与类名应保持一致且模块文件夹应包含一个`module.yml`文件。例如

```yaml
name: Deutsch
des: 德语学习助手
enable: true
func:
  - 单词搜索
  - 每日一句
route:
  - get:
      Deutsch/dailySentence: App\Module\Deutsch\Deutsch@dailySentence
      Deutsch/search/(:any): App\Module\Deutsch\Deutsch@search
```

