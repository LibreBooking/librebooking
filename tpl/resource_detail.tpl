{include file='globalheader.tpl'}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid mt-4">
    {if $ShowError|default:false}
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> {$ErrorMessage|default:'Unknown error'}
        </div>
        <a href="view_resources.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {translate key='BackToList'}
        </a>
    {else}
        {* Header with Back and Reserve buttons *}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>
                <i class="fas fa-flask"></i> Resource: {$ResourceName}
            </h2>
            <div>
                <a href="view_resources.php" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="reservation.php?rid={$ResourceId}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Reserve
                </a>
            </div>
        </div>

        {* Main info card with image and details *}
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    {* Left column - Image only *}
                    <div class="col-md-3">
                        <div class="d-flex align-items-center justify-content-center rounded border shadow-sm p-2 bg-light" 
                             style="height: 250px; width: 100%; min-height: 250px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-4x mb-3"></i>
                                <p class="mb-0 small">No image available</p>
                            </div>
                        </div>
                    </div>

                    {* Right column - Info and Description *}
                    <div class="col-md-9">
                        {* Info in 2 columns *}
                        <div class="row">
                            {* First info column *}
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-info-circle text-muted"></i> 
                                    <strong>ResourceId:</strong> {$ResourceId}
                                </p>
                                
                                <p class="mb-2">
                                    <strong>Resource Type:</strong> 
                                    {if $ResourceType}
                                        {$ResourceType}
                                    {else}
                                        <span class="text-muted">General</span>
                                    {/if}
                                </p>
                                
                                <p class="mb-2">
                                    <strong>Contact:</strong> 
                                    {if $ResourceContact}
                                        {$ResourceContact}
                                    {else}
                                        <span class="text-muted fst-italic">(no contact information)</span>
                                    {/if}
                                </p>
                                
                                <p class="mb-2">
                                    <strong>Schedule:</strong> 
                                    <a href="schedule.php?rid={$ResourceId}" class="text-primary text-decoration-none">
                                        <i class="fas fa-calendar-alt"></i> See availability
                                    </a>
                                </p>
                            </div>

                            {* Second info column *}
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Location:</strong> 
                                    {if $ResourceLocation}
                                        <i class="fas fa-map-marker-alt text-muted"></i> {$ResourceLocation}
                                    {else}
                                        <span class="text-muted fst-italic">(no location set)</span>
                                    {/if}
                                </p>
                                
                                <p class="mb-2">
                                    <strong>Capacity:</strong> 
                                    {if $MaxParticipants}
                                        <span class="badge bg-info">{$MaxParticipants}</span>
                                    {else}
                                        <span class="text-muted">Unlimited</span>
                                    {/if}
                                </p>
                                
                                <p class="mb-2">
                                    <strong>Resource Groups:</strong> 
                                    {if $ResourceGroups}
                                        {$ResourceGroups}
                                    {else}
                                        <span class="text-muted">None</span>
                                    {/if}
                                </p>
                                
                                <p class="mb-2">
                                    <strong>Status:</strong>
                                    {if $IsAvailable}
                                        <span class="badge bg-success">Available</span>
                                    {else}
                                        <span class="badge bg-danger">Unavailable</span>
                                    {/if}
                                </p>
                            </div>
                        </div>

                        {* Description - Inside col-md-9, below info columns *}
                        {if $ResourceDescription}
                            <div class="mt-4">
                                <h5 class="border-bottom pb-2">{translate key='Description'}</h5>
                                <p>{$ResourceDescription}</p>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>

        {* Notes - Separate card, full width, highlighted *}
        {if $ResourceNotes}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="mb-3">
                        <i class="fas fa-exclamation-circle text-warning"></i> {translate key='Notes'}
                    </h5>
                    <div class="border-start border-warning border-4 bg-light p-3">
                        {$ResourceNotes}
                    </div>
                </div>
            </div>
        {/if}

        {* Custom Attributes Section *}
        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-cog"></i> Custom Attributes
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Field</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Test Number</td>
                            <td class="text-muted">-</td>
                        </tr>
                        <tr>
                            <td>Test String</td>
                            <td class="text-muted">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {* Accessories Section *}
        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-tools"></i> Accessories
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-wrench text-muted"></i> 
                        <strong>accessory limited to 10</strong>
                        <br>
                        <small class="text-muted">Quantity Available: 10</small>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-wrench text-muted"></i> 
                        <strong>accessory limited to 2</strong>
                        <br>
                        <small class="text-muted">Quantity Available: 2</small>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-wrench text-muted"></i> 
                        <strong>unlimited accessory</strong>
                        <br>
                        <small class="text-muted">Quantity Available: Unlimited</small>
                    </li>
                </ul>
            </div>
        </div>
    {/if}
</div>

{include file='globalfooter.tpl'}