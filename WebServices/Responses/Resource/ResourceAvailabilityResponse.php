<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ResourcesAvailabilityResponse extends RestResponse
{
    /**
     * @var ResourceAvailabilityResponse[]
     */
    public $resources;

    /**
     * @param ResourceAvailabilityResponse[] $resources
     */
    public function __construct($resources)
    {
        $this->resources[] = $resources;
    }

    public static function Example()
    {
        return new ExampleResourcesAvailabilityResponse();
    }
}

class ExampleResourcesAvailabilityResponse extends ResourcesAvailabilityResponse
{
    public function __construct()
    {
        $this->resources = [ResourceAvailabilityResponse::Example()];
    }
}

class ResourceAvailabilityResponse extends RestResponse
{
    /**
     * @var bool
     */
    public $available;

    /**
     * @var ResourceReference
     */
    public $resource;

    /**
     * @var Date|null
     */
    public $availableAt;

    /**
     * @var Date|null
     */
    public $availableUntil;

    /**
     * @param IRestServer $server
     * @param BookableResource $resource
     * @param ReservationItemView|null $conflictingReservation
     * @param ReservationItemView|null $nextReservation
     * @param Date|null $nextAvailableTime
     * @param Date $lastDateSearched
     */
    public function __construct(IRestServer $server, $resource, $conflictingReservation, $nextReservation, $nextAvailableTime, $lastDateSearched)
    {
        $this->resource = new ResourceReference($server, $resource);
        $this->available = $conflictingReservation == null;

        $this->AddService($server, WebServices::GetResource, [WebServiceParams::ResourceId => $resource->GetId()]);

        if (!$this->available) {
            $this->availableAt = $nextAvailableTime != null ? $nextAvailableTime->ToTimezone($server->GetSession()->Timezone)->ToIso() : null;

            $this->AddService(
                $server,
                WebServices::GetUser,
                [WebServiceParams::UserId => $conflictingReservation->UserId]
            );
            $this->AddService(
                $server,
                WebServices::GetReservation,
                [WebServiceParams::ReferenceNumber => $conflictingReservation->ReferenceNumber]
            );
        }

        if ($nextReservation != null) {
            $this->availableUntil = $nextReservation->BufferedTimes()->GetBegin()->ToTimezone($server->GetSession()->Timezone)->ToIso();
        } else {
            $this->availableUntil = $lastDateSearched->ToTimezone($server->GetSession()->Timezone)->ToIso();
        }
    }

    public static function Example()
    {
        return new ExampleResourceAvailabilityResponse();
    }
}

class ExampleResourceAvailabilityResponse extends ResourceAvailabilityResponse
{
    public function __construct()
    {
        $this->available = true;
        $this->availableAt = Date::Now()->ToIso();
        $this->availableUntil = Date::Now()->ToIso();
        $this->resource = ResourceReference::Example();

        $this->AddServiceLink(new RestServiceLink('http://get-user-url', WebServices::GetUser));
        $this->AddServiceLink(new RestServiceLink('http://get-reservation-url', WebServices::GetReservation));
    }
}
