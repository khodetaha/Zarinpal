<?php

use Khodetaha\Zarinpal\Zarinpal;

if (! function_exists('zarinpal')) {
    function zarinpal(): Zarinpal
    {
        return new Zarinpal();
    }
}
