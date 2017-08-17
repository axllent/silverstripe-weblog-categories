<?php

namespace Axllent\Weblog\Extensions;

use Axllent\Weblog\Model\BlogCategory;
use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Forms\GridField\Gridfield;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;


class BlogCategoriesExt extends SiteTreeExtension
{

    private static $has_many = [
        'Categories' => BlogCategory::class
    ];

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(\SilverStripe\Forms\FieldList $fields)
    {
        $gridFieldConfig = GridFieldConfig_RecordEditor::create();
        $fields->addFieldToTab('Root.BlogCategories',
            new GridField('Categories', 'Blog Categories', $this->owner->Categories(), $gridFieldConfig)
        );
        return $fields;
    }

}
