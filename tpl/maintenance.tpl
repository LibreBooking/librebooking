{include file='globalheader.tpl'}

<div id="page-maintenance">
    <div class="col-md-6 col-12 mx-auto mt-5">
        <div class="card shadow mb-2">
            <div class="card-body mx-3">
                <div id="maintenance-box" class="default-box">
                    <div class="maintenance-icon my-2">
                        <img src="{$Path}img/{$LogoUrl}?{$Version}" alt="{$Title}" class="mx-auto d-block w-50">
                    </div>
                    <div class="text-center mb-2">
                        <i class="bi bi-tools fs-1"></i>
                        <h2 class="fw-bold mb-3">{translate key='MaintenanceNotice'}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="javascript-includes.tpl"}
{include file='globalfooter.tpl'}