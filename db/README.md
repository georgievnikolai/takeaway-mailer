docker run \
--name db0 \
-p 127.0.0.1:33066:3306 \
-e MYSQL_ROOT_PASSWORD=16783b3dc9fc \
-e MYSQL_DATABASE=takeawaymailer \
-d \
mariadb/server:10.3