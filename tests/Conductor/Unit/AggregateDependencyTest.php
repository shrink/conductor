<?php

declare(strict_types=1);

namespace Tests\Conductor\Unit;

use DateTimeImmutable;
use InvalidArgumentException;
use Lcobucci\Clock\Clock;
use PHPUnit\Framework\TestCase;
use Shrink\Conductor\AggregateDependency;
use Shrink\Conductor\BooleanStatus;
use Shrink\Conductor\ChecksDependencyStatus;
use Shrink\Conductor\DescribesDependencyStatus;

final class AggregateDependencyTest extends TestCase
{
    /**
     * Create list of expected status check results from a set of status checks.
     *
     * @return array<string<array<BooleanStatus,array<DescribesDependencyStatus>>>
     */
    public function expectedStatusCheckResults(): array
    {
        $pass = true;
        $fail = false;

        $at = new DateTimeImmutable('2018-01-01T00:00:00Z');

        ($fails = $this->createMock(ChecksDependencyStatus::class))
            ->method('dependencyStatus')
            ->willReturn(new BooleanStatus($fail, $at));

        ($passes = $this->createMock(ChecksDependencyStatus::class))
            ->method('dependencyStatus')
            ->willReturn(new BooleanStatus($pass, $at));

        return [
            'One dependency, one failure' => [$fail, [$fails]],
            'Many dependencies, one failure' => [$fail, [$passes, $passes, $fails]],
            'Many dependencies, many failure' => [$fail, [$passes, $fails, $fails]],
            'Many dependencies, all failure' => [$fail, [$fails, $fails, $fails]],
            'One dependency, one pass' => [$pass, [$passes]],
            'Many dependencies, all pass' => [$pass, [$passes, $passes, $passes]],
        ];
    }

    /**
     * @test
     * @dataProvider expectedStatusCheckResults
     *
     * @param bool $expectedPass
     * @param array<\ChecksDependencyStatus> $dependencies
     */
    public function StatusIsDerivedFromAggregatedResults(
        bool $expectedPass,
        array $dependencies
    ): void {
        ($clock = $this->createMock(Clock::class))
            ->method('now')
            ->willReturn(new DateTimeImmutable('2018-01-01T00:00:00Z'));

        $aggregateDependency = new AggregateDependency($clock, $dependencies);

        $this->assertEquals(
            $expectedPass,
            $aggregateDependency->dependencyStatus()->hasStatusCheckPassed()
        );
    }

    /**
     * @test
     */
    public function StatusIsReportedAtCurrentClockTime(): void
    {
        $now = new DateTimeImmutable('2018-01-01T00:00:00Z');

        ($clock = $this->createMock(Clock::class))
            ->method('now')
            ->willReturn($now);

        $aggregateDependency = new AggregateDependency($clock, []);

        $this->assertEquals(
            $now,
            $aggregateDependency->dependencyStatus()->statusCheckedAt()
        );
    }
}
