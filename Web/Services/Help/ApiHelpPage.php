<?php

class ApiHelpPage
{
    public static function Render(SlimWebServiceRegistry $registry, Slim\Slim $app)
    {
        $head = <<<EOT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8"/>
            <title>LibreBooking API Documentation</title>
            <link rel="shortcut icon" href="../favicon.ico"/>
            <link rel="icon" href="../favicon.ico"/>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    overflow-x: hidden;
                    margin: 0;
                    padding: 0;
                }
                    h2, h6 {
                    scroll-margin-top: 160px;
                }
                .topbar {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    background: #36648B;
                    color:#ffffff;
                    z-index: 1030;
                    padding-bottom: 1rem;
                }
                .sidebar {
                    width: 280px;
                    height: calc(100vh - 140px);
                    position: fixed;
                    top: 140px;
                    left: 0;
                    overflow-y: auto;
                    border-right: 1px solid #dee2e6;
                    background-color: #f8f9fa;
                }
                .content {
                    margin-left: 280px;
                    padding: 1rem;
                    margin-top: 140px;
                }
                .code {
                    font-family: monospace;
                    font-size: 0.9rem;
                    background: #f1f1f1;
                    padding: .5rem;
                    border-radius: .25rem;
                }
                .secure {
                    color: #dc3545;
                    font-weight: bold;
                }
                .admin {
                    color: #d63384;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="topbar">
                <div class="container-fluid">
                    <h1 class="mb-3">LibreBooking API Documentation</h1>
EOT;

        echo $head;

        $security = sprintf(
            "<div class='alert alert-warning mb-0'>⚠️ Pass the following headers for all secure service calls: <code>%s</code> and <code>%s</code></div>",
            WebServiceHeaders::SESSION_TOKEN,
            WebServiceHeaders::USER_ID
        );
        echo $security;

        echo <<<EOT
                </div>
            </div>

            <nav class="sidebar p-3">
                <h5 class="mb-3">📂 API Endpoints</h5>
                <ul class="list-group list-group-flush">
        EOT;

        foreach ($registry->Categories() as $category) {
            echo "<li class='list-group-item'>";
            echo "<a href='#{$category->Name()}' class='d-block small fw-semibold text-dark mb-1'>{$category->Name()}</a>";
            echo "<div class='ms-3'>";
            echo "<a href='#{$category->Name()}-post' class='d-block small text-muted'>POST Services</a>";
            echo "<a href='#{$category->Name()}-get' class='d-block small text-muted'>GET Services</a>";
            echo "<a href='#{$category->Name()}-delete' class='d-block small text-muted'>DELETE Services</a>";
            echo "</div></li>";
        }

        echo <<<EOT
                </ul>
            </nav>

            <main class="content">
                <div class="container-fluid">
        EOT;

        foreach ($registry->Categories() as $category) {
            echo "<h2 id='{$category->Name()}' class='mt-5'>{$category->Name()}</h2>";

            // POST
            echo "<h6 id='{$category->Name()}-post' class='mt-3 text-muted'>POST Services</h6>";
            if (count($category->Posts()) === 0) {
                echo "<p class='ms-2'><em>None</em></p>";
            } else {
                echo "<div class='accordion ms-2' id='accordion-post-{$category->Name()}'>";
                $i = 0;
                foreach ($category->Posts() as $service) {
                    $i++;
                    $md = $service->Metadata();
                    $collapseId = "collapse-{$category->Name()}-post-$i";
                    echo "<div class='accordion-item'>";
                    echo "<h2 class='accordion-header' id='heading-$collapseId'>";
                    echo "<button class='accordion-button collapsed bg-success text-white' type='button' data-bs-toggle='collapse' data-bs-target='#$collapseId'>{$md->Name()}</button>";
                    echo "</h2>";
                    echo "<div id='$collapseId' class='accordion-collapse collapse'>";
                    echo "<div class='accordion-body'>";
                    $request = $md->Request();
                    self::EchoCommon($md, $service, $app);
                    echo "<h5>Request</h5>";
                    if (is_object($request)) {
                        echo "<div class='code'><pre>" . json_encode($request, JSON_PRETTY_PRINT) . "</pre></div>";
                    } elseif (is_null($request)) {
                        echo "<p><em>None</em></p>";
                    } else {
                        echo "<p>Unstructured request of type <i>$request</i></p>";
                    }
                    echo "</div></div></div>";
                }
                echo "</div>";
            }

            // GET
            echo "<h6 id='{$category->Name()}-get' class='mt-3 text-muted'>GET Services</h6>";
            if (count($category->Gets()) === 0) {
                echo "<p class='ms-2'><em>None</em></p>";
            } else {
                echo "<div class='accordion ms-2' id='accordion-get-{$category->Name()}'>";
                $i = 0;
                foreach ($category->Gets() as $service) {
                    $i++;
                    $md = $service->Metadata();
                    $collapseId = "collapse-{$category->Name()}-get-$i";
                    echo "<div class='accordion-item'>";
                    echo "<h2 class='accordion-header' id='heading-$collapseId'>";
                    echo "<button class='accordion-button collapsed bg-primary text-white' type='button' data-bs-toggle='collapse' data-bs-target='#$collapseId'>{$md->Name()}</button>";
                    echo "</h2>";
                    echo "<div id='$collapseId' class='accordion-collapse collapse'>";
                    echo "<div class='accordion-body'>";
                    self::EchoCommon($md, $service, $app);
                    echo "</div></div></div>";
                }
                echo "</div>";
            }

            // DELETE
            echo "<h6 id='{$category->Name()}-delete' class='mt-3 text-muted'>DELETE Services</h6>";
            if (count($category->Deletes()) === 0) {
                echo "<p class='ms-2'><em>None</em></p>";
            } else {
                echo "<div class='accordion ms-2' id='accordion-delete-{$category->Name()}'>";
                $i = 0;
                foreach ($category->Deletes() as $service) {
                    $i++;
                    $md = $service->Metadata();
                    $collapseId = "collapse-{$category->Name()}-delete-$i";
                    echo "<div class='accordion-item'>";
                    echo "<h2 class='accordion-header' id='heading-$collapseId'>";
                    echo "<button class='accordion-button collapsed bg-danger text-white' type='button' data-bs-toggle='collapse' data-bs-target='#$collapseId'>{$md->Name()}</button>";
                    echo "</h2>";
                    echo "<div id='$collapseId' class='accordion-collapse collapse'>";
                    echo "<div class='accordion-body'>";
                    self::EchoCommon($md, $service, $app);
                    echo "</div></div></div>";
                }
                echo "</div>";
            }
        }

        echo <<<EOT
                </div>
            </main>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
EOT;
    }

    private static function EchoCommon(SlimServiceMetadata $md, $endpoint, Slim\Slim $app)
    {
        $response = $md->Response();
        echo "<h5>Name</h5><p>{$md->Name()}</p>";
        echo "<h5>Description</h5><p>" . nl2br($md->Description()) . "</p>";
        echo "<h5>Route</h5><p><code>" . $app->urlFor($endpoint->RouteName()) . "</code></p>";

        if ($endpoint->IsSecure()) {
            echo "<p class='secure'>🔒 This service is secure and requires authentication</p>";
        }
        if ($endpoint->IsLimitedToAdmin()) {
            echo "<p class='admin'>⚠️ This service is only available to application administrators</p>";
        }

        echo "<h5>Response</h5>";
        if (is_object($response)) {
            echo "<div class='code'><pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre></div>";
        } elseif (is_null($response)) {
            echo "<p><em>None</em></p>";
        } else {
            echo "<p>Unstructured response of type <i>$response</i></p>";
        }
    }
}
