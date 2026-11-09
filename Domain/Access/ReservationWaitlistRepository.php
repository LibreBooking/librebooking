<?php

require_once(ROOT_DIR . 'Domain/ReservationWaitlistRequest.php');

interface IReservationWaitlistRepository
{
    /**
     * @param ReservationWaitlistRequest $request
     * @return int
     */
    public function Add(ReservationWaitlistRequest $request);

    /**
     * @return ReservationWaitlistRequest[]
     */
    public function GetAll();

    /**
     * @param int $waitlistId
     */
    public function LoadById($waitlistId): ?ReservationWaitlistRequest;

    /**
     * @param ReservationWaitlistRequest $request
     */
    public function Delete(ReservationWaitlistRequest $request);
}

class ReservationWaitlistRepository implements IReservationWaitlistRepository
{
    /**
     * @param ReservationWaitlistRequest $request
     * @return int
     */
    public function Add(ReservationWaitlistRequest $request)
    {
        $command = new AddReservationWaitlistCommand($request->UserId(), $request->StartDate(), $request->EndDate(), $request->ResourceId());
        $id = ServiceLocator::GetDatabase()->ExecuteInsert($command);

        $request->WithId($id);

        return $id;
    }

    public function GetAll()
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetAllReservationWaitlistRequests());

        $requests = [];

        while ($row = $reader->GetRow()) {
            $requests[] = ReservationWaitlistRequest::FromRow($row);
        }

        $reader->Free();

        return $requests;
    }

    public function Delete(ReservationWaitlistRequest $request)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteReservationWaitlistCommand($request->Id()));
    }

    public function LoadById($waitlistId): ?ReservationWaitlistRequest
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetReservationWaitlistRequestCommand($waitlistId));

        if ($row = $reader->GetRow()) {
            $reader->Free();
            return ReservationWaitlistRequest::FromRow($row);
        }

        return null;
    }
}
