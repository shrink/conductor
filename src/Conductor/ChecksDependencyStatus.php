<?php

declare(strict_types=1);

namespace Shrink\Conductor;

interface ChecksDependencyStatus
{
    /**
     * Describe status of the dependency.
     */
    public function dependencyStatus(): DescribesDependencyStatus;
}
