# Quickbooks
PHP Library for connecting to QuickBooks.

> Note: This was done with an AU account. There are differences between regions of Quickbooks Account. If you are using a different region's account and encounter any issue, please make a report of it.

# Installation
Install it using composer.

```
composer require rangka/quickbooks dev-master
```

# Configuring
To start using this library, you must create an app in the [Developer's Dashboard](https://developer.intuit.com/v2/ui#/app/dashboard) and obtain the client ID and secret.

Add this to the beginning of your app.

```
\Rangka\Quickbooks\Client::configure([
    'client_id'     => 'CLIENT_ID',
    'client_secret' => 'CLIENT_SECRET',
    'sandbox'       => true, // or `false`
    'oauth'         => $_SESSION['OAUTH'],
    'realm_id'      => $_SESSION['REALM_ID'],
]);
```

- `SANDBOX` - Use `true` if this is a development environment. Leave it empty or `false` for production.
- `OAUTH` - Represents the entire array returned after requesting for access token. This will be `null` when you first start out but must be filled after successfully obtaining an access token.
- `realm_id` - This is your company's ID and is required when making API calls. You will obtain this ID on the 2nd step (after user authorizes on Quickbook) of **Connecting to Quickbooks**.


# Connecting to Quickbooks 
Quickbooks uses OAuth2 in order to establish an authenticated connection to it. This documentation will not delve too much detail into it and instead will just lay out what you need. Go through the documentation to find out more.

**1. Authorization**

Redirect user to Quickbooks' login page. Get the URL via;

```
$connector = new Rangka\Quickbooks\Connect();
$url = $connector->getAuthorizationURL();
```


**2. Prepare a redirection page**
```
$connector = new \Rangka\Quickbooks\Connect();

// Provide it with values from query parameters.
$result = $connector->requestToken($_GET);

// Save $result somewhere. We will use it in Client::configure().
$_SESSION['oauth'] = $result;

// Realm ID is provided on query parameter so make sure to save it as well.
$_SESSION['realm_id'] = $_GET['realmId'];
```

# Reconnect
OAuth have a very limited usage period (usually about 1 hour). In order to refresh the token;

```
$connector = new \Rangka\Quickbooks\Connect();

if ($connector->hasExpired()) {
    // Make sure to save the result. We will use it in Client::configure().
    $_SESSION['oauth'] = $connector->refreshToken();
}
```

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

#### Create
In order to create, you will need to obtain a Builder for the entity you wish for. You can get the Builder instance from its Service. Example given is for Invoice;
```
$service = new \Rangka\Quickbooks\Services\Invoice;
$builder = $service->getBuilder();
```

Any property can be added by calling `set{$propertyName}`. For example setting the amount;
```
$builder->setAmount(100);
```

Nested value have two ways to be added. Either straight up arrays via its top-level property or using various helper methods within the Builder.

Straight up array;
```
$builder->setCustomerRef([
    'value' => 1
]);
```

Helper methods;
```
$builder->setCustomer(1);
```

Once you've built up your data, just call `create()`.
```
$invoice = $builder->create();
```

Upon doing so you Invoice will be created and your Invoice data object will be retured.

Putting it all together;
```
$service = new \Rangka\Quickbooks\Services\Invoice;
$builder = $service->getBuilder();
$builder->setAmount(100)
        ->setCustomer(1);

$invoice = $builder->create();
```


Note 1: Above example is a non-working solution. Please check Quickbooks' documentation on what are the required fields for each Entity.

Note 2: Builder methods can be chained, except for `create()` which will return the created object.


#### Update
Updating is exactly the same as Create however you will need to set a SyncToken first `setSyncToken()` and call `update()` instead.

```
$service = new \Rangka\Quickbooks\Services\Invoice;
$builder = $service->getBuilder();
$builder->setAmount(100)
        ->setCustomer(1)
        ->setSyncToken($syncToken);

$invoice = $builder->update();
```
An updated object will be returned by `update()`.

#### Attachment
```
$id = 566;                         // Required
$files = [
    [
        'path' => '/path/to/file', // Required
        'type' => 'image/png'      // Optional
        'name' => 'filename.png'   // Optional
    ]
];
$includeOnSend = true;             // Optional

$service = new \Rangka\Quickbooks\Services\Invoice;
$service->attach($id, $files, $includeOnSend);
```
`$files` is an array of associative array. Add more of the associative array to upload more files at the same time.

Note: Not all Entities can have attachments. Currently supported Entity with Attachments is Invoice, Vendor, VendorCredit, Purchase, PurchaseOrder, Transfer, JournalEntry, Deposit, CreditMemo, Estimate, RefundReceipt, SalesReceipt, Bill, Customer and Payment.

# Entity-specific Usage
Certain entities have usages beyond the normal CRUD operation.

## Attachable
#### Uploading File
```
$service = new \Rangka\Quickbooks\Services\Attachable;
$builder = $service->getBuilder();

// This is required
$file = [
    'path' => '/path/to/file', // Required
    'type' => 'image/png'      // Optional
    'name' => 'filename.png'   // Optional
];

// This is optional
$entities = [
    [
        'entity'        => 'Invoice',
        'id'            => 566,
        'includeOnSend' => true,
    ]
];

$builder->addFile($file, $entities);
$response = $builder->upload(); 
```
You are allowed to specify multiple Entities per Attachable.

Response upon upload is an array of Attachable objects.

Note: While Attachable can be used directly, its generally not recommended if you wish to upload a single file to a specific entity. Use each respective Services to upload files. Only use this if you wish to upload and link to several entities at once.

## Invoice
#### Send Email
```
$service = new \Rangka\Quickbooks\Services\Invoice;
$service->email($invoiceID, $email); // Email is optional
```
#### Download PDF
```
$service = new \Rangka\Quickbooks\Services\Invoice;
$service->downloadPdf($invoiceID);
```
