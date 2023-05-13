<?php

namespace Axllent\Weblog\Extensions;

use Axllent\Weblog\Model\BlogCategory;
use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

class BlogCategoriesExt extends SiteTreeExtension
{
    /**
     * Has many relationship
     *
     * @var array
     */
    private static $has_many = [
        'Categories' => BlogCategory::class,
    ];

    /**
     * Update Fields
     *
     * @param FieldList $fields Form fields
     *
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $gridFieldConfig = GridFieldConfig_RecordEditor::create();
        $fields->addFieldToTab(
            'Root.Categories',
            GridField::create(
                'Categories',
                'Categories',
                $this->owner->Categories(),
                $gridFieldConfig
            )
        );

        return $fields;
    }
}
