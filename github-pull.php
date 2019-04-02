<?php

if ($_POST['payload']) {
    shell_exec('cd /var/www/html/ossn/ && git reset --hard HEAD && git pull && chown -R www-data:www-data /var/www/html/ossn/');
}
