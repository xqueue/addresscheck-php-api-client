<?php

namespace XQueue\AddressCheck\API\Info;

use XQueue\AddressCheck\API\AbstractAddressCheckService;

class InfoService extends AbstractAddressCheckService
{
    function mailserverDiagnosis($domain)
    {
        return $this->get("info/mailserverdiagnosis/$domain");
    }
}
