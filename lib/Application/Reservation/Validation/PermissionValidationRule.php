<?php

require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

class PermissionValidationRule implements IReservationValidationRule
{
    /**
     * @var IPermissionServiceFactory
     */
    private $permissionServiceFactory;

    public function __construct(IPermissionServiceFactory $permissionServiceFactory)
    {
        $this->permissionServiceFactory = $permissionServiceFactory;
    }

    /**
     * @param ReservationSeries $reservationSeries
     * @param $retryParameters
     * @return ReservationRuleResult
     */
    public function Validate($reservationSeries, $retryParameters)
    {
        $reservationSeries->UserId();

        $permissionService = $this->permissionServiceFactory->GetPermissionService();
        $session = $reservationSeries->BookedBy();
        // The usual permission store also includes resources administered by this user.
        // Restricted bookings require ordinary user/group permission as well as any
        // checks imposed by the configured permission plugin.
        $ordinaryPermissions = $session->AllowAdminPrivileges ? null
            : new PermissionService(new ResourcePermissionStore(new ScheduleUserRepository(includeAdminPermissions: false)));

        $resourceIds = $reservationSeries->AllResourceIds();

        foreach ($resourceIds as $resourceId) {
            if ($ordinaryPermissions !== null && !$ordinaryPermissions->CanBookResource(new ReservationResource($resourceId), $session)) {
                return new ReservationRuleResult(false, Resources::GetInstance()->GetString('NoResourcePermission'));
            }
            if (!$permissionService->CanBookResource(new ReservationResource($resourceId), $reservationSeries->BookedBy())) {
                return new ReservationRuleResult(false, Resources::GetInstance()->GetString('NoResourcePermission'));
            }
        }

        return new ReservationRuleResult(true);
    }
}
