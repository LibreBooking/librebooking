{function name="avisoResource"}
    {if $resource->GetId()==44 || $resource->GetId()==42 || $resource->GetId()==177 || $resource->GetId()==180}
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading d-flex align-items-center justify-content-center">
                Condiciones de uso para {$resource->GetName()}
            </h4>

            {if $resource->GetId()==44} {*Máquina de tensión para aceros*}
                <p class="mb-3"><strong>Este equipo está destinado exclusivamente para:</strong></p>
                <ul class="mb-3">
                    <li>Ensayo de tensión en metales</li>
                    <li class="d-none">Ensayo de compresión en metales</li>
                    <li>Ensayo de flexión en metales</li>
                </ul>

                <p class="mb-3"><strong>Restricciones importantes:</strong></p>
                <ul class="mb-3">
                    <li>Para ensayos de <strong>torsión, fatiga o impacto en metales</strong>, debe reservar los recursos
                        específicos correspondientes en esta reserva.</li>
                    <li>Para ensayos de <strong>tensión, compresión o flexión en plásticos</strong>, debe realizarlos en el
                        Laboratorio <a href="https://rita.udistrital.edu.co:23604/reservas/Web/schedule.php?sid=19"
                            class="alert-link text-decoration-underline">Diseño y Desarrollo Tecnológico / Plásticos</a>.</li>
                    <li>Recuerde revisar la <a href="https://rita.udistrital.edu.co:23604/adminlab/recursos/inventario/13536"
                            class="alert-link text-decoration-underline" target="_blank"> Guía de Laboratorio para Tensión</a> antes
                        de realizar la
                        práctica, aquí encontrará información relevante como las dimensiones de las probetas</li>
                </ul>
            {/if}

            {if $resource->GetId()==42} {*Máquina de fatiga*}
                <p class="mb-0"><strong>Restricciones importantes:</strong></p>
                <ul class="mb-3">
                    <li>Recuerde que para este ensayo es necesario el uso de dos probetas del mismo material.</li>
                    <li><strong>Diámetro máximo permitido:</strong> 15.00 mm <sup>0.00</sup>⁄<sub>-0.011</sub> mm
                        (tolerancia unilateral negativa).</li>
                </ul>
            {/if}

            {if $resource->GetId()==177} {*Escáner 3D*}
                <p class="mb-0"><strong>Restricciones importantes:</strong></p>
                <ul class="mb-3">
                    <li>Puede traer sus propias piezas para escanear, se recomienda que éstas sean opacas.</li>
                    <li>Debido al tamaño de archivos en el proceso de escaneo, no se conservan en el computador del escáner.</li>
                </ul>
            {/if}

            {if $resource->GetId()==180} {*Máquina de Tensión para plásticos*}
                <p class="mb-0"><strong>Restricciones importantes:</strong></p>
                <ul class="mb-3">
                    <li>Límites en la Máquina de Tensión para Plásticos: Planas ≤14 mm (espesor) | Redondas ≤20 mm (diámetro). </li>
                </ul>
            {/if}
        </div>
    {/if}
{/function}

{function name="avisoSchedule"}


    {if $ScheduleId=="13"}{*Tratamientos Térmicos*}
        <h4 class="alert-heading d-flex align-items-center justify-content-center mt-2">Normas de Seguridad</h4>
        <ul class="mb-0">
            <li>Uso <strong>OBLIGATORIO</strong> de bata de laboratorio</li>
            <li>En el caso de usar el <strong>Mesón de lijado</strong>, el estudiante debe encargarse de llevar las lijas
                necesarias para la preparación metalográfica</li>
            <li>Prohibido el acceso sin EPP adecuado</li>
        </ul>
    {elseif $ScheduleId=="3"} {*Ciencias térmicas*}
        <ul class="mb-0">
            <li>Leer previamente las respectivas <a class="alert-link text-decoration-underline"
                    href="https://rita.udistrital.edu.co:23604/adminlab/recursos/laboratorio/ciencias-termicas"
                    target="_blank">Guías de Laboratorio</a>. Si no se cumple, la práctica puede ser cancelada.</li>
        </ul>
    {elseif $ScheduleId=="8"} {*Neumática*}
        <h4 class="alert-heading d-flex align-items-center justify-content-center mt-2">Normas de Seguridad</h4>
        <ul class="mb-0">
            <li>No utilizar aire comprimido para limpieza de ropa/piel.</li>
            <li>Protectores auditivos (presiones >3 bar)</li>
            <li>Guantes anti-vibración para manejo de pistones</li>
        </ul>

    {elseif $ScheduleId=="4"} {*Hidráulica*}
        <h4 class="alert-heading d-flex align-items-center justify-content-center mt-2">
            Normas de Seguridad
        </h4>
        <ul class="mb-0">
            <li>Uso <strong>OBLIGATORIO</strong> de bata de laboratorio.</li>
            <li>Se recomienda <strong>encarecidamente</strong> el uso de guantes de Latex o Nitrilo.</li>
            <li>Prohibido el acceso sin EPP adecuado.</li>
        </ul>

    {elseif $ScheduleId=="10"} {*Resistencia*}
        <ul class="mb-0 d-none">
            <li>Recuerde que para este ensayo es necesario el uso de dos probetas del mismo material.</li>
            <li>El diámetro más grande de las probetas debe ser de 15 mm exactos, sin tolerancia.</li>
        </ul>
        <h4 class="alert-heading d-flex align-items-center justify-content-center mt-2">
            Normas de Seguridad
        </h4>

    {elseif $ScheduleId=="14" or $ScheduleId=="15" or $ScheduleId=="16" or $ScheduleId=="11"} {*Talleres*}
        <ul class="mb-0">
            <li>Este espacio requiere la aprobación del docente a cargo, descargue el <a
                    class="alert-link text-decoration-underline"
                    href="https://rita.udistrital.edu.co:23604/adminlab/recursos/publicacion/formato-para-la-solicitud-de-practicas-de-laboratorio"
                    target="_blank">Formato para la Solicitud de Prácticas de Laboratorio</a> y llévelo firmado el día de la
                reserva. Puede ser llenado en computador o a mano.</li>
        </ul>
        <h4 class="alert-heading d-flex align-items-center justify-content-center mt-2">
            Normas de Seguridad
        </h4>

    {elseif $ScheduleId=="19"} {*Diseño y Desarrollo Tecnológico/Plásticos*}
        <ul class="mb-0">
            <li>En este laboratorio sólo se puede reservar un equipo simultáneamente.</li>
        </ul>

        <h4 class="alert-heading d-flex align-items-center justify-content-center mt-2">
            Normas de Seguridad
        </h4>
        <ul>
            <li>Respete el área de demarcado cuando se opera la Máquina de Tensión para Plásticos.</li>
        </ul>
    {/if}
{/function}
<div class="mt-2">
    {if isset($resource)}
        {avisoResource resource=$resource}
    {else}
        {if $ScheduleId!==1}
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading d-flex align-items-center justify-content-center">
                    Condiciones de uso del laboratorio
                </h4>
                <ul class="mb-0">
                    <li>Se dará una espera máxima de 20 minutos. Si el estudiante llega después, la práctica se cancela.</li>
                    <li>Comer/beber dentro del laboratorio (riesgo de contaminación química).
                    </li>
                </ul>
                {avisoSchedule ScheduleId=$ScheduleId}

                <hr>
                <p class="mb-0"><strong>Nota:</strong> El incumplimiento de estas normas puede resultar en la suspensión del
                    acceso al laboratorio.</p>
            </div>
        {/if}
    {/if}
</div>