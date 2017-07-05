<?php

namespace Rangka\Quickbooks;

use GuzzleHttp\Client AS Guzzle;

class Connect extends Client {
    /**
     * URL to request token.
     *
     * * @var string
     */
    const URL_REQUEST_TOKEN = 'https://oauth.intuit.com/oauth/v1/get_request_token';

    /**
     * URL to obtain access token
     *
     * * @var string
     */
    const URL_ACCESS_TOKEN = 'https://oauth.intuit.com/oauth/v1/get_access_token';

    /**
     * URL to connect/authorize OAuth
     *
     * * @var string
     */
    const URL_CONNECT = 'https://appcenter.intuit.com/Connect/Begin';

    /**
     * URL to reconnect OAuth (refresh token)
     *
     * * @var string
     */
    const URL_RECONNECT = 'https://appcenter.intuit.com/api/v1/connection/reconnect';

    /**
     * Holds callback URL for redirection when user has authorized.
     * 
     * @var string
     */
    protected $callback_url;

    /**
     * Connect constructor.
     * @param array $options
     */
    public function __construct($options = []) {
        parent::__construct();

        if (isset($options['callback_url']))
            $this->callback_url = $options['callback_url'];
    }

    /**
     * Get token from QuickBooks.
     * @return array
     * @throws \Exception
     */
    public function requestAccess() {
        if(self::$oauth_token)
            throw new \Exception('QuickBooks has been connected. Please disconnect before proceeding.');
        
        $res = $this->request('GET', self::URL_REQUEST_TOKEN, [
            'oauth_callback' => $this->callback_url
        ]);

        // retrieve the value
        $params = [];
        parse_str(((string) $res->getBody()), $params);

        return [
            'oauth_token_secret' => $params['oauth_token_secret'],
            'url'                => self::URL_CONNECT . '?' . (string) $res->getBody()
        ];
    }

    /**
     * Reconnect to QuickBooks to get a fresh token.
     * @return array
     * @throws \Exception
     */
    public function reconnect() {
        $signed = $this->sign('GET', self::URL_RECONNECT, [
            'oauth_token' => self::$oauth_token
        ]);

        $response = (new Guzzle([
            'headers' => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => $signed['header']
            ]
        ]))->request('GET', self::URL_RECONNECT);

        // retrieve the value
        $response = json_decode((string) $response->getBody(), true);

        if ($response['ErrorMessage'])
            throw new \Exception($response['ErrorMessage'], $response['ErrorCode']);

        return [
            'oauth_token_secret' => $response['OAuthTokenSecret'],
            'oauth_token'        => $response['OAuthToken'],
            'oauth_expiry'       => time() + (86400 * 180)
        ];
    }

    /**
     * Connect to QuickBooks and save OAuth token for future usage.
     *
     * @param $params
     * @return array
     */
    public function connect($params) {
        $response = $this->request('GET', self::URL_ACCESS_TOKEN, [
            'oauth_token'    => $params['oauth_token'], 
            'oauth_verifier' => $params['oauth_verifier']
        ]);

        // retrieve the value
        $values = [];
        parse_str(((string) $response->getBody()), $values);

        return [
            'oauth_token_secret' => $values['oauth_token_secret'],
            'oauth_token'        => $values['oauth_token'],
            'oauth_expiry'       => time() + (86400 * 180),
            'company_id'         => $params['realmId']
        ];
    }

    /**
     * Request from QuickBooks.
     * @param $method
     * @param $url
     * @param array $params
     * @param array $headers
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function request($method, $url, $params = [], $headers = []) {
        $signed = $this->sign('GET', $url, $params);

        return $response = (new Guzzle())->request($method, $signed['url']);
    }
}

