<!DOCTYPE html>
<html>
<head>
    <title>Swagger UI</title>
    <link rel="icon" type="image/png" href="swagger/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="swagger/images/favicon-16x16.png" sizes="16x16" />
    <link href='swagger/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='swagger/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='swagger/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='swagger/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
    <link href='swagger/css/print.css' media='print' rel='stylesheet' type='text/css'/>
    <script src='swagger/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
    <script src='swagger/lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='swagger/lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='swagger/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='swagger/lib/handlebars-2.0.0.js' type='text/javascript'></script>
    <script src='swagger/lib/underscore-min.js' type='text/javascript'></script>
    <script src='swagger/lib/backbone-min.js' type='text/javascript'></script>
    <script src='swagger/swagger-ui.js' type='text/javascript'></script>
    <script src='swagger/lib/highlight.7.3.pack.js' type='text/javascript'></script>
    <script src='swagger/lib/marked.js' type='text/javascript'></script>

    <style>
        .swagger-section #header form#api_selector .input input#input_apiKey {
            width: 600px;
        }
    </style>
    <script type="text/javascript">
        $(function () {
            var url = window.location.search.match(/url=([^&]+)/);
            if (url && url.length > 1) {
                url = decodeURIComponent(url[1]);
            } else {
                url = "<?php echo $_ENV['API_URL']?>/v1/docs";
            }
            window.swaggerUi = new SwaggerUi({
                url: url,
                dom_id: "swagger-ui-container",
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function(swaggerApi, swaggerUi){
                    if(typeof initOAuth == "function") {
                        /*
                         initOAuth({
                         clientId: "your-client-id",
                         realm: "your-realms",
                         appName: "your-app-name"
                         });
                         */
                    }
                    $('pre code').each(function(i, e) {
                        hljs.highlightBlock(e)
                    });
                },
                onFailure: function(data) {
                    log("Unable to Load SwaggerUI");
                },
                docExpansion: "none",
                sorter : "alpha"
            });

            function addApiKeyAuthorization() {
                var key = encodeURIComponent($('#input_apiKey')[0].value);
                log("key: " + key);
                if(key && key.trim() != "") {
                    var apiKeyAuth = new SwaggerClient.ApiKeyAuthorization("access_token", key, "query");
                    window.swaggerUi.api.clientAuthorizations.add("access_token", apiKeyAuth);
                    log("added key " + key);
                }
            }

            $('#input_apiKey').change(addApiKeyAuthorization);

            // if you have an apiKey you would like to pre-populate on the page for demonstration purposes...
            /*
             var apiKey = "myApiKeyXXXX123456789";
             $('#input_apiKey').val(apiKey);
             addApiKeyAuthorization();
             */

            window.swaggerUi.load();

            function log() {
                if ('console' in window) {
                    console.log.apply(console, arguments);
                }
            }
        });
    </script>
</head>

<body class="swagger-section">
<div id='header'>
    <div class="swagger-ui-wrap">
        <a id="logo" href="http://swagger.io">swagger</a>
        <form id='api_selector'>

            <div class='input'><input placeholder="access_token" id="input_apiKey" name="apiKey" type="text"/></div>
            <div class='input'><a id="explore" href="#">Explore</a></div>
        </form>
    </div>
</div>

<div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
