<?php
namespace example\domain;

require_once "Bus.php";

use DateTimeImmutable;
use weburg\ghowst\attributes\localdatetime;
use weburg\ghowst\attributes\offsetdatetime;

class Omnibus extends Bus {
    public function __construct() {
        Bus::__construct("omni-58-87");
    }

    #[localdatetime]
    public ?DateTimeImmutable $birthtime = null;

    #[offsetdatetime]
    public ?DateTimeImmutable $sendtime = null;

    public array $toppings = [];
    public array $sides = [];
    public bool $onFire = false;
    public ?\CURLFile $document = null;
    public array $pairing = [];
}
?>