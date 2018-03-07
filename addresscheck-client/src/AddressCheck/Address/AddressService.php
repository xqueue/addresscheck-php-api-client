<?php

namespace XQueue\AddressCheck\Address;

use XQueue\AddressCheck\AbstractAddressCheckService;

class AddressService extends AbstractAddressCheckService
{
    public function fastQualityCheck($email)
    {
        return $this->get("address/quality/$email");
    }

    public function enhancedQualityCheck($email)
    {
        return $this->get("address/quality-n/$email");
    }

    public function syntaxCheck($email)
    {
        return $this->get("address/syntax/$email");
    }

    public function blacklistCheck($email)
    {
        return $this->get("address/blacklist/$email");
    }

    public function botriskCheck($email)
    {
        return $this->get("address/botrisk/$email");
    }

    public function challengeResponseCheck($email)
    {
        return $this->get("address/crrisk/$email");
    }

    public function companyDomainCheck($email)
    {
        return $this->get("address/companydomain/$email");
    }

    public function disposableAddressCheck($email)
    {
        return $this->get("address/disposable/$email");
    }

    public function educationDomainCheck($email)
    {
        return $this->get("address/educationdomain/$email");
    }

    public function genderCheck($email)
    {
        return $this->get("address/gender/$email");
    }

    public function gravatarCheck($email)
    {
        return $this->get("address/gravatar/$email");
    }

    public function iwtCheck($email)
    {
        return $this->get("address/iwt/$email");
    }

    public function languageCheck($email)
    {
        return $this->get("address/language/$email");
    }

    public function noAdvertisingCheck($email)
    {
        return $this->get("address/no-advertising/$email");
    }

    public function publicServiceDomainCheck($email)
    {
        return $this->get("address/publicservicedomain/$email");
    }

    public function robinsonListCheck($email)
    {
        return $this->get("address/robinsonlist/$email");
    }

    public function roleCheck($email)
    {
        return $this->get("address/role/$email");
    }

    public function spamTrapCheck($email)
    {
        return $this->get("address/spamtrap/$email");
    }
}
