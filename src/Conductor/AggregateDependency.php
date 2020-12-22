<?php

declare(strict_types=1);

namespace Shrink\Conductor;

use Lcobucci\Clock\Clock;

use function array_filter;
use function count;

final class AggregateDependency implements ChecksDependencyStatus
{
    private Clock $clock;

    /**
     * @var array<\Shrink\Conductor\ChecksDependencyStatus>
     */
    private array $dependencies;

    /**
     * @param array<\Shrink\Conductor\ChecksDependencyStatus> $dependencies
     */
    public function __construct(Clock $clock, array $dependencies)
    {
        $this->clock = $clock;
        $this->dependencies = $dependencies;
    }

    /**
     * Check each aggregated dependency passes the status check.
     */
    public function dependencyStatus(): DescribesDependencyStatus
    {
        $passed = static function (ChecksDependencyStatus $check): bool {
            return $check->dependencyStatus()->hasStatusCheckPassed();
        };

        $passed = array_filter($this->dependencies, $passed);

        return new BooleanStatus(
            count($passed) === count($this->dependencies),
            $this->clock->now()
        );
    }
}
