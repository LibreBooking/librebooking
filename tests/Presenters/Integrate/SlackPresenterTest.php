<?php

require_once(ROOT_DIR . 'Presenters/Integrate/SlackPresenter.php');

class SlackPresenterTest extends TestBase
{
    /**
     * @var SlackPresenter
     */
    public $presenter;

    /**
     * @var FakeSlackPage
     */
    public $page;
    /**
     * @var FakeResourceRepository
     */
    public $resourceRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeSlackPage();
        $this->resourceRepository = new FakeResourceRepository();

        $this->presenter = new SlackPresenter($this->page, $this->resourceRepository);

        $this->fakeConfig->_ScriptUrl = 'http://something/Web';
        $this->fakeConfig->SetKey(ConfigKeys::SLACK_TOKEN, 'token');
    }

    public function testWhenResourceNameIsProvided_AndFound_ReturnLinkToResource()
    {
        $resourceName = 'resourcename';

        $this->page->_Command = '/book';
        $this->page->_Text = $resourceName;
        $this->page->_Token = 'token';

        $resource = new FakeBookableResource(10, $resourceName);
        $this->resourceRepository->_NamedResources[$resourceName] = $resource;

        $this->presenter->PageLoad();

        $url = 'http://something/Web/reservation.php?rid=10';

        $expectedResponse = new SlackBookResponse($resourceName, $url);

        $this->assertEquals($expectedResponse, $this->page->_BoundResponse);
    }

    public function testWhenResourceNameIsProvided_AndNotFound_ReturnMessageAndBasicLink()
    {
        $resourceName = 'resourcename';

        $this->page->_Command = '/book';
        $this->page->_Text = $resourceName;
        $this->page->_Token = 'token';

        $this->resourceRepository->_NamedResources[$resourceName] = BookableResource::Null();

        $this->presenter->PageLoad();

        $url = 'http://something/Web/reservation.php';

        $expectedResponse = new SlackBookResponse(null, $url);

        $this->assertEquals($expectedResponse, $this->page->_BoundResponse);
    }

    public function testWhenNoResourceNameIsProvided_ReturnBasicLink()
    {
        $resourceName = '';

        $this->page->_Command = '/book';
        $this->page->_Text = $resourceName;
        $this->page->_Token = 'token';

        $this->presenter->PageLoad();

        $url = 'http://something/Web/reservation.php';

        $expectedResponse = new SlackBookResponse(null, $url);

        $this->assertEquals($expectedResponse, $this->page->_BoundResponse);
    }

    public function testWhenTokenDoesNotMatch_BlowUp()
    {
        $resourceName = '';

        $this->page->_Command = '/book';
        $this->page->_Text = $resourceName;
        $this->page->_Token = 'token other';

        $this->presenter->PageLoad();

        $this->assertTrue($this->page->_BoundError);
    }
}

class FakeSlackPage implements ISlackPage
{
    public $_Command;
    public $_Text;
    public $_Token;
    public $_ChannelId;
    public $_UserId;
    public $_TeamId;
    public $_BoundResponse;
    public $_BoundError;

    public function GetCommand()
    {
        return $this->_Command;
    }

    public function GetText()
    {
        return $this->_Text;
    }

    public function GetToken()
    {
        return $this->_Token;
    }

    public function BindResponse(SlackResponse $response)
    {
        $this->_BoundResponse = $response;
    }

    public function BindError()
    {
        $this->_BoundError = true;
    }
}
