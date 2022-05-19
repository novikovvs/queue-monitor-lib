<?php

return napopravku\Fixer\Config::make()
    ->in(__DIR__)
    ->preset(
        new napopravku\Fixer\Presets\PrettyLaravel()
    )
    ->out();
