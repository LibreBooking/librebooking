<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationParticipationActivityEmail.php');

class ReservationParticipationActivityEmailTest extends TestBase
{
    public function testBodyEscapesParticipantAndOwnerButSubjectKeepsPlainText()
    {
        $this->fakeResources->SetDateFormat('reservation_email', 'Y-m-d H:i');

        $owner = new FakeUser(1, 'owner@example.com');
        $owner->ChangeName('<b>Owner</b>', 'Smith &amp; Sons');

        $resource = new FakeBookableResource(1, 'Room');
        $resource->SetScheduleId(1);

        $series = (new ExistingReservationSeriesBuilder())
            ->WithPrimaryResource($resource)
            ->WithCurrentInstance(new TestReservation())
            ->Build();

        $participantDetails = '<a href="https://evil.example">Verify</a> O\'Brien';
        $message = new ReservationParticipationActivityEmail($series, InvitationAction::Accept, $owner, $participantDetails);
        $body = $message->Body();

        $this->assertStringContainsString('&lt;a href=&quot;https://evil.example&quot;&gt;Verify&lt;/a&gt; O&#039;Brien', $body);
        $this->assertStringNotContainsString('<a href="https://evil.example"', $body);
        $this->assertStringContainsString($participantDetails, $message->Subject());
    }
}
