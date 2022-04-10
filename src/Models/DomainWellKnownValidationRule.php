<?php

namespace LoveDuckie\SilverStripe\WellKnownValidation\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\File;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;

use SilverStripe\SiteConfig\SiteConfig;

use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\CheckboxField;

use Exception;

class DomainWellKnownValidationRule extends DataObject
{
    private static $singular_name = "Domain Well-Known Validation Rule";
    private static $plural_name = "Domain Well-Known Validation Rules";
    private static $table_name = "DomainWellKnownValidationRule";

    private static $db = [
        'Title' => 'Varchar',
        'Description' => 'Text',
        'DomainName' => 'Varchar',
        'URLSegment' => 'Varchar',
        'ResponseContentType' => 'Varchar',
        'ResponseBody' => 'Varchar',
        'Enabled' => 'Boolean(true)'
    ];

    private static $field_labels = [
        'URLSegment' => 'URL Segment',
        'DomainName' => 'Domain Name',
        'Description' => 'Description'
    ];

    private static $summary_fields = [
        'Title' => 'Title',
        'DomainName' => 'Domain Name',
        'Description' => 'Description',
        'URLSegment' => 'URL Segment',
        'Enabled' => 'Boolean(true)'
    ];

    private static $has_one = [
        'VerificationFile' => File::class,
        'Configuration' => SiteConfig::class
    ];

    private static $owns = [
        'VerificationFile'
    ];

    public function getCMSValidator()
    {
        return RequiredFields::create(
            'Title',
            'Description',
            'DomainName'
        );
    }

    public function updateCMSFields(FieldList $fields)
    {
        if (!empty($fields)) {
            throw new Exception("The fields are invalid or null");
        }
        $owner = $this->owner;
        $fields->addFieldsToTab(
            'Root.Main',
            [
                TextField::create('Title', 'Title'),
                CheckboxField::create('Enabled', 'Enabled'),
                TextareaField::create('Description', 'Description'),
                TextField::create('DomainName', 'Domain Name'),
                TextField::create('URLSegment', 'URL Segment'),
                TextField::create('ResponseContentType', 'Response Content Type'),
                TextareaField::create('ResponseBody', 'Response Body')
            ]
        );
        $fields->addFieldToTab(
            'Root.Main',
            UploadField::create('VerificationFile', 'Verification File')
        );

        return $fields;
    }
}