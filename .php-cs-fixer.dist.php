<?php

return Napopravku\Fixer\Config::make()
    ->in(__DIR__)
    ->preset(
        new Napopravku\Fixer\Presets\PrettyLaravel()
    )
    ->out();
