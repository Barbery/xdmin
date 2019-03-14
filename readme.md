# xdmin
xdmin is a admin project base on the laravel framework

## 安装步骤

* composer install

* mv .env.example .env

* 修改你的.env配置

## 设置nginx

```
server {
    listen 80;
    server_name local.xdmin.com;
    root "/xdmin/public";

    index index.php index.htm index.html;
    #add_header Strict-Transport-Security "max-age=8640000";
    #add_header Content-Security-Policy "upgrade-insecure-requests";

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    #access_log /var/log/nginx/local.xdmin.com.access.log combined buffer=16k flush=5s;
    #error_log  /var/log/nginx/local.xdmin.com.error.log error;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        #set $corsHost "*";
        #if ($http_origin ~* ^(https?://[^/]*\.dejupay\.com)) {
         #   set $corsHost $1;
        #}

        #add_header Access-Control-Allow-Origin $corsHost always;
        #add_header Access-Control-Max-Age 8640000;
        #add_header Access-Control-Allow-Credentials 'true' always;
        #add_header Access-Control-Allow-Methods 'GET, POST, PUT, DELETE, OPTIONS' always;
        #add_header Access-Control-Allow-Headers 'token,Authorization,DNT,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,X-File-Name' always;

        #if ($request_method = 'OPTIONS') {
        #    return 200;
        #}

        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php-fpm:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        #fastcgi_intercept_errors off;
        #fastcgi_buffer_size 16k;
        #fastcgi_buffers 4 16k;
    }

    location ~ /\. {
        deny all;
    }
}
```