<?php

require_once(ROOT_DIR . 'Domain/Blackout.php');

interface IBlackoutRepository
{
    /**
     * @param BlackoutSeries $blackoutSeries
     * @return int
     */
    public function Add(BlackoutSeries $blackoutSeries);

    /**
     * @param BlackoutSeries $blackoutSeries
     */
    public function Update(BlackoutSeries $blackoutSeries);

    /**
     * @param int $blackoutId
     */
    public function Delete($blackoutId);

    /**
     * @param int $blackoutId
     */
    public function DeleteSeries($blackoutId);

    /**
     * @param int $blackoutId
     */
    public function LoadByBlackoutId($blackoutId): ?BlackoutSeries;
}

class BlackoutRepository implements IBlackoutRepository
{
    /**
     * @param BlackoutSeries $blackoutSeries
     * @return int
     */
    public function Add(BlackoutSeries $blackoutSeries)
    {
        $seriesId = $this->AddSeries($blackoutSeries);
        foreach ($blackoutSeries->AllBlackouts() as $blackout) {
            ServiceLocator::GetDatabase()->ExecuteInsert(new AddBlackoutInstanceCommand($seriesId, $blackout->StartDate(), $blackout->EndDate()));
        }

        return $seriesId;
    }

    private function AddSeries(BlackoutSeries $blackoutSeries)
    {
        $db = ServiceLocator::GetDatabase();
        $seriesId = $db->ExecuteInsert(new AddBlackoutCommand(
            $blackoutSeries->OwnerId(),
            $blackoutSeries->Title(),
            $blackoutSeries->RepeatType(),
            $blackoutSeries->RepeatConfigurationString()
        ));

        foreach ($blackoutSeries->ResourceIds() as $resourceId) {
            $db->ExecuteInsert(new AddBlackoutResourceCommand($seriesId, $resourceId));
        }

        return $seriesId;
    }

    /**
     * @param int $blackoutId
     */
    public function Delete($blackoutId)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteBlackoutInstanceCommand($blackoutId));
    }

    /**
     * @param int $blackoutId
     */
    public function DeleteSeries($blackoutId)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteBlackoutSeriesCommand($blackoutId));
    }

    /**
     * @param int $blackoutId
     */
    public function LoadByBlackoutId($blackoutId): ?BlackoutSeries
    {
        $db = ServiceLocator::GetDatabase();
        $reader = $db->Query(new GetBlackoutSeriesByBlackoutIdCommand($blackoutId));

        if ($row = $reader->GetRow()) {
            $series = BlackoutSeries::FromRow($row);

            $result = $db->Query(new GetBlackoutInstancesCommand($series->Id()));

            while ($row = $result->GetRow()) {
                $instance = new Blackout(new DateRange(
                    Date::FromDatabase($row[ColumnNames::BLACKOUT_START]),
                    Date::FromDatabase($row[ColumnNames::BLACKOUT_END])
                ));
                $instance->WithId($row[ColumnNames::BLACKOUT_INSTANCE_ID]);
                $series->AddBlackout($instance);
            }
            $result->Free();

            $result = $db->Query(new GetBlackoutResourcesCommand($series->Id()));

            while ($row = $result->GetRow()) {
                $series->AddResource(new BlackoutResource(
                    $row[ColumnNames::RESOURCE_ID],
                    $row[ColumnNames::RESOURCE_NAME],
                    $row[ColumnNames::SCHEDULE_ID],
                    $row[ColumnNames::RESOURCE_ADMIN_GROUP_ID],
                    $row[ColumnNames::SCHEDULE_ADMIN_GROUP_ID_ALIAS],
                    $row[ColumnNames::RESOURCE_STATUS_ID]
                ));
            }

            $result->Free();
            $reader->Free();
            return $series;
        }

        $reader->Free();
        return null;
    }

    /**
     * @param BlackoutSeries $blackoutSeries
     */
    public function Update(BlackoutSeries $blackoutSeries)
    {
        if ($blackoutSeries->IsNew()) {
            $seriesId = $this->AddSeries($blackoutSeries);
            $db = ServiceLocator::GetDatabase();
            $start = $blackoutSeries->CurrentBlackout()->StartDate();
            $end = $blackoutSeries->CurrentBlackout()->EndDate();
            $db->Execute(new UpdateBlackoutInstanceCommand($blackoutSeries->CurrentBlackoutInstanceId(), $seriesId, $start, $end));
        } else {
            $this->DeleteSeries($blackoutSeries->CurrentBlackoutInstanceId());
            $this->Add($blackoutSeries);
        }
    }
}
