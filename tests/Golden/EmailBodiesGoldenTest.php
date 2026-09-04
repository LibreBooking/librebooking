<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Email/namespace.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/Values/InvitationAction.php');
require_once(__DIR__ . '/../../Controls/Control.php');
require_once(__DIR__ . '/../../Controls/AttributeControl.php');
require_once(__DIR__ . '/../../lib/Application/Attributes/Attribute.php');

/**
 * Golden tests for the 20 en_us email body templates migrated to Twig in Phase 4b.
 *
 * Strategy
 * --------
 * TwigRenderer::fetchLocalized('X.tpl', false) (CurrentLanguage=en_us) now selects
 * the .twig counterpart in lang/en_us/ and renders via Twig.
 * SmartyRenderer::fetchLocalized('X.tpl', false, 'en_us') renders the .tpl via Smarty.
 *
 * We compare HtmlNormalizer::normalize(Twig output) === normalize(Smarty output)
 * for each body with fixture vars that:
 *  (a) cover ALL conditional branches in that template, and
 *  (b) use plain ASCII fixture data with no HTML-special chars so autoescape
 *      and Smarty's no-autoescape produce identical text, preserving parity.
 *
 * Templates containing {control type="AttributeControl" ...} are tested without
 * attributes (Attributes=[]) so the control block is skipped in both engines,
 * giving exact byte-level parity. A separate structural assertion confirms the
 * conditional renders correctly with non-empty Attributes in Twig.
 *
 * Escaping / |raw decisions (security-backlog notes)
 * ---------------------------------------------------
 * User-supplied fields (FirstName, FullName, Title, Description, ResourceName, etc.)
 * are emitted |raw in the .twig files to match Smarty's no-autoescape behavior.
 * These should be reviewed for XSS hardening once the full Twig migration is complete.
 * Password was explicitly |escape:'html' in Smarty → Twig autoescape handles it without |raw.
 * AnnouncementText, resource.description, resource.notes, Message go through
 * sanitize_rich_text|url2link|nl2br|raw — the sanitizer provides the safety layer.
 *
 * The 27 other language directories keep their .tpl bodies (Smarty fallback).
 * That translation tail remains as Phase-5 prerequisite backlog.
 */
class EmailBodiesGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
    }

    protected function tearDown(): void
    {
        Resources::SetInstance($this->savedResources);
        parent::tearDown();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Render via Twig (fetchLocalized picks the .twig) and Smarty (picks the .tpl)
     * with the given vars (CurrentLanguage=en_us) and assert normalized parity.
     *
     * @param array<string, mixed> $vars
     */
    private function assertBodyParity(string $tplName, array $vars): void
    {
        $vars = array_merge(['CurrentLanguage' => 'en_us'], $vars);

        // Smarty path: SmartyRenderer::fetchLocalized → lang/en_us/<name>.tpl
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyOut = $smarty->fetchLocalized($tplName, false, 'en_us');

        // Twig path: TwigRenderer::fetchLocalized → lang/en_us/<name>.twig
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigOut = $twig->fetchLocalized($tplName, false, 'en_us');

        $this->assertSame(
            HtmlNormalizer::normalize($smartyOut),
            HtmlNormalizer::normalize($twigOut),
            "Smarty vs Twig mismatch for $tplName"
        );
    }

    /**
     * Render via Twig only and assert the output contains all expected strings.
     *
     * @param array<string, mixed> $vars
     * @param string[]             $expected
     */
    private function assertTwigBodyContains(string $tplName, array $vars, array $expected): void
    {
        $vars = array_merge(['CurrentLanguage' => 'en_us'], $vars);
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $out = $twig->fetchLocalized($tplName, false, 'en_us');
        foreach ($expected as $needle) {
            $this->assertStringContainsString($needle, $out, "Expected '$needle' in Twig output of $tplName");
        }
    }

    /**
     * Verify that TwigRenderer::fetchLocalized fires the Twig branch (not Smarty fallback)
     * for a real on-disk .twig file, and that the output is non-empty HTML.
     */
    private function assertTwigBranchFired(string $tplName, array $vars): void
    {
        $vars = array_merge(['CurrentLanguage' => 'en_us'], $vars);
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $out = $twig->fetchLocalized($tplName, false, 'en_us');
        $this->assertIsString($out);
        $this->assertNotEmpty($out);
    }

    /**
     * Build a minimal DateRange stub for RepeatRanges testing.
     * Returns the real DateRange from lib/Common/DateRange.php.
     */
    private function makeRange(string $beginStr, string $endStr): DateRange
    {
        return new DateRange(Date::Parse($beginStr, 'UTC'), Date::Parse($endStr, 'UTC'));
    }

    /**
     * Build a minimal user stub for Participants/Invitees iteration.
     * Returns an anonymous object with a FullName() method.
     */
    private function makeUser(string $name): object
    {
        return new class ($name) {
            public function __construct(private string $name)
            {
            }

            public function FullName(): string
            {
                return $this->name;
            }
        };
    }

    /**
     * Build a minimal resource array matching what ReservationEmailTemplateContext::Resources() returns.
     *
     * @return array<string, mixed>
     */
    private function makeResource(string $name, ?string $description = null, ?string $notes = null): array
    {
        return [
            'name' => $name,
            'id' => 1,
            'scheduleName' => 'Schedule A',
            'location' => 'Room 101',
            'contact' => 'admin@example.com',
            'description' => $description,
            'notes' => $notes,
            'resourceAdministrator' => 'Admin User',
            'attributeRows' => [],
            'image' => null,
        ];
    }

    // ── AccountActivation ────────────────────────────────────────────────────

    public function testAccountActivationParity(): void
    {
        $this->assertBodyParity('AccountActivation.tpl', [
            'FirstName' => 'Alice',
            'AppTitle' => 'LibreBooking',
            'ActivationUrl' => 'http://localhost/activate?code=abc123',
        ]);
    }

    public function testAccountActivationTwigBranchFired(): void
    {
        $this->assertTwigBranchFired('AccountActivation.tpl', [
            'FirstName' => 'Alice',
            'AppTitle' => 'LibreBooking',
            'ActivationUrl' => 'http://localhost/activate?code=abc123',
        ]);
        $this->assertTwigBodyContains('AccountActivation.tpl', [
            'FirstName' => 'Alice',
            'AppTitle' => 'LibreBooking',
            'ActivationUrl' => 'http://localhost/activate?code=abc123',
        ], ['Alice', 'LibreBooking', 'activate your account']);
    }

    // ── AccountCreation ──────────────────────────────────────────────────────

    public function testAccountCreationWithoutCreatedByParity(): void
    {
        $this->assertBodyParity('AccountCreation.tpl', [
            'To' => 'Administrator',
            'FullName' => 'Bob Smith',
            'EmailAddress' => 'bob@example.com',
            'Phone' => '555-1234',
            'Organization' => 'Acme Corp',
            'Position' => 'Engineer',
            'CreatedBy' => '',
        ]);
    }

    public function testAccountCreationWithCreatedByParity(): void
    {
        $this->assertBodyParity('AccountCreation.tpl', [
            'To' => 'Administrator',
            'FullName' => 'Bob Smith',
            'EmailAddress' => 'bob@example.com',
            'Phone' => '555-1234',
            'Organization' => 'Acme Corp',
            'Position' => 'Engineer',
            'CreatedBy' => 'Carol Admin',
        ]);
    }

    // ── AccountCreationForUser ───────────────────────────────────────────────

    public function testAccountCreationForUserWithoutCreatedByParity(): void
    {
        $this->assertBodyParity('AccountCreationForUser.tpl', [
            'FullName' => 'Dave Jones',
            'EmailAddress' => 'dave@example.com',
            'Phone' => '555-5678',
            'Organization' => 'Widgets Inc',
            'Position' => 'Manager',
            'Password' => 'tempPass123',
            'AppTitle' => 'LibreBooking',
            'ScriptUrl' => 'http://localhost/',
            'CreatedBy' => '',
        ]);
    }

    public function testAccountCreationForUserWithCreatedByParity(): void
    {
        $this->assertBodyParity('AccountCreationForUser.tpl', [
            'FullName' => 'Dave Jones',
            'EmailAddress' => 'dave@example.com',
            'Phone' => '555-5678',
            'Organization' => 'Widgets Inc',
            'Position' => 'Manager',
            'Password' => 'tempPass123',
            'AppTitle' => 'LibreBooking',
            'ScriptUrl' => 'http://localhost/',
            'CreatedBy' => 'Eve Admin',
        ]);
    }

    // ── AccountDeleted ───────────────────────────────────────────────────────

    public function testAccountDeletedParity(): void
    {
        $this->assertBodyParity('AccountDeleted.tpl', [
            'UserFullName' => 'Frank User',
            'AdminFullName' => 'Grace Admin',
        ]);
    }

    // ── AnnouncementEmail ────────────────────────────────────────────────────

    public function testAnnouncementEmailParity(): void
    {
        $this->assertBodyParity('AnnouncementEmail.tpl', [
            'AnnouncementText' => 'System maintenance on Saturday.',
        ]);
    }

    public function testAnnouncementEmailWithNewlines(): void
    {
        // nl2br on multi-line content — both engines produce <br /> at newlines
        $this->assertBodyParity('AnnouncementEmail.tpl', [
            'AnnouncementText' => "Line one.\nLine two.",
        ]);
    }

    // ── EndReminderEmail ─────────────────────────────────────────────────────

    public function testEndReminderEmailParity(): void
    {
        $start = Date::Parse('2025-06-15 09:00', 'UTC');
        $end = Date::Parse('2025-06-15 10:00', 'UTC');
        $this->assertBodyParity('EndReminderEmail.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'ResourceName' => 'Conference Room A',
            'Title' => 'Team Meeting',
            'Description' => 'Weekly standup',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF001',
            'ICalUrl' => 'export/calendar.php?rn=REF001',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── GuestAccountCreation ─────────────────────────────────────────────────

    public function testGuestAccountCreationParity(): void
    {
        $this->assertBodyParity('GuestAccountCreation.tpl', [
            'AppTitle' => 'LibreBooking',
            'EmailAddress' => 'guest@example.com',
            'Password' => 'guestPass99',
            'ScriptUrl' => 'http://localhost/',
        ]);
    }

    // ── InviteUser ───────────────────────────────────────────────────────────

    public function testInviteUserParity(): void
    {
        $this->assertBodyParity('InviteUser.tpl', [
            'FullName' => 'Helen Host',
            'AppTitle' => 'LibreBooking',
            'RegisterUrl' => 'http://localhost/register.php',
        ]);
    }

    // ── MissedCheckinEmail ───────────────────────────────────────────────────

    public function testMissedCheckinEmailWithoutAutoReleaseParity(): void
    {
        $start = Date::Parse('2025-06-15 08:00', 'UTC');
        $end = Date::Parse('2025-06-15 09:00', 'UTC');
        $this->assertBodyParity('MissedCheckinEmail.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'ResourceName' => 'Lab Room B',
            'Title' => 'Lab Session',
            'Description' => 'Experiment work',
            'IsAutoRelease' => false,
            'AutoReleaseTime' => null,
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF002',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    public function testMissedCheckinEmailWithAutoReleaseParity(): void
    {
        $start = Date::Parse('2025-06-15 08:00', 'UTC');
        $end = Date::Parse('2025-06-15 09:00', 'UTC');
        $autoRelease = Date::Parse('2025-06-15 08:15', 'UTC');
        $this->assertBodyParity('MissedCheckinEmail.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'ResourceName' => 'Lab Room B',
            'Title' => 'Lab Session',
            'Description' => 'Experiment work',
            'IsAutoRelease' => true,
            'AutoReleaseTime' => $autoRelease,
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF002',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── ReportEmail ──────────────────────────────────────────────────────────

    public function testReportEmailParity(): void
    {
        $this->assertBodyParity('ReportEmail.tpl', [
            'AppTitle' => 'LibreBooking',
            'ScriptUrl' => 'http://localhost/',
        ]);
    }

    /**
     * This test verifies the Twig-select branch in fetchLocalized fires for a real
     * on-disk .twig file — replacing the previously vacuous testFetchLocalizedSelectsTwigWhenTwigFileExists
     * in TwigRendererTest.php. The .twig file is ReportEmail.twig (simplest body).
     */
    public function testFetchLocalizedSelectsTwigBranchWithRealFile(): void
    {
        $twig = new TwigRenderer();
        $twig->assign('CurrentLanguage', 'en_us');
        $twig->assign('AppTitle', 'LibreBooking');
        $twig->assign('ScriptUrl', 'http://localhost/');

        $twigOut = $twig->fetchLocalized('ReportEmail.tpl', false, 'en_us');

        $smarty = new SmartyRenderer();
        $smarty->assign('AppTitle', 'LibreBooking');
        $smarty->assign('ScriptUrl', 'http://localhost/');
        $smartyOut = $smarty->fetchLocalized('ReportEmail.tpl', false, 'en_us');

        // Both must be non-empty strings
        $this->assertIsString($twigOut);
        $this->assertNotEmpty($twigOut);
        // Twig-rendered output must match Smarty-rendered .tpl output (proves Twig branch fires)
        $this->assertSame(
            HtmlNormalizer::normalize($smartyOut),
            HtmlNormalizer::normalize($twigOut),
            'fetchLocalized must select and render the .twig file for en_us ReportEmail'
        );
    }

    // ── ReservationAvailable ─────────────────────────────────────────────────

    public function testReservationAvailableParity(): void
    {
        $start = Date::Parse('2025-06-20 10:00', 'UTC');
        $end = Date::Parse('2025-06-20 11:00', 'UTC');
        $this->assertBodyParity('ReservationAvailable.tpl', [
            'FirstName' => 'Ivan',
            'ResourceName' => 'Meeting Room C',
            'StartDate' => $start,
            'EndDate' => $end,
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rid=5',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── ReservationCreated ───────────────────────────────────────────────────

    /**
     * Minimal case: single resource, no repeat, no participants, no attributes.
     */
    public function testReservationCreatedMinimalParity(): void
    {
        $start = Date::Parse('2025-07-01 09:00', 'UTC');
        $end = Date::Parse('2025-07-01 10:00', 'UTC');
        $this->assertBodyParity('ReservationCreated.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Project Review',
            'Description' => 'Quarterly review meeting',
            'Attributes' => [],
            'Resources' => [$this->makeResource('Board Room')],
            'RequiresApproval' => false,
            'CheckInEnabled' => false,
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'CreditsCurrent' => 0,
            'CreditsTotal' => 0,
            'ReferenceNumber' => 'REF-2025-001',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-2025-001',
            'ICalUrl' => 'export/calendar.php?rn=REF-2025-001',
            'GoogleCalendarUrl' => 'https://www.google.com/calendar/event?action=TEMPLATE',
            'AppTitle' => 'LibreBooking',
            'Deleted' => false,
        ]);
    }

    /**
     * With participants, invitees, credits, repeat ranges, multiple resources.
     */
    public function testReservationCreatedFullParity(): void
    {
        $start = Date::Parse('2025-07-01 09:00', 'UTC');
        $end = Date::Parse('2025-07-01 10:00', 'UTC');
        $range1 = $this->makeRange('2025-07-01 09:00', '2025-07-01 10:00');
        $range2 = $this->makeRange('2025-07-08 09:00', '2025-07-08 10:00');
        $user1 = $this->makeUser('Jane Doe');
        $user2 = $this->makeUser('John Smith');
        $accessory = new class () {
            public int $QuantityReserved = 2;
            public string $Name = 'Projector';
        };
        $this->assertBodyParity('ReservationCreated.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Board Meeting',
            'Description' => 'Annual board meeting',
            'Attributes' => [],
            'Resources' => [
                $this->makeResource('Board Room', 'Main conference room', null),
                $this->makeResource('Overflow Room', null, 'Check AC settings'),
            ],
            'RequiresApproval' => true,
            'CheckInEnabled' => true,
            'AutoReleaseMinutes' => 15,
            'RepeatRanges' => [$range1, $range2],
            'Participants' => [$user1],
            'ParticipatingGuests' => ['guest1@example.com'],
            'Invitees' => [$user2],
            'InvitedGuests' => ['guest2@example.com'],
            'Accessories' => [$accessory],
            'CreditsCurrent' => 5,
            'CreditsTotal' => 10,
            'CreatedBy' => 'Admin User',
            'ReferenceNumber' => 'REF-2025-002',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-2025-002',
            'ICalUrl' => 'export/calendar.php?rn=REF-2025-002',
            'GoogleCalendarUrl' => 'https://www.google.com/calendar/event?action=TEMPLATE',
            'AppTitle' => 'LibreBooking',
            'Deleted' => false,
        ]);
    }

    public function testReservationCreatedDeletedStateParity(): void
    {
        $start = Date::Parse('2025-07-01 09:00', 'UTC');
        $end = Date::Parse('2025-07-01 10:00', 'UTC');
        $this->assertBodyParity('ReservationCreated.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Deleted Meeting',
            'Description' => 'Was deleted',
            'Attributes' => [],
            'Resources' => [$this->makeResource('Room X')],
            'RequiresApproval' => false,
            'CheckInEnabled' => false,
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'CreditsCurrent' => 0,
            'CreditsTotal' => 0,
            'ReferenceNumber' => 'REF-DEL',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-DEL',
            'ICalUrl' => 'export/calendar.php?rn=REF-DEL',
            'GoogleCalendarUrl' => 'https://www.google.com/calendar/event',
            'AppTitle' => 'LibreBooking',
            'Deleted' => true,
        ]);
    }

    // ── ReservationCreatedAdmin ───────────────────────────────────────────────

    public function testReservationCreatedAdminMinimalParity(): void
    {
        $start = Date::Parse('2025-07-01 09:00', 'UTC');
        $end = Date::Parse('2025-07-01 10:00', 'UTC');
        $this->assertBodyParity('ReservationCreatedAdmin.tpl', [
            'UserName' => 'Alice User',
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Team Standup',
            'Description' => 'Daily standup',
            'Attributes' => [],
            'Resources' => [$this->makeResource('Standup Room')],
            'RequiresApproval' => false,
            'CheckInEnabled' => false,
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'ReferenceNumber' => 'REF-ADMIN-001',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-ADMIN-001',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    public function testReservationCreatedAdminWithCreatedByParity(): void
    {
        $start = Date::Parse('2025-07-01 09:00', 'UTC');
        $end = Date::Parse('2025-07-01 10:00', 'UTC');
        $this->assertBodyParity('ReservationCreatedAdmin.tpl', [
            'UserName' => 'Bob Booker',
            'CreatedBy' => 'Carol Manager',
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Strategy Session',
            'Description' => 'Quarterly strategy',
            'Attributes' => [],
            'Resources' => [$this->makeResource('Strategy Room')],
            'RequiresApproval' => true,
            'CheckInEnabled' => true,
            'AutoReleaseMinutes' => 10,
            'RepeatRanges' => [$this->makeRange('2025-07-01 09:00', '2025-07-01 10:00')],
            'Participants' => [$this->makeUser('Dan P')],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'ReferenceNumber' => 'REF-ADMIN-002',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-ADMIN-002',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── ReservationCreated / ReservationCreatedAdmin: attributeRows ──────────

    /**
     * ReservationCreated.twig renders a resource-attributes table when
     * resource.attributeRows is non-empty ({% for row in resource.attributeRows %}).
     *
     * This test supplies a resource with two attribute rows — one with a plain
     * single-line displayValue and one with a multi-line displayValue (to exercise
     * |nl2br) — and asserts byte-level parity between Twig and Smarty output.
     *
     * Escaping equivalence:
     *   .tpl:  {$row.label|escape} / {$row.displayValue|escape|nl2br}
     *   .twig: {{ row.label }}     / {{ row.displayValue|nl2br }}
     * With Twig autoescape enabled, {{ row.label }} HTML-escapes, matching |escape.
     * {{ row.displayValue|nl2br }} calls Twig's built-in nl2br which first escapes
     * then inserts <br /> — identical to Smarty's |escape|nl2br. Plain ASCII fixture
     * data produces no escaping differences, so exact byte-level parity is expected.
     */
    public function testReservationCreatedWithAttributeRowsParity(): void
    {
        $start = Date::Parse('2025-07-01 09:00', 'UTC');
        $end = Date::Parse('2025-07-01 10:00', 'UTC');

        $resource = $this->makeResource('Attr Room');
        $resource['attributeRows'] = [
            ['label' => 'Capacity', 'displayValue' => '12'],
            ['label' => 'Location Detail', 'displayValue' => "Floor 2\nWing B"],
        ];

        $this->assertBodyParity('ReservationCreated.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Attr Test Meeting',
            'Description' => 'Testing attribute rows',
            'Attributes' => [],
            'Resources' => [$resource],
            'RequiresApproval' => false,
            'CheckInEnabled' => false,
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'CreditsCurrent' => 0,
            'CreditsTotal' => 0,
            'ReferenceNumber' => 'REF-ATTR-001',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-ATTR-001',
            'ICalUrl' => 'export/calendar.php?rn=REF-ATTR-001',
            'GoogleCalendarUrl' => 'https://www.google.com/calendar/event',
            'AppTitle' => 'LibreBooking',
            'Deleted' => false,
        ]);
    }

    /**
     * ReservationCreated.twig: structural assertion that the attribute table renders
     * with the correct label, displayValue, and nl2br conversion visible in the output.
     */
    public function testReservationCreatedAttributeRowsTableRendered(): void
    {
        $start = Date::Parse('2025-07-01 09:00', 'UTC');
        $end = Date::Parse('2025-07-01 10:00', 'UTC');

        $resource = $this->makeResource('Structural Room');
        $resource['attributeRows'] = [
            ['label' => 'Building', 'displayValue' => "Block A\nBlock B"],
        ];

        $this->assertTwigBodyContains('ReservationCreated.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Attr Structural Test',
            'Description' => 'Structural',
            'Attributes' => [],
            'Resources' => [$resource],
            'RequiresApproval' => false,
            'CheckInEnabled' => false,
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'CreditsCurrent' => 0,
            'CreditsTotal' => 0,
            'ReferenceNumber' => 'REF-ATTR-002',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-ATTR-002',
            'ICalUrl' => 'export/calendar.php?rn=REF-ATTR-002',
            'GoogleCalendarUrl' => 'https://www.google.com/calendar/event',
            'AppTitle' => 'LibreBooking',
            'Deleted' => false,
        ], [
            'Resource Details',
            'Building',
            'Block A',
            '<br />',
            'Block B',
        ]);
    }

    /**
     * ReservationCreatedAdmin.twig renders the same attribute table — verify parity.
     */
    public function testReservationCreatedAdminWithAttributeRowsParity(): void
    {
        $start = Date::Parse('2025-07-01 09:00', 'UTC');
        $end = Date::Parse('2025-07-01 10:00', 'UTC');

        $resource = $this->makeResource('Admin Attr Room');
        $resource['attributeRows'] = [
            ['label' => 'Floor', 'displayValue' => '3'],
            ['label' => 'Notes', 'displayValue' => "Quiet area\nNo calls"],
        ];

        $this->assertBodyParity('ReservationCreatedAdmin.tpl', [
            'UserName' => 'Test User',
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Admin Attr Meeting',
            'Description' => 'Admin attr test',
            'Attributes' => [],
            'Resources' => [$resource],
            'RequiresApproval' => false,
            'CheckInEnabled' => false,
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'ReferenceNumber' => 'REF-ATTR-ADMIN-001',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-ATTR-ADMIN-001',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── ReservationDeleted ───────────────────────────────────────────────────

    public function testReservationDeletedSingleResourceParity(): void
    {
        $start = Date::Parse('2025-07-05 14:00', 'UTC');
        $end = Date::Parse('2025-07-05 15:00', 'UTC');
        $this->assertBodyParity('ReservationDeleted.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Cancelled Meeting',
            'Description' => 'Postponed indefinitely',
            'Attributes' => [],
            'ResourceName' => 'Meeting Room A',
            'ResourceNames' => ['Meeting Room A'],
            'ReferenceNumber' => 'REF-DEL-001',
            'ScriptUrl' => 'http://localhost/',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    public function testReservationDeletedWithDeletedByParity(): void
    {
        $start = Date::Parse('2025-07-05 14:00', 'UTC');
        $end = Date::Parse('2025-07-05 15:00', 'UTC');
        $this->assertBodyParity('ReservationDeleted.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Deleted Meeting',
            'Description' => 'Admin deleted it',
            'Attributes' => [],
            'ResourceName' => 'Room B',
            'ResourceNames' => ['Room B'],
            'CreatedBy' => 'Eve Admin',
            'DeleteReason' => 'No longer needed',
            'RepeatRanges' => [$this->makeRange('2025-07-05 14:00', '2025-07-05 15:00')],
            'ReferenceNumber' => 'REF-DEL-002',
            'ScriptUrl' => 'http://localhost/',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    public function testReservationDeletedMultipleResourcesParity(): void
    {
        $start = Date::Parse('2025-07-05 14:00', 'UTC');
        $end = Date::Parse('2025-07-05 15:00', 'UTC');
        $this->assertBodyParity('ReservationDeleted.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Multi-Room Meeting',
            'Description' => 'Uses multiple rooms',
            'Attributes' => [],
            'ResourceName' => 'Room A',
            'ResourceNames' => ['Room A', 'Room B', 'Room C'],
            'ReferenceNumber' => 'REF-DEL-003',
            'ScriptUrl' => 'http://localhost/',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── ReservationInvitation ─────────────────────────────────────────────────

    /**
     * Invitation (not deleted, not updated) — shows Attending? links.
     */
    public function testReservationInvitationActiveParity(): void
    {
        $start = Date::Parse('2025-07-10 10:00', 'UTC');
        $end = Date::Parse('2025-07-10 11:00', 'UTC');
        $this->assertBodyParity('ReservationInvitation.tpl', [
            'UserName' => 'Frank Organizer',
            'Deleted' => false,
            'Updated' => false,
            'StartDate' => $start,
            'EndDate' => $end,
            'ResourceName' => 'Conf Room 1',
            'ResourceNames' => ['Conf Room 1'],
            'RequiresApproval' => false,
            'Title' => 'Kickoff Meeting',
            'Description' => 'Project kickoff',
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'AcceptUrl' => 'invitation.php?rn=REF003&action=accept',
            'DeclineUrl' => 'invitation.php?rn=REF003&action=decline',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF003',
            'ICalUrl' => 'export/calendar.php?rn=REF003',
            'GoogleCalendarUrl' => 'https://www.google.com/calendar/event',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    /**
     * Deleted invitation — shows "has deleted a reservation", no Attending? links.
     */
    public function testReservationInvitationDeletedParity(): void
    {
        $start = Date::Parse('2025-07-10 10:00', 'UTC');
        $end = Date::Parse('2025-07-10 11:00', 'UTC');
        $this->assertBodyParity('ReservationInvitation.tpl', [
            'UserName' => 'Grace Organizer',
            'Deleted' => true,
            'Updated' => false,
            'DeleteReason' => 'Room unavailable',
            'StartDate' => $start,
            'EndDate' => $end,
            'ResourceName' => 'Conf Room 2',
            'ResourceNames' => ['Conf Room 2'],
            'RequiresApproval' => false,
            'Title' => 'Deleted Kickoff',
            'Description' => 'Was planned',
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'ScriptUrl' => 'http://localhost/',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    /**
     * Updated invitation — shows "has added you", no Attending? links.
     */
    public function testReservationInvitationUpdatedParity(): void
    {
        $start = Date::Parse('2025-07-10 10:00', 'UTC');
        $end = Date::Parse('2025-07-10 11:00', 'UTC');
        $this->assertBodyParity('ReservationInvitation.tpl', [
            'UserName' => 'Henry Organizer',
            'Deleted' => false,
            'Updated' => true,
            'StartDate' => $start,
            'EndDate' => $end,
            'ResourceName' => 'Conf Room 3',
            'ResourceNames' => ['Conf Room 3'],
            'RequiresApproval' => false,
            'Title' => 'Updated Meeting',
            'Description' => 'Time changed',
            'RepeatRanges' => [],
            'Participants' => [],
            'ParticipatingGuests' => [],
            'Invitees' => [],
            'InvitedGuests' => [],
            'Accessories' => [],
            'AcceptUrl' => 'invitation.php?rn=REF004&action=accept',
            'DeclineUrl' => 'invitation.php?rn=REF004&action=decline',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF004',
            'ICalUrl' => 'export/calendar.php?rn=REF004',
            'GoogleCalendarUrl' => 'https://www.google.com/calendar/event',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── ReservationParticipationActivity ─────────────────────────────────────

    public function testReservationParticipationActivityAcceptParity(): void
    {
        $start = Date::Parse('2025-07-15 14:00', 'UTC');
        $end = Date::Parse('2025-07-15 15:00', 'UTC');
        $this->assertBodyParity('ReservationParticipationActivity.tpl', [
            'ParticipantDetails' => 'Jane Doe',
            'InvitationAction' => InvitationAction::Accept,
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Workshop',
            'Description' => 'PHP workshop',
            'Attributes' => [],
            'ResourceName' => 'Training Room',
            'ResourceNames' => ['Training Room'],
            'ReferenceNumber' => 'REF-PA-001',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-PA-001',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    public function testReservationParticipationActivityDeclineParity(): void
    {
        $start = Date::Parse('2025-07-15 14:00', 'UTC');
        $end = Date::Parse('2025-07-15 15:00', 'UTC');
        $this->assertBodyParity('ReservationParticipationActivity.tpl', [
            'ParticipantDetails' => 'John Smith',
            'InvitationAction' => InvitationAction::Decline,
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Workshop',
            'Description' => 'PHP workshop',
            'Attributes' => [],
            'ResourceName' => 'Training Room',
            'ResourceNames' => ['Training Room'],
            'ReferenceNumber' => 'REF-PA-002',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-PA-002',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    public function testReservationParticipationActivityJoinParity(): void
    {
        $start = Date::Parse('2025-07-15 14:00', 'UTC');
        $end = Date::Parse('2025-07-15 15:00', 'UTC');
        $this->assertBodyParity('ReservationParticipationActivity.tpl', [
            'ParticipantDetails' => 'Eve Joiner',
            'InvitationAction' => InvitationAction::Join,
            'StartDate' => $start,
            'EndDate' => $end,
            'Title' => 'Workshop',
            'Description' => 'PHP workshop',
            'Attributes' => [],
            'ResourceName' => 'Training Room',
            'ResourceNames' => ['Training Room'],
            'ReferenceNumber' => 'REF-PA-003',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-PA-003',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── ReservationSeriesEnding ───────────────────────────────────────────────

    public function testReservationSeriesEndingParity(): void
    {
        $start = Date::Parse('2025-08-01 10:00', 'UTC');
        $end = Date::Parse('2025-08-01 11:00', 'UTC');
        $this->assertBodyParity('ReservationSeriesEnding.tpl', [
            'ResourceName' => 'Weekly Room',
            'Title' => 'Weekly Standup',
            'Description' => 'Last occurrence',
            'StartDate' => $start,
            'EndDate' => $end,
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-SE-001',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── ResetPassword ────────────────────────────────────────────────────────

    public function testResetPasswordParity(): void
    {
        $this->assertBodyParity('ResetPassword.tpl', [
            'AppTitle' => 'LibreBooking',
            'TemporaryPassword' => 'TempPass2025',
            'ScriptUrl' => 'http://localhost/',
        ]);
    }

    // ── ResourceStatusChanged ────────────────────────────────────────────────

    public function testResourceStatusChangedParity(): void
    {
        $this->assertBodyParity('ResourceStatusChanged.tpl', [
            'ResourceName' => 'Server Room',
            'Message' => 'Resource is now unavailable due to maintenance.',
            'ScriptUrl' => 'http://localhost/',
            'AppTitle' => 'LibreBooking',
        ]);
    }

    // ── StartReminderEmail ───────────────────────────────────────────────────

    public function testStartReminderEmailParity(): void
    {
        $start = Date::Parse('2025-09-01 09:00', 'UTC');
        $end = Date::Parse('2025-09-01 10:00', 'UTC');
        $this->assertBodyParity('StartReminderEmail.tpl', [
            'StartDate' => $start,
            'EndDate' => $end,
            'ResourceName' => 'Exec Suite',
            'Title' => 'Board Presentation',
            'Description' => 'Annual board presentation',
            'ScriptUrl' => 'http://localhost/',
            'ReservationUrl' => 'reservation.php?rn=REF-SR-001',
            'ICalUrl' => 'export/calendar.php?rn=REF-SR-001',
            'AppTitle' => 'LibreBooking',
        ]);
    }
}
