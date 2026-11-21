<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

/**
 * Text saved through web forms is stored HTML-encoded (Server::GetForm), text
 * from the API, imports and SSO is stored raw. Templates must show both
 * correctly: no double encoding, and raw markup still escaped.
 */
class TwigStoredEntityEscapingTest extends TestBase
{
    public function testPageShowsStoredEncodedTextOnceAndEscapesRawMarkup(): void
    {
        $encoded = $this->renderPage(htmlspecialchars('R&D "Lab"'));
        $this->assertStringContainsString('R&amp;D &quot;Lab&quot;', $encoded);
        $this->assertStringNotContainsString('&amp;amp;', $encoded);

        $raw = $this->renderPage('<script>x</script>');
        $this->assertStringContainsString('&lt;script&gt;x&lt;/script&gt;', $raw);
        $this->assertStringNotContainsString('<script>x', $raw);
    }

    public function testEmailShowsStoredEncodedTextOnceAndEscapesRawMarkup(): void
    {
        // FakeResources has no weekday names for the default format
        $this->fakeResources->SetDateFormat('reservation_email', 'Y-m-d H:i');
        $twig = new TwigRenderer();
        foreach ([
            'StartDate' => Date::Now(),
            'EndDate' => Date::Now(),
            'Title' => '<b>title</b>',
            'Description' => "line1\n<i>line2</i>",
            'CreatedBy' => 'Admin',
            'DeleteReason' => htmlspecialchars("It's R&D"),
            'ResourceName' => htmlspecialchars('Room "A"'),
        ] as $k => $v) {
            $twig->assign($k, $v);
        }
        $body = $twig->fetchLocalized('ReservationDeleted.tpl', false, 'en_us');

        $this->assertStringContainsString('Delete Reason: It&#039;s R&amp;D', $body);
        $this->assertStringContainsString('Room &quot;A&quot;', $body);
        $this->assertStringContainsString('&lt;b&gt;title&lt;/b&gt;', $body);
        $this->assertStringContainsString("line1<br />\n&lt;i&gt;line2&lt;/i&gt;", $body);
        $this->assertStringNotContainsString('&amp;#039;', $body);
        $this->assertStringNotContainsString('&amp;amp;', $body);
    }

    private function renderPage(string $resourceName): string
    {
        $twig = new TwigRenderer();
        $twig->assign('ResourceName', $resourceName);
        $twig->assign('QRImageUrl', 'qr.png');

        return $twig->render('Admin/Resources/show_resource_qr.twig');
    }
}
