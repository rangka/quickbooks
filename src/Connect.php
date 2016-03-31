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
     * Holds callback URL for redirection when user has authorized.
     * 
     * @var string
     */
    protected $callback_url;

    /**
    * Constructor
    * @return void
    */
    public function __construct($options = []) {
        parent::__construct($options);

        $this->callback_url = $options['callback_url'];
    }

    /**
    * Get token from QuickBooks.
    * 
    * @return void
    */
    public function requestAccess() {
        if(self::$oauth_token)
            throw new \Exception('Quickbooks has been connected. Please disconnect before proceeding.');
        
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
    * Connecto to Quickbooks and save OAuth token for future usage.
    * 
    * @return void
    */
    public function connect($params) {
        $res = $this->request('GET', self::URL_ACCESS_TOKEN, [
            'oauth_token'    => $params['oauth_token'], 
            'oauth_verifier' => $params['oauth_verifier']
        ]);

        // retrieve the value
        $values = [];
        parse_str(((string) $res->getBody()), $values);

        return [
            'oauth_token_secret' => $values['oauth_token_secret'],
            'oauth_token'        => $values['oauth_token'],
            'oauth_expiry'       => time() + (86400 * 180),
            'company_id'         => $params['realmId']
        ];
    }

    /**
    * Request from Quickbooks.
    * 
    * @return 
    */
    public function request($method, $url, $params = []) {
        $signed = $this->sign('GET', $url, $params);

        return $response = (new Guzzle())->request($method, $signed['url']);
    }
}

