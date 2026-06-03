<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class ReservationValidationFactoryTest extends TestBase
{
    public function testLoadsAddRulesFromPlugins()
    {
        $preResPlugin = $this->createMock('IPreReservationFactory');
        $pluginManager = new FakePluginManager();
        $pluginManager->preResPlugin = $preResPlugin;
        $validationService = $this->createMock('IReservationValidationService');

        PluginManager::SetInstance($pluginManager);

        $preResPlugin->expects($this->once())
                ->method('CreatePreAddService')
                ->with($this->fakeUser)
                ->willReturn($validationService);

        $reservationValidationFactory = new ReservationValidationFactory();
        $actual = $reservationValidationFactory->Create(ReservationAction::Create, $this->fakeUser);

        $this->assertEquals($validationService, $actual);
    }

    public function testLoadsUpdateRulesFromPlugins()
    {
        $preResPlugin = $this->createMock('IPreReservationFactory');
        $pluginManager = new FakePluginManager();
        $pluginManager->preResPlugin = $preResPlugin;
        $validationService = $this->createMock('IReservationValidationService');

        PluginManager::SetInstance($pluginManager);

        $preResPlugin->expects($this->once())
                ->method('CreatePreUpdateService')
                ->with($this->fakeUser)
                ->willReturn($validationService);

        $reservationValidationFactory = new ReservationValidationFactory();
        $actual = $reservationValidationFactory->Create(ReservationAction::Update, $this->fakeUser);

        $this->assertEquals($validationService, $actual);
    }

    public function testLoadsDeleteRulesFromPlugins()
    {
        $preResPlugin = $this->createMock('IPreReservationFactory');
        $pluginManager = new FakePluginManager();
        $pluginManager->preResPlugin = $preResPlugin;
        $validationService = $this->createMock('IReservationValidationService');

        PluginManager::SetInstance($pluginManager);

        $preResPlugin->expects($this->once())
                ->method('CreatePreDeleteService')
                ->with($this->fakeUser)
                ->willReturn($validationService);

        $reservationValidationFactory = new ReservationValidationFactory();
        $actual = $reservationValidationFactory->Create(ReservationAction::Delete, $this->fakeUser);

        $this->assertEquals($validationService, $actual);
    }
}
