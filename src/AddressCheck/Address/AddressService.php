<?php

namespace XQueue\AddressCheck\Address;

use XQueue\AddressCheck\AbstractAddressCheckService;

class AddressService extends AbstractAddressCheckService
{
    function fastQualityCheck($email)
    {
        return $this->get('address/quality/'.utf8_encode($email));
    }
}
