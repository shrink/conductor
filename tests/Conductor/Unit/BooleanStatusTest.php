<?php

declare(strict_types=1);

namespace Tests\Conductor\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Shrink\Conductor\BooleanStatus;

final class BooleanStatusTest extends TestCase
{
    /**
     * @test
     */
    public function CheckPassedForTrue(): void
    {
        $passed = new BooleanStatus(
            true,
            new DateTimeImmutable('2018-01-01T00:00:00Z')
        );

        $this->assertTrue($passed->hasStatusCheckPassed());
    }

    /**
     * @test
     */
    public function CheckFailedForFalse(): void
    {
        $failed = new BooleanStatus(
            false,
            new DateTimeImmutable('2018-01-01T00:00:00Z')
        );

        $this->assertFalse($failed->hasStatusCheckPassed());
    }

    /**
     * @test
     */
    public function CheckOccurredAtDateTime(): void
    {
        $at = new DateTimeImmutable('2018-01-01T00:00:00Z');

        $this->assertEquals(
            $at,
            (new BooleanStatus(true, $at))->statusCheckedAt()
        );
    }
}
