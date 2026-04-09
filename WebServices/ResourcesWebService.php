<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'WebServices/Responses/ResourceResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/ResourcesResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/CustomAttributes/CustomAttributeResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceStatusResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceStatusReasonsResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceAvailabilityResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceReference.php');
require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceTypesResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceGroupsResponse.php');

class ResourcesWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    /**
     * @var IReservationViewRepository
     */
    private $reservationRepository;

    public function __construct(
        IRestServer $server,
        IResourceRepository $resourceRepository,
        IAttributeService $attributeService,
        IReservationViewRepository $reservationRepository
    ) {
        $this->server = $server;
        $this->resourceRepository = $resourceRepository;
        $this->attributeService = $attributeService;
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @name GetAllResources
     * @description Loads all resources
     * Optional query string parameter: scheduleId. One or more schedule IDs, comma-separated (e.g. scheduleId=1,2,3). If provided, only resources belonging to those schedules will be returned. Each value must be a positive integer (greater than zero); if any value is non-integer or zero, a 400 Bad Request is returned.
     * Optional query string parameter: groupId. One or more resource group IDs, comma-separated (e.g. groupId=1,2,3). If provided, only resources belonging to those groups (including sub-groups) will be returned. Each value must be a positive integer (greater than zero); if any value is non-integer or zero, a 400 Bad Request is returned. If any group ID does not exist, a 404 Not Found is returned.
     * @response ResourcesResponse
     * @return void
     */
    public function GetAll()
    {
        $scheduleIds = null;
        $scheduleIdParam = $this->server->GetQueryString(WebServiceQueryStringKeys::SCHEDULE_ID);
        if ($scheduleIdParam !== null && $scheduleIdParam !== '') {
            $rawIds = explode(',', $scheduleIdParam);
            foreach ($rawIds as $id) {
                if (!ctype_digit($id) || (int)$id === 0) {
                    $this->server->WriteResponse(
                        RestResponse::BadRequest("Invalid scheduleId '$id': must be a positive integer"),
                        RestResponse::BAD_REQUEST_CODE
                    );
                    return;
                }
            }
            $scheduleIds = array_map('intval', $rawIds);
        }

        $groupIds = null;
        $groupIdParam = $this->server->GetQueryString(WebServiceQueryStringKeys::GROUP_ID);
        if ($groupIdParam !== null && $groupIdParam !== '') {
            $rawIds = explode(',', $groupIdParam);
            foreach ($rawIds as $id) {
                if (!ctype_digit($id) || (int)$id === 0) {
                    $this->server->WriteResponse(
                        RestResponse::BadRequest("Invalid groupId '$id': must be a positive integer"),
                        RestResponse::BAD_REQUEST_CODE
                    );
                    return;
                }
            }
            $groupIds = array_map('intval', $rawIds);
        }

        $resources = $this->resourceRepository->GetUserResourceList();

        if ($scheduleIds !== null) {
            $filteredResources = [];
            foreach ($resources as $resource) {
                if (in_array($resource->GetScheduleId(), $scheduleIds)) {
                    $filteredResources[] = $resource;
                }
            }
            $resources = $filteredResources;
        }

        if ($groupIds !== null) {
            $groupTree = $this->resourceRepository->GetResourceGroups();
            $groupList = $groupTree->GetGroupList();
            $groupResourceIds = [];
            foreach ($groupIds as $groupId) {
                if (!array_key_exists($groupId, $groupList)) {
                    $this->server->WriteResponse(RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
                    return;
                }
                $groupResourceIds = array_merge($groupResourceIds, $groupTree->GetResourceIds($groupId));
            }
            $groupResourceIds = array_unique($groupResourceIds);
            $filteredResources = [];
            foreach ($resources as $resource) {
                if (in_array($resource->GetId(), $groupResourceIds)) {
                    $filteredResources[] = $resource;
                }
            }
            $resources = $filteredResources;
        }

        $resourceIds = [];
        foreach ($resources as $resource) {
            $resourceIds[] = $resource->GetId();
        }
        $attributes = $this->attributeService->GetAttributes(CustomAttributeCategory::RESOURCE, $resourceIds);
        $this->server->WriteResponse(new ResourcesResponse($this->server, $resources, $attributes));
    }

    /**
     * @name GetResource
     * @description Loads a specific resource by id
     * @param int $resourceId
     * @response ResourceResponse
     * @return void
     */
    public function GetResource($resourceId)
    {
        $allowedResourceIds = $this->resourceRepository->GetUserResourceIdList();
        if (!in_array($resourceId, $allowedResourceIds)) {
            $resourceId = null;
            $resource = null;
        } else {
            $resource = $this->resourceRepository->LoadById($resourceId);
            $resourceId = $resource->GetResourceId();
        }
        if (empty($resourceId)) {
            $this->server->WriteResponse(RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
        } else {
            $attributes = $this->attributeService->GetAttributes(CustomAttributeCategory::RESOURCE, [$resourceId]);
            $this->server->WriteResponse(new ResourceResponse($this->server, $resource, $attributes));
        }
    }

    /**
     * @name GetStatuses
     * @description Returns all available resource statuses
     * @response ResourceStatusResponse
     * @return void
     */
    public function GetStatuses()
    {
        $this->server->WriteResponse(new ResourceStatusResponse());
    }

    /**
     * @name GetStatusReasons
     * @description Returns all available resource status reasons
     * @response ResourceStatusReasonsResponse
     * @return void
     */
    public function GetStatusReasons()
    {
        $reasons = $this->resourceRepository->GetStatusReasons();

        $this->server->WriteResponse(new ResourceStatusReasonsResponse($reasons));
    }

    /**
     * @name GetResourceTypes
     * @description Returns all available resource types
     * @response ResourceTypesResponse
     * @return void
     */
    public function GetTypes()
    {
        $types = $this->resourceRepository->GetResourceTypes();
        $this->server->WriteResponse(new ResourceTypesResponse($types));
    }

    /**
     * @name GetAvailability
     * @description Returns resource availability for the requested resource (optional). "availableAt" and "availableUntil" will include availability through the next 7 days
     * Optional query string parameter: dateTime. If no dateTime is requested the current datetime will be used.
     * @response ResourcesAvailabilityResponse
     * @return void
     */
    public function GetAvailability()
    {
        $dateQueryString = $this->server->GetQueryString(WebServiceQueryStringKeys::DATE_TIME);
        $resourceId = $this->server->GetQueryString(WebServiceQueryStringKeys::RESOURCE_ID);

        if (!empty($dateQueryString)) {
            $requestedTime = WebServiceDate::GetDate($dateQueryString, $this->server->GetSession());
        } else {
            $requestedTime = Date::Now();
        }

        $resources = [];
        if (empty($resourceId)) {
            $resources = $this->resourceRepository->GetUserResourceList();
        } else {
            $allowedResourceIds = $this->resourceRepository->GetUserResourceIdList();
            if (in_array($resourceId, $allowedResourceIds)) {
                $resources[] = $this->resourceRepository->LoadById($resourceId);
            }
        }

        $lastDateSearched = $requestedTime->AddDays(7);
        $reservations = $this->GetReservations($this->reservationRepository->GetReservations($requestedTime, $lastDateSearched, null, null, null, $resourceId));

        $resourceAvailability = [];

        foreach ($resources as $resource) {
            $reservation = $this->GetOngoingReservation($resource, $reservations);

            if ($reservation != null) {
                $lastReservationBeforeOpening = $this->GetLastReservationBeforeAnOpening($resource, $reservations);

                if ($lastReservationBeforeOpening == null) {
                    $lastReservationBeforeOpening = $reservation;
                }

                $resourceAvailability[] = new ResourceAvailabilityResponse($this->server, $resource, $lastReservationBeforeOpening, null, $lastReservationBeforeOpening->EndDate, $lastDateSearched);
            } else {
                $resourceId = $resource->GetId();
                if (array_key_exists($resourceId, $reservations)) {
                    $resourceAvailability[] = new ResourceAvailabilityResponse($this->server, $resource, null, $reservations[$resourceId][0], null, $lastDateSearched);
                } else {
                    $resourceAvailability[] = new ResourceAvailabilityResponse($this->server, $resource, null, null, null, $lastDateSearched);
                }
            }
        }

        $this->server->WriteResponse(new ResourcesAvailabilityResponse($resourceAvailability));
    }

    /**
     * @name GetGroups
     * @description Returns the full resource group tree
     * @response ResourceGroupsResponse
     * @return void
     */
    public function GetGroups()
    {
        $groups = $this->resourceRepository->GetResourceGroups();

        $this->server->WriteResponse(new ResourceGroupsResponse($groups));
    }

    /**
     * @param BookableResource $resource
     * @param ReservationItemView[][] $reservations
     * @return ReservationItemView|null
     */
    private function GetOngoingReservation($resource, $reservations)
    {
        if (array_key_exists($resource->GetId(), $reservations) && $reservations[$resource->GetId()][0]->StartDate->LessThan(Date::Now())) {
            return $reservations[$resource->GetId()][0];
        }

        return null;
    }

    /**
     * @param ReservationItemView[] $reservations
     * @return ReservationItemView[][]
     */
    private function GetReservations($reservations)
    {
        $indexed = [];
        foreach ($reservations as $reservation) {
            $indexed[$reservation->ResourceId][] = $reservation;
        }

        return $indexed;
    }

    /**
     * @param BookableResource $resource
     * @param ReservationItemView[][] $reservations
     * @return null|ReservationItemView
     */
    private function GetLastReservationBeforeAnOpening($resource, $reservations)
    {
        $resourceId = $resource->GetId();
        if (!array_key_exists($resourceId, $reservations)) {
            return null;
        }

        $resourceReservations = $reservations[$resourceId];
        for ($i = 0; $i < count($resourceReservations) - 1; $i++) {
            $current = $resourceReservations[$i];
            $next = $resourceReservations[$i + 1];

            if ($current->EndDate->Equals($next->StartDate)) {
                continue;
            }

            return $current;
        }

        return $resourceReservations[count($resourceReservations) - 1];
    }
}
