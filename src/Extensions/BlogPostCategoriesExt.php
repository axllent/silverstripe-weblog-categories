<?php
namespace Axllent\Weblog\Extensions;

use Axllent\Weblog\Model\BlogCategory;
use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ListboxField;

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
        $p = $this->owner->Parent();
        if ($p->exists()) {
            $fields->addFieldToTab(
                'Root.Main',
                ListboxField::create(
                    'Categories',
                    'Post Categories',
                    $p->Categories(),
                    $this->owner->Categories()
                ),
                'Content'
            );
        }

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
}
