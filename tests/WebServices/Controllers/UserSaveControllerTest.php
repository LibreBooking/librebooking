<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Controllers/UserSaveController.php');

class UserSaveControllerTest extends TestBase
{
    /**
     * @var UserSaveController
     */
    private $controller;

    private IManageUsersServiceFactory&\PHPUnit\Framework\MockObject\MockObject $manageUserServiceFactory;

    private IManageUsersService&\PHPUnit\Framework\MockObject\MockObject $manageUsersService;

    private IUserRequestValidator&\PHPUnit\Framework\MockObject\MockObject $requestValidator;

    public function setUp(): void
    {
        parent::setup();

        $this->manageUserServiceFactory = $this->createMock('IManageUsersServiceFactory');
        $this->manageUsersService = $this->createMock('IManageUsersService');
        $this->requestValidator = $this->createMock('IUserRequestValidator');

        $this->controller = new UserSaveController($this->manageUserServiceFactory, $this->requestValidator);
    }

    public function testCreatesNewUser()
    {
        $createdUserId = 123;
        $createdUser = new FakeUser($createdUserId);

        $request = CreateUserRequest::Example();
        $session = new FakeWebServiceUserSession(123);

        $this->requestValidator->expects($this->once())
                               ->method('ValidateCreateRequest')
                               ->with($this->equalTo($request))
                               ->willReturn(null);

        $this->manageUserServiceFactory->expects($this->once())
                                       ->method('CreateAdmin')
                                       ->willReturn($this->manageUsersService);

        $this->manageUsersService->expects($this->once())
                                 ->method('AddUser')
                                 ->with(
                                     $this->equalTo($request->userName),
                                     $this->equalTo($request->emailAddress),
                                     $this->equalTo($request->firstName),
                                     $this->equalTo($request->lastName),
                                     $this->equalTo($request->password),
                                     $this->equalTo($request->timezone),
                                     $this->equalTo($request->language),
                                     $this->equalTo(Pages::DEFAULT_HOMEPAGE_ID),
                                     $this->equalTo([UserAttribute::Phone => $request->phone, UserAttribute::Organization => $request->organization, UserAttribute::Position => $request->position]),
                                     $this->equalTo([new AttributeValue(
                                         $request->customAttributes[0]->attributeId,
                                         $request->customAttributes[0]->attributeValue
                                     )])
                                 )
                                 ->willReturn($createdUser);

        $result = $this->controller->Create($request, $session);

        $expectedResult = new UserControllerResult($createdUserId);
        $this->assertEquals($expectedResult, $result);
        $this->assertTrue($result->WasSuccessful());
    }

    public function testValidatesCreateRequest()
    {
        $request = CreateUserRequest::Example();
        $session = new FakeWebServiceUserSession(123);

        $errors = ['error'];

        $this->requestValidator->expects($this->once())
                               ->method('ValidateCreateRequest')
                               ->with($this->equalTo($request))
                               ->willReturn($errors);

        $result = $this->controller->Create($request, $session);

        $this->assertFalse($result->WasSuccessful());
        $this->assertEquals($errors, $result->Errors());
    }

    public function testUpdatesUser()
    {
        $userId = 123;
        $user = new FakeUser($userId);
        $request = UpdateUserRequest::Example();
        $session = new FakeWebServiceUserSession(123);

        $this->requestValidator->expects($this->once())
                               ->method('ValidateUpdateRequest')
                               ->with($this->equalTo($userId), $this->equalTo($request))
                               ->willReturn(null);

        $this->manageUserServiceFactory->expects($this->once())
                                       ->method('CreateAdmin')
                                       ->willReturn($this->manageUsersService);

        $this->manageUsersService->expects($this->once())
                                 ->method('UpdateUser')
                                 ->with(
                                     $this->equalTo($userId),
                                     $this->equalTo($request->userName),
                                     $this->equalTo($request->emailAddress),
                                     $this->equalTo($request->firstName),
                                     $this->equalTo($request->lastName),
                                     $this->equalTo($request->timezone),
                                     $this->equalTo([UserAttribute::Phone => $request->phone, UserAttribute::Organization => $request->organization, UserAttribute::Position => $request->position]),
                                     $this->equalTo([new AttributeValue(
                                         $request->customAttributes[0]->attributeId,
                                         $request->customAttributes[0]->attributeValue
                                     )])
                                 )
                                 ->willReturn($user);


        $result = $this->controller->Update($userId, $request, $session);

        $expectedResult = new UserControllerResult($userId);
        $this->assertEquals($expectedResult, $result);
        $this->assertTrue($result->WasSuccessful());
    }

    public function testValidatesUpdateRequest()
    {
        $request = UpdateUserRequest::Example();
        $session = new FakeWebServiceUserSession(123);

        $errors = ['error'];

        $this->requestValidator->expects($this->once())
                               ->method('ValidateUpdateRequest')
                               ->with($this->equalTo(1), $this->equalTo($request))
                               ->willReturn($errors);

        $result = $this->controller->Update(1, $request, $session);

        $this->assertFalse($result->WasSuccessful());
        $this->assertEquals($errors, $result->Errors());
    }

    public function testDeletesUser()
    {
        $userId = 99;
        $session = new FakeWebServiceUserSession(123);

        $this->manageUserServiceFactory->expects($this->once())
                                       ->method('CreateAdmin')
                                       ->willReturn($this->manageUsersService);

        $this->manageUsersService->expects($this->once())
                                 ->method('DeleteUser')
                                 ->with($this->equalTo($userId));

        $result = $this->controller->Delete($userId, $session);

        $this->assertTrue($result->WasSuccessful());
    }

    public function testUpdatesUserPassword()
    {
        $userId = 123;
        $password = 'password';
        $session = new FakeWebServiceUserSession(123);

        $this->requestValidator->expects($this->once())
                               ->method('ValidateUpdatePasswordRequest')
                               ->with($this->equalTo($userId), $this->equalTo($password))
                               ->willReturn(null);

        $this->manageUserServiceFactory->expects($this->once())
                                       ->method('CreateAdmin')
                                       ->willReturn($this->manageUsersService);

        $this->manageUsersService->expects($this->once())
                                 ->method('UpdatePassword')
                                 ->with($this->equalTo($userId), $this->equalTo($password));

        $result = $this->controller->UpdatePassword($userId, $password, $session);

        $expectedResult = new UserControllerResult($userId);
        $this->assertEquals($expectedResult, $result);
        $this->assertTrue($result->WasSuccessful());
    }
}
