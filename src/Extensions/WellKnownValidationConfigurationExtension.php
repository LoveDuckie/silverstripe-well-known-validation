<?php

namespace LoveDuckie\SilverStripe\WellKnownValidation\Extensions;

use SilverStripe\ORM\DataExtension;
use Exception;
use LoveDuckie\SilverStripe\WellKnownValidation\Models\DomainWellKnownValidationRule;
use SilverStripe\Forms\FieldList;

use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;

class WellKnownValidationConfigurationExtension extends DataExtension
{
    private static $has_many = [
        'ValidationRules' => DomainWellKnownValidationRule::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        if (empty($fields)) {
            throw new Exception("The fields are invalid or null");
        }

        $domainValidationRulesConfig = new GridFieldConfig_RelationEditor(5);
        $domainValidationRulesDataColumns = $domainValidationRulesConfig->getComponentByType(GridFieldDataColumns::class);
        $domainValidationRulesDataColumns->setDisplayFields([
            'Title' => 'Title',
            'Description' => 'Description',
            'DomainName' => 'Domain Name',
            'URLSegment' => 'URL Segment'
        ]);

        $tabsToAdd = [];
        $tabsToAdd[] = Tab::create(
            'WellKnownValidation',
            'Well-Known Validation',
            new GridField(
                'ValidationRules',
                'Validation Rules',
                $this->owner->ValidationRules(),
                $domainValidationRulesConfig
            )
        );
        
        $tabsetToAdd = TabSet::create('Validation', 'Validation', $tabsToAdd);
        $fields->addFieldToTab('Root', $tabsetToAdd);
        return $fields;
    }
}
