<?php

define('VERIFY_TOKEN','');

// freedomainapi.com (One Request Per Minute)
define('FREEDOMAIN_API_URL','http://api.freedomainapi.com');
define('FREEDOMAIN_API_KEY','');
define('FREEDOMAIN_API_METHOD','GET');
define('FREEDOMAIN_API_STATUS','DISABLED');

// jsonwhoisapi.com (1000 Requests Per Month)
define('JSONWHOISAPI_API_URL','https://jsonwhoisapi.com/api/v1/whois');
define('JSONWHOISAPI_API_ACC_NO','');
define('JSONWHOISAPI_API_KEY','');
define('JSONWHOISAPI_API_METHOD','GET');
define('JSONWHOISAPI_API_STATUS','ENABLED');

// jsonwhois.io (250 Requests - domain/ip, 500 Requests - availability)
define('JSONWHOIS_API_WHOIS_URL','https://api.jsonwhois.io/whois/domain');
define('JSONWHOIS_API_IP_URL','https://api.jsonwhois.io/whois/ip');
define('JSONWHOIS_API_AVAIL_URL','https://api.jsonwhois.io/whois/availability');
define('JSONWHOIS_API_METHOD','GET');
define('JSONWHOIS_API_KEY','');
define('JSONWHOIS_API_STATUS','ENABLED');

?>