<?php

ray($_SERVER, kirby()->environment()->host());
echo $page->getenv('APP_MODE');
