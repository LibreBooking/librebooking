{include file='globalheader.tpl'}

<div class="maintenance-container d-flex flex-column align-items-center justify-content-center py-4">
    <div class="maintenance-card card border-0 shadow-lg" style="max-width: 650px;">
        <div class="card-header bg-primary text-white text-center py-3">
            <h2 class="m-0"><i class="bi bi-tools me-2"></i>{$AppTitle}</h2>
        </div>

        <div class="card-body p-4">
            <!-- Mensaje principal -->
            <div class="alert alert-warning text-center mb-4">
                <h3 class="alert-heading mb-2">Estamos realizando mantenimiento</h3>
                <p class="mb-0">Disculpe las molestias. Estamos trabajando para mejorar nuestros servicios.</p>
            </div>

            <!-- Contador regresivo -->
            <div class="mb-4">
                <h4 class="text-center mb-3"><i class="bi bi-clock-history me-2"></i>Reanudaremos servicios en:</h4>

                <div class="countdown-container d-flex justify-content-center mb-3">
                    <div class="countdown-item mx-2 text-center">
                        <span
                            class="countdown-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            id="days">00</span>
                        <span class="countdown-label text-capitalize mt-1">{translate key="days"}</span>
                    </div>
                    <div class="countdown-separator d-flex align-items-center">:</div>
                    <div class="countdown-item mx-2 text-center">
                        <span
                            class="countdown-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            id="hours">00</span>
                        <span class="countdown-label text-capitalize mt-1">{translate key="hours"}</span>
                    </div>
                    <div class="countdown-separator d-flex align-items-center">:</div>
                    <div class="countdown-item mx-2 text-center">
                        <span
                            class="countdown-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            id="minutes">00</span>
                        <span class="countdown-label text-capitalize mt-1">{translate key="minutes"}</span>
                    </div>
                    <div class="countdown-separator d-flex align-items-center d-none">:</div>
                    <div class="countdown-item mx-2 text-center d-none">
                        <span
                            class="countdown-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            id="seconds">00</span>
                        <span class="countdown-label text-capitalize mt-1">{translate key="seconds"}</span>
                    </div>
                </div>
            </div>

            <!-- Información de contacto -->
            <div class="contact-info bg-light p-3 rounded">
                <h4 class="text-center mb-3"><i class="bi bi-info-circle me-2"></i>Información de contacto</h4>
                <div class="d-flex flex-column align-items-center">
                    <div class="mb-2">
                        <i class="bi bi-telephone me-2"></i>Teléfono: 3239300 ext 5046
                    </div>
                    <div>
                        <i class="bi bi-envelope me-2"></i>Correo:
                        <a class="link-primary" href="mailto:labmecanicatec@udistrital.edu.co" target="_top">
                            labmecanicatec@udistrital.edu.co
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{jsfile src="maintenance.js"}

{include file="javascript-includes.tpl"}
{include file='globalfooter.tpl'}