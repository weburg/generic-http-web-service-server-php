<?php
namespace example\domain;

abstract class Bus {
    private ?string $callsign = null;
    //private ?Engine $engine = null;
    public string $color = "yellow";

    public function __construct(string $callsign) {
        $this->callsign = $callsign;
        //$this->engine = new Engine();
        //$this->engine->cylinders = 8;
        //$this->engine->name = "Bluebird V8";
    }
}