@ECHO OFF

mysql -uroot -p het2 -e "select `id` from  `%*` order by `id`  desc limit 1  

