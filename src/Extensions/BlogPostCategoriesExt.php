<?php
namespace Axllent\Weblog\Extensions;

use Axllent\Weblog\Model\BlogCategory;
use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ListboxField;
use SilverStripe\TagField\TagField;

class BlogPostCategoriesExt extends SiteTreeExtension
{
    /**
     * Many many relationship
     *
     * @var array
     */
    private static $many_many = [
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
        $fields->addFieldToTab(
            'Root.Main',
            ListboxField::create(
                'Categories',
                'Post Categories',
                $this->owner->Parent()->Categories(),
                $this->owner->Categories()
            ),
            'Content'
        );

        return $fields;
    }

    /**
     * Return blog categories - templating
     *
     * @return DataList
     */
    public function getCategories()
    {
        return $this->owner->Categories();
    }

    /**
     * Force new TagField categories to inherit ParentID
     *
     * @return void
     */
    public function onAfterWrite()
    {
        foreach ($this->owner->Categories() as $category) {
            $category->BlogID = $this->owner->ParentID;
            $category->write();
        }
    }
}
