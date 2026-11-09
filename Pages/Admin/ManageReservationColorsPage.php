<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

interface IManageReservationColorsPage extends IActionPage
{
    /**
     * @param Attribute[] $attributes
     */
    public function SetAttributes($attributes);

    /**
     * @param ReservationColorRule[] $rules
     */
    public function SetRules($rules);

    /**
     * @return array|AttributeFormElement[]
     */
    public function GetAttributes();

    /**
     * @return string
     */
    public function GetColor();

    public function GetRuleId(): ?int;
}

class ManageReservationColorsPresenter extends ActionPresenter
{
    /**
     * @var IReservationRepository
     */
    private $reservationRepository;

    /**
     * @var IAttributeRepository
     */
    private $attributeRepository;

    /**
     * @var IManageReservationColorsPage
     */
    private $page;

    public function __construct(IManageReservationColorsPage $page, IReservationRepository $reservationRepository, IAttributeRepository $attributeRepository)
    {
        parent::__construct($page);
        $this->page = $page;
        $this->reservationRepository = $reservationRepository;
        $this->attributeRepository = $attributeRepository;

        $this->AddAction('add', 'Add');
        $this->AddAction('delete', 'Delete');
    }

    public function PageLoad()
    {
        $attributes = $this->attributeRepository->GetByCategory(CustomAttributeCategory::RESERVATION);

        $attrs = [];
        foreach ($attributes as $a) {
            $attrs[] = new LBAttribute($a);
        }
        $this->page->SetAttributes($attrs);

        $rules = $this->reservationRepository->GetReservationColorRules();
        $this->page->SetRules($rules);
    }

    public function Add()
    {
        $attributes = $this->page->GetAttributes();
        if (count($attributes) == 1) {
            $colorRule = ReservationColorRule::Create($attributes[0]->Id, $attributes[0]->Value, $this->page->GetColor());
            $this->reservationRepository->AddReservationColorRule($colorRule);
        }
    }

    public function Delete()
    {
        $ruleId = $this->page->GetRuleId();
        $rule = $this->reservationRepository->GetReservationColorRule($ruleId);
        $this->reservationRepository->DeleteReservationColorRule($rule);
    }
}

class ManageReservationColorsPage extends ActionPage implements IManageReservationColorsPage
{
    /**
     * @var ManageReservationColorsPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('ReservationColors', 1);

        $this->presenter = new ManageReservationColorsPresenter($this, new ReservationRepository(), new AttributeRepository());
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        // TODO: Implement ProcessDataRequest() method.
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad();
        $this->Display('Admin/Reservations/manage_reservation_colors.tpl');
    }

    public function SetAttributes($attributes)
    {
        $this->Set('Attributes', $attributes);
    }

    public function SetRules($rules)
    {
        $this->Set('Rules', $rules);
    }

    /**
     * @return array|AttributeFormElement[]
     */
    public function GetAttributes()
    {
        return AttributeFormParser::GetAttributes($this->GetForm(FormKeys::ATTRIBUTE_PREFIX));
    }

    /**
     * @return string
     */
    public function GetColor()
    {
        return $this->GetForm(FormKeys::RESERVATION_COLOR);
    }

    public function GetRuleId(): ?int
    {
        return $this->GetFormInt(key: FormKeys::RESERVATION_COLOR_RULE_ID);
    }
}
