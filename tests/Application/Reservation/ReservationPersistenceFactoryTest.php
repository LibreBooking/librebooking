<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/Persistence/namespace.php');

class ReservationPersistenceFactoryTest extends TestBase
{
    public function testCreatesTheAddService()
    {
        $factory = new ReservationPersistenceFactory();

        $this->assertInstanceOf(AddReservationPersistenceService::class, $factory->CreateAddService());
    }

    public function testCreatesTheUpdateService()
    {
        $factory = new ReservationPersistenceFactory();

        $this->assertInstanceOf(UpdateReservationPersistenceService::class, $factory->CreateUpdateService());
    }

    public function testCreatesTheDeleteService()
    {
        $factory = new ReservationPersistenceFactory();

        $this->assertInstanceOf(DeleteReservationPersistenceService::class, $factory->CreateDeleteService());
    }
}
