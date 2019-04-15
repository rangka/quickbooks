<?php

namespace Rangka\Quickbooks;

use GuzzleHttp\Client AS Guzzle;

class Connect extends Client {
    /**
     * URL to request for authorization code.
     *
     * @var string
     */
    const URL_AUTH_CODE_REQUEST = 'https://appcenter.intuit.com/connect/oauth2?response_type=code&';

    /**
     * URL to obtain a new OAuth Token.
     *
     * @var string
     */
    const URL_TOKEN_REQUEST = 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer';

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

        if (isset($options['callback_url']))
            $this->callback_url = $options['callback_url'];
    }

    /**
     * Get URL to be used to get authorization code from user.
     * 
     * @return String
     */
    public function getAuthorizationURL($options = [])
    {
        return self::URL_AUTH_CODE_REQUEST . implode('&', [
            'client_id='    . self::$client_id,
            'scope='        . $this->getScope($options['scope']),
            'redirect_uri=' . $options['redirect_uri'],
            'state='        . $options['state'],
        ]); 
    }

    /**
     * Get scope to be used in request.
     *
     * @param String  $scope  Comma-separated scope.
     * 
     * @return String
     */
    public function getScope(string $scope)
    {
        return 'com.intuit.quickbooks.' . implode(' ', array_map('trim', explode(',', $scope)));
    }

    /**
    * Get token from QuickBooks.
    * 
    * @return array
    */
    public function requestToken($data) {
        if(self::$oauth) {
            throw new \Exception('Quickbooks has been connected. Please disconnect before proceeding.');
        }

        $res = (new Guzzle([
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(self::$client_id . ':' . self::$client_secret),
            ],
            'form_params' => [
                'code'         => $data['code'],
                'redirect_uri' => 'http://local.test:8000/rangka/quickbooks-dev/redirect.php',
                'grant_type'   => 'authorization_code',
            ],
        ]))->request('POST', self::URL_TOKEN_REQUEST);

        // retrieve the value
        $params = json_decode((string) $res->getBody(), true);

        $params['expires_at'] = time() + $params['expires_in'];
        $params['x_refresh_token_expires_at'] = time() + $params['x_refresh_token_expires_in'];

        // TODO: Handle error

        return $params;
    }

    /**
    * Reconnect to Quickbooks to get a fresh token.
    * 
    * @return array
    */
    public function refreshToken() {
        if(!self::$oauth) {
            throw new \Exception('Quickbooks has not been connected. Please connect or properly configure it before proceeding.');
        }

        $res = (new Guzzle([
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(self::$client_id . ':' . self::$client_secret),
            ],
            'form_params' => [
                'grant_type'   => 'refresh_token',
                'refresh_token'   => self::$oauth['refresh_token'],
            ],
        ]))->request('POST', self::URL_TOKEN_REQUEST);

        // retrieve the value
        $params = json_decode((string) $res->getBody(), true);

        $params['expires_at'] = time() + $params['expires_in'];
        $params['x_refresh_token_expires_at'] = time() + $params['x_refresh_token_expires_in'];

        // TODO: Handle error

        return $params;
    }
}

