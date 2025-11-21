<?php

namespace Axllent\Weblog\Extensions;

use Axllent\Weblog\Model\BlogCategory;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

class BlogCategoriesExt extends Extension
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
                'Blog categories',
                $this->owner->Categories(),
                $gridFieldConfig
            )
        );

        return $fields;
    }
}
