<?php
namespace example\domain;

use DateTime;

class Omnibus {
    public function __construct() {}

    public ?DateTime $birthtime = null;
    public ?DateTime $sendtime = null;
    public array $toppings = [];
    public array $sides = [];
    public bool $isOnFire = false;
    public ?\CURLFile $document = null;
    public array $pairing = [];
}
?>