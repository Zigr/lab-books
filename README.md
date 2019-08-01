# lab-books
Several giants framework integration discovery

To deploy:
1. clone the repo
2. in project root dir execute
$  composer install
2. adjust database configuration in app/config/application.php to fit your db needs. Set table_prefix if neccessary.
3. Adjust your virtual host configuration or rum php built-in server from project root directory:
e.g.
$ php -S localhost:8888 -t ./public/
4. Ensure served folders and files have proper permissions(can be read and written by web server).
5. in browser go to http://localhost:8888/migration to create,delete or truncate tables and fill sample data.

