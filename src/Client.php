<?php
namespace Rangka\Quickbooks;

use GuzzleHttp\Client as Guzzle;
use Rangka\Quickbooks\Builders\Builder;
use Rangka\Quickbooks\Builders\Traits\UseMultipart;

class Client {
    /**
     * API Base Url (Sandbox)
     *
     * @var string
     */
    const URL_API_BASE_SANDBOX = 'https://sandbox-quickbooks.api.intuit.com/v3/company';

    /**
     * API Base Url (Live)
     *
     * @var string
     */
    const URL_API_BASE_LIVE = 'https://quickbooks.api.intuit.com/v3/company';

    /**
     * Hold's QuickBooks' Client ID.
     *
     * @var string
     */
    protected static $client_id;

    /**
     * Holds QuickBook's Client Secret. 
     *
     * @var string
     */
    protected static $client_secret;

    /**
     * Webhook's Verifier Token.
     *
     * @var string
     */
    protected static $webhook_token;

    /**
     * Default redirect URL.
     *
     * @var string
     */
    protected static $redirect_uri;

    /**
     * Hold's QuickBooks' OAuth.
     *
     * @var string
     */
    protected static $oauth;

    /**
     * Holds QuickBook's Realm ID. 
     *
     * @var string
     */
    protected static $realm_id;

    /**
     * Flag for sandbox mode. Defaults to FALSE.
     *
     * @var boolean
     */
    protected static $sandbox;

    /**
     * Construct a new client.
     * 
     * @return void
     */
    public function __construct() {
    }

    /**
    * Configure Client's tokens.
    *
    * @param  array  $params  Array of `oauth_token`, `oauth_token_secret` and `realm_id`.
    * @return void
    */
    public static function configure($options) {
        self::$client_id          = $options['client_id'] ?? self::$client_id ?? getenv('QUICKBOOKS_CLIENT_ID');
        self::$client_secret      = $options['client_secret'] ?? self::$client_secret ?? getenv('QUICKBOOKS_CLIENT_SECRET');
        self::$webhook_token      = $options['webhook_token'] ?? self::$webhook_token ?? getenv('QUICKBOOKS_WEBHOOK_TOKEN');
        self::$redirect_uri       = $options['redirect_uri'] ?? self::$redirect_uri ?? getenv('QUICKBOOKS_REDIRECT_URI');
        self::$sandbox            = isset($options['sandbox']) && $options['sandbox'] === true;
        self::$oauth              = $options['oauth'] ?? $options['oauth'] ?? '';
        self::$realm_id           = $options['realm_id'] ?? self::$realm_id ?? '';
    }

    /**
    * Request from Quickbooks.
    * 
    * @return 
    */
    public function request($method, $url, $body = [], $headers = []) {
        $url      = trim($url, '/');
        $base_uri = $this->getBaseURL() . '/' . self::$realm_id . '/';
        $full_url = $base_uri . $url;

        $headers  = array_merge([
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json'
        ], $headers);

        $guzzleOptions = [
            'base_uri' => $base_uri,
            'headers'  => [
                'Accept'        => $headers['Accept'],
                'Content-Type'  => $headers['Content-Type'],
                'Authorization' => 'Bearer ' . self::$oauth['access_token'],
            ],
        ];

        $requestOptions = [];
        if ($body) {
            if ($body instanceof Builder) {
                if (in_array(UseMultipart::class, class_uses($body))) {
                    $requestOptions['multipart'] = $body->getParts();
                }
                else {
                    $guzzleOptions['json'] =  $body->toArray();
                }
            }
            else {
                $guzzleOptions['json'] =  $body;
            }
        }

        $response = (new Guzzle($guzzleOptions))->request($method, $url, $requestOptions);

        if ($headers['Accept'] == 'application/json') {
            return json_decode((string) $response->getBody());
        }
        else {
            return $response->getBody();
        }
    }

    /**
    * Make a GET request.
    * 
    * @return void
    */
    public function get($url, $body = []) {
        return $this->request('GET', $url, $body);
    }

    /**
    * Make a POST request.
    * 
    * @return void
    */
    public function post($url, $body = [], $headers = []) {
        return $this->request('POST', $url, $body, $headers);
    }

    /**
     * Get base URL. This will switch between Sandbox and Live depending on config.
     * 
     * @return string
     */
    private function getBaseURL() {
        return self::$sandbox ? self::URL_API_BASE_SANDBOX : self::URL_API_BASE_LIVE;
    }
}