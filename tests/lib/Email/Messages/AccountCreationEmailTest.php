<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/Messages/AccountCreationEmail.php');

class AccountCreationEmailTest extends TestBase
{
    public function testBodyEscapesProfileDataWithoutDoubleEncoding()
    {
        $user = new FakeUser(1, '"><b>user</b>@example.com');
        $user->ChangeName('<a href="https://evil.example">Verify your account</a>', 'Smith &amp; Sons');
        $user->ChangeAttributes('<img src=x>', 'O&#039;Brien &amp; Co', '<script>alert(1)</script>');

        $creator = new UserSession(2);
        $creator->FirstName = '<i>Admin</i>';
        $creator->LastName = 'User';

        $message = new AccountCreationEmail($user, $creator);
        $body = $message->Body();

        $this->assertStringContainsString('&lt;a href=&quot;https://evil.example&quot;&gt;Verify your account&lt;/a&gt; Smith &amp; Sons', $body);
        $this->assertStringContainsString('&quot;&gt;&lt;b&gt;user&lt;/b&gt;@example.com', $body);
        $this->assertStringContainsString('&lt;img src=x&gt;', $body);
        $this->assertStringContainsString('O&#039;Brien &amp; Co', $body);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $body);
        $this->assertStringContainsString('&lt;i&gt;Admin&lt;/i&gt; User', $body);
        $this->assertStringNotContainsString('<a href="https://evil.example"', $body);
        $this->assertStringNotContainsString('<b>user', $body);
        $this->assertStringNotContainsString('<img src=x', $body);
        $this->assertStringNotContainsString('<script>', $body);
        $this->assertStringNotContainsString('<i>Admin', $body);
        $this->assertStringNotContainsString('&amp;amp;', $body);
        $this->assertStringNotContainsString('&amp;#039;', $body);
    }
}
