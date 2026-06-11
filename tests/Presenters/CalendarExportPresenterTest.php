<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Export/CalendarExportPage.php');
require_once(ROOT_DIR . 'Presenters/CalendarExportPresenter.php');

class CalendarExportPresenterTest extends TestBase
{
    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $repo;

    /**
     * @var ICalendarExportPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var CalendarExportPresenter
     */
    private $presenter;

    /**
     * @var ICalendarExportValidator|PHPUnit\Framework\MockObject\MockObject
     */
    private $validator;

    /**
     * @var FakePrivacyFilter
     */
    private $privacyFilter;

    public function setUp(): void
    {
        parent::setup();

        $this->repo = $this->createMock('IReservationViewRepository');
        $this->page = $this->createMock('ICalendarExportPage');
        $this->validator = $this->createMock('ICalendarExportValidator');
        $this->privacyFilter = new FakePrivacyFilter();

        $this->presenter = new CalendarExportPresenter($this->page, $this->repo, $this->validator, $this->privacyFilter);
    }

    public function testLoadsReservationByReferenceNumber()
    {
        $referenceNumber = 'ref';
        $reservationResult = new ReservationView();

        $this->validator->expects($this->atLeastOnce())
                ->method('IsValid')
                ->willReturn(true);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->repo->expects($this->once())
                ->method('GetReservationForEditing')
                ->with($this->equalTo($referenceNumber))
                ->willReturn($reservationResult);

        $this->page->expects($this->once())
                ->method('SetReservations')
                ->with($this->arrayHasKey(0));

        $this->presenter->PageLoad($this->fakeUser);
    }

    public function testCannotSeeReservationDetailsIfConfiguredOff()
    {
        $referenceNumber = 'ref';
        $reservationResult = new ReservationView();

        $this->validator->expects($this->atLeastOnce())
                ->method('IsValid')
                ->willReturn(true);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->repo->expects($this->once())
                ->method('GetReservationForEditing')
                ->with($this->equalTo($referenceNumber))
                ->willReturn($reservationResult);

        $this->page->expects($this->once())
                ->method('SetReservations')
                ->with($this->arrayHasKey(0));

        $this->presenter->PageLoad($this->fakeUser);
    }

