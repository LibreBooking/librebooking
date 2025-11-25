{include file='globalheader.tpl'}

{* Font Awesome for lab icons *}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

{* 
 * TODO for Institute Integration:
 * - Add Division/Department field
 * - Add Platform/Platform group fields  
 * - Add Technical_contact field
 * - Add UUID field
 * - Add User_level field
 * - Translate to German
 *}

<div class="container mt-4">
    {if $ShowError|default:false}
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> {$ErrorMessage|default:'Unknown error'}
        </div>
        <a href="view_resources.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {translate key='BackToList'}
        </a>
    {else}
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">
                    <i class="fas fa-microscope"></i> {$ResourceName}
                </h2>
            </div>
            
            <div class="card-body">
                <div class="row">
                    {* Left column - Basic information *}
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2">{translate key='Details'|default:'details'}</h5>
                        
                        <p><strong>{translate key='ResourceId'|default:'ID'}:</strong> #{$ResourceId}</p>
                        
                        <p><strong>{translate key='Location'|default:'Position'}:</strong> 
                            {if $ResourceLocation}
                                {$ResourceLocation}
                            {else}
                                <span class="text-muted">{translate key='NoLocationLabel'|default:'(no position specified)'}</span>
                            {/if}
                        </p>
                        
                        <p><strong>{translate key='Contact'|default:'Contact' }:</strong> 
                            {if $ResourceContact}
                                {$ResourceContact}
                            {else}
                                <span class="text-muted">{translate key='NoContactLabel'|default:'No contact information'}</span>
                            {/if}
                        </p>
                        
                        <p><strong>{translate key='MaxParticipants'|default:'Max Capacity'}:</strong> 
                            {if $MaxParticipants}
                                <span class="badge bg-info">{$MaxParticipants} {translate key='people'|default:'people'}</span>
                            {else}
                                <span class="text-muted">{translate key='Unlimited'|default:'No limit'}</span>
                            {/if}
                        </p>
                        
                        <p><strong>{translate key='Status'}:</strong>
                            {if $IsAvailable}
                                <span class="badge bg-success">{translate key='Available'}</span>
                            {else}
                                <span class="badge bg-danger">{translate key='Unavailable'}</span>
                            {/if}
                        </p>
                    </div>
                
                    {* Right column - Rules and restrictions *}
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2">{translate key='ReservationDetails'|default:'Reservation Details'}</h5>
                        
                        <p>
                            <strong>{translate key='MinimumDuration'|default:'Minimum Duration'}:</strong>
                            <br>
                            {if $MinDurationValue}
                                {translate key=$MinDurationKey args=$MinDurationValue}
                            {else}
                                {translate key=$MinDurationKey}
                            {/if}
                        </p>
                        
                        <p>
                            <strong>{translate key='MaximumDuration'|default:'Maximum Duration'}:</strong>
                            <br>
                            {if $MaxDurationValue}
                                {translate key=$MaxDurationKey args=$MaxDurationValue}
                            {else}
                                {translate key=$MaxDurationKey}
                            {/if}
                        </p>
                        
                        <p>
                            <strong>{translate key='RequiresApproval'}:</strong>
                            {if $RequiresApproval}
                                <span class="badge bg-warning text-dark">{translate key='Yes'}</span>
                            {else}
                                <span class="badge bg-success">{translate key='No'}</span>
                            {/if}
                        </p>
                    </div>
                </div> {* End of first row with 2 columns *}
                
                {* Complete description - Second row *}
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2">{translate key='Description'}</h5>
                        <p>{$ResourceDescription|default:'<em class="text-muted">No description available</em>'}</p>
                        
                        {if $ResourceNotes}
                            <h5 class="border-bottom pb-2 mt-3">{translate key='Notes'}</h5>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> {$ResourceNotes}
                            </div>
                        {/if}
                    </div>
                </div> {* End of description row *}
               
                {* Calendar availability link *}
                <div class="row mt-4">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2">
                            <i class="fas fa-calendar-alt"></i> Resource Availability
                        </h5>
                        <iframe 
                            id="scheduleFrame"
                            src="schedule-minimal.php?rid={$smarty.get.id|default:$ResourceId|default:1}" 
                            data-auto-resize="true"
                            width="100%" 
                            style="border: 1px solid #dee2e6; border-radius: 4px;"
                        >
                        </iframe>                        
                    </div>
                </div>


            </div> {* End of card-body *}   

            {* Card footer at same level as card-body *}
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="view_resources.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {translate key='BackToList'|default:'Back to list'}
                    </a>
                    <a href="reservation.php?rid={$ResourceId}" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-plus"></i> {translate key='CreateReservation'|default:'Create Reservation'}
                    </a>
                </div>
            </div>    
        </div> {* End of card *}
    {/if}    
</div> {* End of container *}           

<script src="/Web/scripts/iframe-manager.js"></script>
<script>
    // Configuración específica para esta página
    IframeManager.configure({
        defaultPadding: 0,
        resizeDebounce: 100
    });
</script>

{include file='globalfooter.tpl'}

