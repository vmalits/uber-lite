<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Uber API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
                    body .content .php-example code { display: none; }
                    body .content .python-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8080";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.6.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.6.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;,&quot;php&quot;,&quot;python&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                            <button type="button" class="lang-button" data-language-name="php">php</button>
                                            <button type="button" class="lang-button" data-language-name="python">python</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-admin" class="tocify-header">
                <li class="tocify-item level-1" data-unique="admin">
                    <a href="#admin">Admin</a>
                </li>
                                    <ul id="tocify-subheader-admin" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="admin-POSTapi-v1-admin-login">
                                <a href="#admin-POSTapi-v1-admin-login">POST api/v1/admin/login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-users">
                                <a href="#admin-GETapi-v1-admin-users">GET api/v1/admin/users</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-users--id-">
                                <a href="#admin-GETapi-v1-admin-users--id-">GET api/v1/admin/users/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-drivers">
                                <a href="#admin-GETapi-v1-admin-drivers">GET api/v1/admin/drivers</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-drivers--id-">
                                <a href="#admin-GETapi-v1-admin-drivers--id-">GET api/v1/admin/drivers/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-rides">
                                <a href="#admin-GETapi-v1-admin-rides">GET api/v1/admin/rides</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-rides--id-">
                                <a href="#admin-GETapi-v1-admin-rides--id-">GET api/v1/admin/rides/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-DELETEapi-v1-admin-users--id-">
                                <a href="#admin-DELETEapi-v1-admin-users--id-">DELETE api/v1/admin/users/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-v1-admin-drivers--driver_id--ban">
                                <a href="#admin-POSTapi-v1-admin-drivers--driver_id--ban">POST api/v1/admin/drivers/{driver_id}/ban</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-v1-admin-drivers--driver_id--unban">
                                <a href="#admin-POSTapi-v1-admin-drivers--driver_id--unban">POST api/v1/admin/drivers/{driver_id}/unban</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-auth" class="tocify-header">
                <li class="tocify-item level-1" data-unique="auth">
                    <a href="#auth">Auth</a>
                </li>
                                    <ul id="tocify-subheader-auth" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-request-otp">
                                <a href="#auth-POSTapi-v1-auth-request-otp">POST api/v1/auth/request-otp</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-request-otp-resend">
                                <a href="#auth-POSTapi-v1-auth-request-otp-resend">POST api/v1/auth/request-otp/resend</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-verify-otp">
                                <a href="#auth-POSTapi-v1-auth-verify-otp">POST api/v1/auth/verify-otp</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-add-email">
                                <a href="#auth-POSTapi-v1-auth-add-email">POST api/v1/auth/add-email</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-select-role">
                                <a href="#auth-POSTapi-v1-auth-select-role">POST api/v1/auth/select-role</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-complete-profile">
                                <a href="#auth-POSTapi-v1-auth-complete-profile">POST api/v1/auth/complete-profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-logout">
                                <a href="#auth-POSTapi-v1-auth-logout">POST api/v1/auth/logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-email-verification-notification">
                                <a href="#auth-POSTapi-v1-auth-email-verification-notification">POST api/v1/auth/email/verification-notification</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-GETapi-v1-auth-email-verify--user_id---hash-">
                                <a href="#auth-GETapi-v1-auth-email-verify--user_id---hash-">GET api/v1/auth/email/verify/{user_id}/{hash}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-DELETEapi-v1-auth-delete-account">
                                <a href="#auth-DELETEapi-v1-auth-delete-account">DELETE api/v1/auth/delete-account</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-driver" class="tocify-header">
                <li class="tocify-item level-1" data-unique="driver">
                    <a href="#driver">Driver</a>
                </li>
                                    <ul id="tocify-subheader-driver" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="driver-GETapi-v1-driver-profile">
                                <a href="#driver-GETapi-v1-driver-profile">GET api/v1/driver/profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-PUTapi-v1-driver-profile">
                                <a href="#driver-PUTapi-v1-driver-profile">PUT api/v1/driver/profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-online">
                                <a href="#driver-POSTapi-v1-driver-online">POST api/v1/driver/online</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-offline">
                                <a href="#driver-POSTapi-v1-driver-offline">POST api/v1/driver/offline</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-GETapi-v1-driver-rides-active">
                                <a href="#driver-GETapi-v1-driver-rides-active">GET api/v1/driver/rides/active</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-GETapi-v1-driver-rides-available">
                                <a href="#driver-GETapi-v1-driver-rides-available">GET api/v1/driver/rides/available</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-rides--ride_id--accept">
                                <a href="#driver-POSTapi-v1-driver-rides--ride_id--accept">POST api/v1/driver/rides/{ride_id}/accept</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-rides--ride_id--on-the-way">
                                <a href="#driver-POSTapi-v1-driver-rides--ride_id--on-the-way">POST api/v1/driver/rides/{ride_id}/on-the-way</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-rides--ride_id--arrived">
                                <a href="#driver-POSTapi-v1-driver-rides--ride_id--arrived">POST api/v1/driver/rides/{ride_id}/arrived</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-rides--ride_id--start">
                                <a href="#driver-POSTapi-v1-driver-rides--ride_id--start">POST api/v1/driver/rides/{ride_id}/start</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-rides--ride_id--complete">
                                <a href="#driver-POSTapi-v1-driver-rides--ride_id--complete">POST api/v1/driver/rides/{ride_id}/complete</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-rides--ride_id--cancel">
                                <a href="#driver-POSTapi-v1-driver-rides--ride_id--cancel">POST api/v1/driver/rides/{ride_id}/cancel</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-location">
                                <a href="#driver-POSTapi-v1-driver-location">POST api/v1/driver/location</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-avatar">
                                <a href="#driver-POSTapi-v1-driver-avatar">POST api/v1/driver/avatar</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-GETapi-v1-driver-vehicles">
                                <a href="#driver-GETapi-v1-driver-vehicles">GET api/v1/driver/vehicles</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-GETapi-v1-driver-vehicle--vehicle_id-">
                                <a href="#driver-GETapi-v1-driver-vehicle--vehicle_id-">GET api/v1/driver/vehicle/{vehicle_id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-POSTapi-v1-driver-vehicle">
                                <a href="#driver-POSTapi-v1-driver-vehicle">POST api/v1/driver/vehicle</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-PUTapi-v1-driver-vehicle--id-">
                                <a href="#driver-PUTapi-v1-driver-vehicle--id-">PUT api/v1/driver/vehicle/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="driver-DELETEapi-v1-driver-vehicle--vehicle_id-">
                                <a href="#driver-DELETEapi-v1-driver-vehicle--vehicle_id-">DELETE api/v1/driver/vehicle/{vehicle_id}</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-profile" class="tocify-header">
                <li class="tocify-item level-1" data-unique="profile">
                    <a href="#profile">Profile</a>
                </li>
                                    <ul id="tocify-subheader-profile" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="profile-POSTapi-v1-profile-locale">
                                <a href="#profile-POSTapi-v1-profile-locale">POST api/v1/profile/locale</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-rider" class="tocify-header">
                <li class="tocify-item level-1" data-unique="rider">
                    <a href="#rider">Rider</a>
                </li>
                                    <ul id="tocify-subheader-rider" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="rider-GETapi-v1-rider-locations-search">
                                <a href="#rider-GETapi-v1-rider-locations-search">GET api/v1/rider/locations/search</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-GETapi-v1-rider-favorites">
                                <a href="#rider-GETapi-v1-rider-favorites">GET api/v1/rider/favorites</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-POSTapi-v1-rider-favorites">
                                <a href="#rider-POSTapi-v1-rider-favorites">POST api/v1/rider/favorites</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-DELETEapi-v1-rider-favorites--id-">
                                <a href="#rider-DELETEapi-v1-rider-favorites--id-">DELETE api/v1/rider/favorites/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-GETapi-v1-rider-profile">
                                <a href="#rider-GETapi-v1-rider-profile">GET api/v1/rider/profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-PUTapi-v1-rider-profile">
                                <a href="#rider-PUTapi-v1-rider-profile">PUT api/v1/rider/profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-POSTapi-v1-rider-estimates">
                                <a href="#rider-POSTapi-v1-rider-estimates">POST api/v1/rider/estimates</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-POSTapi-v1-rider-rides">
                                <a href="#rider-POSTapi-v1-rider-rides">POST api/v1/rider/rides</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-GETapi-v1-rider-rides-active">
                                <a href="#rider-GETapi-v1-rider-rides-active">GET api/v1/rider/rides/active</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-GETapi-v1-rider-rides-history">
                                <a href="#rider-GETapi-v1-rider-rides-history">GET api/v1/rider/rides/history</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-GETapi-v1-rider-rides--id-">
                                <a href="#rider-GETapi-v1-rider-rides--id-">GET api/v1/rider/rides/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-POSTapi-v1-rider-rides--ride_id--cancel">
                                <a href="#rider-POSTapi-v1-rider-rides--ride_id--cancel">POST api/v1/rider/rides/{ride_id}/cancel</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-PUTapi-v1-rider-rides--ride_id--rating">
                                <a href="#rider-PUTapi-v1-rider-rides--ride_id--rating">PUT api/v1/rider/rides/{ride_id}/rating</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-GETapi-v1-rider-stats">
                                <a href="#rider-GETapi-v1-rider-stats">GET api/v1/rider/stats</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="rider-POSTapi-v1-rider-avatar">
                                <a href="#rider-POSTapi-v1-rider-avatar">POST api/v1/rider/avatar</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-support" class="tocify-header">
                <li class="tocify-item level-1" data-unique="support">
                    <a href="#support">Support</a>
                </li>
                                    <ul id="tocify-subheader-support" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="support-POSTapi-v1-support-tickets">
                                <a href="#support-POSTapi-v1-support-tickets">POST api/v1/support/tickets</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-ws" class="tocify-header">
                <li class="tocify-item level-1" data-unique="ws">
                    <a href="#ws">WS</a>
                </li>
                                    <ul id="tocify-subheader-ws" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="ws-GETapi-v1-ws-token">
                                <a href="#ws-GETapi-v1-ws-token">GET api/v1/ws/token</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: January 21, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8080</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="admin">Admin</h1>

    

                                <h2 id="admin-POSTapi-v1-admin-login">POST api/v1/admin/login</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-admin-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/admin/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"phone\": \"+3736000000\",
    \"password\": \"admin12345\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "phone": "+3736000000",
    "password": "admin12345"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/login';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'phone' =&gt; '+3736000000',
            'password' =&gt; 'admin12345',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/login'
payload = {
    "phone": "+3736000000",
    "password": "admin12345"
}
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-login">
            <blockquote>
            <p>Example response (200, Admin authenticated successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Invalid credentials.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-login" data-method="POST"
      data-path="api/v1/admin/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-login"
                    onclick="tryItOut('POSTapi-v1-admin-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-login"
                    onclick="cancelTryOut('POSTapi-v1-admin-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-v1-admin-login"
               value="+3736000000"
               data-component="body">
    <br>
<p>Phone number of the admin. The <code>phone</code> of an existing record in the users table. Example: <code>+3736000000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-admin-login"
               value="admin12345"
               data-component="body">
    <br>
<p>Password of the admin. Example: <code>admin12345</code></p>
        </div>
        </form>

                    <h2 id="admin-GETapi-v1-admin-users">GET api/v1/admin/users</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-users">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/admin/users?per_page=15&amp;role=architecto&amp;status=architecto&amp;banned=&amp;filter%5Bphone%5D=architecto&amp;filter%5Bemail%5D=architecto&amp;filter%5Bfirst_name%5D=architecto&amp;filter%5Blast_name%5D=architecto&amp;sort=architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"per_page\": 15,
    \"role\": \"admin\",
    \"status\": \"active\",
    \"banned\": false,
    \"filter\": {
        \"phone\": \"+3736\",
        \"email\": \"example\",
        \"first_name\": \"John\",
        \"last_name\": \"Doe\"
    },
    \"sort\": \"-created_at\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/users"
);

