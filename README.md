# Test console application
Test console application based on symfony/console component and mpdf library.

## Requirements
- php (7 or higher)
- [composer](https://getcomposer.org/)

## Installation
1. Clone project into the directory of your web server.
2. Install required packages from composer:
```bash
composer install
```
3. Run console command. You can use `./test` directory for the command testing:
```bash
php application.php export test
```
You can set `$extensions` property from `ExportFilesToPdfCommand` class 
to search another files. Default extensions - `php, html, js`.