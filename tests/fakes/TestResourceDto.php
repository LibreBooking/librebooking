<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/ResourceRepository.php');

class TestResourceDto extends ResourceDto
{
    public function __construct(
        $id = 1,
        $name = 'testresourcedto',
        $canAccess = true,
        $canBook = true,
        $scheduleId = 1,
        $minLength = null,
        $resourceTypeId = null,
        $adminGroupId = null,
        $scheduleAdminGroupId = null,
        $statusId = 1,
        $requiresApproval = false,
        $isCheckInEnabled = false,
        $isAutoReleased = false,
        $autoReleaseMinutes = null,
        $color = null,
        $maxConcurrentReservations = null
    ) {
        parent::__construct(
            $id,
            $name,
            $canAccess,
            $canBook,
            $scheduleId,
            ($minLength == null ? TimeInterval::None() : $minLength),
            $resourceTypeId,
            $adminGroupId,
            $scheduleAdminGroupId,
            $statusId,
            $requiresApproval,
            $isCheckInEnabled,
            $isAutoReleased,
            $autoReleaseMinutes,
            $color,
            $maxConcurrentReservations
        );
    }
}
