<?php

class SlotLabelFactoryTest extends TestBase
{
    /**
     * @var ReservationItemView
     */
    private $reservation;

    public function setUp(): void
    {
        parent::setup();

        $this->SetConfig(null);

        $this->reservation = new ReservationItemView();
        $this->reservation->Title = 'some title';
        $this->reservation->FirstName = 'first';
        $this->reservation->LastName = 'last';
        $this->reservation->StartDate = Date::Now();
        $this->reservation->EndDate = Date::Now();
        $this->reservation->UserId = 1;
        $this->reservation->UserLevelId = ReservationUserLevel::OWNER;
    }

    public function testGetsNone()
    {
        $this->SetConfig('none');

        $value = SlotLabelFactory::Create($this->reservation);

        $this->assertEquals('', $value);
    }

    public function testWhenUselessValueIsProvided()
    {
        $this->SetConfig('foo');

        $value = SlotLabelFactory::Create($this->reservation);

        $this->assertEquals('foo', $value);
    }

    public function testUsesFormatIfAvailable()
    {
        $this->reservation->Title = 'mytitle';
        $this->reservation->Description = 'mydescription';
        $this->reservation->OwnerEmailAddress = 'myemail';
        $this->reservation->OwnerOrganization = 'myorg';
        $this->reservation->OwnerPhone = 'myphone';
        $this->reservation->OwnerPosition = 'myposition';
        $this->reservation->ParticipantNames = ['p1', 'p2'];
        $this->reservation->InviteeNames = ['i1', 'i2'];
        $this->reservation->StartDate = Date::Now();
        $this->reservation->EndDate = Date::Now();

        $this->SetConfig('{name} + {title} - {description} {email} {phone} {organization} {position} {participants} {invitees}');
        $value = SlotLabelFactory::Create($this->reservation);

        $this->assertEquals(
            'first last + mytitle - mydescription myemail myphone myorg myposition p1, p2 i1, i2',
            $value
        );
    }

    public function testFormatsDates()
    {
        $this->reservation->StartDate = Date::Parse('2014-04-05 08:14:12', 'UTC');
        $this->reservation->EndDate = Date::Parse('2014-04-06 17:18:12', 'UTC');

        $this->SetConfig('{startdate} {enddate}');
        $factory = new SlotLabelFactory($this->fakeUser);
        $value = $factory->Format($this->reservation);

        $this->assertEquals(
            $this->reservation->StartDate->ToTimezone($this->fakeUser->Timezone)->Format($this->fakeResources->GetDateFormat('res_popup')) . ' ' . $this->reservation->EndDate->ToTimezone($this->fakeUser->Timezone)->Format($this->fakeResources->GetDateFormat('res_popup')),
            $value
        );
    }

    public function testParsesAttributes()
    {
        $this->reservation->Attributes = new CustomAttributes();
        $this->reservation->Attributes->Add(2, 'value2');
        $this->reservation->Attributes->Add(1, 'value1');

        $this->SetConfig('{att1} {att2}');

        $value = SlotLabelFactory::Create($this->reservation);
        $this->assertEquals('value1 value2', $value);
    }

    public function testGetsAllAttributes()
    {
        $attributeRepository = new FakeAttributeRepository();
        $attributeRepository->_CustomAttributes = [new FakeCustomAttribute(1), new FakeCustomAttribute(2)];
        $attributes = new CustomAttributes();
        $attributes->Add(1, '1');
        $attributes->Add(2, '2');

        $this->reservation->Attributes = $attributes;

        $this->SetConfig('{reservationAttributes}');
        $factory = new SlotLabelFactory(null, null, $attributeRepository);

        $this->assertEquals('fakeCustomAttribute1: 1, fakeCustomAttribute2: 2', $factory->Format($this->reservation));
    }

    public function testHidesUserDetails()
    {
        $this->SetConfig('{name}');
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, 'true');
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS, 'false');

        $authService = new FakeAuthorizationService();
        $this->fakeUser->AdminGroups = [];
        $this->fakeUser->IsAdmin = false;
        $authService->_CanEditForResource = false;

        $factory = new SlotLabelFactory($this->fakeUser, $authService, new FakeAttributeRepository());

        $label = $factory->Format($this->reservation);

        $fullName = $this->reservation->GetUserName()->__toString();

        $this->assertStringNotContainsString($fullName, $label);
    }

    public function testShowsUserDetailsIfCanEditResource()
    {
        $this->SetConfig('{name}');
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, 'true');
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS, 'true');

        $authService = new FakeAuthorizationService();
        $this->fakeUser->AdminGroups = [];
        $this->fakeUser->IsAdmin = false;
        $authService->_CanEditForResource = true;

        $factory = new SlotLabelFactory($this->fakeUser, $authService, new FakeAttributeRepository());

        $label = $factory->Format($this->reservation);

        $fullName = $this->reservation->GetUserName()->__toString();

        $this->assertStringContainsString($fullName, $label);
    }

    public function testHidesReservationDetails()
    {
        $this->SetConfig('{name} {title}');
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, 'false');
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS, 'true');

        $authService = new FakeAuthorizationService();
        $this->reservation->WithOwnerGroupIds([1]);
        $this->fakeUser->AdminGroups = [$this->reservation->OwnerGroupIds()];
        $this->fakeUser->IsAdmin = false;
        $authService->_CanReserveFor = true;
        $authService->_CanEditForResource = false;

        $factory = new SlotLabelFactory($this->fakeUser, $authService, new FakeAttributeRepository());

        $label = $factory->Format($this->reservation);

        $this->assertEquals('', $label);
    }

    private function SetConfig($value)
    {
        $this->fakeConfig->SetKey(ConfigKeys::SCHEDULE_RESERVATION_LABEL, $value);
    }
}
