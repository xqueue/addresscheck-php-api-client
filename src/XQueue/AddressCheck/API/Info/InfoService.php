<?php

namespace XQueue\AddressCheck\API\Info;

use XQueue\AddressCheck\API\AbstractAddressCheckService;

/**
 * Provides all info resource calls
 */
class InfoService extends AbstractAddressCheckService
{
    /**
     * Provides information about various blacklist resources, according to the resource type.
     * 
     * @param string $id A local part or domain name
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/blacklist-resource/
     */
    public function blacklist($id)
    {
        return $this->get("info/blacklist/$id");
    }

    /**
     * Provides information about botrisk resources.
     * 
     * @param string $id An info ID with prefix 'a:' for an address or 'm:' for a mailexchanger name.
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/botrisk-resource/
     */
    public function botrisk($id)
    {
        return $this->get("info/botrisk/$id");
    }

    /**
     * Provides information about company resources.
     * 
     * @param string $domain A domain name
     * @param string|null $language Set optional language for language-specific economic classifications as language tag according to RFC 5646.
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/company-domain-resource/
     */
    public function companyDomain($domain, $language=null)
    {
        $this->setAcceptLanguage($language);
        return $this->get("info/companydomain/$domain");
    }

    /**
     * Provides information about disposable resources.
     * 
     * @param string $id An info ID with prefix 'a:' for an address or 'm:' for a mailexchanger name.
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/disposable-address-resource/
     */
    public function disposable($id)
    {
        return $this->get("info/disposable/$id");
    }

    /**
     * Provides information about education resources.
     * 
     * @param string $domain A domain name
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/education-domain-resource/
     */
    public function educationDomain($domain)
    {
        return $this->get("info/educationdomain/$domain");
    }

    /**
     * Provides all existing information to a Gravatar profile.
     * 
     * @param string $email An email address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/public-profiles/#Gravatar_Profile_Information
     */
    public function gravatarProfileInformation($email)
    {
        return $this->get("info/gravatar/$email");
    }

    /**
     * Provides information about the behaviour of mailservers, when asked about the existence of email addresses.
     * 
     * @param string $domain A domain name
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/mailserver-diagnosis/
     */
    public function mailserverDiagnosis($domain)
    {
        return $this->get("info/mailserverdiagnosis/$domain");
    }

    /**
     * Provides information about spam trap resources.
     * 
     * @param string $id An info ID with prefix 'a:' for an address or 'm:' for a mailexchanger name.
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/spam-traps/#Information_about_a_spam_trap_resource
     */
    public function spamTrap($id)
    {
        return $this->get("info/spamtrap/$id");
    }
}
