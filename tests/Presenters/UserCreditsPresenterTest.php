<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Credits/UserCreditsPresenter.php');

class UserCreditsPresenterTest extends TestBase
{
    /**
     * @var FakeUserCreditsPage
     */
    private $page;
    /**
     * @var FakeUserRepository
     */
    private $userRepository;
    /**
     * @var FakePaymentRepository
     */
    private $paymentRepository;
    /**
     * @var UserCreditsPresenter
     */
    private $presenter;
    /**
     * @var FakeCreditRepository
     */
    private $creditRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeUserCreditsPage();
        $this->userRepository = new FakeUserRepository();
        $this->paymentRepository = new FakePaymentRepository();
        $this->creditRepository = new FakeCreditRepository();

        $this->presenter = new UserCreditsPresenter(
            $this->page,
            $this->userRepository,
            $this->paymentRepository,
            $this->creditRepository
        );
    }

    public function testPageLoad()
    {
        $currentCredits = 10.5;
        $this->userRepository->_User = new FakeUser();
        $this->userRepository->_User->WithCredits($currentCredits);
        $this->paymentRepository->_CreditCost = [new CreditCost(1, '10.11')];

        $this->presenter->PageLoad($this->fakeUser);

        $this->assertEquals($currentCredits, $this->page->_CurrentCredits);
    }

    public function testGetCreditLog()
    {
        $this->page->_CurrentPage = 10;
        $this->page->_PageSize = 50;

        $this->creditRepository->_UserCredits = new PageableData([new CreditLogView(Date::Now(), 'note', 10, 15)]);

        $this->presenter->GetCreditLog($this->fakeUser);

        $this->assertEquals($this->creditRepository->_UserCredits, $this->page->_CreditLog);
        $this->assertEquals(10, $this->creditRepository->_LastPage);
        $this->assertEquals(50, $this->creditRepository->_LastPageSize);
        $this->assertEquals($this->fakeUser->UserId, $this->creditRepository->_LastUserId);
    }

    public function testGetTransactionLog()
    {
        $this->page->_CurrentPage = 10;
        $this->page->_PageSize = 50;

        $this->paymentRepository->_TransactionLogs = new PageableData([
            new TransactionLogView(
                Date::Now(),
                'status',
                'invoice',
                'txid',
                10.6,
                .33,
                'USD',
                'selfref',
                'refundref',
                'gatewaydate',
                'PayPal',
                10
            )
        ]);

        $this->presenter->GetTransactionLog($this->fakeUser);

        $this->assertEquals($this->paymentRepository->_TransactionLogs, $this->page->_TransactionLog);
        $this->assertEquals(10, $this->paymentRepository->_LastPage);
        $this->assertEquals(50, $this->paymentRepository->_LastPageSize);
        $this->assertEquals($this->fakeUser->UserId, $this->paymentRepository->_LastUserId);
    }
}

class FakeUserCreditsPage extends UserCreditsPage
{
    public $_CurrentCredits;
    public $_PaymentResult;
    public $_CreditCost;
    public $_CurrentPage;
    public $_PageSize;
    public $_CreditLog;
    public $_TransactionLog;

    public function SetCurrentCredits($credits)
    {
        $this->_CurrentCredits = $credits;
    }

    public function GetPaymentResult()
    {
        return $this->_PaymentResult;
    }

    public function SetCreditCost(CreditCost $cost)
    {
        $this->_CreditCost = $cost;
    }

    public function GetPageNumber(): int
    {
        return intval($this->_CurrentPage);
    }

    public function GetPageSize(): int
    {
        return intval($this->_PageSize);
    }

    public function BindCreditLog($creditLog)
    {
        $this->_CreditLog = $creditLog;
    }

    public function BindTransactionLog($transactionLog)
    {
        $this->_TransactionLog = $transactionLog;
    }
}
