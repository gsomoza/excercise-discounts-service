<?php

namespace App;

use GuzzleHttp\ClientInterface;

/**
 * Currently only used for typing in the Service Manager configuration (to avoid using the 'alias' feature)
 */
interface ApiClient extends ClientInterface
{

}
