<?php

namespace XQueue\AddressCheck\Info;

use XQueue\AddressCheck\AbstractAddressCheckService;

class InfoService extends AbstractAddressCheckService
{
    function mailserverDiagnosis($domain)
    {
        return $this->get("info/mailserverdiagnosis/$domain");
    }
}
