<?php
namespace common\widgets\phpqrcode\vendor\qrencode;
use common\widgets\phpqrcode\vendor\qrrscode\QRrsItem;
class QRrsblock {
    public $dataLength;
    public $data = array();
    public $eccLength;
    public $ecc = array();

    public function __construct($dl, $data, $el, &$ecc, QRrsItem $rs)
    {
        $rs->encode_rs_char($data, $ecc);

        $this->dataLength = $dl;
        $this->data = $data;
        $this->eccLength = $el;
        $this->ecc = $ecc;
    }
};
?>