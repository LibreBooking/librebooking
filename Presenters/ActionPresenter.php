<?php

require_once(ROOT_DIR . 'Pages/ActionPage.php');

abstract class ActionPresenter
{
    /**
     * @var IActionPage
     */
    private $actionPage;

    /**
     * @var array
     */
    private $actions;

    /**
     * @var array
     */
    private $validations;

    protected function __construct(IActionPage $page)
    {
        $this->actionPage = $page;
        $this->actions = [];
        $this->validations = [];
    }

    /**
     * @param string $actionName
     * @param string $actionMethod
     * @return void
     */
    protected function AddAction($actionName, $actionMethod)
    {
        $this->actions[$actionName] = $actionMethod;
    }

    protected function AddValidation($actionName, $validationMethod)
    {
        $this->validations[$actionName] = $validationMethod;
    }

    protected function ActionIsKnown($action)
    {
        return isset($this->actions[$action]);
    }

    protected function LoadValidators($action)
    {
        // Hook for children to load validators
    }

    public function ProcessAction()
    {
        /** @var string $action */
        $action = $this->actionPage->GetAction();

        if ($this->ActionIsKnown($action)) {
            $this->actionPage->EnforceCSRFCheck();

            $method = $this->actions[$action];
            try {
                $this->LoadValidators($action);

                if ($this->actionPage->IsValid()) {
                    Log::Debug("Processing page action. Action %s", $action);
                    $this->$method();
                }
            } catch (Exception $ex) {
                Log::Error("ProcessAction Error. Action %s, Error %s", $action, $ex);
            }
        } else {
            Log::Error("Unknown action %s", $action);
        }
    }
}
