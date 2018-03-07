<?php

namespace XQueue\AddressCheck\Util;

abstract class SyntaxWarningUtil
{
    private static $syntaxWarnings = array(
        "synm001" => "No '@' found",
        "synm002" => "No local part (mailbox name) found",
        "synm003" => "No domain name found",
        "synm004" => "Local part contains non-ASCII characters",
        "synm005" => "Domain name contains non-ASCII characters",
        "synm006" => "Invalid address format",
        "synm007" => "Invalid mailbox name",
        "synm008" => "Invalid domain name",
        "synm009" => "Invalid top level domain (TLD)",
        "synm010" => "Invalid IP address format",
        "synm011" => "More than one '@' found",
        "synm012" => "The top level domain (TLD) can only contain letters and must have a minimum length of two",
        "synm013" => "The local part can't be longer than 64 characters",
        "synm014" => "The domain name can't be longer than 254 characters",
        "synm015" => "The e-mail address can't be longer than 254 characters",
        "synm016" => "Invalid domain name (IRI) according to RFC 3490",
        "synm017" => "The domain name contained Unicode characters and was decoded",
        "synm018" => "The local part contained Unicode characters and was decoded",

        "extm001" => "The mailbox length must be between 3-32 characters",
        "extm002" => "Only letters (a-z) and digits (0-9) are allowed",
        "extm003" => "The first character must be a letter",
        "extm004" => "Only letters, digits and punctuation characters dot, hyphen and underscore are allowed",
        "extm005" => "Multiple occurences of punctuation characters dot, hyphen and underscore are not allowed",
        "extm006" => "The punctuation characters dot, hyphen and underscore are not allowed at the beginning or end",
        "extm007" => "Dots are not allowd at the end",
        "extm008" => "The mailbox length must be between 3-50 characters",
        "extm009" => "The mailbox length must be between 5-40 characters",
        "extm010" => "The mailbox length must be between 5-30 characters",
        "extm011" => "The mailbox length must be between 4-32 characters",
        "extm012" => "Only letters, digits and punctuation characters dot and underscore are allowed",
        "extm013" => "Only one dot is allowed",
        "extm014" => "The mailbox length must be between 2-50 characters",
        "extm015" => "The mailbox length must be between 3-40 characters",
        "extm016" => "Mutiple occurences of dots are not allowed",
        "extm017" => "The mailbox length must be between 2-30 characters",
        "extm018" => "Only letters, digits and punctuation characters dot, hyphen, underscore, plus, minus, slash and ampersand are allowed",
        "extm019" => "The mailbox length must be between 6-30 characters",
        "extm020" => "Only letters, digits and the punctuation character dot are allowed",
        "extm021" => "The mailbox length must be between 3-20 characters",
        "extm022" => "The mailbox length must be between 1-64 characters",
        "extm023" => "The mailbox length must be between 6-20 characters",
        "extm024" => "The mailbox length must be larger than 2",
        "extm025" => "The mailbox length must be between 2-31 characters",
        "extm026" => "The first character must be either a letter or a digit",
        "extm027" => "Only letters and dits are allowed at the beginning or end",
        "extm028" => "Multiple occurences of underscores are not allowed",
        "extm029" => "Dots are not allowed at the beginning or end",
        "extm030" => "The first chracter must be either a letter or a digit",
    );
    
    public static function getWarningMessageById($id) {
        if( !array_key_exists($id, self::$syntaxWarnings) ) {
            throw new AddressCheckException("ID of syntax warning not valid");
        }
        
        return self::$syntaxWarnings[$id];
    }
}
