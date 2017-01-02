# Quickbooks
PHP Library for connecting to QuickBooks.

> Note: This was done with an AU account. There are differences between regions of Quickbooks Account. If you are using a different region's account and encounter any issue, please make a report of it.

# Installation
Install it using composer.

```
composer require rangka/quickbooks dev-master
```

# Configuring
To start using this library, you must create an app in the [Developer's Dashboard](https://developer.intuit.com/v2/ui#/app/dashboard) and obtain the consumer key and secret.

Add this to the beginning of your app.

```
\Rangka\Quickbooks\Client::configure([
    'consumer_key'       => CONSUMER_KEY,
    'consumer_secret'    => CONSUMER_SECRET,
    'sandbox'            => SANDBOX,
    'oauth_token'        => OAUTH_TOKEN,
    'oauth_token_secret' => OAUTH_TOKEN_SECRET,
    'company_id'         => COMPANY_ID,
]);
```

- `SANDBOX` - Use `sandbox` if this is a development environment. Leave it empty for production.
- `OAUTH_TOKEN`, `OAUTH_TOKEN_SECRET` and `COMPANY_ID` - This will be retrieved after the OAuth process. It can be left empty until the value is obtained.
- Take note for `OAUTH_TOKEN_SECRET`, there are 2 token secret values given by Quickbooks. One during authorization, and another after authorizing. Make sure to set both values into the Configuration as you receive them.


# Connecting to Quickbooks 
Quickbooks uses OAuth in order to establish an authenticated connection to it. This documentation will not delve too much detail into it and instead will just lay out what you need. Go through the documentation to find out more.

**1. Connect Button**

Follow Quickbooks' documentation [here](https://developer.intuit.com/docs/0100_accounting/0060_authentication_and_authorization/connect_from_within_your_app).

**2. Prepare a redirection page**
```
// CONNECT_URL is page in Step 3. This is the landing page 
// user will be redirected to after authorizing.
$connector = new \Rangka\Quickbooks\Connect([
    'callback_url' => CONNECT_URL
]);

// This will return a `url` for redirecting and `oauth_token_secret`.
$result = $connector->requestAccess();

// Save `oauth_token_secret` somewhere. We will use it later on. 
// Make sure this value is used in Configuration above.
$_SESSION['oauth_token_secret'] = $result['oauth_token_secret'];

header("Location:" . $result['url']);
```

**3. Prepare landing page (after user authorizes).**
```
$connector = new \Rangka\Quickbooks\Connect();
$result = $connector->connect($_GET);
```
`$result` will contain `oauth_token`, `oauth_token_secret`, `oauth_expiry` and `company_id`. Save all of this value into a permanent storage (eg: database). These values are the ones that will be used on the Configuration.

`oauth_expiry` is a UNIX Timestamp indicating when this token will expire. By Quickbooks' rule, it will expire after 6 month although this timestamp will expire in 180 days for simplicity sake (no need for date manipulation).

Take note, make sure `oauth_token_secret` provided into Configuration is value returned here, NOT value returned previously during authorization. That value can now safely be removed.

# Reconnect
As the OAuth Token will expire, you will need to reconnect when the expiry date approaches. I recommend reconnecting a few days before the expiry instead of on the day itself.

```
$connector = new \Rangka\Quickbooks\Connect();
$result = $connector->reconnect();
```
`$result` will contain a new `oauth_token`, `oauth_token_secret` and `oauth_expiry`.

# Disconnect 
Simply remove all of the Configuration values from your storage. 

# Usage
#### Read
To read a resource, initialize its service and call `load($id)` on it. Take note not all Service have read capability. Check QB's documentation to know which one does and which one does not; https://developer.intuit.com/v2/apiexplorer?apiname=V3QBO .
```
$service = new \Rangka\Quickbooks\Services\Invoice;
$response = $service->load($id);
```

Response will be in `stdClass` object.

#### Delete
Deleting a resource is basically the same as loading except calling on `delete($id)` instead.
```
$service = new \Rangka\Quickbooks\Services\Invoice;
$response = $service->delete($id);
```
