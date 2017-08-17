<?php

namespace Axllent\Weblog\Extensions;

use Axllent\Weblog\Model\BlogCategory;
use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Forms\GridField\Gridfield;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\TagField\TagField;

class BlogPostCategoriesExt extends SiteTreeExtension
{
    private static $many_many = array(
        'Categories' => BlogCategory::class
    );

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(\SilverStripe\Forms\FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.Main',
            TagField::create(
                'Categories',
                'Post Categories',
                $this->owner->Parent()->Categories(),
                $this->owner->Categories()
            )->setShouldLazyLoad(true), 'Content'
        );


        return $fields;
    }

    /**
     * Force new TagField categories to inherit ParentID
     */
    public function onAfterWrite()
    {
        foreach ($this->owner->Categories() as $category) {
            $category->BlogID = $this->owner->ParentID;
            $category->write();
        }
    }
}
