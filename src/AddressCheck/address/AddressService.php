<?php

namespace XQueue\AddressCheck\Address;

use XQueue\AddressCheck\AbstractAddressCheckService;

class AddressService extends AbstractAddressCheckService
{
    function fastQualityCheck($email)
    {
        return $this->get("address/quality/$email");
    }

    function enhancedQualityCheck($email)
    {
        return $this->get("address/quality-n/$email");
    }

    function syntaxCheck($email)
    {
        return $this->get("address/syntax/$email");
    }

    function blacklistCheck($email)
    {
        return $this->get("address/blacklist/$email");
    }

    function botriskCheck($email)
    {
        return $this->get("address/botrisk/$email");
    }

    function challengeResponseCheck($email)
    {
        return $this->get("address/crrisk/$email");
    }

    function companyDomainCheck($email)
    {
        return $this->get("address/companydomain/$email");
    }

    function disposableAddressCheck($email)
    {
        return $this->get("address/disposable/$email");
    }

    function educationDomainCheck($email)
    {
        return $this->get("address/educationdomain/$email");
    }

    function genderCheck($email)
    {
        return $this->get("address/gender/$email");
    }

    function gravatarCheck($email)
    {
        return $this->get("address/gravatar/$email");
    }

    function iwtCheck($email)
    {
        return $this->get("address/iwt/$email");
    }

    function languageCheck($email)
    {
        return $this->get("address/language/$email");
    }

    function noAdvertisingCheck($email)
    {
        return $this->get("address/no-advertising/$email");
    }

    function publicServiceDomainCheck($email)
    {
        return $this->get("address/publicservicedomain/$email");
    }

    function robinsonListCheck($email)
    {
        return $this->get("address/robinsonlist/$email");
    }

    function roleCheck($email)
    {
        return $this->get("address/role/$email");
    }

    function spamTrapCheck($email)
    {
        return $this->get("address/spamtrap/$email");
    }
}
