<?php

use App\Models\MemberModel;

$loader = require __DIR__ . '/../vendor/autoload.php';

$loader->addPsr4('App\\\\', __DIR__ . '/../app');

$base = new \App\Base\Base();




