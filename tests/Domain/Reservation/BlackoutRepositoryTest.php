<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/BlackoutRepository.php');

class BlackoutRepositoryTest extends TestBase
{
    /**
     * @var BlackoutRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setup();

        $this->repository = new BlackoutRepository();
    }

    public function testCanAddNewBlackoutSeries()
    {
        $seriesId = 9909870;
        $userId = 123;
        $resourceId = 555;
        $resourceId2 = 5552;
        $title = 'title';
        $start = Date::Parse('2000-01-01 4:44:44');
        $end = Date::Parse('2000-02-01 13:22:33');
        $date = new DateRange($start, $end);

        $series = BlackoutSeries::Create($userId, $title, $date);
        $series->AddResourceId($resourceId);
        $series->AddResourceId($resourceId2);
        $repeatOptions = new RepeatDaily(1, $start->AddDays(2));
        $series->Repeats($repeatOptions);

        $this->db->_ExpectedInsertId = $seriesId;

        $this->repository->Add($series);

        $addBlackoutCommand = new AddBlackoutCommand($userId, $title, $repeatOptions->RepeatType(), $repeatOptions->ConfigurationString());
        $addBlackoutResourceCommand1 = new AddBlackoutResourceCommand($seriesId, $resourceId);
        $addBlackoutResourceCommand2 = new AddBlackoutResourceCommand($seriesId, $resourceId2);
        $addBlackoutInstanceCommand = new AddBlackoutInstanceCommand($seriesId, $start, $end);

        $this->assertEquals($addBlackoutCommand, $this->db->_Commands[0]);
        $this->assertTrue($this->db->ContainsCommand($addBlackoutResourceCommand1));
        $this->assertTrue($this->db->ContainsCommand($addBlackoutResourceCommand2));
        $this->assertTrue($this->db->ContainsCommand($addBlackoutInstanceCommand));
        $this->assertTrue($this->db->ContainsCommand(new AddBlackoutInstanceCommand($seriesId, $start->AddDays(1), $end->AddDays(1))));
    }

    public function testDeletesBlackout()
    {
        $id = 98123;
        $deleteBlackoutCommand = new DeleteBlackoutInstanceCommand($id);

        $this->repository->Delete($id);

        $this->assertEquals($deleteBlackoutCommand, $this->db->_Commands[0]);
    }

    public function testDeletesBlackoutSeriesByBlackoutId()
    {
        $id = 98123;
        $deleteBlackoutCommand = new DeleteBlackoutSeriesCommand($id);
        $this->repository->DeleteSeries($id);

        $this->assertEquals($deleteBlackoutCommand, $this->db->_Commands[0]);
    }

    public function testLoadsBlackoutSeriesByBlackoutId()
    {
        $id = 19191;
        $seriesId = 110101;
        $ownerId = 919199;
        $title = 'title';
        $description = 'description';
        $tz = 'UTC';
        $repeatType = RepeatType::Daily;
        $repeatDaily = new RepeatDaily(1, Date::Parse('2013-04-15', $tz));

        $b1Start = Date::Parse('2013-04-14 12:30:00', $tz);
        $b1End = Date::Parse('2013-04-14 13:30:00', $tz);

        $series = new BlackoutSeriesRow();
        $series->With($seriesId, $ownerId, $title, $repeatType, $repeatDaily->ConfigurationString(), $b1Start, $b1End);

        $instances = new BlackoutInstanceRow();
        $instances->With($seriesId, 1, $b1Start->ToDatabase(), $b1End->ToDatabase());
        $instances->With($seriesId, 2, Date::Parse('2013-04-15 12:30:00', $tz)->ToDatabase(), Date::Parse('2013-04-15 13:30:00', $tz)->ToDatabase());

        $resources = new BlackoutResourceRow();
        $resources->With(1, 'r1', 2, 3, 4);
        $resources->With(2, 'r2', 4);

        $this->db->SetRow(0, $series->Rows());
        $this->db->SetRow(1, $instances->Rows());
        $this->db->SetRow(2, $resources->Rows());

        $loadBlackoutCommand = new GetBlackoutSeriesByBlackoutIdCommand($id);
        $loadBlackoutInstancesCommand = new GetBlackoutInstancesCommand($seriesId);
        $loadBlackoutResourcesCommand = new GetBlackoutResourcesCommand($seriesId);

        $series = $this->repository->LoadByBlackoutId($id);

        $this->assertEquals($loadBlackoutCommand, $this->db->_Commands[0]);
        $this->assertTrue($this->db->ContainsCommand($loadBlackoutInstancesCommand));
        $this->assertTrue($this->db->ContainsCommand($loadBlackoutResourcesCommand));

        $this->assertEquals($seriesId, $series->Id());
        $this->assertEquals($ownerId, $series->OwnerId());
        $this->assertEquals($title, $series->Title());
        $this->assertEquals($repeatType, $series->RepeatType());
        $this->assertEquals($repeatDaily->ConfigurationString(), $series->RepeatConfigurationString());

        $instances = $series->AllBlackouts();
        $this->assertEquals(2, count($instances));
        $keys = array_keys($instances);
        $this->assertEquals($b1Start, $instances[$keys[0]]->StartDate());
        $this->assertEquals($b1End, $instances[$keys[0]]->EndDate());

        $this->assertEquals(count($resources->Rows()), count($series->Resources()));

        $this->assertEquals($b1Start, $series->CurrentBlackout()->StartDate());
    }
}
