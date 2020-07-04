<?php

namespace extensions\weixin\mp\message;

use extensions\weixin\core\Driver;

class Image extends Driver {

    public $type = 'image';
    public $props = [];// MediaId
}