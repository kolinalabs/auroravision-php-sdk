
> **Thanks to [Sun Mobi](https://sunmobi.com.br/) for contributions!**

![KolinaLabs & SunMobi](https://cdn.kolinalabs.com/partner-sunmobi.png)

# Experimental project

Hi, thanks for visiting this repository.

At the moment, our team is working on parameterizing and implementing useful features for accessing data from api aurora vision.

Therefore, we do not recommend using this sdk in production environments until its stable version is released.

If you want to contribute or send questions about this resource, feel free.

Send an email to si@kolinalabs.com.

# Setup


```php

// Configure client

use KolinaLabs\SolarUtils\AuroraVision\Client;

$apiKey = '<your-api-key>';
$apiAuth = '<your-api-auth>';   // "Basic your-basic-api-auth="
$identity = '<your-plan-identity>';

$client = new Client($apiKey, $apiAuth, $identity);

// alternative (static method)
// $client = Client::create($apiKey, $apiAuth, $identity);

// Usage

// Get plant information
$plan = $client->getPlantInfo();

// Get generation data

/** @see Generation instances */

$data = $client->generation($generationQuery);

```

# Usage

# Features

## GenerationQuery (abstract)

| Property | Type | Default | Accept |
|---|---|---|---|
| $startDate | DateTime | new \DateTime() | \DateTime instance |
| $endDate | DateTime | new \DateTime('1 month ago') | \DateTime instance |
| $sampleSize | string | Month | One of the options <Min5/Hour/Day/Month/Year> |
| $timeZone | string | UTC | One of the timezones available at \DateTimeZone::listIdentifiers() |

**_Note on some properties_**

**sampleSize**

This property determines how the results will be grouped, depending on the interval between startDate and endDate, the results can generate more or less items.

**timeZone**

Some time zones may show inconsistency and / or not show results and / or generate errors, this property is conditioned to the internal characteristics of the api.

### GenerationEnergy

```php
use KolinaLabs\SolarUtils\AuroraVision\GenerationEnergy;

// Instance
$generationQuery = GenerationEnergy::delta();

$result = $client->generation($generationQuery);

// Result format (when there is data)
// $result = [
//     [
//         'start' => 1580846400,
//         'units' => 'kilowatt-hours',
//         'value' => 39.743
//     ],
//     [
//         'start' => 1580886000,
//         'units' => "kilowatt-hours",
//         'value' => 11.609
//     ],
//     // ....
// ];
```

### GenerationPower

```php
use KolinaLabs\SolarUtils\AuroraVision\GenerationPower;

// Instance
$generationQuery = GenerationPower::average();

$result = $client->generation($generationQuery);

// Result format (when there is data)
// $result = [
//     [
//         'start' => 1577844000,
//         'units' => 'watts',
//         'value' => 122470.3644563
//     ],
//     [
//         'start' => 1580522400,
//         'units' => 'watts',
//         'value' => 91121.158631334
//     ]
// ];
```
