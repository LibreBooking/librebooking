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

        $resourceIds = $reservationSeries->AllResourceIds();

        foreach ($resourceIds as $resourceId) {
            if (!$permissionService->CanBookResource(new ReservationResource($resourceId), $reservationSeries->BookedBy())) {
                return new ReservationRuleResult(false, Resources::GetInstance()->GetString('NoResourcePermission'));
            }
        }

        return new ReservationRuleResult(true);
    }
}
