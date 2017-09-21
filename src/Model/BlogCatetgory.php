<?php

namespace Axllent\Weblog\Model;

use Axllent\Weblog\Model\Blog;
use Axllent\Weblog\Model\BlogPost;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Core\Convert;
use SilverStripe\Security\Permission;

class BlogCategory extends DataObject
{

    private static $table_name = 'BlogCategory';

    private static $default_sort = [
        'Title'
    ];

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(100)',
        'URLSegment' => 'Varchar(100)'
    ];

    private static $has_one = [
        'Blog' => Blog::class
    ];

    private static $belongs_many_many = array(
        'BlogPosts' => BlogPost::class,
    );

    private static $summary_fields = [
        'Title', 'BlogPosts.Count'
    ];

    private static $field_labels = [
        'BlogPosts.Count' => 'Blog Posts'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('URLSegment');

        if ($this->exists()) {
            /* Remove Add New button */
            $config = $fields->dataFieldByName('BlogPosts')
                ->getConfig();
            $config->removeComponentsByType(
                $config->getComponentByType('SilverStripe\\Forms\\GridField\\GridFieldAddNewButton')
            );
        }

        return $fields;
    }

    public function Link()
    {
        return $this->Blog()->Link() . 'category/' . $this->URLSegment . '/';
    }

    /* Return only valid blog posts > PublishDate */
    public function getVisibleBlogPosts()
    {
        return $this->Blogposts()
            ->where(sprintf('"PublishDate" < \'%s\'', Convert::raw2sql(DBDatetime::now())));
    }

    public function validate()
    {
    	$valid = parent::validate();
        $this->Title = trim($this->Title);

        $filter = new URLSegmentFilter;

        $this->URLSegment = $filter->filter($this->Title);

        $exists = $this->Blog()->Categories()->filter([
            'URLSegment' => $this->URLSegment
        ])->exclude('ID', $this->ID)->count();

        if (!$this->Title) {
            $valid->addError('Enter a category name');
        } elseif ($exists) {
            $valid->addError('A category named ' . $this->Title . ' already exists');
        }
    	return $valid;
    }

    public function canEdit($member = null, $context = [])
    {
        $extended = $this->extendedCan('canEdit', $member);
        if ($extended !== null) {
            return $extended;
        }
        if (Permission::check('CMS_ACCESS_Weblog', 'any', $member)) {
            return true;
        };
        return parent::canEdit($member, $context);
    }

    public function canView($member = null, $context = [])
    {
        $extended = $this->extendedCan('canView', $member);
        if ($extended !== null) {
            return $extended;
        }
        if (Permission::check('CMS_ACCESS_Weblog', 'any', $member)) {
            return true;
        };
        return parent::canView($member, $context);
    }

    public function canDelete($member = null, $context = [])
    {
        $extended = $this->extendedCan('canDelete', $member);
        if ($extended !== null) {
            return $extended;
        }
        if (Permission::check('CMS_ACCESS_Weblog', 'any', $member)) {
            return true;
        };
        return parent::canDelete($member, $context);
    }

}
