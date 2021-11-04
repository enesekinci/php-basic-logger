# php-basic-logger

## composer require enesekinci/php-basic-logger

```php
<?php

require_once('vendor/autoload.php');

use EnesEkinci\PhpBasicLogger\Log;

$dir = __DIR__;

$log_path = $dir . '/Storage/logs/';

Log::setPath($log_path);

Log::add('Test');

```
