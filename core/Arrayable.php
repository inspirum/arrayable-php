<?php

declare(strict_types=1);

if (interface_exists('\Arrayable') === false) {
    interface Arrayable extends \Inspirum\Arrayable\Arrayable
    {

    }
}

