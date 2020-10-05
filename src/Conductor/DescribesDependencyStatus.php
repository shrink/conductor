<?php

declare(strict_types=1);

namespace Shrink\Conductor;

use DateTimeInterface;

interface DescribesDependencyStatus
{
    /**
     * Boolean describing whether the status check passed.
     */
    public function hasStatusCheckPassed(): bool;

    /**
     * Time at which the Dependency status was checked.
     */
    public function statusCheckedAt(): DateTimeInterface;
}
