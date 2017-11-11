Dependencies
=============

Requires [Composer](https://getcomposer.org/)

Once you have that installed, run:

composer global require "fxp/composer-asset-plugin"

and then:

composer install to install php dependencies.

Also install [FFmpeg](https://www.ffmpeg.org/download.html) and add it to the windows PATH environment variable.

Settings
----------------
In php.ini, enable short_open_tag, enable the extensions: php_pdo_pgsql, php_pgsql, php_fileinfo, php_gd2. If you're configuring apache manually, enable rewrite module.

Configure config/params.php and config/db.php based on the example files.

Given that we support large file uploads such as movies, set php's post_max_size and upload_max_filesize to a larger setting.

REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0. Just use xampp.
