<?php

declare(strict_types=1);

namespace Shrink\Conductor;

use DateTimeInterface;

final class BooleanStatus implements DescribesDependencyStatus
{
    private bool $status;

    private DateTimeInterface $checkedAt;

    public function __construct(bool $status, DateTimeInterface $checkedAt)
    {
        $this->status = $status;
        $this->checkedAt = $checkedAt;
    }

    public function hasStatusCheckPassed(): bool
    {
        return $this->status;
    }

    public function statusCheckedAt(): DateTimeInterface
    {
        return $this->checkedAt;
    }
}
