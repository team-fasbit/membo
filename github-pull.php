<?php

if ($_POST['payload']) {
    shell_exec('cd /var/www/html/ossn/ && git reset --hard HEAD && git pull');
    // shell_exec('sudo chown -R www-data:www-data /var/www/html/ossn/');
}
