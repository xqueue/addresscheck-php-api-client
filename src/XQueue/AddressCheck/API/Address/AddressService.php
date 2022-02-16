<?php

namespace XQueue\AddressCheck\API\Address;

use XQueue\AddressCheck\API\AbstractAddressCheckService;

/**
 * Provides all checks
 */
class AddressService extends AbstractAddressCheckService
{
    /**
     * Verifies that an e-mail address is formally valid and does actually exist.
     * 
     * The check treats temporary errors as problems that make it impossible to verify the existence of an e-mail address.
     * It will immediately return upon encountering such a problem.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/address-quality/
     */
    public function fastQualityCheck($email)
    {
        return $this->get("address/quality/$email");
    }

    /**
     * Verifies that an e-mail address is formally valid and does actually exist.
     * 
     * The check is more thoroughly than the fast quality check, and starts a background check for addresses with temporary errors.
     * The results of these background checks can be queried by simply repeating the enhanced address quality check.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/address-quality/
     */
    public function enhancedQualityCheck($email)
    {
        return $this->get("address/quality-n/$email");
    }

    /**
     * Verifies that an e-mail address is formally valid, focusing on the syntax of the email address.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/address-syntax/
     */
    public function syntaxCheck($email)
    {
        return $this->get("address/syntax/$email");
    }

    /**
     * Evaluates the risk for being blacklisted when sending mails to the specified email address.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/blacklist-risk/
     */
    public function blacklistRiskCheck($email)
    {
        return $this->get("address/blacklist/$email");
    }

    /**
     * Evaluates the risk that the specified email address represents an automated bot, not a real person.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/botrisks/
     */
    public function botRiskCheck($email)
    {
        return $this->get("address/botrisk/$email");
    }

    /**
     * Tests if an email address belongs to a domain that uses Challenge-Response as an anti-spam mechanism.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/challenge-response-risks/
     */
    public function challengeResponseRiskCheck($email)
    {
        return $this->get("address/crrisk/$email");
    }

    /**
     * Tests whether an email address belongs to a domain representing a company.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/company-domains/
     */
    public function companyDomainCheck($email)
    {
        return $this->get("address/companydomain/$email");
    }

    /**
     * Tests if an email address belongs to a domain providing temporary, disposable email addresses.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/disposable-address/
     */
    public function disposableAddressCheck($email)
    {
        return $this->get("address/disposable/$email");
    }

    /**
     * Tests whether an email address belongs to a domain related to an educational institution.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/education-domains/
     */
    public function educationDomainCheck($email)
    {
        return $this->get("address/educationdomain/$email");
    }

    /**
     * Analyzes the local part of an email address for gender and name information.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/gender-and-names/
     */
    public function genderAndNameCheck($email)
    {
        return $this->get("address/gender/$email");
    }

    /**
     * Checks if a Gravatar account for the given e-mail address exists.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/public-profiles/
     */
    public function gravatarCheck($email)
    {
        return $this->get("address/gravatar/$email");
    }

    /**
     * Tests whether an email address belongs to a domain of an ISP, a Webmail or a Telecom provider.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/iwt-domains/
     */
    public function iwtDomainCheck($email)
    {
        return $this->get("address/iwt/$email");
    }

    /**
     * Evaluates the primary languages for an email address.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/languages/
     */
    public function languageCheck($email)
    {
        return $this->get("address/language/$email");
    }

    /**
     * Test whether an email address belongs to the list of registered no advertising households that should not be bothered with advertising information.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/no-advertising-household-check/
     */
    public function noAdvertisingCheck($email)
    {
        return $this->get("address/no-advertising/$email");
    }

    /**
     * Test whether an email address belongs to a domain related to a public service organization.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/public-service-domains/
     */
    public function publicServiceDomainCheck($email)
    {
        return $this->get("address/publicservicedomain/$email");
    }

    /**
     * Test whether the specified email address is registered in Robinson Lists.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/robinsons-lists/
     */
    public function robinsonListCheck($email)
    {
        return $this->get("address/robinsonlist/$email");
    }

    /**
     * Tests if an email address is a functional one / belongs to a role, rather than to a person.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/role-analysis/
     */
    public function roleCheck($email)
    {
        return $this->get("address/role/$email");
    }

    /**
     * Tests whether the specified email address is known as a spam trap.
     * 
     * @param string $email An e-mail address
     * @return AddressCheckResult The result as an object
     * @link https://dev.addresscheck.eu/home/public-services/spam-traps/
     */
    public function spamTrapCheck($email)
    {
        return $this->get("address/spamtrap/$email");
    }
}
