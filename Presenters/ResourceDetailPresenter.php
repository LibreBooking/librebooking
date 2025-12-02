<?php
require_once(ROOT_DIR . 'Domain/Access/ResourceRepository.php');

class ResourceDetailPresenter
{
    /**
     * @var ResourceDetailPage 
     */

    private $page;

    /**
     * @var IResourceRepository
     * This property will hold the repository instance for accessing resource data.
     */
    private $resourceRepository;

    /**
     * @param ResourceDetailPage $page 
     */

    public function __construct($page)
    {
        $this->page = $page;
        $this->resourceRepository = new ResourceRepository();
    }

    public function PageLoad()
    {
        // Step 1: get and validate resource ID from query parameters
        $resourceId = $this->GetResourceId();

        // Step 2: check that ID is valid
        if ($resourceId === null) {
            // If no valid ID, show error message
            $this->page->Set('ErrorMessage', 'No resource ID provided.');
            $this->page->Set('ShowError', true);
            return;
        } 
        
        // Step 3: get resource details from DB
        try {
            $resource = $this->resourceRepository->LoadById($resourceId);
        } catch (Exception $e) {
            // If there is a db error, show error message
            $this->page->Set('ErrorMessage', 'Database error: ' . $e->GetMessage());
            $this->page->Set('ShowError', true);
            return;
        }

        // Step 4: check if resource exists
        if ($resource === null) {
            $this->page->Set('ErrorMessage', 'Resource not found.');
            $this->page->Set('ShowError', true);
            return;
        }
        // Step 5: If the resource exists, set resource details to template
        $this->PopulateTemplate($resource);
    }

    /** Private method that gets and validates the resource ID from query parameters
     * @return int|null 
     */

    private function GetResourceId()
    {   // isset() checks if the variable exists
        if(!isset($_GET['id'])) {
            return null;
        }
        // filter_var() validates and sanitizes the input
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        // If the number is not a valid integer or less than or equal to 0, return null
        if ($id === false || $id < 1) {
            return null;
        }
        return $id;
    }

    /** Private method that populates the template with resource details
     * @param BookableResource $resource 
     */
private function PopulateTemplate($resource)
{
    // Basic data
    $this->page->Set('ResourceId', $resource->GetId());
    $this->page->Set('ResourceName', $resource->GetName());

    // Use ternary operator to check if empty
    $this->page->Set('ResourceDescription',
        $resource->HasDescription() ? $resource->GetDescription() : null
    );

    $this->page->Set('ResourceLocation',
        $resource->HasLocation() ? $resource->GetLocation() : null
    );

    $this->page->Set('ResourceContact',
        $resource->HasContact() ? $resource->GetContact() : null
    );

    // Additional info
    $this->page->Set('ResourceNotes', $resource->GetNotes());
    $this->page->Set('RequiresApproval', $resource->GetRequiresApproval());
    $this->page->Set('MaxParticipants', $resource->GetMaxParticipants());

    // Availability info
    $this->page->Set('IsAvailable', $resource->IsAvailable());

    // MINIMUM DURATION - with keys logic
    $minLength = $resource->GetMinLength();
    if ($minLength !== null && method_exists($minLength, 'TotalMinutes')) {
        $minutes = $minLength->TotalMinutes();
        if ($minutes > 0) {
            $formattedMin = $this->FormatDuration($minLength);
            $this->page->Set('MinDurationKey', 'ResourceMinLength');
            $this->page->Set('MinDurationValue', $formattedMin);
        } else {
            $this->page->Set('MinDurationKey', 'ResourceMinLengthNone');
            $this->page->Set('MinDurationValue', null);
        }
    } else {
        $this->page->Set('MinDurationKey', 'ResourceMinLengthNone');
        $this->page->Set('MinDurationValue', null);
    }

    // MAXIMUM DURATION - with keys logic
    $maxLength = $resource->GetMaxLength();
    if ($maxLength !== null && method_exists($maxLength, 'TotalMinutes')) {
        $minutes = $maxLength->TotalMinutes();
        if ($minutes > 0) {
            $formattedMax = $this->FormatDuration($maxLength);
            $this->page->Set('MaxDurationKey', 'ResourceMaxLength');
            $this->page->Set('MaxDurationValue', $formattedMax);
        } else {
            $this->page->Set('MaxDurationKey', 'ResourceMaxLengthNone');
            $this->page->Set('MaxDurationValue', null);
        }
    } else {
        $this->page->Set('MaxDurationKey', 'ResourceMaxLengthNone');
        $this->page->Set('MaxDurationValue', null);
    }

    // No errors to show
    $this->page->Set('ShowError', false);
}

    /** Private method that formats a duration in minutes to a human-readable string
     * @param int $minutes 
     * @return string 
     */
    private function FormatDuration($timeInterval) 
    {
        if ($timeInterval === null) {
            return 'Not set';
        }

        $minutes = 0;

        if(is_object($timeInterval)) {
            if (method_exists($timeInterval, 'TotalMinutes')) {
                $minutes = $timeInterval->totalMinutes();
            } elseif (method_exists($timeInterval, 'Minutes')) {
                $minutes = $timeInterval->minutes();
            } 
        } else {
                $minutes = inval($timeInterval);
        }

        if ($minutes <= 0) {
            return 'Not set';
        }

        // Entire division in PHP
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60; // Modulus that obtains the remainder

        if ($hours > 0 && $mins > 0) {
            return sprintf('%d hour(s) %d minute(s)', $hours, $mins);
        } elseif ($hours > 0) {
            return sprintf('%d hour(s)', $hours);
        } else {
            return sprintf('%d minute(s)', $mins);  
        }
    }

}