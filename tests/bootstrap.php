<?php

const KIRBY_HELPER_DUMP = false;

require_once __DIR__ . '/bootstrap.php';

$kirby = new Kirby();

echo $kirby->render();
