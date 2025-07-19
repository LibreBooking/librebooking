<?php

require_once(ROOT_DIR . 'Pages/Page.php');

interface IActionPage extends IPage
{
    public function TakingAction();

    public function GetAction();

    public function RequestingData();

    public function GetDataRequest();
}

abstract class ActionPage extends Page implements IActionPage
{
    public function __construct($titleKey, $pageDepth = 0)
    {
        parent::__construct($titleKey, $pageDepth);
    }

    public function PageLoad()
    {
        try {
            if ($this->TakingAction()) {
                $this->ProcessAction();
            } else {
                if ($this->RequestingData()) {
                    $this->ProcessDataRequest($this->GetDataRequest());
                } else {
                    $this->ProcessPageLoad();
                }
            }
        } catch (Exception $ex) {
            Log::Error('Error loading page. %s', $ex);
            throw $ex;
        }
    }
    /**
     * @return bool
     */
    public function TakingAction()
    {
        $action = $this->GetAction();
        return !empty($action);
    }

    /**
     * @return bool
     */
    public function RequestingData()
    {
        $dataRequest = $this->GetDataRequest();
        return !empty($dataRequest);
    }

    /**
     * @return null|string
     */
    public function GetAction()
    {
        return $this->GetQuerystring(QueryStringKeys::ACTION);
    }

    /**
     * @return null|string
     */
    public function GetDataRequest()
    {
        return $this->GetQuerystring(QueryStringKeys::DATA_REQUEST);
    }

    /**
     * @return bool
     */
    public function IsValid()
    {
        if (parent::IsValid()) {
            Log::Debug('Action passed all validations');
            return true;
        }

        $errors = new ActionErrors();
        $inlineErrors = [];

        foreach ($this->smarty->failedValidators as $id => $validator) {
            Log::Debug('Failed validator %s', $id);
            $errors->Add($id, $validator->Messages());

            if ($validator->ReturnsErrorResponse()) {
                http_response_code(400);
                $inlineErrors = array_merge($validator->Messages(), $inlineErrors);
            }
        }

        if (!empty($inlineErrors)) {
            $this->SetJson(implode(',', $inlineErrors));
        } else {
            $this->SetJson($errors);
        }
        return false;
    }

    /**
     * @return void
     */
    abstract public function ProcessAction();

    /**
     * @param $dataRequest string
     * @return void
     */
    abstract public function ProcessDataRequest($dataRequest);

    /**
     * @return void
     */
    abstract public function ProcessPageLoad();
}

class ActionErrors
{
    public $ErrorIds = [];
    public $Messages = [];

    public function Add($id, $messages = [])
    {
        $this->ErrorIds[] = $id;
        $this->Messages[$id] = $messages;
    }
}
