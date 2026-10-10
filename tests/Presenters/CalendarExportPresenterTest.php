<?php

declare(strict_types=1);

use LibreBooking\Calendar\IcsMethod;

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

    public function testOrganizerIsTheReservationOwnerWithSentByAsTheConfiguredDefaultAddress()
    {
        // ORGANIZER is the owner; SENT-BY is the site's default address.
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, 'bookings@example.com');
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_NAME, 'Example Bookings');

        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->OwnerId = $user->UserId;
        $res->OwnerFirstName = 'f';
        $res->OwnerLastName = 'l';
        $res->OwnerEmailAddress = 'e@m.com';

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);
        $this->assertEquals('e@m.com', $reservationView->OrganizerEmail);
        $this->assertEquals('f l', $reservationView->Organizer);
        $this->assertEquals('bookings@example.com', $reservationView->OrganizerSentBy);
    }

    public function testOrganizerFallsBackToTheDefaultAddressWithNoSentByWhenPrivacyHidesTheOwner()
    {
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, 'bookings@example.com');
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_NAME, 'Example Bookings');

        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->OwnerId = $user->UserId;
        $res->OwnerFirstName = 'f';
        $res->OwnerLastName = 'l';
        $res->OwnerEmailAddress = 'e@m.com';

        $this->privacyFilter->_CanViewUser = false;
        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);

        $this->assertEquals('bookings@example.com', $reservationView->OrganizerEmail);
        $this->assertEquals('Private', $reservationView->Organizer);
        // no real identity, so nothing to send on behalf of
        $this->assertNull($reservationView->OrganizerSentBy);
    }

    public function testOrganizerIsOmittedFromRenderedOutputWhenOwnerHasNoEmailOnFile()
    {
        // an owner without an email gets no ORGANIZER rather than an empty "mailto:"
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);
        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';
        $display = new CalendarExportDisplay();
        $ics = $display->Render([$reservationView]);

        $this->assertStringNotContainsString('ORGANIZER', $ics);
    }

    public function testOrganizerRendersWithSentByAndNoAttendeeIsAdded()
    {
        // the owner is only ever ORGANIZER, never ATTENDEE
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, 'bookings@example.com');

        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->OwnerId = $user->UserId;
        $res->OwnerFirstName = 'f';
        $res->OwnerLastName = 'l';
        $res->OwnerEmailAddress = 'e@m.com';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->privacyFilter->_CanViewDetails = true;
        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);

        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';
        $display = new CalendarExportDisplay();
        $ics = $display->Render([$reservationView]);
        $unfolded = str_replace("\r\n ", '', $ics);

        $this->assertMatchesRegularExpression('/ORGANIZER;CN=f l;SENT-BY="mailto:bookings@example\.com":mailto:e@m\.com/', $unfolded);
        $this->assertStringNotContainsString('ATTENDEE', $ics);
    }

    public function testViewHidesDetailsWhenNoAccess()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();

        $this->privacyFilter->_CanViewDetails = false;
        $this->privacyFilter->_CanViewUser = false;
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, 'noreply@example.com');
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_NAME, 'LB');

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);

        $this->assertEquals($user, $this->privacyFilter->_LastViewDetailsUserSession);
        $this->assertEquals($user, $this->privacyFilter->_LastViewUserUserSession);

        $this->assertEquals($res, $this->privacyFilter->_LastViewDetailsReservation);
        $this->assertEquals($res, $this->privacyFilter->_LastViewUserReservation);

        // hidden owner: ORGANIZER is the default address, without SENT-BY
        $this->assertEquals('Private', $reservationView->Organizer);
        $this->assertEquals('noreply@example.com', $reservationView->OrganizerEmail);
        $this->assertNull($reservationView->OrganizerSentBy);
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
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, 'noreply@example.com');
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_NAME, 'LB');
        $this->privacyFilter->_CanViewDetails = true;
        $this->privacyFilter->_CanViewUser = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter);

        $this->assertEquals('Private', $reservationView->Summary);
        $this->assertEquals('Private', $reservationView->Description);
        // anonymous viewers also lose the owner, as in the hidden-owner case
        $this->assertEquals('Private', $reservationView->Organizer);
        $this->assertEquals('noreply@example.com', $reservationView->OrganizerEmail);
        $this->assertNull($reservationView->OrganizerSentBy);
    }

    public function testViewStoresRawTextInSummaryAndDescription()
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

        // View stores raw values; RFC 5545 escaping is handled by Sabre at serialization time.
        $this->assertEquals("First line\r\nSecond line\nThird line", $reservationView->Summary);
        $this->assertEquals("Alpha\r\nBeta\nGamma", $reservationView->Description);
    }

    public function testViewStoresRawSpecialCharactersInSummaryAndDescription()
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

        // View stores raw values; Sabre escapes backslash, semicolon, and comma during serialization.
        $this->assertEquals('x\\y;z,w', $reservationView->Summary);
        $this->assertEquals('a\\b;c,d', $reservationView->Description);
    }

    public function testSerializedOutputEscapesRFC5545ReservedCharactersInDescriptionAndSummary()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->UserId = $user->UserId;
        $res->UserLevelId = ReservationUserLevel::OWNER;
        $res->Title = 'Title; with, reserved\\ chars';
        $res->Description = 'Desc; with, reserved\\ chars';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);
        $res->OwnerId = $user->UserId + 1;
        $res->OwnerEmailAddress = 'owner@example.com';

        $this->privacyFilter->_CanViewDetails = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter, '{title}');
        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';
        $display = new CalendarExportDisplay();
        $ics = $display->Render([$reservationView]);

        // RFC 5545 §3.3.11: backslash, comma, semicolon are reserved in TEXT values.
        $this->assertStringContainsString('SUMMARY:Title\; with\, reserved\\\\ chars', $ics);
        $this->assertStringContainsString('DESCRIPTION:Desc\; with\, reserved\\\\ chars', $ics);
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

    public function testCalendarNameIsRenderedAsNameAndXWrCalname()
    {
        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';

        $display = new CalendarExportDisplay();
        $calendar = $display->Render([], 'Engineering Schedule');

        // NAME: is checked with line boundaries since X-WR-CALNAME: also contains "NAME:" as a substring.
        $this->assertStringContainsString("\r\nNAME:Engineering Schedule\r\n", $calendar);
        $this->assertStringContainsString('X-WR-CALNAME:Engineering Schedule', $calendar);
    }

    public function testCalendarNameIsOmittedWhenNotProvided()
    {
        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';

        $display = new CalendarExportDisplay();
        $calendar = $display->Render([]);

        $this->assertStringNotContainsString('NAME:', $calendar);
        $this->assertStringNotContainsString('X-WR-CALNAME:', $calendar);
    }

    public function testExtraIcalLinesPreservesPropertyParametersAndNestedComponents()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->UserId = $user->UserId;
        $res->UserLevelId = ReservationUserLevel::OWNER;
        $res->Title = 'Title';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->privacyFilter->_CanViewDetails = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter, '{title}');
        // a parameterised property and a nested VALARM must round-trip
        // through Reader::read() rather than becoming malformed flat text.
        $reservationView->ExtraIcalLines = "CATEGORIES;LANGUAGE=en:Meeting\r\n"
            . "BEGIN:VALARM\r\nACTION:AUDIO\r\nTRIGGER:-PT15M\r\nEND:VALARM";

        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';
        $display = new CalendarExportDisplay();
        $ics = $display->Render([$reservationView]);

        $this->assertStringContainsString('CATEGORIES;LANGUAGE=en:Meeting', $ics);
        $this->assertStringContainsString('BEGIN:VALARM', $ics);
        $this->assertStringContainsString('ACTION:AUDIO', $ics);
    }

    public function testRenderStripsAttendeePropertiesInjectedViaExtraIcalLines()
    {
        // plugin-supplied lines must not smuggle attendees in
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->Title = 'Title';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->privacyFilter->_CanViewDetails = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter, '{title}');
        $reservationView->ExtraIcalLines = "ATTENDEE;CN=JaneDoe;ROLE=REQ-PARTICIPANT:mailto:jane@example.com\r\n"
            . "BEGIN:VALARM\r\nACTION:AUDIO\r\nTRIGGER:-PT15M\r\nEND:VALARM";

        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';
        $display = new CalendarExportDisplay();
        $ics = $display->Render([$reservationView]);

        $this->assertStringContainsString('METHOD:PUBLISH', $ics);
        $this->assertStringNotContainsString('ATTENDEE', $ics);
        // other lines survive
        $this->assertStringContainsString('BEGIN:VALARM', $ics);
        $this->assertStringContainsString('ACTION:AUDIO', $ics);
    }

    public function testExtraIcalLinesSupportsRfc5545FoldedContinuationLines()
    {
        $user = new FakeUserSession();
        $res = new ReservationItemView();
        $res->UserId = $user->UserId;
        $res->UserLevelId = ReservationUserLevel::OWNER;
        $res->Title = 'Title';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $this->privacyFilter->_CanViewDetails = true;

        $reservationView = new iCalendarReservationView($res, $user, $this->privacyFilter, '{title}');
        // RFC 5545 §3.1: a line starting with a single space is a folded continuation
        // of the previous line, joined without the leading space.
        $reservationView->ExtraIcalLines = "X-CUSTOM-FIELD:Hello\r\n World";

        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';
        $display = new CalendarExportDisplay();
        $ics = $display->Render([$reservationView]);

        $this->assertStringContainsString('X-CUSTOM-FIELD:HelloWorld', $ics);
    }

    public function testMalformedExtraIcalLinesIsSkippedWithoutBreakingTheExport()
    {
        $user = new FakeUserSession();

        $goodRes = new ReservationItemView();
        $goodRes->UserId = $user->UserId;
        $goodRes->UserLevelId = ReservationUserLevel::OWNER;
        $goodRes->ReferenceNumber = 'good-ref';
        $goodRes->Title = 'Good reservation';
        $goodRes->StartDate = Date::Now();
        $goodRes->EndDate = Date::Now()->AddHours(1);

        $badRes = new ReservationItemView();
        $badRes->UserId = $user->UserId;
        $badRes->UserLevelId = ReservationUserLevel::OWNER;
        $badRes->ReferenceNumber = 'bad-ref';
        $badRes->Title = 'Bad reservation';
        $badRes->StartDate = Date::Now();
        $badRes->EndDate = Date::Now()->AddHours(1);

        $this->privacyFilter->_CanViewDetails = true;

        $goodView = new iCalendarReservationView($goodRes, $user, $this->privacyFilter, '{title}');
        $badView = new iCalendarReservationView($badRes, $user, $this->privacyFilter, '{title}');
        // Not valid iCalendar syntax: no property name/value separator.
        $badView->ExtraIcalLines = 'this is not a valid ical line';

        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';
        $display = new CalendarExportDisplay();
        $ics = $display->Render([$goodView, $badView]);

        // The malformed fragment on one reservation must not prevent the other
        // reservation (or the rest of the malformed one's own properties) from rendering.
        $this->assertStringContainsString('good-ref', $ics);
        $this->assertStringContainsString('bad-ref', $ics);
    }

    /**
     * Both privacy settings through the real PrivacyFilter, for PUBLISH and CANCEL.
     *
     * @return array<string, array{bool, bool, string, IcsMethod}>
     */
    public static function privacyMatrix(): array
    {
        $rows = [];
        foreach ([IcsMethod::PUBLISH, IcsMethod::CANCEL] as $method) {
            foreach ([[false, false], [true, false], [false, true], [true, true]] as [$hideUser, $hideDetails]) {
                $rows[sprintf('stranger user=%d details=%d %s', $hideUser, $hideDetails, $method->value)] = [$hideUser, $hideDetails, 'stranger', $method];
            }
            foreach (['owner', 'admin'] as $viewer) {
                $rows[sprintf('%s user=1 details=1 %s', $viewer, $method->value)] = [true, true, $viewer, $method];
            }
        }

        return $rows;
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('privacyMatrix')]
    public function testPrivacySettingsApplyToEveryMethod(bool $hideUser, bool $hideDetails, string $viewer, IcsMethod $method)
    {
        $this->fakeConfig->SetKey(ConfigKeys::EMAIL_DEFAULT_FROM_ADDRESS, 'bookings@example.com');
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, $hideUser ? 'true' : 'false');
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS, $hideDetails ? 'true' : 'false');
        $this->fakeConfig->_ScriptUrl = 'https://example.com/Web';
        // only the viewer's resource permission lookup returns a row
        ServiceLocator::SetDatabase(new class () extends FakeDatabase {
            public function Query(ISqlCommand $command)
            {
                parent::Query($command);

                return new FakeReader($command instanceof GetUserPermissionsCommand ? [[ColumnNames::RESOURCE_ID => 1]] : []);
            }
        });

        $ownerId = 1;
        $session = match ($viewer) {
            'owner' => new FakeUserSession(false, 'UTC', $ownerId),
            'admin' => new FakeUserSession(true, 'UTC', 3),
            default => new FakeUserSession(false, 'UTC', 2),
        };

        $res = new ReservationItemView();
        $res->ResourceId = 1;
        $res->UserId = $ownerId;
        $res->UserLevelId = ReservationUserLevel::OWNER;
        $res->OwnerId = $ownerId;
        $res->OwnerFirstName = 'f';
        $res->OwnerLastName = 'l';
        $res->OwnerEmailAddress = 'e@m.com';
        $res->Title = 'Secret Title';
        $res->Description = 'Secret Description';
        $res->StartDate = Date::Now();
        $res->EndDate = Date::Now()->AddHours(1);

        $view = new iCalendarReservationView($res, $session, new PrivacyFilter($this->createMock(IReservationAuthorization::class)), '{title}');
        $ics = str_replace("\r\n ", '', (new CalendarExportDisplay())->Render([$view], null, $method));

        $seesUser = !$hideUser || $viewer !== 'stranger';
        $seesDetails = !$hideDetails || $viewer !== 'stranger';

        $this->assertStringContainsString('METHOD:' . $method->value, $ics);
        $this->assertStringContainsString($method === IcsMethod::CANCEL ? 'STATUS:CANCELLED' : 'STATUS:CONFIRMED', $ics);
        $this->assertStringNotContainsString('ATTENDEE', $ics);
        if ($seesUser) {
            $this->assertStringContainsString('ORGANIZER;CN=f l;SENT-BY="mailto:bookings@example.com":mailto:e@m.com', $ics);
        } else {
            $this->assertStringContainsString('ORGANIZER;CN=Private:mailto:bookings@example.com', $ics);
            $this->assertStringNotContainsString('e@m.com', $ics);
        }
        if ($seesDetails) {
            $this->assertStringContainsString('SUMMARY:Secret Title', $ics);
            $this->assertStringContainsString('DESCRIPTION:Secret Description', $ics);
        } else {
            $this->assertStringContainsString('SUMMARY:Private', $ics);
            $this->assertStringContainsString('DESCRIPTION:Private', $ics);
            $this->assertStringNotContainsString('Secret', $ics);
        }
    }
}
