<?php

namespace Novikovvs\QueueMonitor\Validators;

use Novikovvs\QueueMonitor\Validators\Checks\ClassHeir;
use Novikovvs\QueueMonitor\Validators\Checks\Check;

class ClassValidator
{
    const CHECKS = [
        ClassHeir::class
    ];

    private bool $result = true;

    public function validate($class): bool
    {
        /**
         * @var Check $check
         */
        foreach (self::CHECKS as $check) {
          $this->result &= $check::check($class);
        }
        return $this->result;
    }

}
