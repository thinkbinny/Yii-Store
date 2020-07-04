<?php

namespace extensions\weixin\mp\message;

use extensions\weixin\core\Driver;

class Voice extends Driver {

    public $type = 'voice';
    public $props = [];// MediaId
}