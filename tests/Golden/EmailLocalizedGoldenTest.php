<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../lib/Email/namespace.php');
require_once(__DIR__ . '/../../Domain/namespace.php');
require_once(__DIR__ . '/../../Domain/Values/InvitationAction.php');

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Smarty-vs-Twig parity for EVERY localized email body: each lang/<code>/<Name>.tpl
 * that has a <Name>.twig next to it, rendered with several fixture variants
 * (fully populated, empty, and each invitation action).
 *
 * Deliberate difference (the only one): Twig HTML-escapes user-typed text that
 * Smarty prints raw. The escaping allowlist is applied on the Smarty side, so any
 * other difference fails the test:
 *   - Description, DeleteReason, Message where the template uses |nl2br
 * Participant and invitee names are escaped before rendering, as in production.
 *
 * The fixtures contain <, & and " so that escaping is actually exercised.
 */
class EmailLocalizedGoldenTest extends GoldenTemplateTestCase
{
    /** Templates Smarty cannot compile, with the reason. Twig must still render them. */
    private const SMARTY_CANNOT_COMPILE = [
        'th_th/StartReminderEmail.tpl' => 'Thai variable name {$คำอธิบาย} is not a valid Smarty variable',
    ];

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

    /**
     * @return array<string, array{string, string}>
     */
    public static function localizedTemplates(): array
    {
        $cases = [];
        $root = dirname(__DIR__, 2) . '/lang/';
        foreach (glob($root . '*', GLOB_ONLYDIR) as $dir) {
            foreach (glob($dir . '/*.tpl') as $tpl) {
                $name = basename($tpl);
                if (str_ends_with($name, '-custom.tpl') || !file_exists(substr($tpl, 0, -4) . '.twig')) {
                    continue;
                }
                $cases[basename($dir) . '/' . $name] = [basename($dir), $name];
            }
        }

        return $cases;
    }

    #[DataProvider('localizedTemplates')]
    public function testSmartyAndTwigRenderTheSameEmail(string $lang, string $name): void
    {
        $this->assertNotEmpty($name);
        foreach (self::variants() as $variantName => $vars) {
            $vars = self::preEscapedNames($vars);
            $twig = new TwigRenderer();
            foreach ($vars as $k => $v) {
                $twig->assign($k, $v);
            }
            $twigOut = $twig->fetchLocalized($name, false, $lang);
            $this->assertStringStartsNotWith("\xEF\xBB\xBF", $twigOut, "$lang/$name: BOM in Twig output");

            if (isset(self::SMARTY_CANNOT_COMPILE["$lang/$name"])) {
                $this->assertNotSame('', trim($twigOut), "$lang/$name ($variantName): Twig must still render");
                continue;
            }

            $smarty = new SmartyRenderer();
            foreach ($vars as $k => $v) {
                $smarty->assign($k, $v);
            }
            $adjusted = self::smartySourceWithEscapingAllowlist("lang/$lang/$name");
            try {
                $smartyOut = $smarty->fetch('file:' . $adjusted);
            } finally {
                unlink($adjusted);
            }

            $this->assertSame(
                HtmlNormalizer::normalize($smartyOut),
                HtmlNormalizer::normalize($twigOut),
                "$lang/$name ($variantName): Smarty vs Twig mismatch"
            );
        }
    }

