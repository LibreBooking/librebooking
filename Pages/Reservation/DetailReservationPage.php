<?php

require_once(ROOT_DIR . 'Pages/Ajax/AutoCompletePage.php');
require_once(ROOT_DIR . 'Pages/Reservation/NewReservationPage.php');
require_once(ROOT_DIR . 'Pages/Reservation/ExistingReservationPage.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/GuestPermissionServiceFactory.php');

class DetailReservationPage extends ExistingReservationPage
{
    public function __construct()
    {
        $this->permissionServiceFactory = new GuestPermissionServiceFactory();
        parent::__construct();
        $this->IsEditable = false;
        $this->IsApprovable = false;
    }

   public function PageLoad()
{
    $this->EnrichReservationByReference();
    parent::PageLoad();
}

private function EnrichReservationByReference()
{
    $referenceNumber = $this->GetReferenceNumber();
    if (!$referenceNumber) {
        return;
    }

    $reservationRepository = new ReservationRepository();
    $reservation = $reservationRepository->LoadByReferenceNumber($referenceNumber);
    $location = $this->GetResourceLocations($reservation);

    if (!$reservation) {
        return;
    }

    $userRepository = new UserRepository();
    $user = $userRepository->LoadById($reservation->UserId());

    $phone = null;
    if ($user) {
        $phone = $user->GetAttribute(UserAttribute::Phone);
        // Gruppen des Benutzers laden
        $groups = $userRepository->LoadGroups($user->Id());
        
        foreach ($groups as $group) {
            $userGroups[] = $group->GroupName;
        }
    }

    $this->Set('UserPhone', $phone);
    $this->Set('ResourceLocation', $location);
    $this->Set('UserGroup', $userGroups);
}


    /**
     * Get locations of all resources in the reservation
     *
     * @param ExistingReservationSeries $series
     * @return array
     */
    private function GetResourceLocations($series)
    {
        $resourceLocations = [];
        $processedResourceIds = [];

        foreach ($series->AllResources() as $resource) {
            if (empty($resource)) {
                continue;
            }

            $resourceId = $resource->GetResourceId();

            // Deduplicate by resource ID
            if (!in_array($resourceId, $processedResourceIds)) {
                $processedResourceIds[] = $resourceId;
                $location = $resource->GetLocation();
                if (!empty($location)) {
                    $resourceLocations[] = $location;
                }
            }
        }

        return $resourceLocations;
    }
    
    protected function GetResourceRepository()
    {
        return ServiceLocator::GetResourceRepository();
    }

    public function SetIsEditable($canBeEdited)
    {
        // no-op
    }

    public function SetIsApprovable($canBeApproved)
    {
        // no-op
    }
    
    protected function GetTemplateName()
    {
        return 'Reservation/view.tpl';
    }
}
