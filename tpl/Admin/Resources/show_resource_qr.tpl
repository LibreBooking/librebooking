{include file='globalheader.tpl' HideNavBar=true}

<div class="min-vh-75 d-flex justify-content-center align-items-center p-3">
  <div class="border rounded-3 p-3 text-center bg-white shadow-sm" style="max-width: 300px; width: 100%;">
    <h2 class="h4 fw-bold mb-3">{$ResourceName}</h2>
    <div class="mb-3">
      <img src="{$QRImageUrl}" alt="QR Code" class="img-fluid" style="max-width: 200px; height: auto;">
    </div>
    <p class="text-muted mb-0">{translate key=ScanToSchedule}</p>
  </div>
</div>

{include file='globalfooter.tpl'}