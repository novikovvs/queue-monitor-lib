<?php

return Novikovvs\Fixer\Config::make()
    ->in(__DIR__)
    ->preset(
        new Novikovvs\Fixer\Presets\PrettyLaravel()
    )
    ->out();
