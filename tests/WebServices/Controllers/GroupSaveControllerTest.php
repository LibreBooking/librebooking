<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Controllers/GroupSaveController.php');
require_once(ROOT_DIR . 'WebServices/Requests/Group/GroupRequest.php');

class GroupSaveControllerTest extends TestBase
{
    private GroupRepository&\PHPUnit\Framework\MockObject\MockObject $groupRepository;

    private GroupSaveController $controller;

    private WebServiceUserSession $session;

    public function setUp(): void
    {
        parent::setup();

        $this->groupRepository = $this->createMock('GroupRepository');
        $this->session = new FakeWebServiceUserSession(1);
        $this->controller = new GroupSaveController(
            groupRepository: $this->groupRepository,
            resourceRepository: $this->createMock('ResourceRepository'),
            scheduleRepository: $this->createMock('IScheduleRepository')
        );
    }

    public function testCreatesGroupWhenRequestOmitsIsDefault(): void
    {
        $this->groupRepository->expects($this->once())
            ->method('Add')
            ->with($this->callback(fn (Group $group) => $group->Name() === 'new group' && !$group->IsDefault()))
            ->willReturn(12);

        $result = $this->controller->Create(request: $this->RequestWithoutIsDefault(name: 'new group'), session: $this->session);

        $this->assertTrue($result->WasSuccessful());
        $this->assertSame(12, $result->GroupId());
    }

    public function testUpdatesGroupWhenRequestOmitsIsDefault(): void
    {
        $group = new Group(id: 5, name: 'old name');
        $group->ChangeDefault(isDefault: true);
        $this->groupRepository->method('LoadById')->willReturn($group);
        $this->groupRepository->expects($this->once())->method('Update')->with($this->identicalTo($group));

        $result = $this->controller->Update(groupId: 5, request: $this->RequestWithoutIsDefault(name: 'new name'), session: $this->session);

        $this->assertTrue($result->WasSuccessful());
        $this->assertSame('new name', $group->Name());
        $this->assertFalse($group->IsDefault());
    }

    public function testFacadeTreatsMissingOrNullIsDefaultAsFalse(): void
    {
        $nullIsDefault = $this->Request(name: 'g', isDefault: null);
        $trueIsDefault = $this->Request(name: 'g', isDefault: true);
        $falseIsDefault = $this->Request(name: 'g', isDefault: false);

        $this->assertFalse((new CreateGroupFacade(request: $this->RequestWithoutIsDefault(name: 'g')))->AutomaticallyAddToGroup());
        $this->assertFalse((new CreateGroupFacade(request: $nullIsDefault))->AutomaticallyAddToGroup());
        $this->assertFalse((new CreateGroupFacade(request: null, id: 5))->AutomaticallyAddToGroup());
        $this->assertTrue((new CreateGroupFacade(request: $trueIsDefault))->AutomaticallyAddToGroup());
        $this->assertFalse((new CreateGroupFacade(request: $falseIsDefault))->AutomaticallyAddToGroup());
    }

    private function Request(string $name, ?bool $isDefault): GroupRequest
    {
        $request = new GroupRequest();
        $request->name = $name;
        $request->isDefault = $isDefault;

        return $request;
    }

    /**
     * Mimics a decoded JSON body that has no isDefault field.
     */
    private function RequestWithoutIsDefault(string $name): GroupRequest
    {
        $request = $this->Request(name: $name, isDefault: false);
        unset($request->isDefault);

        return $request;
    }
}
