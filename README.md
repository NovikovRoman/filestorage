FileStorage
===========

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use FileStorage\Exceptions\DataException;
use FileStorage\Exceptions\FileStorageException;
use FileStorage\Exceptions\NotFoundException;
use FileStorage\FileStorage;

try {
    $fs = new FileStorage(__DIR__ . '/storage');
} catch (FileStorageException $e) {
    die($e->getMessage());
}

$data = [
    'title' => 'Title',
    'desc' => 'Description',
    'h1' => 'H1',
];

try {
    $fs->save('page', $data);
} catch (DataException $e) {
    die($e->getMessage());
}

try {
    $d = $fs->load('page', $data);
} catch (DataException $e) {
    die($e->getMessage());
} catch (NotFoundException $e) {
    die($e->getMessage());
}

print_r($d);
```