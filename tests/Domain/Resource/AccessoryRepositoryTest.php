<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/AccessoryRepository.php');

class AccessoryRepositoryTest extends TestBase
{
    /**
     * @var AccessoryRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setup();

        $this->repository = new AccessoryRepository();
    }

    public function testCanLoadById()
    {
        $accessoryId = 100;
        $name = 'n';
        $available = 100;

        $command = new GetAccessoryByIdCommand($accessoryId);
        $this->db->SetRows([$this->GetAccessoryRow($accessoryId, $name, $available)]);

        $accessory = $this->repository->LoadById($accessoryId);

        $this->assertEquals(new Accessory($accessoryId, $name, $available), $accessory);
        $this->assertTrue($this->db->ContainsCommand($command));
        $this->assertTrue($this->db->ContainsCommand(new GetAccessoryResources($accessoryId)));
    }

    public function testCanAdd()
    {
        $name = 'n';
        $available = 100;

        $accessory = Accessory::Create($name, $available);

        $command = new AddAccessoryCommand($name, $available);

        $this->repository->Add($accessory);

        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testCanUpdate()
    {
        $accessoryId = 100;
        $name = 'n';
        $available = 100;

        $accessory = new Accessory($accessoryId, $name, $available);

        $command = new UpdateAccessoryCommand($accessoryId, $name, $available);

        $this->repository->Update($accessory);

        $this->assertTrue($this->db->ContainsCommand($command));
    }

    public function testCanDelete()
    {
        $accessoryId = 100;
        $command = new DeleteAccessoryCommand($accessoryId);

        $this->repository->Delete($accessoryId);

        $this->assertEquals($command, $this->db->_LastCommand);
    }

    private function GetAccessoryRow($accessoryId, $name, $available)
    {
        return [
            ColumnNames::ACCESSORY_ID => $accessoryId,
            ColumnNames::ACCESSORY_NAME => $name,
            ColumnNames::ACCESSORY_QUANTITY => $available];
    }
}
