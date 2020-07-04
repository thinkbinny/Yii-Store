<?php

namespace extensions\weixin\mp\message;

use extensions\weixin\core\Driver;

class Transfer extends Driver {

    public $type = 'transfer_customer_service';
    public $props = [];
}