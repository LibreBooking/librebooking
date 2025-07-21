<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/namespace.php');

class ReservationNotificationFactoryTest extends TestBase
{
    /**
     * @var IPostReservationFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $plugin;

    /**
     * @var IReservationNotificationService|PHPUnit\Framework\MockObject\MockObject
     */
    private $service;

    public function setUp(): void
    {
        parent::setup();

        $this->plugin = $this->createMock('IPostReservationFactory');
        $pluginManager = new FakePluginManager();
        $pluginManager->postResPlugin = $this->plugin;
        $this->service = $this->createMock('IReservationNotificationService');

        PluginManager::SetInstance($pluginManager);
    }

    public function testLoadsAddFromPlugins()
    {
        $this->plugin->expects($this->once())
                ->method('CreatePostAddService')
                ->with($this->fakeUser)
                ->willReturn($this->service);

        $reservationValidationFactory = new ReservationNotificationFactory();
        $actual = $reservationValidationFactory->Create(ReservationAction::Create, $this->fakeUser);

        $this->assertEquals($this->service, $actual);
    }

    public function testLoadsUpdateRulesFromPlugins()
    {
        $this->plugin->expects($this->once())
                ->method('CreatePostUpdateService')
                ->with($this->fakeUser)
                ->willReturn($this->service);

        $reservationValidationFactory = new ReservationNotificationFactory();
        $actual = $reservationValidationFactory->Create(ReservationAction::Update, $this->fakeUser);

        $this->assertEquals($this->service, $actual);
    }

    public function testLoadsDeleteRulesFromPlugins()
    {
        $this->plugin->expects($this->once())
                ->method('CreatePostDeleteService')
                ->with($this->fakeUser)
                ->willReturn($this->service);

        $reservationValidationFactory = new ReservationNotificationFactory();
        $actual = $reservationValidationFactory->Create(ReservationAction::Delete, $this->fakeUser);

        $this->assertEquals($this->service, $actual);
    }


    public function testLoadsApproveRulesFromPlugins()
    {
        $this->plugin->expects($this->once())
                ->method('CreatePostApproveService')
                ->with($this->fakeUser)
                ->willReturn($this->service);

        $reservationValidationFactory = new ReservationNotificationFactory();
        $actual = $reservationValidationFactory->Create(ReservationAction::Approve, $this->fakeUser);

        $this->assertEquals($this->service, $actual);
    }
}