    public function testOrganizerIsOwnerIfCurrentUserIsNotOrganizer()
    {
        // this fixes a bug in outlook which prevents you from adding a meeting that you are the organizer of
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->OwnerId = $user->UserId + 1;
        $res->OwnerFirstName = 'f';
        $res->OwnerLastName = 'l';
        $res->OwnerEmailAddress = 'e@m.com';

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);
        $this->assertEquals($res->OwnerEmailAddress, $reservationView->OrganizerEmail);
        $fullName = new FullName($res->OwnerFirstName, $res->OwnerLastName);
        $this->assertEquals($fullName->__toString(), $reservationView->Organizer);
    }

    public function testOrganizerIsDefaultedIfCurrentUserIsOrganizer()
    {
        // this fixes a bug in outlook which prevents you from adding a meeting that you are the organizer of
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->OwnerId = $user->UserId;
        $res->OwnerFirstName = 'f';
        $res->OwnerLastName = 'l';
        $res->OwnerEmailAddress = 'e@m.com';

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);
        $this->assertEquals('e-noreply@m.com', $reservationView->OrganizerEmail);
        $fullName = new FullName($res->OwnerFirstName, $res->OwnerLastName);
        $this->assertEquals($fullName->__toString(), $reservationView->Organizer);
    }

    public function testViewHidesDetailsWhenNoAccess()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();

        $this->privacyFilter->_CanViewDetails = false;
        $this->privacyFilter->_CanViewUser = false;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);

        $this->assertEquals($user, $this->privacyFilter->_LastViewDetailsUserSession);
        $this->assertEquals($user, $this->privacyFilter->_LastViewUserUserSession);

        $this->assertEquals($res, $this->privacyFilter->_LastViewDetailsReservation);
        $this->assertEquals($res, $this->privacyFilter->_LastViewUserReservation);

        $this->assertEquals('Private', $reservationView->Organizer);
        $this->assertEquals('Private', $reservationView->OrganizerEmail);
        $this->assertEquals('Private', $reservationView->Summary);
        $this->assertEquals('Private', $reservationView->Description);
    }

    public function testViewShowsFormattedSummaryWhenDetailsVisible()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->UserId = $user->UserId;
        $res->UserLevelId = ReservationUserLevel::OWNER;
        $res->Title = 'My Booking Title';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);
        $res->OwnerFirstName = 'Test';
        $res->OwnerLastName = 'User';
        $res->OwnerEmailAddress = 'test@example.com';

        $this->privacyFilter->_CanViewDetails = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter, '{title}');

        $this->assertEquals('My Booking Title', $reservationView->Summary);
    }

    public function testViewShowsDescriptionFromReservationNotesWhenDetailsVisible()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->Description = 'Booking notes';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->privacyFilter->_CanViewDetails = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);

        $this->assertEquals('Booking notes', $reservationView->Description);
    }

    public function testAnonymousUserSeesPrivateWhenPublicReservationViewingIsDisabled()
    {
        $user = new NullUserSession();
        $res = new ReservationItemView();
        $res->OwnerId = 42;
        $res->OwnerFirstName = 'Alice';
        $res->OwnerLastName = 'Smith';
        $res->OwnerEmailAddress = 'alice@example.com';
        $res->Title = 'Secret title';
        $res->Description = 'Secret notes';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        // privacy.view.reservations=false (default) means anonymous users must not see any details
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, false);
        $this->privacyFilter->_CanViewDetails = true;
        $this->privacyFilter->_CanViewUser = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);

        $this->assertEquals('Private', $reservationView->Summary);
        $this->assertEquals('Private', $reservationView->Description);
        $this->assertEquals('Private', $reservationView->Organizer);
        $this->assertEquals('Private', $reservationView->OrganizerEmail);
    }

    public function testViewEscapesNewlinesInTextPropertiesForICalCompliance()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->UserId = $user->UserId;
        $res->UserLevelId = ReservationUserLevel::OWNER;
        $res->Title = "First line\r\nSecond line\nThird line";
        $res->Description = "Alpha\r\nBeta\nGamma";
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->privacyFilter->_CanViewDetails = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter, '{title}');

        $this->assertEquals('First line\\nSecond line\\nThird line', $reservationView->Summary);
        $this->assertEquals('Alpha\\nBeta\\nGamma', $reservationView->Description);
    }

    public function testViewEscapesBackslashSemicolonAndCommaInTextPropertiesForICalCompliance()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->UserId = $user->UserId;
        $res->UserLevelId = ReservationUserLevel::OWNER;
        $res->Title = 'x\\y;z,w';
        $res->Description = 'a\\b;c,d';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->privacyFilter->_CanViewDetails = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter, '{title}');

        $this->assertEquals('x\\\\y\\;z\\,w', $reservationView->Summary);
        $this->assertEquals('a\\\\b\\;c\\,d', $reservationView->Description);
    }

    public function testSlotLabelFormatCanExplicitlySkipVisibilityChecks()
    {
        $user = new NullUserSession();
        $res = new ReservationItemView();
        $res->Title = 'Public Meeting';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, false);

        $factory = new SlotLabelFactory($user, new FakeAuthorizationService());

        $this->assertEquals('', $factory->Format($res, '{title}'));
        $this->assertEquals('Public Meeting', $factory->Format($res, '{title}', skipVisibilityChecks: true));
    }

    public function testSlotLabelFormatStillRedactsUserTokensWhenSkippingVisibilityChecks()
    {
        $user = new FakeUserSession(false, 'America/New_York', 7);
        $auth = new FakeAuthorizationService();
        $auth->_CanEditForResource = false;

        $res = new ReservationItemView();
        $res->OwnerId = 42;
        $res->FirstName = 'Alice';
        $res->LastName = 'Smith';
        $res->OwnerEmailAddress = 'alice@example.com';
        $res->OwnerPhone = '555-1234';
        $res->OwnerOrganization = 'Engineering';
        $res->OwnerPosition = 'Manager';
        $res->ParticipantNames = ['Participant One'];
        $res->InviteeNames = ['Invitee One'];
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, true);

        $factory = new SlotLabelFactory($user, $auth);
        $label = $factory->Format(
            $res,
            '{name} {email} {phone} {organization} {position} {participants} {invitees}',
            skipVisibilityChecks: true
        );

        $this->assertStringContainsString('Private', $label);
        $this->assertStringNotContainsString('Alice', $label);
        $this->assertStringNotContainsString('alice@example.com', $label);
        $this->assertStringNotContainsString('555-1234', $label);
        $this->assertStringNotContainsString('Engineering', $label);
        $this->assertStringNotContainsString('Manager', $label);
        $this->assertStringNotContainsString('Participant One', $label);
        $this->assertStringNotContainsString('Invitee One', $label);
    }

    public function testNullSlotLabelFactoryRemainsFailClosedWhenSkippingVisibilityChecks()
    {
        $res = new ReservationItemView();
        $res->Title = 'Public Meeting';

        $factory = new NullSlotLabelFactory();

        $this->assertEquals('', $factory->Format($res, '{title}', skipVisibilityChecks: true));
    }

    public function testCalendarExportProdIdUsesApplicationVersionInsteadOfConfigValue()
    {
        $this->fakeConfig->SetKey('version', '9.9.9-user-config');
        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';

        $display = new CalendarExportDisplay();
        $calendar = $display->Render([]);

        $this->assertStringContainsString(
            'PRODID:-//LibreBooking//NONSGML ' . Configuration::VERSION . '//EN',
            $calendar
        );
        $this->assertStringNotContainsString('9.9.9-user-config', $calendar);
    }
}