    public function testSmartyCannotCompileExceptionsAreStillNeeded(): void
    {
        foreach (self::SMARTY_CANNOT_COMPILE as $path => $reason) {
            $smarty = new SmartyRenderer();
            try {
                $smarty->fetch('file:' . ROOT_DIR . 'lang/' . $path);
                $this->fail("$path now compiles in Smarty; remove it from SMARTY_CANNOT_COMPILE ($reason)");
            } catch (\Smarty\CompilerException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    /**
     * The escaping allowlist: user text that Smarty printed raw is escaped by Twig
     * (|escape_once), so Smarty renders it with |escape:
     *   - {$Description|nl2br}, {$DeleteReason|nl2br}, {$Message|nl2br}
     *   - {$Title}, {$Description}, {$ResourceName}, {$resourceName}, {$accessory->Name}, {$LastName}
     * Only those occurrences change; the same variable used elsewhere (for example
     * |escape:'url' in calendar links) is left as is.
     * Returns the path of a temporary copy of the template.
     */
    private static function smartySourceWithEscapingAllowlist(string $relativePath): string
    {
        $source = file_get_contents(ROOT_DIR . $relativePath);
        $source = preg_replace('/\{\$(Description|DeleteReason|Message)\|nl2br\}/', '{$\1|escape|nl2br}', $source);
        $source = preg_replace('/\{\$(Title|Description|ResourceName|resourceName|accessory->Name|LastName)\}/', '{$\1|escape}', $source);
        $copy = sys_get_temp_dir() . '/golden-email-' . md5($relativePath) . '.tpl';
        file_put_contents($copy, $source);

        return $copy;
    }

    /**
     * Participant and invitee names reach the templates already escaped
     * (ReservationEmailMessage::EscapedUser), and both engines print them raw.
     *
     * @param array<string, mixed> $vars
     * @return array<string, mixed>
     */
    private static function preEscapedNames(array $vars): array
    {
        foreach (['Participants', 'Invitees'] as $key) {
            $vars[$key] = array_map(
                static fn (EmailFixtureUser $u) => new EmailFixtureUser(htmlspecialchars($u->FullName(), ENT_QUOTES)),
                $vars[$key] ?? []
            );
        }

        return $vars;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function variants(): array
    {
        $d1 = Date::Parse('2025-03-04 10:00', 'UTC');
        $d2 = Date::Parse('2025-03-04 11:30', 'UTC');
        $d3 = Date::Parse('2025-03-06 09:00', 'UTC');
        $resources = [
            ['name' => 'Room <A>', 'scheduleName' => 'Sched & Co', 'id' => 7, 'location' => 'Loc "1"', 'contact' => 'c@x.test',
                'description' => "Desc <b>b</b> http://x.test\nline2", 'notes' => 'Notes <script>x</script>', 'resourceAdministrator' => 'Adm <A>',
                'attributeRows' => [['label' => 'L<1>', 'displayValue' => "V&1\nV2"]], 'image' => 'img/a b.png'],
            ['name' => 'Room B', 'scheduleName' => '', 'id' => 8, 'location' => '', 'contact' => '', 'description' => '', 'notes' => '',
                'resourceAdministrator' => '', 'attributeRows' => [], 'image' => ''],
        ];
        $full = [
            'ScriptUrl' => 'http://lb.test/Web', 'AppTitle' => 'Libre <B> & Co', 'ResourceName' => 'Room <A>', 'CreatedBy' => 'Cr <E>',
            'ApprovedBy' => 'Ap <P>', 'Title' => 'Title <t> & "q"', 'ReservationUrl' => 'reservation.php?rn=1&x=2',
            'Description' => "Line1 <b>\nLine2 & more", 'ICalUrl' => 'ical.php?rn=1', 'GoogleCalendarUrl' => 'http://g.test/?a=1&b=2',
            'ReferenceNumber' => 'ref123', 'FullName' => 'Full <N>', 'AutoReleaseMinutes' => 15, 'UserName' => 'user<1>',
            'EmailAddress' => 'u@x.test', 'DeleteReason' => "Reason <r>\nline2", 'CreditsCurrent' => 5, 'CreditsTotal' => 10,
            'Position' => 'Pos <1>', 'Phone' => '123 & 4', 'Password' => 'pw<&>', 'Organization' => 'Org <&>', 'FirstName' => 'First <N>',
            'UserFullName' => 'UF <U>', 'AdminFullName' => 'AF <A>', 'Updated' => 'Upd <1>', 'To' => 'to<1>', 'TemporaryPassword' => 'tmp<1>&',
            'RegisterUrl' => 'http://lb.test/register.php?a=1&b=2', 'ParticipantDetails' => 'PD <1>', 'Message' => "Msg <m>\nline2",
            'IsAutoRelease' => true, 'DeclineUrl' => 'http://lb.test/d?a=1&b=2', 'AutoReleaseTime' => '10:15', 'AnnouncementText' => 'Ann <b>b</b>',
            'ActivationUrl' => 'http://lb.test/act?a=1&b=2', 'AcceptUrl' => 'http://lb.test/acc?a=1&b=2', 'ResourceImage' => 'img/a b.png',
            'InvitationAction' => InvitationAction::Join, 'Deleted' => false, 'RequiresApproval' => true, 'CheckInEnabled' => true,
            'StartDate' => $d1, 'EndDate' => $d2, 'RepeatRanges' => [new DateRange($d1, $d2), new DateRange($d1, $d3)],
            'Participants' => [new EmailFixtureUser('P1 <a> & Co'), new EmailFixtureUser('P2')], 'ParticipatingGuests' => ['g1@x.test', 'g<2>@x.test'],
            'Invitees' => [new EmailFixtureUser('I1 <i>')], 'InvitedGuests' => ['ig@x.test'],
            'Accessories' => [new EmailFixtureAccessory(), new EmailFixtureAccessory()], 'Attributes' => [],
            'Resources' => $resources, 'ResourceNames' => ['Room <A>', 'Room B'],
        ];
        $variants = [
            'full' => $full,
            'empty' => array_merge($full, [
                'Deleted' => true, 'RequiresApproval' => false, 'CheckInEnabled' => false, 'AutoReleaseMinutes' => null,
                'CreditsCurrent' => 0, 'CreditsTotal' => 0, 'CreatedBy' => '', 'ApprovedBy' => '', 'RepeatRanges' => [],
                'Participants' => [], 'ParticipatingGuests' => [], 'Invitees' => [], 'InvitedGuests' => [], 'Accessories' => [],
                'Resources' => [$resources[1]], 'ResourceNames' => ['Only'], 'IsAutoRelease' => false, 'EndDate' => $d1,
            ]),
        ];
        foreach (['Decline', 'CancelAll', 'CancelInstance', 'JoinAll'] as $action) {
            $variants["invitation $action"] = array_merge($full, ['InvitationAction' => constant("InvitationAction::$action")]);
        }

        return $variants;
    }
}

class EmailFixtureUser
{
    public function __construct(private string $name)
    {
    }

    public function FullName(): string
    {
        return $this->name;
    }

    public function EmailAddress(): string
    {
        return 'm@x.test';
    }

    public function Timezone(): string
    {
        return 'UTC';
    }
}

class EmailFixtureAccessory
{
    public int $QuantityReserved = 2;
    public string $Name = 'Beamer <&>';
}