const params = {
    "per_page": "15",
    "role": "architecto",
    "status": "architecto",
    "banned": "0",
    "filter[phone]": "architecto",
    "filter[email]": "architecto",
    "filter[first_name]": "architecto",
    "filter[last_name]": "architecto",
    "sort": "architecto",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "per_page": 15,
    "role": "admin",
    "status": "active",
    "banned": false,
    "filter": {
        "phone": "+3736",
        "email": "example",
        "first_name": "John",
        "last_name": "Doe"
    },
    "sort": "-created_at"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/users';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'per_page' =&gt; '15',
            'role' =&gt; 'architecto',
            'status' =&gt; 'architecto',
            'banned' =&gt; '0',
            'filter[phone]' =&gt; 'architecto',
            'filter[email]' =&gt; 'architecto',
            'filter[first_name]' =&gt; 'architecto',
            'filter[last_name]' =&gt; 'architecto',
            'sort' =&gt; 'architecto',
        ],
        'json' =&gt; [
            'per_page' =&gt; 15,
            'role' =&gt; 'admin',
            'status' =&gt; 'active',
            'banned' =&gt; false,
            'filter' =&gt; [
                'phone' =&gt; '+3736',
                'email' =&gt; 'example',
                'first_name' =&gt; 'John',
                'last_name' =&gt; 'Doe',
            ],
            'sort' =&gt; '-created_at',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/users'
payload = {
    "per_page": 15,
    "role": "admin",
    "status": "active",
    "banned": false,
    "filter": {
        "phone": "+3736",
        "email": "example",
        "first_name": "John",
        "last_name": "Doe"
    },
    "sort": "-created_at"
}
params = {
  'per_page': '15',
  'role': 'architecto',
  'status': 'architecto',
  'banned': '0',
  'filter[phone]': 'architecto',
  'filter[email]': 'architecto',
  'filter[first_name]': 'architecto',
  'filter[last_name]': 'architecto',
  'sort': 'architecto',
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, json=payload, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-users">
            <blockquote>
            <p>Example response (200, Paginated users list retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - not an admin.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-users" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-users"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-users" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-users" data-method="GET"
      data-path="api/v1/admin/users"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-users', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-users"
                    onclick="tryItOut('GETapi-v1-admin-users');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-users"
                    onclick="cancelTryOut('GETapi-v1-admin-users');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-users"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/users</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-users"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-admin-users"
               value="15"
               data-component="query">
    <br>
<p>Number of items per page Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="GETapi-v1-admin-users"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by user role (admin, driver, rider) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-admin-users"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by user status (active, inactive, banned) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>banned</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-v1-admin-users" style="display: none">
            <input type="radio" name="banned"
                   value="1"
                   data-endpoint="GETapi-v1-admin-users"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-admin-users" style="display: none">
            <input type="radio" name="banned"
                   value="0"
                   data-endpoint="GETapi-v1-admin-users"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter banned users (true/false) Example: <code>false</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[phone]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[phone]"                data-endpoint="GETapi-v1-admin-users"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by phone number (partial match) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[email]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[email]"                data-endpoint="GETapi-v1-admin-users"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by email (partial match) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[first_name]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[first_name]"                data-endpoint="GETapi-v1-admin-users"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by first name (partial match) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[last_name]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[last_name]"                data-endpoint="GETapi-v1-admin-users"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by last name (partial match) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-admin-users"
               value="architecto"
               data-component="query">
    <br>
<p>Sort field (prefix with - for descending) Example: <code>architecto</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-admin-users"
               value="15"
               data-component="body">
    <br>
<p>Number of items per page. Must be at least 2. Must not be greater than 50. Example: <code>15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="GETapi-v1-admin-users"
               value="admin"
               data-component="body">
    <br>
<p>Filter by user role. Example: <code>admin</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>admin</code></li> <li><code>rider</code></li> <li><code>driver</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-admin-users"
               value="active"
               data-component="body">
    <br>
<p>Filter by user status. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active</code></li> <li><code>inactive</code></li> <li><code>banned</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>banned</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-v1-admin-users" style="display: none">
            <input type="radio" name="banned"
                   value="true"
                   data-endpoint="GETapi-v1-admin-users"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-admin-users" style="display: none">
            <input type="radio" name="banned"
                   value="false"
                   data-endpoint="GETapi-v1-admin-users"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Filter banned users (true/false). Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>filter</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.phone"                data-endpoint="GETapi-v1-admin-users"
               value="+3736"
               data-component="body">
    <br>
<p>Filter by phone number (partial match). Example: <code>+3736</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.email"                data-endpoint="GETapi-v1-admin-users"
               value="example"
               data-component="body">
    <br>
<p>Filter by email (partial match). Example: <code>example</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.first_name"                data-endpoint="GETapi-v1-admin-users"
               value="John"
               data-component="body">
    <br>
<p>Filter by first name (partial match). Example: <code>John</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.last_name"                data-endpoint="GETapi-v1-admin-users"
               value="Doe"
               data-component="body">
    <br>
<p>Filter by last name (partial match). Example: <code>Doe</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-admin-users"
               value="-created_at"
               data-component="body">
    <br>
<p>Sort field (prefix with - for descending). Example: <code>-created_at</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>created_at</code></li> <li><code>-created_at</code></li> <li><code>updated_at</code></li> <li><code>-updated_at</code></li> <li><code>last_login_at</code></li> <li><code>-last_login_at</code></li> <li><code>phone</code></li> <li><code>-phone</code></li> <li><code>email</code></li> <li><code>-email</code></li></ul>
        </div>
        </form>

                    <h2 id="admin-GETapi-v1-admin-users--id-">GET api/v1/admin/users/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-users--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/admin/users/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/users/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/users/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/users/architecto'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-users--id-">
            <blockquote>
            <p>Example response (200, User details retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - not an admin.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, User not found.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-users--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-users--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-users--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-users--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-users--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-users--id-" data-method="GET"
      data-path="api/v1/admin/users/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-users--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-users--id-"
                    onclick="tryItOut('GETapi-v1-admin-users--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-users--id-"
                    onclick="cancelTryOut('GETapi-v1-admin-users--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-users--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/users/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-users--id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-users--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-users--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-admin-users--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="admin-GETapi-v1-admin-drivers">GET api/v1/admin/drivers</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-drivers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/admin/drivers?per_page=15&amp;status=architecto&amp;banned=&amp;filter%5Bphone%5D=architecto&amp;filter%5Bemail%5D=architecto&amp;filter%5Bfirst_name%5D=architecto&amp;filter%5Blast_name%5D=architecto&amp;sort=architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"per_page\": 15,
    \"status\": \"active\",
    \"banned\": false,
    \"filter\": {
        \"phone\": \"+3736\",
        \"email\": \"example\",
        \"first_name\": \"John\",
        \"last_name\": \"Doe\"
    },
    \"sort\": \"-created_at\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/drivers"
);

const params = {
    "per_page": "15",
    "status": "architecto",
    "banned": "0",
    "filter[phone]": "architecto",
    "filter[email]": "architecto",
    "filter[first_name]": "architecto",
    "filter[last_name]": "architecto",
    "sort": "architecto",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "per_page": 15,
    "status": "active",
    "banned": false,
    "filter": {
        "phone": "+3736",
        "email": "example",
        "first_name": "John",
        "last_name": "Doe"
    },
    "sort": "-created_at"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/drivers';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'per_page' =&gt; '15',
            'status' =&gt; 'architecto',
            'banned' =&gt; '0',
            'filter[phone]' =&gt; 'architecto',
            'filter[email]' =&gt; 'architecto',
            'filter[first_name]' =&gt; 'architecto',
            'filter[last_name]' =&gt; 'architecto',
            'sort' =&gt; 'architecto',
        ],
        'json' =&gt; [
            'per_page' =&gt; 15,
            'status' =&gt; 'active',
            'banned' =&gt; false,
            'filter' =&gt; [
                'phone' =&gt; '+3736',
                'email' =&gt; 'example',
                'first_name' =&gt; 'John',
                'last_name' =&gt; 'Doe',
            ],
            'sort' =&gt; '-created_at',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/drivers'
payload = {
    "per_page": 15,
    "status": "active",
    "banned": false,
    "filter": {
        "phone": "+3736",
        "email": "example",
        "first_name": "John",
        "last_name": "Doe"
    },
    "sort": "-created_at"
}
params = {
  'per_page': '15',
  'status': 'architecto',
  'banned': '0',
  'filter[phone]': 'architecto',
  'filter[email]': 'architecto',
  'filter[first_name]': 'architecto',
  'filter[last_name]': 'architecto',
  'sort': 'architecto',
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, json=payload, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-drivers">
            <blockquote>
            <p>Example response (200, Paginated drivers list retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - not an admin.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-drivers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-drivers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-drivers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-drivers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-drivers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-drivers" data-method="GET"
      data-path="api/v1/admin/drivers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-drivers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-drivers"
                    onclick="tryItOut('GETapi-v1-admin-drivers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-drivers"
                    onclick="cancelTryOut('GETapi-v1-admin-drivers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-drivers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/drivers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-drivers"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-drivers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-drivers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-admin-drivers"
               value="15"
               data-component="query">
    <br>
<p>Number of items per page Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-admin-drivers"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by user status (active, inactive, banned) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>banned</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-v1-admin-drivers" style="display: none">
            <input type="radio" name="banned"
                   value="1"
                   data-endpoint="GETapi-v1-admin-drivers"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-admin-drivers" style="display: none">
            <input type="radio" name="banned"
                   value="0"
                   data-endpoint="GETapi-v1-admin-drivers"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter banned drivers (true/false) Example: <code>false</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[phone]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[phone]"                data-endpoint="GETapi-v1-admin-drivers"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by phone number (partial match) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[email]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[email]"                data-endpoint="GETapi-v1-admin-drivers"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by email (partial match) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[first_name]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[first_name]"                data-endpoint="GETapi-v1-admin-drivers"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by first name (partial match) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[last_name]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[last_name]"                data-endpoint="GETapi-v1-admin-drivers"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by last name (partial match) Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-admin-drivers"
               value="architecto"
               data-component="query">
    <br>
<p>Sort field (prefix with - for descending) Example: <code>architecto</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-admin-drivers"
               value="15"
               data-component="body">
    <br>
<p>Number of items per page. Must be at least 2. Must not be greater than 50. Example: <code>15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-admin-drivers"
               value="active"
               data-component="body">
    <br>
<p>Filter by user status. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active</code></li> <li><code>inactive</code></li> <li><code>banned</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>banned</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-v1-admin-drivers" style="display: none">
            <input type="radio" name="banned"
                   value="true"
                   data-endpoint="GETapi-v1-admin-drivers"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-admin-drivers" style="display: none">
            <input type="radio" name="banned"
                   value="false"
                   data-endpoint="GETapi-v1-admin-drivers"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Filter banned drivers (true/false). Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>filter</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.phone"                data-endpoint="GETapi-v1-admin-drivers"
               value="+3736"
               data-component="body">
    <br>
<p>Filter by phone number (partial match). Example: <code>+3736</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.email"                data-endpoint="GETapi-v1-admin-drivers"
               value="example"
               data-component="body">
    <br>
<p>Filter by email (partial match). Example: <code>example</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.first_name"                data-endpoint="GETapi-v1-admin-drivers"
               value="John"
               data-component="body">
    <br>
<p>Filter by first name (partial match). Example: <code>John</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.last_name"                data-endpoint="GETapi-v1-admin-drivers"
               value="Doe"
               data-component="body">
    <br>
<p>Filter by last name (partial match). Example: <code>Doe</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-admin-drivers"
               value="-created_at"
               data-component="body">
    <br>
<p>Sort field (prefix with - for descending). Example: <code>-created_at</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>created_at</code></li> <li><code>-created_at</code></li> <li><code>updated_at</code></li> <li><code>-updated_at</code></li> <li><code>last_login_at</code></li> <li><code>-last_login_at</code></li> <li><code>phone</code></li> <li><code>-phone</code></li> <li><code>email</code></li> <li><code>-email</code></li></ul>
        </div>
        </form>

                    <h2 id="admin-GETapi-v1-admin-drivers--id-">GET api/v1/admin/drivers/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-drivers--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/admin/drivers/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/drivers/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/drivers/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/drivers/architecto'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-drivers--id-">
            <blockquote>
            <p>Example response (200, Driver details with statistics retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - not an admin.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Driver not found.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-drivers--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-drivers--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-drivers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-drivers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-drivers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-drivers--id-" data-method="GET"
      data-path="api/v1/admin/drivers/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-drivers--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-drivers--id-"
                    onclick="tryItOut('GETapi-v1-admin-drivers--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-drivers--id-"
                    onclick="cancelTryOut('GETapi-v1-admin-drivers--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-drivers--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/drivers/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-drivers--id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-drivers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-drivers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-admin-drivers--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the driver. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="admin-GETapi-v1-admin-rides">GET api/v1/admin/rides</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-rides">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/admin/rides?per_page=15&amp;status=architecto&amp;filter%5Brider_id%5D=architecto&amp;filter%5Bdriver_id%5D=architecto&amp;sort=architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"per_page\": 15,
    \"status\": \"completed\",
    \"filter\": {
        \"rider_id\": \"01h7x9b6w5e8v2k4n8m0p3q5r1\",
        \"driver_id\": \"01h7x9b6w5e8v2k4n8m0p3q5r2\"
    },
    \"sort\": \"-created_at\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/rides"
);

const params = {
    "per_page": "15",
    "status": "architecto",
    "filter[rider_id]": "architecto",
    "filter[driver_id]": "architecto",
    "sort": "architecto",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "per_page": 15,
    "status": "completed",
    "filter": {
        "rider_id": "01h7x9b6w5e8v2k4n8m0p3q5r1",
        "driver_id": "01h7x9b6w5e8v2k4n8m0p3q5r2"
    },
    "sort": "-created_at"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/rides';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'per_page' =&gt; '15',
            'status' =&gt; 'architecto',
            'filter[rider_id]' =&gt; 'architecto',
            'filter[driver_id]' =&gt; 'architecto',
            'sort' =&gt; 'architecto',
        ],
        'json' =&gt; [
            'per_page' =&gt; 15,
            'status' =&gt; 'completed',
            'filter' =&gt; [
                'rider_id' =&gt; '01h7x9b6w5e8v2k4n8m0p3q5r1',
                'driver_id' =&gt; '01h7x9b6w5e8v2k4n8m0p3q5r2',
            ],
            'sort' =&gt; '-created_at',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/rides'
payload = {
    "per_page": 15,
    "status": "completed",
    "filter": {
        "rider_id": "01h7x9b6w5e8v2k4n8m0p3q5r1",
        "driver_id": "01h7x9b6w5e8v2k4n8m0p3q5r2"
    },
    "sort": "-created_at"
}
params = {
  'per_page': '15',
  'status': 'architecto',
  'filter[rider_id]': 'architecto',
  'filter[driver_id]': 'architecto',
  'sort': 'architecto',
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, json=payload, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-rides">
            <blockquote>
            <p>Example response (200, Paginated rides list retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - not an admin.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-rides" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-rides"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-rides"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-rides" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-rides">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-rides" data-method="GET"
      data-path="api/v1/admin/rides"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-rides', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-rides"
                    onclick="tryItOut('GETapi-v1-admin-rides');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-rides"
                    onclick="cancelTryOut('GETapi-v1-admin-rides');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-rides"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/rides</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-rides"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-rides"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-rides"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-admin-rides"
               value="15"
               data-component="query">
    <br>
<p>Number of items per page Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-admin-rides"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by ride status Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[rider_id]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[rider_id]"                data-endpoint="GETapi-v1-admin-rides"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by rider ID Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter[driver_id]</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter[driver_id]"                data-endpoint="GETapi-v1-admin-rides"
               value="architecto"
               data-component="query">
    <br>
<p>Filter by driver ID Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-admin-rides"
               value="architecto"
               data-component="query">
    <br>
<p>Sort field (prefix with - for descending) Example: <code>architecto</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-admin-rides"
               value="15"
               data-component="body">
    <br>
<p>Number of items per page. Must be at least 2. Must not be greater than 50. Example: <code>15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-admin-rides"
               value="completed"
               data-component="body">
    <br>
<p>Filter by ride status. Example: <code>completed</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>pending</code></li> <li><code>accepted</code></li> <li><code>on_the_way</code></li> <li><code>arrived</code></li> <li><code>started</code></li> <li><code>completed</code></li> <li><code>cancelled</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>filter</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>rider_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.rider_id"                data-endpoint="GETapi-v1-admin-rides"
               value="01h7x9b6w5e8v2k4n8m0p3q5r1"
               data-component="body">
    <br>
<p>Filter by rider ID. Example: <code>01h7x9b6w5e8v2k4n8m0p3q5r1</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>driver_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.driver_id"                data-endpoint="GETapi-v1-admin-rides"
               value="01h7x9b6w5e8v2k4n8m0p3q5r2"
               data-component="body">
    <br>
<p>Filter by driver ID. Example: <code>01h7x9b6w5e8v2k4n8m0p3q5r2</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-admin-rides"
               value="-created_at"
               data-component="body">
    <br>
<p>Sort field (prefix with - for descending). Example: <code>-created_at</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>created_at</code></li> <li><code>-created_at</code></li> <li><code>updated_at</code></li> <li><code>-updated_at</code></li> <li><code>price</code></li> <li><code>-price</code></li> <li><code>status</code></li> <li><code>-status</code></li></ul>
        </div>
        </form>

                    <h2 id="admin-GETapi-v1-admin-rides--id-">GET api/v1/admin/rides/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-admin-rides--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/admin/rides/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/rides/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/rides/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/rides/architecto'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-rides--id-">
            <blockquote>
            <p>Example response (200, Ride details retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - not an admin.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Ride not found.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-rides--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-rides--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-rides--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-rides--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-rides--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-rides--id-" data-method="GET"
      data-path="api/v1/admin/rides/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-rides--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-rides--id-"
                    onclick="tryItOut('GETapi-v1-admin-rides--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-rides--id-"
                    onclick="cancelTryOut('GETapi-v1-admin-rides--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-rides--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/rides/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-rides--id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-rides--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-rides--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-admin-rides--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="admin-DELETEapi-v1-admin-users--id-">DELETE api/v1/admin/users/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-admin-users--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8080/api/v1/admin/users/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/users/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/users/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/users/architecto'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('DELETE', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-admin-users--id-">
            <blockquote>
            <p>Example response (204, User deleted successfully.):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - not an admin or trying to delete self.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, User not found.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-admin-users--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-admin-users--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-admin-users--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-admin-users--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-admin-users--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-admin-users--id-" data-method="DELETE"
      data-path="api/v1/admin/users/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-admin-users--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-admin-users--id-"
                    onclick="tryItOut('DELETEapi-v1-admin-users--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-admin-users--id-"
                    onclick="cancelTryOut('DELETEapi-v1-admin-users--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-admin-users--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/admin/users/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-admin-users--id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-admin-users--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-admin-users--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="DELETEapi-v1-admin-users--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="admin-POSTapi-v1-admin-drivers--driver_id--ban">POST api/v1/admin/drivers/{driver_id}/ban</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-admin-drivers--driver_id--ban">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/admin/drivers/architecto/ban" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"reason\": \"Violation of rules\",
    \"ban_type\": \"temporary\",
    \"ban_source\": \"admin\",
    \"expires_at\": \"2026-01-10 12:00:00\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/drivers/architecto/ban"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reason": "Violation of rules",
    "ban_type": "temporary",
    "ban_source": "admin",
    "expires_at": "2026-01-10 12:00:00"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/drivers/architecto/ban';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'reason' =&gt; 'Violation of rules',
            'ban_type' =&gt; 'temporary',
            'ban_source' =&gt; 'admin',
            'expires_at' =&gt; '2026-01-10 12:00:00',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/drivers/architecto/ban'
payload = {
    "reason": "Violation of rules",
    "ban_type": "temporary",
    "ban_source": "admin",
    "expires_at": "2026-01-10 12:00:00"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-drivers--driver_id--ban">
            <blockquote>
            <p>Example response (200, Driver banned successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Unauthorized – not an admin):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Driver not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (409, Driver with id [%s] is already banned):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-drivers--driver_id--ban" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-drivers--driver_id--ban"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-drivers--driver_id--ban"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-drivers--driver_id--ban" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-drivers--driver_id--ban">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-drivers--driver_id--ban" data-method="POST"
      data-path="api/v1/admin/drivers/{driver_id}/ban"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-drivers--driver_id--ban', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-drivers--driver_id--ban"
                    onclick="tryItOut('POSTapi-v1-admin-drivers--driver_id--ban');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-drivers--driver_id--ban"
                    onclick="cancelTryOut('POSTapi-v1-admin-drivers--driver_id--ban');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-drivers--driver_id--ban"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/drivers/{driver_id}/ban</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-drivers--driver_id--ban"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--ban"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--ban"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>driver_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="driver_id"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--ban"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the driver. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reason</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reason"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--ban"
               value="Violation of rules"
               data-component="body">
    <br>
<p>Ban reason. Must not be greater than 500 characters. Example: <code>Violation of rules</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ban_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ban_type"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--ban"
               value="temporary"
               data-component="body">
    <br>
<p>Ban type (temporary|permanent). Example: <code>temporary</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>temporary</code></li> <li><code>permanent</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ban_source</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ban_source"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--ban"
               value="admin"
               data-component="body">
    <br>
<p>Ban source (admin). Example: <code>admin</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>admin</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>expires_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="expires_at"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--ban"
               value="2026-01-10 12:00:00"
               data-component="body">
    <br>
<p>End date of temporary ban (Y-m-d H:i:s). Must be a valid date. Example: <code>2026-01-10 12:00:00</code></p>
        </div>
        </form>

                    <h2 id="admin-POSTapi-v1-admin-drivers--driver_id--unban">POST api/v1/admin/drivers/{driver_id}/unban</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-admin-drivers--driver_id--unban">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/admin/drivers/architecto/unban" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"reason\": \"Ban lifted after review\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/admin/drivers/architecto/unban"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reason": "Ban lifted after review"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/admin/drivers/architecto/unban';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'reason' =&gt; 'Ban lifted after review',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/admin/drivers/architecto/unban'
payload = {
    "reason": "Ban lifted after review"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-drivers--driver_id--unban">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Unauthorized – not an admin):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Driver not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (409, Driver is not currently banned):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-drivers--driver_id--unban" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-drivers--driver_id--unban"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-drivers--driver_id--unban"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-drivers--driver_id--unban" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-drivers--driver_id--unban">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-drivers--driver_id--unban" data-method="POST"
      data-path="api/v1/admin/drivers/{driver_id}/unban"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-drivers--driver_id--unban', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-drivers--driver_id--unban"
                    onclick="tryItOut('POSTapi-v1-admin-drivers--driver_id--unban');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-drivers--driver_id--unban"
                    onclick="cancelTryOut('POSTapi-v1-admin-drivers--driver_id--unban');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-drivers--driver_id--unban"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/drivers/{driver_id}/unban</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-drivers--driver_id--unban"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--unban"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--unban"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>driver_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="driver_id"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--unban"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the driver. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reason</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reason"                data-endpoint="POSTapi-v1-admin-drivers--driver_id--unban"
               value="Ban lifted after review"
               data-component="body">
    <br>
<p>Reason for unbanning the driver. Must not be greater than 500 characters. Example: <code>Ban lifted after review</code></p>
        </div>
        </form>

                <h1 id="auth">Auth</h1>

    

                                <h2 id="auth-POSTapi-v1-auth-request-otp">POST api/v1/auth/request-otp</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-request-otp">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/auth/request-otp" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"phone\": \"+37360123456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/request-otp"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "phone": "+37360123456"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/request-otp';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'phone' =&gt; '+37360123456',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/request-otp'
payload = {
    "phone": "+37360123456"
}
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-request-otp">
            <blockquote>
            <p>Example response (200, OTP has been requested successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (409, An active OTP code already exists. Please use the resend endpoint.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (429, Too many OTP requests. Please try again later.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-request-otp" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-request-otp"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-request-otp"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-request-otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-request-otp">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-request-otp" data-method="POST"
      data-path="api/v1/auth/request-otp"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-request-otp', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-request-otp"
                    onclick="tryItOut('POSTapi-v1-auth-request-otp');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-request-otp"
                    onclick="cancelTryOut('POSTapi-v1-auth-request-otp');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-request-otp"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/request-otp</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-request-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-request-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-v1-auth-request-otp"
               value="+37360123456"
               data-component="body">
    <br>
<p>Phone number of the user to receive the OTP. Must be a valid Moldova phone number. Example: <code>+37360123456</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-request-otp-resend">POST api/v1/auth/request-otp/resend</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-request-otp-resend">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/auth/request-otp/resend" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"phone\": \"+37360123456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/request-otp/resend"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "phone": "+37360123456"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/request-otp/resend';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'phone' =&gt; '+37360123456',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/request-otp/resend'
payload = {
    "phone": "+37360123456"
}
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-request-otp-resend">
            <blockquote>
            <p>Example response (200, OTP has been resent successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (429, Too many OTP requests. Please try again later.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-request-otp-resend" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-request-otp-resend"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-request-otp-resend"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-request-otp-resend" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-request-otp-resend">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-request-otp-resend" data-method="POST"
      data-path="api/v1/auth/request-otp/resend"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-request-otp-resend', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-request-otp-resend"
                    onclick="tryItOut('POSTapi-v1-auth-request-otp-resend');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-request-otp-resend"
                    onclick="cancelTryOut('POSTapi-v1-auth-request-otp-resend');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-request-otp-resend"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/request-otp/resend</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-request-otp-resend"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-request-otp-resend"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-v1-auth-request-otp-resend"
               value="+37360123456"
               data-component="body">
    <br>
<p>Phone number of the user to receive the OTP. Must be a valid Moldova phone number. Example: <code>+37360123456</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-verify-otp">POST api/v1/auth/verify-otp</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-auth-verify-otp">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/auth/verify-otp" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"phone\": \"+37360123456\",
    \"code\": \"123456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/verify-otp"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "phone": "+37360123456",
    "code": "123456"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/verify-otp';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'phone' =&gt; '+37360123456',
            'code' =&gt; '123456',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/verify-otp'
payload = {
    "phone": "+37360123456",
    "code": "123456"
}
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-verify-otp">
            <blockquote>
            <p>Example response (200, OTP verified successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (429, Too many failed attempts. Account temporarily locked.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-verify-otp" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-verify-otp"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-verify-otp"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-verify-otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-verify-otp">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-verify-otp" data-method="POST"
      data-path="api/v1/auth/verify-otp"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-verify-otp', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-verify-otp"
                    onclick="tryItOut('POSTapi-v1-auth-verify-otp');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-verify-otp"
                    onclick="cancelTryOut('POSTapi-v1-auth-verify-otp');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-verify-otp"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/verify-otp</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-verify-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-verify-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-v1-auth-verify-otp"
               value="+37360123456"
               data-component="body">
    <br>
<p>User's phone number (Moldova). Include country code, e.g., +37360123456. Example: <code>+37360123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="POSTapi-v1-auth-verify-otp"
               value="123456"
               data-component="body">
    <br>
<p>6-digit one-time password (OTP) sent to the provided phone number. Must be 6 characters. Example: <code>123456</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-add-email">POST api/v1/auth/add-email</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-auth-add-email">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/auth/add-email" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"user@example.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/add-email"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "user@example.com"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/add-email';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'email' =&gt; 'user@example.com',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/add-email'
payload = {
    "email": "user@example.com"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-add-email">
            <blockquote>
            <p>Example response (200, Email added successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-add-email" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-add-email"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-add-email"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-add-email" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-add-email">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-add-email" data-method="POST"
      data-path="api/v1/auth/add-email"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-add-email', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-add-email"
                    onclick="tryItOut('POSTapi-v1-auth-add-email');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-add-email"
                    onclick="cancelTryOut('POSTapi-v1-auth-add-email');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-add-email"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/add-email</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-add-email"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-add-email"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-add-email"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-auth-add-email"
               value="user@example.com"
               data-component="body">
    <br>
<p>User's email address. Must be a valid, unique email. Must be a valid email address. Must not be greater than 255 characters. Example: <code>user@example.com</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-select-role">POST api/v1/auth/select-role</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-auth-select-role">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/auth/select-role" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"role\": \"rider\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/select-role"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "role": "rider"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/select-role';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'role' =&gt; 'rider',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/select-role'
payload = {
    "role": "rider"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-select-role">
            <blockquote>
            <p>Example response (200, Role selected successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-select-role" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-select-role"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-select-role"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-select-role" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-select-role">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-select-role" data-method="POST"
      data-path="api/v1/auth/select-role"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-select-role', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-select-role"
                    onclick="tryItOut('POSTapi-v1-auth-select-role');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-select-role"
                    onclick="cancelTryOut('POSTapi-v1-auth-select-role');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-select-role"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/select-role</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-select-role"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-select-role"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-select-role"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="POSTapi-v1-auth-select-role"
               value="rider"
               data-component="body">
    <br>
<p>The user role. Allowed values: rider, driver. Example: <code>rider</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>rider</code></li> <li><code>driver</code></li></ul>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-complete-profile">POST api/v1/auth/complete-profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-auth-complete-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/auth/complete-profile" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"John\",
    \"last_name\": \"Doe\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/complete-profile"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "John",
    "last_name": "Doe"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/complete-profile';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'first_name' =&gt; 'John',
            'last_name' =&gt; 'Doe',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/complete-profile'
payload = {
    "first_name": "John",
    "last_name": "Doe"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-complete-profile">
            <blockquote>
            <p>Example response (200, Profile completed successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors or email not verified.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-complete-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-complete-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-complete-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-complete-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-complete-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-complete-profile" data-method="POST"
      data-path="api/v1/auth/complete-profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-complete-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-complete-profile"
                    onclick="tryItOut('POSTapi-v1-auth-complete-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-complete-profile"
                    onclick="cancelTryOut('POSTapi-v1-auth-complete-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-complete-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/complete-profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-complete-profile"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-complete-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-complete-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="POSTapi-v1-auth-complete-profile"
               value="John"
               data-component="body">
    <br>
<p>First name of the user. Must not be greater than 255 characters. Example: <code>John</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="POSTapi-v1-auth-complete-profile"
               value="Doe"
               data-component="body">
    <br>
<p>Last name of the user. Must not be greater than 255 characters. Example: <code>Doe</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-logout">POST api/v1/auth/logout</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/auth/logout" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/logout"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/logout';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/logout'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-logout">
            <blockquote>
            <p>Example response (200, Logged out successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-logout" data-method="POST"
      data-path="api/v1/auth/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-logout"
                    onclick="tryItOut('POSTapi-v1-auth-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-logout"
                    onclick="cancelTryOut('POSTapi-v1-auth-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-logout"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="auth-POSTapi-v1-auth-email-verification-notification">POST api/v1/auth/email/verification-notification</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-auth-email-verification-notification">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/auth/email/verification-notification" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/email/verification-notification"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/email/verification-notification';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/email/verification-notification'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-email-verification-notification">
            <blockquote>
            <p>Example response (200, Verification link sent.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (200, Email is already verified.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-email-verification-notification" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-email-verification-notification"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-email-verification-notification"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-email-verification-notification" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-email-verification-notification">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-email-verification-notification" data-method="POST"
      data-path="api/v1/auth/email/verification-notification"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-email-verification-notification', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-email-verification-notification"
                    onclick="tryItOut('POSTapi-v1-auth-email-verification-notification');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-email-verification-notification"
                    onclick="cancelTryOut('POSTapi-v1-auth-email-verification-notification');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-email-verification-notification"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/email/verification-notification</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-email-verification-notification"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-email-verification-notification"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-email-verification-notification"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="auth-GETapi-v1-auth-email-verify--user_id---hash-">GET api/v1/auth/email/verify/{user_id}/{hash}</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-auth-email-verify--user_id---hash-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/auth/email/verify/architecto/922815DfBeA128EaEAaaA185508FE078BecEfbbe" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/email/verify/architecto/922815DfBeA128EaEAaaA185508FE078BecEfbbe"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/email/verify/architecto/922815DfBeA128EaEAaaA185508FE078BecEfbbe';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/email/verify/architecto/922815DfBeA128EaEAaaA185508FE078BecEfbbe'
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-auth-email-verify--user_id---hash-">
            <blockquote>
            <p>Example response (200, Email verified successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (200, Email already verified.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-auth-email-verify--user_id---hash-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-auth-email-verify--user_id---hash-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-email-verify--user_id---hash-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-auth-email-verify--user_id---hash-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-email-verify--user_id---hash-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-auth-email-verify--user_id---hash-" data-method="GET"
      data-path="api/v1/auth/email/verify/{user_id}/{hash}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-email-verify--user_id---hash-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-auth-email-verify--user_id---hash-"
                    onclick="tryItOut('GETapi-v1-auth-email-verify--user_id---hash-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-auth-email-verify--user_id---hash-"
                    onclick="cancelTryOut('GETapi-v1-auth-email-verify--user_id---hash-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-auth-email-verify--user_id---hash-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/auth/email/verify/{user_id}/{hash}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-auth-email-verify--user_id---hash-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-auth-email-verify--user_id---hash-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="user_id"                data-endpoint="GETapi-v1-auth-email-verify--user_id---hash-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>hash</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="hash"                data-endpoint="GETapi-v1-auth-email-verify--user_id---hash-"
               value="922815DfBeA128EaEAaaA185508FE078BecEfbbe"
               data-component="url">
    <br>
<p>Example: <code>922815DfBeA128EaEAaaA185508FE078BecEfbbe</code></p>
            </div>
                    </form>

                    <h2 id="auth-DELETEapi-v1-auth-delete-account">DELETE api/v1/auth/delete-account</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-auth-delete-account">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8080/api/v1/auth/delete-account" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/auth/delete-account"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/auth/delete-account';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/auth/delete-account'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('DELETE', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-auth-delete-account">
            <blockquote>
            <p>Example response (200, Account deleted successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-auth-delete-account" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-auth-delete-account"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-auth-delete-account"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-auth-delete-account" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-auth-delete-account">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-auth-delete-account" data-method="DELETE"
      data-path="api/v1/auth/delete-account"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-auth-delete-account', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-auth-delete-account"
                    onclick="tryItOut('DELETEapi-v1-auth-delete-account');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-auth-delete-account"
                    onclick="cancelTryOut('DELETEapi-v1-auth-delete-account');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-auth-delete-account"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/auth/delete-account</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-auth-delete-account"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-auth-delete-account"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-auth-delete-account"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="driver">Driver</h1>

    

                                <h2 id="driver-GETapi-v1-driver-profile">GET api/v1/driver/profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-driver-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/driver/profile" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/profile"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/profile';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/profile'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-driver-profile">
            <blockquote>
            <p>Example response (200, Driver profile retrieved successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-driver-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-driver-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-driver-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-driver-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-driver-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-driver-profile" data-method="GET"
      data-path="api/v1/driver/profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-driver-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-driver-profile"
                    onclick="tryItOut('GETapi-v1-driver-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-driver-profile"
                    onclick="cancelTryOut('GETapi-v1-driver-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-driver-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/driver/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-driver-profile"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-driver-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-driver-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="driver-PUTapi-v1-driver-profile">PUT api/v1/driver/profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-driver-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8080/api/v1/driver/profile" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"John\",
    \"last_name\": \"Doe\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/profile"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "John",
    "last_name": "Doe"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/profile';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'first_name' =&gt; 'John',
            'last_name' =&gt; 'Doe',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/profile'
payload = {
    "first_name": "John",
    "last_name": "Doe"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-driver-profile">
            <blockquote>
            <p>Example response (200, Driver profile updated successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-driver-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-driver-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-driver-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-driver-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-driver-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-driver-profile" data-method="PUT"
      data-path="api/v1/driver/profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-driver-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-driver-profile"
                    onclick="tryItOut('PUTapi-v1-driver-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-driver-profile"
                    onclick="cancelTryOut('PUTapi-v1-driver-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-driver-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/driver/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-driver-profile"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-driver-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-driver-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="PUTapi-v1-driver-profile"
               value="John"
               data-component="body">
    <br>
<p>First name of the user. Must not be greater than 255 characters. Example: <code>John</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="PUTapi-v1-driver-profile"
               value="Doe"
               data-component="body">
    <br>
<p>Last name of the user. Must not be greater than 255 characters. Example: <code>Doe</code></p>
        </div>
        </form>

                    <h2 id="driver-POSTapi-v1-driver-online">POST api/v1/driver/online</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-online">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/online" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"latitude\": 47.0105,
    \"longitude\": 28.8638
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/online"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "latitude": 47.0105,
    "longitude": 28.8638
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/online';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'latitude' =&gt; 47.0105,
            'longitude' =&gt; 28.8638,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/online'
payload = {
    "latitude": 47.0105,
    "longitude": 28.8638
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-online">
            <blockquote>
            <p>Example response (200, Driver is now online.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-online" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-online"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-online"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-online" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-online">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-online" data-method="POST"
      data-path="api/v1/driver/online"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-online', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-online"
                    onclick="tryItOut('POSTapi-v1-driver-online');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-online"
                    onclick="cancelTryOut('POSTapi-v1-driver-online');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-online"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/online</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-online"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-online"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-online"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="POSTapi-v1-driver-online"
               value="47.0105"
               data-component="body">
    <br>
<p>The latitude of the driver's current location. Must be between -90 and 90. Example: <code>47.0105</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="POSTapi-v1-driver-online"
               value="28.8638"
               data-component="body">
    <br>
<p>The longitude of the driver's current location. Must be between -180 and 180. Example: <code>28.8638</code></p>
        </div>
        </form>

                    <h2 id="driver-POSTapi-v1-driver-offline">POST api/v1/driver/offline</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-offline">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/offline" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/offline"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/offline';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/offline'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-offline">
            <blockquote>
            <p>Example response (200, Driver is now offline.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-offline" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-offline"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-offline"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-offline" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-offline">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-offline" data-method="POST"
      data-path="api/v1/driver/offline"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-offline', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-offline"
                    onclick="tryItOut('POSTapi-v1-driver-offline');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-offline"
                    onclick="cancelTryOut('POSTapi-v1-driver-offline');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-offline"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/offline</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-offline"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-offline"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-offline"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="driver-GETapi-v1-driver-rides-active">GET api/v1/driver/rides/active</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-driver-rides-active">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/driver/rides/active" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/rides/active"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/rides/active';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/rides/active'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-driver-rides-active">
            <blockquote>
            <p>Example response (200, Active ride retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (200, No active ride found.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden. Profile step isn&#039;t completed.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-driver-rides-active" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-driver-rides-active"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-driver-rides-active"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-driver-rides-active" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-driver-rides-active">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-driver-rides-active" data-method="GET"
      data-path="api/v1/driver/rides/active"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-driver-rides-active', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-driver-rides-active"
                    onclick="tryItOut('GETapi-v1-driver-rides-active');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-driver-rides-active"
                    onclick="cancelTryOut('GETapi-v1-driver-rides-active');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-driver-rides-active"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/driver/rides/active</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-driver-rides-active"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-driver-rides-active"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-driver-rides-active"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="driver-GETapi-v1-driver-rides-available">GET api/v1/driver/rides/available</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-driver-rides-available">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/driver/rides/available?per_page=15&amp;page=1" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/rides/available"
);

const params = {
    "per_page": "15",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/rides/available';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'per_page' =&gt; '15',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/rides/available'
params = {
  'per_page': '15',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-driver-rides-available">
            <blockquote>
            <p>Example response (200, List of available rides retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-driver-rides-available" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-driver-rides-available"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-driver-rides-available"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-driver-rides-available" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-driver-rides-available">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-driver-rides-available" data-method="GET"
      data-path="api/v1/driver/rides/available"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-driver-rides-available', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-driver-rides-available"
                    onclick="tryItOut('GETapi-v1-driver-rides-available');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-driver-rides-available"
                    onclick="cancelTryOut('GETapi-v1-driver-rides-available');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-driver-rides-available"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/driver/rides/available</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-driver-rides-available"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-driver-rides-available"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-driver-rides-available"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-driver-rides-available"
               value="15"
               data-component="query">
    <br>
<p>Page size. Default: 15. Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-driver-rides-available"
               value="1"
               data-component="query">
    <br>
<p>Page number. Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="driver-POSTapi-v1-driver-rides--ride_id--accept">POST api/v1/driver/rides/{ride_id}/accept</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-rides--ride_id--accept">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/rides/architecto/accept" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/rides/architecto/accept"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/rides/architecto/accept';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/rides/architecto/accept'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-rides--ride_id--accept">
            <blockquote>
            <p>Example response (200, Ride accepted successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-rides--ride_id--accept" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-rides--ride_id--accept"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-rides--ride_id--accept"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-rides--ride_id--accept" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-rides--ride_id--accept">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-rides--ride_id--accept" data-method="POST"
      data-path="api/v1/driver/rides/{ride_id}/accept"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-rides--ride_id--accept', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-rides--ride_id--accept"
                    onclick="tryItOut('POSTapi-v1-driver-rides--ride_id--accept');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-rides--ride_id--accept"
                    onclick="cancelTryOut('POSTapi-v1-driver-rides--ride_id--accept');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-rides--ride_id--accept"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/rides/{ride_id}/accept</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-rides--ride_id--accept"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-rides--ride_id--accept"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-rides--ride_id--accept"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride_id"                data-endpoint="POSTapi-v1-driver-rides--ride_id--accept"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride"                data-endpoint="POSTapi-v1-driver-rides--ride_id--accept"
               value="01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z"
               data-component="url">
    <br>
<p>ULID of the ride. Example: <code>01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z</code></p>
            </div>
                    </form>

                    <h2 id="driver-POSTapi-v1-driver-rides--ride_id--on-the-way">POST api/v1/driver/rides/{ride_id}/on-the-way</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-rides--ride_id--on-the-way">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/rides/architecto/on-the-way" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/rides/architecto/on-the-way"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/rides/architecto/on-the-way';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/rides/architecto/on-the-way'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-rides--ride_id--on-the-way">
            <blockquote>
            <p>Example response (200, Driver is on the way to pickup location.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-rides--ride_id--on-the-way" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-rides--ride_id--on-the-way"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-rides--ride_id--on-the-way"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-rides--ride_id--on-the-way" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-rides--ride_id--on-the-way">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-rides--ride_id--on-the-way" data-method="POST"
      data-path="api/v1/driver/rides/{ride_id}/on-the-way"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-rides--ride_id--on-the-way', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-rides--ride_id--on-the-way"
                    onclick="tryItOut('POSTapi-v1-driver-rides--ride_id--on-the-way');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-rides--ride_id--on-the-way"
                    onclick="cancelTryOut('POSTapi-v1-driver-rides--ride_id--on-the-way');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-rides--ride_id--on-the-way"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/rides/{ride_id}/on-the-way</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-rides--ride_id--on-the-way"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-rides--ride_id--on-the-way"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-rides--ride_id--on-the-way"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride_id"                data-endpoint="POSTapi-v1-driver-rides--ride_id--on-the-way"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride"                data-endpoint="POSTapi-v1-driver-rides--ride_id--on-the-way"
               value="01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z"
               data-component="url">
    <br>
<p>ULID of the ride. Example: <code>01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z</code></p>
            </div>
                    </form>

                    <h2 id="driver-POSTapi-v1-driver-rides--ride_id--arrived">POST api/v1/driver/rides/{ride_id}/arrived</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-rides--ride_id--arrived">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/rides/architecto/arrived" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/rides/architecto/arrived"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/rides/architecto/arrived';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/rides/architecto/arrived'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-rides--ride_id--arrived">
            <blockquote>
            <p>Example response (200, Driver has arrived at pickup location.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-rides--ride_id--arrived" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-rides--ride_id--arrived"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-rides--ride_id--arrived"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-rides--ride_id--arrived" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-rides--ride_id--arrived">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-rides--ride_id--arrived" data-method="POST"
      data-path="api/v1/driver/rides/{ride_id}/arrived"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-rides--ride_id--arrived', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-rides--ride_id--arrived"
                    onclick="tryItOut('POSTapi-v1-driver-rides--ride_id--arrived');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-rides--ride_id--arrived"
                    onclick="cancelTryOut('POSTapi-v1-driver-rides--ride_id--arrived');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-rides--ride_id--arrived"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/rides/{ride_id}/arrived</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-rides--ride_id--arrived"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-rides--ride_id--arrived"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-rides--ride_id--arrived"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride_id"                data-endpoint="POSTapi-v1-driver-rides--ride_id--arrived"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride"                data-endpoint="POSTapi-v1-driver-rides--ride_id--arrived"
               value="01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z"
               data-component="url">
    <br>
<p>ULID of the ride. Example: <code>01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z</code></p>
            </div>
                    </form>

                    <h2 id="driver-POSTapi-v1-driver-rides--ride_id--start">POST api/v1/driver/rides/{ride_id}/start</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-rides--ride_id--start">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/rides/architecto/start" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/rides/architecto/start"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/rides/architecto/start';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/rides/architecto/start'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-rides--ride_id--start">
            <blockquote>
            <p>Example response (200, Ride has started.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-rides--ride_id--start" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-rides--ride_id--start"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-rides--ride_id--start"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-rides--ride_id--start" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-rides--ride_id--start">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-rides--ride_id--start" data-method="POST"
      data-path="api/v1/driver/rides/{ride_id}/start"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-rides--ride_id--start', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-rides--ride_id--start"
                    onclick="tryItOut('POSTapi-v1-driver-rides--ride_id--start');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-rides--ride_id--start"
                    onclick="cancelTryOut('POSTapi-v1-driver-rides--ride_id--start');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-rides--ride_id--start"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/rides/{ride_id}/start</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-rides--ride_id--start"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-rides--ride_id--start"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-rides--ride_id--start"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride_id"                data-endpoint="POSTapi-v1-driver-rides--ride_id--start"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride"                data-endpoint="POSTapi-v1-driver-rides--ride_id--start"
               value="01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z"
               data-component="url">
    <br>
<p>ULID of the ride. Example: <code>01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z</code></p>
            </div>
                    </form>

                    <h2 id="driver-POSTapi-v1-driver-rides--ride_id--complete">POST api/v1/driver/rides/{ride_id}/complete</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-rides--ride_id--complete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/rides/architecto/complete" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/rides/architecto/complete"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/rides/architecto/complete';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/rides/architecto/complete'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-rides--ride_id--complete">
            <blockquote>
            <p>Example response (200, Ride completed successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-rides--ride_id--complete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-rides--ride_id--complete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-rides--ride_id--complete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-rides--ride_id--complete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-rides--ride_id--complete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-rides--ride_id--complete" data-method="POST"
      data-path="api/v1/driver/rides/{ride_id}/complete"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-rides--ride_id--complete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-rides--ride_id--complete"
                    onclick="tryItOut('POSTapi-v1-driver-rides--ride_id--complete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-rides--ride_id--complete"
                    onclick="cancelTryOut('POSTapi-v1-driver-rides--ride_id--complete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-rides--ride_id--complete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/rides/{ride_id}/complete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-rides--ride_id--complete"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-rides--ride_id--complete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-rides--ride_id--complete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride_id"                data-endpoint="POSTapi-v1-driver-rides--ride_id--complete"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride"                data-endpoint="POSTapi-v1-driver-rides--ride_id--complete"
               value="01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z"
               data-component="url">
    <br>
<p>ULID of the ride. Example: <code>01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z</code></p>
            </div>
                    </form>

                    <h2 id="driver-POSTapi-v1-driver-rides--ride_id--cancel">POST api/v1/driver/rides/{ride_id}/cancel</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-rides--ride_id--cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/rides/architecto/cancel" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/rides/architecto/cancel"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/rides/architecto/cancel';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/rides/architecto/cancel'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-rides--ride_id--cancel">
            <blockquote>
            <p>Example response (200, Ride cancelled successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Ride not found.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Ride cannot be cancelled in its current status.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-rides--ride_id--cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-rides--ride_id--cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-rides--ride_id--cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-rides--ride_id--cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-rides--ride_id--cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-rides--ride_id--cancel" data-method="POST"
      data-path="api/v1/driver/rides/{ride_id}/cancel"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-rides--ride_id--cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-rides--ride_id--cancel"
                    onclick="tryItOut('POSTapi-v1-driver-rides--ride_id--cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-rides--ride_id--cancel"
                    onclick="cancelTryOut('POSTapi-v1-driver-rides--ride_id--cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-rides--ride_id--cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/rides/{ride_id}/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-rides--ride_id--cancel"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-rides--ride_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-rides--ride_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride_id"                data-endpoint="POSTapi-v1-driver-rides--ride_id--cancel"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride"                data-endpoint="POSTapi-v1-driver-rides--ride_id--cancel"
               value="01jk9v6v9v6v9v6v9v6v9v6v9v"
               data-component="url">
    <br>
<p>ULID of the ride. Example: <code>01jk9v6v9v6v9v6v9v6v9v6v9v</code></p>
            </div>
                    </form>

                    <h2 id="driver-POSTapi-v1-driver-location">POST api/v1/driver/location</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-location">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/location" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"lat\": 55.751244,
    \"lng\": 37.618423
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/location"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "lat": 55.751244,
    "lng": 37.618423
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/location';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'lat' =&gt; 55.751244,
            'lng' =&gt; 37.618423,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/location'
payload = {
    "lat": 55.751244,
    "lng": 37.618423
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-location">
            <blockquote>
            <p>Example response (200, Driver location updated successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden. Profile step isn&#039;t completed.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-location" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-location"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-location"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-location" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-location">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-location" data-method="POST"
      data-path="api/v1/driver/location"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-location', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-location"
                    onclick="tryItOut('POSTapi-v1-driver-location');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-location"
                    onclick="cancelTryOut('POSTapi-v1-driver-location');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-location"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/location</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-location"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-location"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-location"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lat</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="lat"                data-endpoint="POSTapi-v1-driver-location"
               value="55.751244"
               data-component="body">
    <br>
<p>Latitude. Must be between -90 and 90. Example: <code>55.751244</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lng</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="lng"                data-endpoint="POSTapi-v1-driver-location"
               value="37.618423"
               data-component="body">
    <br>
<p>Longitude. Must be between -180 and 180. Example: <code>37.618423</code></p>
        </div>
        </form>

                    <h2 id="driver-POSTapi-v1-driver-avatar">POST api/v1/driver/avatar</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-avatar">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/avatar" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "avatar=@/tmp/phpaomqkvaespjmfFoobck" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/avatar"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('avatar', document.querySelector('input[name="avatar"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/avatar';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'avatar',
                'contents' =&gt; fopen('/tmp/phpaomqkvaespjmfFoobck', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/avatar'
files = {
  'avatar': open('/tmp/phpaomqkvaespjmfFoobck', 'rb')}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'multipart/form-data',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, files=files)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-avatar">
            <blockquote>
            <p>Example response (200, Avatar upload processing started.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-avatar" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-avatar"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-avatar"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-avatar" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-avatar">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-avatar" data-method="POST"
      data-path="api/v1/driver/avatar"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-avatar', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-avatar"
                    onclick="tryItOut('POSTapi-v1-driver-avatar');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-avatar"
                    onclick="cancelTryOut('POSTapi-v1-driver-avatar');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-avatar"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/avatar</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-avatar"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-avatar"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-avatar"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>avatar</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="avatar"                data-endpoint="POSTapi-v1-driver-avatar"
               value=""
               data-component="body">
    <br>
<p>Avatar image (jpg, png, webp, max 2MB). Must be an image. Must not be greater than 2048 kilobytes. Example: <code>/tmp/phpaomqkvaespjmfFoobck</code></p>
        </div>
        </form>

                    <h2 id="driver-GETapi-v1-driver-vehicles">GET api/v1/driver/vehicles</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-driver-vehicles">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/driver/vehicles" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/vehicles"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/vehicles';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/vehicles'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-driver-vehicles">
            <blockquote>
            <p>Example response (200, List of driver vehicles):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-driver-vehicles" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-driver-vehicles"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-driver-vehicles"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-driver-vehicles" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-driver-vehicles">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-driver-vehicles" data-method="GET"
      data-path="api/v1/driver/vehicles"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-driver-vehicles', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-driver-vehicles"
                    onclick="tryItOut('GETapi-v1-driver-vehicles');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-driver-vehicles"
                    onclick="cancelTryOut('GETapi-v1-driver-vehicles');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-driver-vehicles"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/driver/vehicles</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-driver-vehicles"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-driver-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-driver-vehicles"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="driver-GETapi-v1-driver-vehicle--vehicle_id-">GET api/v1/driver/vehicle/{vehicle_id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-driver-vehicle--vehicle_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/driver/vehicle/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/vehicle/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/vehicle/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/vehicle/architecto'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-driver-vehicle--vehicle_id-">
            <blockquote>
            <p>Example response (200, Vehicle retrieved successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Vehicle not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-driver-vehicle--vehicle_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-driver-vehicle--vehicle_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-driver-vehicle--vehicle_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-driver-vehicle--vehicle_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-driver-vehicle--vehicle_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-driver-vehicle--vehicle_id-" data-method="GET"
      data-path="api/v1/driver/vehicle/{vehicle_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-driver-vehicle--vehicle_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-driver-vehicle--vehicle_id-"
                    onclick="tryItOut('GETapi-v1-driver-vehicle--vehicle_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-driver-vehicle--vehicle_id-"
                    onclick="cancelTryOut('GETapi-v1-driver-vehicle--vehicle_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-driver-vehicle--vehicle_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/driver/vehicle/{vehicle_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-driver-vehicle--vehicle_id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-driver-vehicle--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-driver-vehicle--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="vehicle_id"                data-endpoint="GETapi-v1-driver-vehicle--vehicle_id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="driver-POSTapi-v1-driver-vehicle">POST api/v1/driver/vehicle</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-driver-vehicle">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/driver/vehicle" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"brand\": \"Toyota\",
    \"model\": \"Camry\",
    \"year\": 2022,
    \"color\": \"Black\",
    \"plate_number\": \"AA1234BB\",
    \"vehicle_type\": \"sedan\",
    \"seats\": 4
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/vehicle"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "brand": "Toyota",
    "model": "Camry",
    "year": 2022,
    "color": "Black",
    "plate_number": "AA1234BB",
    "vehicle_type": "sedan",
    "seats": 4
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/vehicle';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'brand' =&gt; 'Toyota',
            'model' =&gt; 'Camry',
            'year' =&gt; 2022,
            'color' =&gt; 'Black',
            'plate_number' =&gt; 'AA1234BB',
            'vehicle_type' =&gt; 'sedan',
            'seats' =&gt; 4,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/vehicle'
payload = {
    "brand": "Toyota",
    "model": "Camry",
    "year": 2022,
    "color": "Black",
    "plate_number": "AA1234BB",
    "vehicle_type": "sedan",
    "seats": 4
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-driver-vehicle">
            <blockquote>
            <p>Example response (201, Vehicle added successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-driver-vehicle" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-driver-vehicle"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-driver-vehicle"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-driver-vehicle" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-driver-vehicle">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-driver-vehicle" data-method="POST"
      data-path="api/v1/driver/vehicle"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-driver-vehicle', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-driver-vehicle"
                    onclick="tryItOut('POSTapi-v1-driver-vehicle');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-driver-vehicle"
                    onclick="cancelTryOut('POSTapi-v1-driver-vehicle');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-driver-vehicle"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/driver/vehicle</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-driver-vehicle"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>brand</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="brand"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="Toyota"
               data-component="body">
    <br>
<p>The brand of the vehicle. Must not be greater than 255 characters. Example: <code>Toyota</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="Camry"
               data-component="body">
    <br>
<p>The model of the vehicle. Must not be greater than 255 characters. Example: <code>Camry</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="2022"
               data-component="body">
    <br>
<p>The manufacturing year of the vehicle. Must be between 1990 and 2026. Example: <code>2022</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>color</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="color"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="Black"
               data-component="body">
    <br>
<p>The color of the vehicle. Must not be greater than 255 characters. Example: <code>Black</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plate_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plate_number"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="AA1234BB"
               data-component="body">
    <br>
<p>The license plate number of the vehicle. Must not be greater than 20 characters. Example: <code>AA1234BB</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="vehicle_type"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="sedan"
               data-component="body">
    <br>
<p>The type of the vehicle. Example: <code>sedan</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>sedan</code></li> <li><code>suv</code></li> <li><code>minivan</code></li> <li><code>hatchback</code></li> <li><code>coupe</code></li> <li><code>pickup</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>seats</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="seats"                data-endpoint="POSTapi-v1-driver-vehicle"
               value="4"
               data-component="body">
    <br>
<p>The number of seats in the vehicle. Must be between 1 and 20. Example: <code>4</code></p>
        </div>
        </form>

                    <h2 id="driver-PUTapi-v1-driver-vehicle--id-">PUT api/v1/driver/vehicle/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-driver-vehicle--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8080/api/v1/driver/vehicle/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"brand\": \"Toyota\",
    \"model\": \"Camry\",
    \"year\": 2022,
    \"color\": \"Black\",
    \"plate_number\": \"AA1234BB\",
    \"vehicle_type\": \"sedan\",
    \"seats\": 4
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/vehicle/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "brand": "Toyota",
    "model": "Camry",
    "year": 2022,
    "color": "Black",
    "plate_number": "AA1234BB",
    "vehicle_type": "sedan",
    "seats": 4
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/vehicle/architecto';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'brand' =&gt; 'Toyota',
            'model' =&gt; 'Camry',
            'year' =&gt; 2022,
            'color' =&gt; 'Black',
            'plate_number' =&gt; 'AA1234BB',
            'vehicle_type' =&gt; 'sedan',
            'seats' =&gt; 4,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/vehicle/architecto'
payload = {
    "brand": "Toyota",
    "model": "Camry",
    "year": 2022,
    "color": "Black",
    "plate_number": "AA1234BB",
    "vehicle_type": "sedan",
    "seats": 4
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-driver-vehicle--id-">
            <blockquote>
            <p>Example response (200, Vehicle updated successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Vehicle not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-driver-vehicle--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-driver-vehicle--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-driver-vehicle--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-driver-vehicle--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-driver-vehicle--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-driver-vehicle--id-" data-method="PUT"
      data-path="api/v1/driver/vehicle/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-driver-vehicle--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-driver-vehicle--id-"
                    onclick="tryItOut('PUTapi-v1-driver-vehicle--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-driver-vehicle--id-"
                    onclick="cancelTryOut('PUTapi-v1-driver-vehicle--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-driver-vehicle--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/driver/vehicle/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>brand</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="brand"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="Toyota"
               data-component="body">
    <br>
<p>The brand of the vehicle. Must not be greater than 255 characters. Example: <code>Toyota</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>model</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="model"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="Camry"
               data-component="body">
    <br>
<p>The model of the vehicle. Must not be greater than 255 characters. Example: <code>Camry</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="2022"
               data-component="body">
    <br>
<p>The manufacturing year of the vehicle. Must be between 1990 and 2026. Example: <code>2022</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>color</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="color"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="Black"
               data-component="body">
    <br>
<p>The color of the vehicle. Must not be greater than 255 characters. Example: <code>Black</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plate_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plate_number"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="AA1234BB"
               data-component="body">
    <br>
<p>The license plate number of the vehicle. Must not be greater than 20 characters. Example: <code>AA1234BB</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>vehicle_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="vehicle_type"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="sedan"
               data-component="body">
    <br>
<p>The type of the vehicle. Example: <code>sedan</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>sedan</code></li> <li><code>suv</code></li> <li><code>minivan</code></li> <li><code>hatchback</code></li> <li><code>coupe</code></li> <li><code>pickup</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>seats</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="seats"                data-endpoint="PUTapi-v1-driver-vehicle--id-"
               value="4"
               data-component="body">
    <br>
<p>The number of seats in the vehicle. Must be between 1 and 20. Example: <code>4</code></p>
        </div>
        </form>

                    <h2 id="driver-DELETEapi-v1-driver-vehicle--vehicle_id-">DELETE api/v1/driver/vehicle/{vehicle_id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-driver-vehicle--vehicle_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8080/api/v1/driver/vehicle/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/driver/vehicle/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/driver/vehicle/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/driver/vehicle/architecto'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('DELETE', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-driver-vehicle--vehicle_id-">
            <blockquote>
            <p>Example response (200, Vehicle deleted successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Vehicle not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-driver-vehicle--vehicle_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-driver-vehicle--vehicle_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-driver-vehicle--vehicle_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-driver-vehicle--vehicle_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-driver-vehicle--vehicle_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-driver-vehicle--vehicle_id-" data-method="DELETE"
      data-path="api/v1/driver/vehicle/{vehicle_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-driver-vehicle--vehicle_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-driver-vehicle--vehicle_id-"
                    onclick="tryItOut('DELETEapi-v1-driver-vehicle--vehicle_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-driver-vehicle--vehicle_id-"
                    onclick="cancelTryOut('DELETEapi-v1-driver-vehicle--vehicle_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-driver-vehicle--vehicle_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/driver/vehicle/{vehicle_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-driver-vehicle--vehicle_id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-driver-vehicle--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-driver-vehicle--vehicle_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>vehicle_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="vehicle_id"                data-endpoint="DELETEapi-v1-driver-vehicle--vehicle_id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the vehicle. Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="profile">Profile</h1>

    

                                <h2 id="profile-POSTapi-v1-profile-locale">POST api/v1/profile/locale</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-profile-locale">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/profile/locale" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"locale\": \"ro\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/profile/locale"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "locale": "ro"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/profile/locale';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'locale' =&gt; 'ro',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/profile/locale'
payload = {
    "locale": "ro"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-profile-locale">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Locale updated successfully.&quot;,
    &quot;locale&quot;: &quot;en&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (400, Invalid locale value.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-profile-locale" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-profile-locale"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-profile-locale"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-profile-locale" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-profile-locale">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-profile-locale" data-method="POST"
      data-path="api/v1/profile/locale"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-profile-locale', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-profile-locale"
                    onclick="tryItOut('POSTapi-v1-profile-locale');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-profile-locale"
                    onclick="cancelTryOut('POSTapi-v1-profile-locale');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-profile-locale"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/profile/locale</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-profile-locale"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-profile-locale"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-profile-locale"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="POSTapi-v1-profile-locale"
               value="ro"
               data-component="body">
    <br>
<p>User preferred locale. Supported values: ro (Romanian), ru (Russian), en (English). Example: <code>ro</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>ro</code></li> <li><code>ru</code></li> <li><code>en</code></li></ul>
        </div>
        </form>

                <h1 id="rider">Rider</h1>

    

                                <h2 id="rider-GETapi-v1-rider-locations-search">GET api/v1/rider/locations/search</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-rider-locations-search">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/rider/locations/search?query=Stefan+cel+Mare&amp;lat=47.0105&amp;lng=28.8638&amp;limit=5" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/locations/search"
);

const params = {
    "query": "Stefan cel Mare",
    "lat": "47.0105",
    "lng": "28.8638",
    "limit": "5",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/locations/search';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'query' =&gt; 'Stefan cel Mare',
            'lat' =&gt; '47.0105',
            'lng' =&gt; '28.8638',
            'limit' =&gt; '5',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/locations/search'
params = {
  'query': 'Stefan cel Mare',
  'lat': '47.0105',
  'lng': '28.8638',
  'limit': '5',
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rider-locations-search">
            <blockquote>
            <p>Example response (200, Locations found successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rider-locations-search" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rider-locations-search"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rider-locations-search"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rider-locations-search" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rider-locations-search">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rider-locations-search" data-method="GET"
      data-path="api/v1/rider/locations/search"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rider-locations-search', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rider-locations-search"
                    onclick="tryItOut('GETapi-v1-rider-locations-search');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rider-locations-search"
                    onclick="cancelTryOut('GETapi-v1-rider-locations-search');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rider-locations-search"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rider/locations/search</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rider-locations-search"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rider-locations-search"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rider-locations-search"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>query</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="query"                data-endpoint="GETapi-v1-rider-locations-search"
               value="Stefan cel Mare"
               data-component="query">
    <br>
<p>Search query (address, place name) Example: <code>Stefan cel Mare</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>lat</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="lat"                data-endpoint="GETapi-v1-rider-locations-search"
               value="47.0105"
               data-component="query">
    <br>
<p>User current latitude (for better results) Example: <code>47.0105</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>lng</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="lng"                data-endpoint="GETapi-v1-rider-locations-search"
               value="28.8638"
               data-component="query">
    <br>
<p>User current longitude (for better results) Example: <code>28.8638</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="limit"                data-endpoint="GETapi-v1-rider-locations-search"
               value="5"
               data-component="query">
    <br>
<p>Maximum number of results Example: <code>5</code></p>
            </div>
                </form>

                    <h2 id="rider-GETapi-v1-rider-favorites">GET api/v1/rider/favorites</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-rider-favorites">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/rider/favorites" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/favorites"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/favorites';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/favorites'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rider-favorites">
            <blockquote>
            <p>Example response (200, Favorite locations retrieved successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rider-favorites" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rider-favorites"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rider-favorites"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rider-favorites" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rider-favorites">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rider-favorites" data-method="GET"
      data-path="api/v1/rider/favorites"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rider-favorites', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rider-favorites"
                    onclick="tryItOut('GETapi-v1-rider-favorites');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rider-favorites"
                    onclick="cancelTryOut('GETapi-v1-rider-favorites');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rider-favorites"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rider/favorites</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rider-favorites"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rider-favorites"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rider-favorites"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rider-POSTapi-v1-rider-favorites">POST api/v1/rider/favorites</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-rider-favorites">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/rider/favorites" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Home\",
    \"lat\": 47.01,
    \"lng\": 28.863,
    \"address\": \"Strada Stefan cel Mare 123, Chisinau\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/favorites"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Home",
    "lat": 47.01,
    "lng": 28.863,
    "address": "Strada Stefan cel Mare 123, Chisinau"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/favorites';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Home',
            'lat' =&gt; 47.01,
            'lng' =&gt; 28.863,
            'address' =&gt; 'Strada Stefan cel Mare 123, Chisinau',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/favorites'
payload = {
    "name": "Home",
    "lat": 47.01,
    "lng": 28.863,
    "address": "Strada Stefan cel Mare 123, Chisinau"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-rider-favorites">
            <blockquote>
            <p>Example response (200, Favorite location added successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-rider-favorites" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-rider-favorites"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-rider-favorites"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-rider-favorites" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-rider-favorites">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-rider-favorites" data-method="POST"
      data-path="api/v1/rider/favorites"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-rider-favorites', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-rider-favorites"
                    onclick="tryItOut('POSTapi-v1-rider-favorites');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-rider-favorites"
                    onclick="cancelTryOut('POSTapi-v1-rider-favorites');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-rider-favorites"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/rider/favorites</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-rider-favorites"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-rider-favorites"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-rider-favorites"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-rider-favorites"
               value="Home"
               data-component="body">
    <br>
<p>The name of the favorite location. Must not be greater than 50 characters. Example: <code>Home</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lat</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="lat"                data-endpoint="POSTapi-v1-rider-favorites"
               value="47.01"
               data-component="body">
    <br>
<p>The latitude of the location. Must be between -90 and 90. Example: <code>47.01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>lng</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="lng"                data-endpoint="POSTapi-v1-rider-favorites"
               value="28.863"
               data-component="body">
    <br>
<p>The longitude of the location. Must be between -180 and 180. Example: <code>28.863</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-v1-rider-favorites"
               value="Strada Stefan cel Mare 123, Chisinau"
               data-component="body">
    <br>
<p>The address of the favorite location. Must not be greater than 255 characters. Example: <code>Strada Stefan cel Mare 123, Chisinau</code></p>
        </div>
        </form>

                    <h2 id="rider-DELETEapi-v1-rider-favorites--id-">DELETE api/v1/rider/favorites/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-v1-rider-favorites--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8080/api/v1/rider/favorites/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/favorites/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/favorites/architecto';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/favorites/architecto'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('DELETE', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-rider-favorites--id-">
            <blockquote>
            <p>Example response (200, Favorite location deleted successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Can only delete own favorite locations):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Favorite location not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-rider-favorites--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-rider-favorites--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-rider-favorites--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-rider-favorites--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-rider-favorites--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-rider-favorites--id-" data-method="DELETE"
      data-path="api/v1/rider/favorites/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-rider-favorites--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-rider-favorites--id-"
                    onclick="tryItOut('DELETEapi-v1-rider-favorites--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-rider-favorites--id-"
                    onclick="cancelTryOut('DELETEapi-v1-rider-favorites--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-rider-favorites--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/rider/favorites/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-rider-favorites--id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-rider-favorites--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-rider-favorites--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="DELETEapi-v1-rider-favorites--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the favorite. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>favorite</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="favorite"                data-endpoint="DELETEapi-v1-rider-favorites--id-"
               value="01jk9v6v9v6v9v6v9v6v9v6v9v6v9v6v9v6v9"
               data-component="url">
    <br>
<p>ULID of favorite location. Example: <code>01jk9v6v9v6v9v6v9v6v9v6v9v6v9v6v9v6v9</code></p>
            </div>
                    </form>

                    <h2 id="rider-GETapi-v1-rider-profile">GET api/v1/rider/profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-rider-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/rider/profile" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/profile"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/profile';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/profile'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rider-profile">
            <blockquote>
            <p>Example response (200, Rider profile retrieved successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rider-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rider-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rider-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rider-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rider-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rider-profile" data-method="GET"
      data-path="api/v1/rider/profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rider-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rider-profile"
                    onclick="tryItOut('GETapi-v1-rider-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rider-profile"
                    onclick="cancelTryOut('GETapi-v1-rider-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rider-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rider/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rider-profile"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rider-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rider-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rider-PUTapi-v1-rider-profile">PUT api/v1/rider/profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-rider-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8080/api/v1/rider/profile" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"John\",
    \"last_name\": \"Doe\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/profile"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "John",
    "last_name": "Doe"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/profile';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'first_name' =&gt; 'John',
            'last_name' =&gt; 'Doe',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/profile'
payload = {
    "first_name": "John",
    "last_name": "Doe"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-rider-profile">
            <blockquote>
            <p>Example response (200, Rider profile updated successfully):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-rider-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-rider-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-rider-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-rider-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-rider-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-rider-profile" data-method="PUT"
      data-path="api/v1/rider/profile"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-rider-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-rider-profile"
                    onclick="tryItOut('PUTapi-v1-rider-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-rider-profile"
                    onclick="cancelTryOut('PUTapi-v1-rider-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-rider-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/rider/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-rider-profile"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-rider-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-rider-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="first_name"                data-endpoint="PUTapi-v1-rider-profile"
               value="John"
               data-component="body">
    <br>
<p>First name of the user. Must not be greater than 255 characters. Example: <code>John</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>last_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="last_name"                data-endpoint="PUTapi-v1-rider-profile"
               value="Doe"
               data-component="body">
    <br>
<p>Last name of the user. Must not be greater than 255 characters. Example: <code>Doe</code></p>
        </div>
        </form>

                    <h2 id="rider-POSTapi-v1-rider-estimates">POST api/v1/rider/estimates</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-rider-estimates">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/rider/estimates" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"origin_address\": \"bd. Ștefan cel Mare și Sfânt, 1, Chișinău\",
    \"origin_lat\": 47.0105,
    \"origin_lng\": 28.8638,
    \"destination_address\": \"str. Mihai Eminescu, 50, Chișinău\",
    \"destination_lat\": 47.0225,
    \"destination_lng\": 28.8353
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/estimates"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "origin_address": "bd. Ștefan cel Mare și Sfânt, 1, Chișinău",
    "origin_lat": 47.0105,
    "origin_lng": 28.8638,
    "destination_address": "str. Mihai Eminescu, 50, Chișinău",
    "destination_lat": 47.0225,
    "destination_lng": 28.8353
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/estimates';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'origin_address' =&gt; 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
            'origin_lat' =&gt; 47.0105,
            'origin_lng' =&gt; 28.8638,
            'destination_address' =&gt; 'str. Mihai Eminescu, 50, Chișinău',
            'destination_lat' =&gt; 47.0225,
            'destination_lng' =&gt; 28.8353,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/estimates'
payload = {
    "origin_address": "bd. Ștefan cel Mare și Sfânt, 1, Chișinău",
    "origin_lat": 47.0105,
    "origin_lng": 28.8638,
    "destination_address": "str. Mihai Eminescu, 50, Chișinău",
    "destination_lat": 47.0225,
    "destination_lng": 28.8353
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-rider-estimates">
            <blockquote>
            <p>Example response (200, Ride estimate calculated successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Profile not completed or active ride exists.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-rider-estimates" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-rider-estimates"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-rider-estimates"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-rider-estimates" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-rider-estimates">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-rider-estimates" data-method="POST"
      data-path="api/v1/rider/estimates"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-rider-estimates', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-rider-estimates"
                    onclick="tryItOut('POSTapi-v1-rider-estimates');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-rider-estimates"
                    onclick="cancelTryOut('POSTapi-v1-rider-estimates');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-rider-estimates"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/rider/estimates</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-rider-estimates"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-rider-estimates"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-rider-estimates"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>origin_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="origin_address"                data-endpoint="POSTapi-v1-rider-estimates"
               value="bd. Ștefan cel Mare și Sfânt, 1, Chișinău"
               data-component="body">
    <br>
<p>The starting address of the ride. Must not be greater than 255 characters. Example: <code>bd. Ștefan cel Mare și Sfânt, 1, Chișinău</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>origin_lat</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="origin_lat"                data-endpoint="POSTapi-v1-rider-estimates"
               value="47.0105"
               data-component="body">
    <br>
<p>The latitude of the origin. Must be between -90 and 90. Example: <code>47.0105</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>origin_lng</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="origin_lng"                data-endpoint="POSTapi-v1-rider-estimates"
               value="28.8638"
               data-component="body">
    <br>
<p>The longitude of the origin. Must be between -180 and 180. Example: <code>28.8638</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>destination_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="destination_address"                data-endpoint="POSTapi-v1-rider-estimates"
               value="str. Mihai Eminescu, 50, Chișinău"
               data-component="body">
    <br>
<p>The destination address of the ride. Must not be greater than 255 characters. Example: <code>str. Mihai Eminescu, 50, Chișinău</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>destination_lat</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="destination_lat"                data-endpoint="POSTapi-v1-rider-estimates"
               value="47.0225"
               data-component="body">
    <br>
<p>The latitude of the destination. Must be between -90 and 90. Example: <code>47.0225</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>destination_lng</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="destination_lng"                data-endpoint="POSTapi-v1-rider-estimates"
               value="28.8353"
               data-component="body">
    <br>
<p>The longitude of the destination. Must be between -180 and 180. Example: <code>28.8353</code></p>
        </div>
        </form>

                    <h2 id="rider-POSTapi-v1-rider-rides">POST api/v1/rider/rides</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-rider-rides">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/rider/rides" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"origin_address\": \"bd. Ștefan cel Mare și Sfânt, 1, Chișinău\",
    \"origin_lat\": 47.0105,
    \"origin_lng\": 28.8638,
    \"destination_address\": \"str. Mihai Eminescu, 50, Chișinău\",
    \"destination_lat\": 47.0225,
    \"destination_lng\": 28.8353
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/rides"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "origin_address": "bd. Ștefan cel Mare și Sfânt, 1, Chișinău",
    "origin_lat": 47.0105,
    "origin_lng": 28.8638,
    "destination_address": "str. Mihai Eminescu, 50, Chișinău",
    "destination_lat": 47.0225,
    "destination_lng": 28.8353
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/rides';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'origin_address' =&gt; 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
            'origin_lat' =&gt; 47.0105,
            'origin_lng' =&gt; 28.8638,
            'destination_address' =&gt; 'str. Mihai Eminescu, 50, Chișinău',
            'destination_lat' =&gt; 47.0225,
            'destination_lng' =&gt; 28.8353,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/rides'
payload = {
    "origin_address": "bd. Ștefan cel Mare și Sfânt, 1, Chișinău",
    "origin_lat": 47.0105,
    "origin_lng": 28.8638,
    "destination_address": "str. Mihai Eminescu, 50, Chișinău",
    "destination_lat": 47.0225,
    "destination_lng": 28.8353
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-rider-rides">
            <blockquote>
            <p>Example response (201, Ride created successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Profile not completed.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation errors.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-rider-rides" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-rider-rides"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-rider-rides"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-rider-rides" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-rider-rides">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-rider-rides" data-method="POST"
      data-path="api/v1/rider/rides"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-rider-rides', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-rider-rides"
                    onclick="tryItOut('POSTapi-v1-rider-rides');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-rider-rides"
                    onclick="cancelTryOut('POSTapi-v1-rider-rides');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-rider-rides"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/rider/rides</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-rider-rides"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-rider-rides"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-rider-rides"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>origin_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="origin_address"                data-endpoint="POSTapi-v1-rider-rides"
               value="bd. Ștefan cel Mare și Sfânt, 1, Chișinău"
               data-component="body">
    <br>
<p>The starting address of the ride. Must not be greater than 255 characters. Example: <code>bd. Ștefan cel Mare și Sfânt, 1, Chișinău</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>origin_lat</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="origin_lat"                data-endpoint="POSTapi-v1-rider-rides"
               value="47.0105"
               data-component="body">
    <br>
<p>The latitude of the origin. Must be between -90 and 90. Example: <code>47.0105</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>origin_lng</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="origin_lng"                data-endpoint="POSTapi-v1-rider-rides"
               value="28.8638"
               data-component="body">
    <br>
<p>The longitude of the origin. Must be between -180 and 180. Example: <code>28.8638</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>destination_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="destination_address"                data-endpoint="POSTapi-v1-rider-rides"
               value="str. Mihai Eminescu, 50, Chișinău"
               data-component="body">
    <br>
<p>The destination address of the ride. Must not be greater than 255 characters. Example: <code>str. Mihai Eminescu, 50, Chișinău</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>destination_lat</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="destination_lat"                data-endpoint="POSTapi-v1-rider-rides"
               value="47.0225"
               data-component="body">
    <br>
<p>The latitude of the destination. Must be between -90 and 90. Example: <code>47.0225</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>destination_lng</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="destination_lng"                data-endpoint="POSTapi-v1-rider-rides"
               value="28.8353"
               data-component="body">
    <br>
<p>The longitude of the destination. Must be between -180 and 180. Example: <code>28.8353</code></p>
        </div>
        </form>

                    <h2 id="rider-GETapi-v1-rider-rides-active">GET api/v1/rider/rides/active</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-rider-rides-active">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/rider/rides/active" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/rides/active"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/rides/active';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/rides/active'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rider-rides-active">
            <blockquote>
            <p>Example response (200, No active ride found - Returns null data with message):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01HGXJ8Z0M1A2B3C4D5E6F7G8H&quot;,
        &quot;rider_id&quot;: &quot;01HGXJ8Z0M1A2B3C4D5E6F7G8I&quot;,
        &quot;driver_id&quot;: &quot;01HGXJ8Z0M1A2B3C4D5E6F7G8J&quot;,
        &quot;origin_address&quot;: &quot;123 Main St, New York, NY&quot;,
        &quot;origin_lat&quot;: 40.7128,
        &quot;origin_lng&quot;: -74.006,
        &quot;destination_address&quot;: &quot;456 Broadway, New York, NY&quot;,
        &quot;destination_lat&quot;: 40.7589,
        &quot;destination_lng&quot;: -73.9851,
        &quot;status&quot;: &quot;accepted&quot;,
        &quot;price&quot;: null,
        &quot;estimated_price&quot;: 25.5,
        &quot;estimated_distance_km&quot;: 5.2,
        &quot;estimated_duration_min&quot;: 15,
        &quot;price_per_km&quot;: 6.5,
        &quot;price_per_minute&quot;: 1.2,
        &quot;base_fee&quot;: 25,
        &quot;arrived_at&quot;: null,
        &quot;started_at&quot;: null,
        &quot;cancelled_at&quot;: null,
        &quot;cancelled_by_type&quot;: null,
        &quot;cancelled_by_id&quot;: null,
        &quot;cancelled_reason&quot;: null,
        &quot;completed_at&quot;: null,
        &quot;created_at&quot;: {
            &quot;human&quot;: &quot;5 minutes ago&quot;,
            &quot;string&quot;: &quot;2024-01-06 10:00:00&quot;
        },
        &quot;updated_at&quot;: {
            &quot;human&quot;: &quot;2 minutes ago&quot;,
            &quot;string&quot;: &quot;2024-01-06 10:03:00&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized - Invalid or missing token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - User does not have rider role or profile incomplete):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rider-rides-active" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rider-rides-active"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rider-rides-active"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rider-rides-active" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rider-rides-active">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rider-rides-active" data-method="GET"
      data-path="api/v1/rider/rides/active"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rider-rides-active', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rider-rides-active"
                    onclick="tryItOut('GETapi-v1-rider-rides-active');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rider-rides-active"
                    onclick="cancelTryOut('GETapi-v1-rider-rides-active');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rider-rides-active"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rider/rides/active</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rider-rides-active"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rider-rides-active"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rider-rides-active"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rider-GETapi-v1-rider-rides-history">GET api/v1/rider/rides/history</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-rider-rides-history">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/rider/rides/history" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/rides/history"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/rides/history';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/rides/history'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rider-rides-history">
            <blockquote>
            <p>Example response (200, Paginated ride history retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rider-rides-history" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rider-rides-history"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rider-rides-history"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rider-rides-history" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rider-rides-history">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rider-rides-history" data-method="GET"
      data-path="api/v1/rider/rides/history"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rider-rides-history', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rider-rides-history"
                    onclick="tryItOut('GETapi-v1-rider-rides-history');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rider-rides-history"
                    onclick="cancelTryOut('GETapi-v1-rider-rides-history');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rider-rides-history"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rider/rides/history</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rider-rides-history"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rider-rides-history"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rider-rides-history"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rider-GETapi-v1-rider-rides--id-">GET api/v1/rider/rides/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-rider-rides--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/rider/rides/architecto" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/rides/architecto"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/rides/architecto';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/rides/architecto'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rider-rides--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01HGXJ8Z0M1A2B3C4D5E6F7G8H&quot;,
        &quot;rider_id&quot;: &quot;01HGXJ8Z0M1A2B3C4D5E6F7G8I&quot;,
        &quot;driver_id&quot;: null,
        &quot;origin_address&quot;: &quot;123 Main St, New York, NY&quot;,
        &quot;origin_lat&quot;: 40.7128,
        &quot;origin_lng&quot;: -74.006,
        &quot;destination_address&quot;: &quot;456 Broadway, New York, NY&quot;,
        &quot;destination_lat&quot;: 40.7589,
        &quot;destination_lng&quot;: -73.9851,
        &quot;status&quot;: &quot;pending&quot;,
        &quot;price&quot;: null,
        &quot;estimated_price&quot;: 25.5,
        &quot;estimated_distance_km&quot;: 5.2,
        &quot;estimated_duration_min&quot;: 15,
        &quot;price_per_km&quot;: 6.5,
        &quot;price_per_minute&quot;: 1.2,
        &quot;base_fee&quot;: 25,
        &quot;arrived_at&quot;: null,
        &quot;started_at&quot;: null,
        &quot;cancelled_at&quot;: null,
        &quot;cancelled_by_type&quot;: null,
        &quot;cancelled_by_id&quot;: null,
        &quot;cancelled_reason&quot;: null,
        &quot;completed_at&quot;: null,
        &quot;created_at&quot;: {
            &quot;human&quot;: &quot;2 hours ago&quot;,
            &quot;string&quot;: &quot;2024-01-06 10:00:00&quot;
        },
        &quot;updated_at&quot;: {
            &quot;human&quot;: &quot;2 hours ago&quot;,
            &quot;string&quot;: &quot;2024-01-06 10:00:00&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized - Invalid or missing token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - User does not have rider role, profile incomplete):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Ride not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rider-rides--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rider-rides--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rider-rides--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rider-rides--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rider-rides--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rider-rides--id-" data-method="GET"
      data-path="api/v1/rider/rides/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rider-rides--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rider-rides--id-"
                    onclick="tryItOut('GETapi-v1-rider-rides--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rider-rides--id-"
                    onclick="cancelTryOut('GETapi-v1-rider-rides--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rider-rides--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rider/rides/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rider-rides--id-"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rider-rides--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rider-rides--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-rider-rides--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="rider-POSTapi-v1-rider-rides--ride_id--cancel">POST api/v1/rider/rides/{ride_id}/cancel</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-rider-rides--ride_id--cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/rider/rides/architecto/cancel" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/rides/architecto/cancel"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/rides/architecto/cancel';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/rides/architecto/cancel'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-rider-rides--ride_id--cancel">
            <blockquote>
            <p>Example response (200, Ride cancelled successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Ride not found.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Ride cannot be cancelled in its current status.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-rider-rides--ride_id--cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-rider-rides--ride_id--cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-rider-rides--ride_id--cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-rider-rides--ride_id--cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-rider-rides--ride_id--cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-rider-rides--ride_id--cancel" data-method="POST"
      data-path="api/v1/rider/rides/{ride_id}/cancel"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-rider-rides--ride_id--cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-rider-rides--ride_id--cancel"
                    onclick="tryItOut('POSTapi-v1-rider-rides--ride_id--cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-rider-rides--ride_id--cancel"
                    onclick="cancelTryOut('POSTapi-v1-rider-rides--ride_id--cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-rider-rides--ride_id--cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/rider/rides/{ride_id}/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-rider-rides--ride_id--cancel"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-rider-rides--ride_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-rider-rides--ride_id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride_id"                data-endpoint="POSTapi-v1-rider-rides--ride_id--cancel"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride"                data-endpoint="POSTapi-v1-rider-rides--ride_id--cancel"
               value="01jk9v6v9v6v9v6v9v6v9v6v9v"
               data-component="url">
    <br>
<p>ULID of the ride. Example: <code>01jk9v6v9v6v9v6v9v6v9v6v9v</code></p>
            </div>
                    </form>

                    <h2 id="rider-PUTapi-v1-rider-rides--ride_id--rating">PUT api/v1/rider/rides/{ride_id}/rating</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-rider-rides--ride_id--rating">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8080/api/v1/rider/rides/architecto/rating" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"rating\": 5,
    \"comment\": \"Great ride!\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/rides/architecto/rating"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "rating": 5,
    "comment": "Great ride!"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/rides/architecto/rating';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'rating' =&gt; 5,
            'comment' =&gt; 'Great ride!',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/rides/architecto/rating'
payload = {
    "rating": 5,
    "comment": "Great ride!"
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-rider-rides--ride_id--rating">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Ride rated successfully.&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01kec121njjgtg1beabpm1es47&quot;,
        &quot;rider_id&quot;: &quot;01kec121nddb9qdz07jq096y0w&quot;,
        &quot;driver_id&quot;: null,
        &quot;origin_address&quot;: &quot;123 Main St, City&quot;,
        &quot;origin_lat&quot;: 40.7128,
        &quot;origin_lng&quot;: -74.006,
        &quot;destination_address&quot;: &quot;456 Elm St, City&quot;,
        &quot;destination_lat&quot;: 40.7589,
        &quot;destination_lng&quot;: -73.9851,
        &quot;status&quot;: &quot;completed&quot;,
        &quot;price&quot;: 25,
        &quot;estimated_price&quot;: 22.5,
        &quot;estimated_distance_km&quot;: 5.5,
        &quot;estimated_duration_min&quot;: 15,
        &quot;price_per_km&quot;: 4,
        &quot;price_per_minute&quot;: 0.5,
        &quot;base_fee&quot;: 5,
        &quot;arrived_at&quot;: null,
        &quot;started_at&quot;: null,
        &quot;cancelled_at&quot;: null,
        &quot;cancelled_by_type&quot;: null,
        &quot;cancelled_by_id&quot;: null,
        &quot;cancelled_reason&quot;: null,
        &quot;completed_at&quot;: {
            &quot;human&quot;: &quot;1 hour ago&quot;,
            &quot;string&quot;: &quot;2026-01-07 12:00:00&quot;
        },
        &quot;rating&quot;: {
            &quot;rating&quot;: 5,
            &quot;comment&quot;: &quot;Great ride!&quot;,
            &quot;created_at&quot;: {
                &quot;human&quot;: &quot;55 minutes ago&quot;,
                &quot;string&quot;: &quot;2026-01-07 12:05:00&quot;
            }
        },
        &quot;created_at&quot;: {
            &quot;human&quot;: &quot;2 hours ago&quot;,
            &quot;string&quot;: &quot;2026-01-07 11:45:00&quot;
        },
        &quot;updated_at&quot;: {
            &quot;human&quot;: &quot;55 minutes ago&quot;,
            &quot;string&quot;: &quot;2026-01-07 12:05:00&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - You can only rate your own completed rides.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (404, Ride not found.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed or rating update restricted (e.g., within 24 hours).):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-rider-rides--ride_id--rating" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-rider-rides--ride_id--rating"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-rider-rides--ride_id--rating"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-rider-rides--ride_id--rating" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-rider-rides--ride_id--rating">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-rider-rides--ride_id--rating" data-method="PUT"
      data-path="api/v1/rider/rides/{ride_id}/rating"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-rider-rides--ride_id--rating', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-rider-rides--ride_id--rating"
                    onclick="tryItOut('PUTapi-v1-rider-rides--ride_id--rating');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-rider-rides--ride_id--rating"
                    onclick="cancelTryOut('PUTapi-v1-rider-rides--ride_id--rating');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-rider-rides--ride_id--rating"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/rider/rides/{ride_id}/rating</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-rider-rides--ride_id--rating"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-rider-rides--ride_id--rating"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-rider-rides--ride_id--rating"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride_id"                data-endpoint="PUTapi-v1-rider-rides--ride_id--rating"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the ride. Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ride</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ride"                data-endpoint="PUTapi-v1-rider-rides--ride_id--rating"
               value="01jk9v6v9v6v9v6v9v6v9v6v9v"
               data-component="url">
    <br>
<p>ULID of the ride. Example: <code>01jk9v6v9v6v9v6v9v6v9v6v9v</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>rating</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="rating"                data-endpoint="PUTapi-v1-rider-rides--ride_id--rating"
               value="5"
               data-component="body">
    <br>
<p>Rating from 1 to 5. Must be at least 1. Must not be greater than 5. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="comment"                data-endpoint="PUTapi-v1-rider-rides--ride_id--rating"
               value="Great ride!"
               data-component="body">
    <br>
<p>Optional comment. Must not be greater than 1000 characters. Example: <code>Great ride!</code></p>
        </div>
        </form>

                    <h2 id="rider-GETapi-v1-rider-stats">GET api/v1/rider/stats</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-rider-stats">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/rider/stats" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/stats"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/stats';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/stats'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-rider-stats">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: {
        &quot;total_rides&quot;: 5,
        &quot;completed_rides&quot;: 4,
        &quot;cancelled_rides&quot;: 1,
        &quot;completion_rate&quot;: 80,
        &quot;average_price&quot;: 25.5,
        &quot;total_spent&quot;: 127.5
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized - Invalid or missing token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (403, Forbidden - User does not have rider role or profile incomplete):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-rider-stats" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-rider-stats"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-rider-stats"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-rider-stats" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-rider-stats">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-rider-stats" data-method="GET"
      data-path="api/v1/rider/stats"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-rider-stats', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-rider-stats"
                    onclick="tryItOut('GETapi-v1-rider-stats');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-rider-stats"
                    onclick="cancelTryOut('GETapi-v1-rider-stats');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-rider-stats"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/rider/stats</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-rider-stats"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-rider-stats"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-rider-stats"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="rider-POSTapi-v1-rider-avatar">POST api/v1/rider/avatar</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-rider-avatar">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/rider/avatar" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "avatar=@/tmp/php8aqid4t67i8leoipbIj" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/rider/avatar"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('avatar', document.querySelector('input[name="avatar"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/rider/avatar';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'avatar',
                'contents' =&gt; fopen('/tmp/php8aqid4t67i8leoipbIj', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/rider/avatar'
files = {
  'avatar': open('/tmp/php8aqid4t67i8leoipbIj', 'rb')}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'multipart/form-data',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, files=files)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-rider-avatar">
            <blockquote>
            <p>Example response (200, Avatar upload processing started.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-rider-avatar" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-rider-avatar"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-rider-avatar"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-rider-avatar" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-rider-avatar">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-rider-avatar" data-method="POST"
      data-path="api/v1/rider/avatar"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-rider-avatar', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-rider-avatar"
                    onclick="tryItOut('POSTapi-v1-rider-avatar');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-rider-avatar"
                    onclick="cancelTryOut('POSTapi-v1-rider-avatar');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-rider-avatar"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/rider/avatar</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-rider-avatar"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-rider-avatar"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-rider-avatar"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>avatar</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="avatar"                data-endpoint="POSTapi-v1-rider-avatar"
               value=""
               data-component="body">
    <br>
<p>Avatar image (jpg, png, webp, max 2MB). Must be an image. Must not be greater than 2048 kilobytes. Example: <code>/tmp/php8aqid4t67i8leoipbIj</code></p>
        </div>
        </form>

                <h1 id="support">Support</h1>

    

                                <h2 id="support-POSTapi-v1-support-tickets">POST api/v1/support/tickets</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-support-tickets">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8080/api/v1/support/tickets" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"subject\": \"Issue with payment\",
    \"message\": \"I was charged twice for my last ride.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/support/tickets"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "subject": "Issue with payment",
    "message": "I was charged twice for my last ride."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/support/tickets';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'subject' =&gt; 'Issue with payment',
            'message' =&gt; 'I was charged twice for my last ride.',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/support/tickets'
payload = {
    "subject": "Issue with payment",
    "message": "I was charged twice for my last ride."
}
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-support-tickets">
            <blockquote>
            <p>Example response (201, Support ticket created successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-support-tickets" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-support-tickets"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-support-tickets"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-support-tickets" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-support-tickets">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-support-tickets" data-method="POST"
      data-path="api/v1/support/tickets"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-support-tickets', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-support-tickets"
                    onclick="tryItOut('POSTapi-v1-support-tickets');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-support-tickets"
                    onclick="cancelTryOut('POSTapi-v1-support-tickets');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-support-tickets"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/support/tickets</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-support-tickets"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-support-tickets"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-support-tickets"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>subject</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="subject"                data-endpoint="POSTapi-v1-support-tickets"
               value="Issue with payment"
               data-component="body">
    <br>
<p>The subject of the support ticket. Must not be greater than 255 characters. Example: <code>Issue with payment</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="message"                data-endpoint="POSTapi-v1-support-tickets"
               value="I was charged twice for my last ride."
               data-component="body">
    <br>
<p>The detailed message of the support ticket. Must not be greater than 5000 characters. Example: <code>I was charged twice for my last ride.</code></p>
        </div>
        </form>

                <h1 id="ws">WS</h1>

    

                                <h2 id="ws-GETapi-v1-ws-token">GET api/v1/ws/token</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-ws-token">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8080/api/v1/ws/token" \
    --header "Authorization: Bearer &amp;lt;token&amp;gt;" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8080/api/v1/ws/token"
);

const headers = {
    "Authorization": "Bearer &amp;lt;token&amp;gt;",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8080/api/v1/ws/token';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer &lt;token&gt;',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8080/api/v1/ws/token'
headers = {
  'Authorization': 'Bearer &amp;lt;token&amp;gt;',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-ws-token">
            <blockquote>
            <p>Example response (200, JWT token for Centrifugo connection retrieved successfully.):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;"></code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-ws-token" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-ws-token"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-ws-token"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-ws-token" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-ws-token">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-ws-token" data-method="GET"
      data-path="api/v1/ws/token"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-ws-token', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-ws-token"
                    onclick="tryItOut('GETapi-v1-ws-token');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-ws-token"
                    onclick="cancelTryOut('GETapi-v1-ws-token');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-ws-token"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/ws/token</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-ws-token"
               value="Bearer <token>"
               data-component="header">
    <br>
<p>Example: <code>Bearer &lt;token&gt;</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-ws-token"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-ws-token"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                                        <button type="button" class="lang-button" data-language-name="php">php</button>
                                                        <button type="button" class="lang-button" data-language-name="python">python</button>
                            </div>
            </div>
</div>
</body>
</html>
