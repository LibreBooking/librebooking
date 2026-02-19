<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class ReservationWaitlistRepositoryTest extends TestBase
{
    /**
     * @var ReservationWaitlistRepository
     */
    public $repository;

    public function setUp(): void
    {
        parent::setup();

        $this->repository = new ReservationWaitlistRepository();
    }

    public function testAddsRequest()
    {
        $startDate = Date::Now();
        $endDate = Date::Now();
        $userId = 1;
        $resourceIds = [1, 2, 3];
        $request = ReservationWaitlistRequest::Create($userId, $startDate, $endDate, $resourceIds);

        $id = $this->repository->Add($request);

        $this->assertEquals($this->db->_ExpectedInsertId, $id);
        $this->assertEquals(new AddReservationWaitlistCommand($userId, $startDate, $endDate, $resourceIds), $this->db->_LastCommand);
    }
}
