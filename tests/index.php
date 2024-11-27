<?php

const KIRBY_HELPER_DUMP = false;
const KIRBY_HELPER_E = false;

require_once __DIR__.'/kirby/bootstrap.php';

$kirby = new Kirby;

echo $kirby->render();
