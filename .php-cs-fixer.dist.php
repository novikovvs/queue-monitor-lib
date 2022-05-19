<?php

return highjin\Fixer\Config::make()
    ->in(__DIR__)
    ->preset(
        new highjin\Fixer\Presets\PrettyLaravel()
    )
    ->out();
