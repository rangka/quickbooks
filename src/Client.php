<?php

namespace ReneDeKat\Quickbooks;

use GuzzleHttp\Client as Guzzle;
use Psr\Http\Message\StreamInterface;
use ReneDeKat\Quickbooks\Builders\Builder;
use ReneDeKat\Quickbooks\Builders\Traits\UseMultipart;

class Client
{
    /**
     * API Base Url (Sandbox).
     *
     * @var string
     */
    const URL_API_BASE_SANDBOX = 'https://sandbox-quickbooks.api.intuit.com/v3/company';

    /**
     * API Base Url (Live).
     *
     * @var string
     */
    const URL_API_BASE_LIVE = 'https://quickbooks.api.intuit.com/v3/company';

    /**
     * Hold's QuickBooks' Consumer Key.
     *
     * @var string
     */
    protected static $consumer_key;

    /**
     * Holds QuickBook's Consume Secret.
     *
     * @var string
     */
    protected static $consumer_secret;

    /**
     * Hold's QuickBooks' OAuth Token.
     *
     * @var string
     */
    protected static $oauth_token;

    /**
     * Holds QuickBook's OAuth Token Secret.
     *
     * @var string
     */
    protected static $oauth_token_secret;

    /**
     * Holds QuickBook's Company ID (previously known as realm).
     *
     * @var string
     */
    protected static $company_id;

    /**
     * Flag for sandbox mode. Defaults to FALSE.
     *
     * @var bool
     */
    protected static $sandbox;

    /**
     * Construct a new client.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Configure Client's tokens.
     *
     * @param array $params Array of `oauth_token`, `oauth_token_secret` and `company_id`
     *
     * @return void
     */
    public static function configure($options)
    {
        self::$consumer_key = isset($options['consumer_key']) ? $options['consumer_key'] : getenv('QUICKBOOKS_CONSUMER_KEY');
        self::$consumer_secret = isset($options['consumer_secret']) ? $options['consumer_secret'] : getenv('QUICKBOOKS_CONSUMER_SECRET');
        self::$sandbox = (isset($options['sandbox']) ? $options['sandbox'] : getenv('QUICKBOOKS_ENV')) == 'sandbox';
        self::$oauth_token = isset($options['oauth_token']) ? $options['oauth_token'] : '';
        self::$oauth_token_secret = isset($options['oauth_token_secret']) ? $options['oauth_token_secret'] : '';
        self::$company_id = isset($options['company_id']) ? $options['company_id'] : '';
    }

    /**
     * Sign a request.
     *
     * @param string $url    Endpoint
     * @param array  $params Array of parameters to be signed.
     * @param string $secret Token secret to be appended to consumer secret for signing.
     *
     * @return array string - base string to be signed,
     *               url    - signed URL (null if QuickBooks has been connected)
     *               header - Authorization header (null if QuickBooks has not been connected)
     */
    protected function sign($method, $url, $params = [])
    {
        // parse URL
        $parsedURL = parse_url($url);

        // reconstruct it with only what we need
        $url = $parsedURL['scheme'].'://'.$parsedURL['host'].$parsedURL['path'];

        // set default parameters and sort it by key
        $params = array_merge([
            'oauth_consumer_key'     => self::$consumer_key,
            'oauth_nonce'            => substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => time(),
            'oauth_version'          => '1.0',
        ], $params);

        // set query parameters if exists
        if (isset($parsedURL['query'])) {
            $parsedQuery = [];
            parse_str($parsedURL['query'], $parsedQuery);
            $params = array_merge($parsedQuery, $params);
        }

        // sort parameters by key
        ksort($params);

        // generate string to be signed
        $string = $method.'&'.rawurlencode($url).'&'.rawurlencode(http_build_query($params, null, '&', PHP_QUERY_RFC3986));

        // calculate signature
        $params['oauth_signature'] = base64_encode(hash_hmac('sha1', $string, rawurlencode(self::$consumer_secret).'&'.rawurlencode(self::$oauth_token_secret), true));

        // generate auth header if oauth_token is present but without verifier (actual API request and not authorization process)
        $header = $signed_url = '';
        if (isset($params['oauth_token']) && !isset($params['oauth_verifier'])) {
            $header = 'OAuth '.implode(', ', [
                'oauth_signature_method="'.$params['oauth_signature_method'].'"',
                'oauth_signature="'.rawurlencode($params['oauth_signature']).'"',
                'oauth_nonce="'.$params['oauth_nonce'].'"',
                'oauth_timestamp="'.$params['oauth_timestamp'].'"',
                'oauth_token="'.$params['oauth_token'].'"',
                'oauth_consumer_key="'.$params['oauth_consumer_key'].'"',
                'oauth_version="'.$params['oauth_version'].'"',
            ]);
        } else {
            // build URL for signed URL request
            $signed_url = $url.'?'.http_build_query($params);
        }

        // return all results
        return [
            'string' => $string,
            'url'    => $signed_url,
            'header' => $header,
        ];
    }

    /**
     * Request from Quickbooks.
     *
     * @return StreamInterface
     */
    public function request($method, $url, $body = [], $headers = [])
    {
        $url = trim($url, '/');
        $base_uri = $this->getBaseURL().'/'.self::$company_id.'/';
        $full_url = $base_uri.$url;
        $signed = $this->sign($method, $full_url, [
            'oauth_token' => self::$oauth_token,
        ]);

        $headers = array_merge([
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ], $headers);

        $guzzleOptions = [
            'base_uri' => $base_uri,
            'headers'  => [
                'Accept'        => $headers['Accept'],
                'Content-Type'  => $headers['Content-Type'],
                'Authorization' => $signed['header'],
            ],
        ];

        $requestOptions = [];
        if ($body) {
            if ($body instanceof Builder) {
                if (in_array(UseMultipart::class, class_uses($body))) {
                    $requestOptions['multipart'] = $body->getParts();
                } else {
                    $guzzleOptions['json'] = $body->toArray();
                }
            } else {
                $guzzleOptions['json'] = $body;
            }
        }

        $response = (new Guzzle($guzzleOptions))->request($method, $url, $requestOptions);

        if ($headers['Accept'] == 'application/json') {
            return json_decode((string) $response->getBody());
        } else {
            return $response->getBody();
        }
    }

    /**
     * Make a GET request.
     *
     * @param $url
     * @param array $body
     *
     * @return string
     */
    public function get($url, $body = [])
    {
        return $this->request('GET', $url, $body);
    }

    /**
     * Make a POST request.
     *
     * @return string
     */
    public function post($url, $body = [], $headers = [])
    {
        return $this->request('POST', $url, $body, $headers);
    }

    /**
     * Get base URL. This will switch between Sandbox and Live depending on config.
     *
     * @return string
     */
    private function getBaseURL()
    {
        return self::$sandbox ? self::URL_API_BASE_SANDBOX : self::URL_API_BASE_LIVE;
    }
}
