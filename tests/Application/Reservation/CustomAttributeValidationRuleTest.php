<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class CustomAttributeValidationRuleTest extends TestBase
{
    /**
     * @var IAttributeService|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeService;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userRepository;

    /**
     * @var CustomAttributeValidationRule
     */
    private $rule;

    /**
     * @var TestReservationSeries
     */
    private $reservation;

    /**
     * @var FakeUser
     */
    private $user;

    /**
     * @var FakeUser
     */
    private $bookedBy;

    public function setUp(): void
    {
        parent::setup();

        $this->attributeService = $this->createMock('IAttributeService');
        $this->userRepository = $this->createMock('IUserRepository');

        $this->reservation = new TestReservationSeries();
        $this->user = new FakeUser(1);
        $this->bookedBy = new FakeUser(2);
        $this->reservation->WithOwnerId($this->user->Id());
        $this->reservation->WithBookedBy(new FakeUserSession(false, 'America/New_York', $this->bookedBy->Id()));

        $this->bookedBy->_IsResourceAdmin = false;
        $this->bookedBy->_SetIsAdminForUser(false);

        $this->userRepository->expects($this->exactly(2))
                             ->method('LoadById')
                             ->willReturnMap([
                                [$this->reservation->UserId(), $this->user],
                                [$this->reservation->BookedBy()->UserId, $this->bookedBy]
                             ]);

        $this->rule = new CustomAttributeValidationRule($this->attributeService, $this->userRepository);
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testChecksEachAttributeInCategory()
    {
        $errors = ['error1', 'error2'];

        $validationResult = new AttributeServiceValidationResult(false, $errors, [
                new InvalidAttribute(new FakeCustomAttribute(), 'error1'),
                new InvalidAttribute(new FakeCustomAttribute(), 'error2')]);

        $this->attributeService->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo(CustomAttributeCategory::RESERVATION), $this->equalTo($this->reservation->AttributeValues()), $this->equalTo([]), $this->isFalse(), $this->isFalse())
                ->willReturn($validationResult);

        $userAttribute = new FakeCustomAttribute();
        $userAttribute->WithSecondaryEntities(CustomAttributeCategory::USER, 123);

        $result = $this->rule->Validate($this->reservation, null);

        $this->assertEquals(false, $result->IsValid());
        $this->assertStringContainsString('error1', $result->ErrorMessage());
        $this->assertStringContainsString('error2', $result->ErrorMessage());
        $this->assertStringNotContainsString('error3', $result->ErrorMessage(), "don't include the 3rd error because it's for an attribute that doesn't apply");
    }

    public function testWhenAllAttributesAreValid()
    {
        $validationResult = new AttributeServiceValidationResult(true, []);

        $this->attributeService->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo(CustomAttributeCategory::RESERVATION), $this->equalTo($this->reservation->AttributeValues()), $this->equalTo([]), $this->isFalse(), $this->isFalse())
                ->willReturn($validationResult);

        $result = $this->rule->Validate($this->reservation, null);

        $this->assertEquals(true, $result->IsValid());
    }

    public function testWhenUserIsAnAdmin_ThenEvaluateAdminOnlyAttributes()
    {
        $this->bookedBy->_IsAdminForUser = true;
        $validationResult = new AttributeServiceValidationResult(true, []);

        $this->attributeService->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo(CustomAttributeCategory::RESERVATION), $this->equalTo($this->reservation->AttributeValues()), $this->equalTo([]), $this->isFalse(), $this->isTrue())
                ->willReturn($validationResult);

        $result = $this->rule->Validate($this->reservation, null);

        $this->assertEquals(true, $result->IsValid());
    }

    public function testWhenTheInvalidAttributeIsForASecondaryUser_AndTheReservationUserIsNotThatUser()
    {
        $this->reservation->WithAttributeValue(new AttributeValue(1, null));

        $attributeService = $this->createMock('IAttributeService');

        $userAttribute = new FakeCustomAttribute();
        $userAttribute->WithSecondaryEntities(CustomAttributeCategory::USER, 123);

        $validationResult = new AttributeServiceValidationResult(false, ['error'], [new InvalidAttribute($userAttribute, 'another message')]);

        $attributeService->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo(CustomAttributeCategory::RESERVATION), $this->equalTo($this->reservation->AttributeValues()))
                ->willReturn($validationResult);

        $rule = new CustomAttributeValidationRule($attributeService, $this->userRepository);

        $result = $rule->Validate($this->reservation, null);

        $this->assertEquals(true, $result->IsValid());
        $this->assertEmpty($result->ErrorMessage());
    }

    public function testWhenTheInvalidAttributeIsForASecondaryResource_AndTheReservationDoesNotHaveThatResource()
    {
        $this->reservation->WithResource(new FakeBookableResource(1));
        $this->reservation->WithAttributeValue(new AttributeValue(1, null));

        $attributeService = $this->createMock('IAttributeService');

        $userAttribute = new FakeCustomAttribute();
        $userAttribute->WithSecondaryEntities(CustomAttributeCategory::RESOURCE, 2);

        $validationResult = new AttributeServiceValidationResult(false, ['error'], [new InvalidAttribute($userAttribute, 'another message')]);

        $attributeService->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo(CustomAttributeCategory::RESERVATION), $this->equalTo($this->reservation->AttributeValues()))
                ->willReturn($validationResult);

        $rule = new CustomAttributeValidationRule($attributeService, $this->userRepository);

        $result = $rule->Validate($this->reservation, null);

        $this->assertEquals(true, $result->IsValid());
        $this->assertEmpty($result->ErrorMessage());
    }

    public function testWhenTheInvalidAttributeIsForASecondaryResourceType_AndTheReservationDoesNotHaveAResourceWithThatResourceType()
    {
        $resource = new FakeBookableResource(1);
        $resource->SetResourceTypeId(1);
        $this->reservation->WithResource($resource);
        $this->reservation->WithAttributeValue(new AttributeValue(1, null));

        $attributeService = $this->createMock('IAttributeService');

        $userAttribute = new FakeCustomAttribute();
        $userAttribute->WithSecondaryEntities(CustomAttributeCategory::RESOURCE_TYPE, 2);

        $validationResult = new AttributeServiceValidationResult(false, ['error'], [new InvalidAttribute($userAttribute, 'another message')]);

        $attributeService->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo(CustomAttributeCategory::RESERVATION), $this->equalTo($this->reservation->AttributeValues()))
                ->willReturn($validationResult);

        $rule = new CustomAttributeValidationRule($attributeService, $this->userRepository);

        $result = $rule->Validate($this->reservation, null);

        $this->assertEquals(true, $result->IsValid());
        $this->assertEmpty($result->ErrorMessage());
    }
}
