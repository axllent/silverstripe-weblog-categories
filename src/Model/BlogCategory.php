<?php

namespace Axllent\Weblog\Model;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Permission;
use SilverStripe\View\Parsers\URLSegmentFilter;

class BlogCategory extends DataObject
{
    /**
     * Table name
     *
     * @var string
     */
    private static $table_name = 'BlogCategory';

    /**
     * The default sort.
     *
     * @var string
     *
     * @config
     */
    private static $default_sort = '"Title" ASC';

    /**
     * Database field definitions.
     *
     * @var array
     *
     * @config
     */
    private static $db = [
        'Title'      => 'Varchar(100)',
        'URLSegment' => 'Varchar(100)',
    ];

    /**
     * One-to-zero relationship definitions.
     *
     * @var array
     *
     * @config
     */
    private static $has_one = [
        'Blog' => Blog::class,
    ];

    /**
     * Defines inverse side of a many-many relationship.
     *
     * @var array
     *
     * @config
     */
    private static $belongs_many_many = [
        'BlogPosts' => BlogPost::class,
    ];

    /**
     * Provides a default list of fields to be used by a 'summary'
     * view of this object.
     *
     * @var string
     *
     * @config
     */
    private static $summary_fields = [
        'Title', 'BlogPosts.Count',
    ];

    /**
     * Field labels
     *
     * @var array
     */
    private static $field_labels = [
        'BlogPosts.Count' => 'Posts',
    ];

    /**
     * Data administration interface in Silverstripe.
     *
     * @see    {@link ValidationResult}
     *
     * @return FieldList Returns a TabSet for usage within the CMS
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(
            [
                'BlogID',
                'URLSegment',
            ]
        );

        if ($this->exists()) {
            // Remove Add New button
            $config = $fields->dataFieldByName('BlogPosts')
                ->getConfig();

            $config->removeComponentsByType(
                GridFieldAddNewButton::class
            );
        }

        return $fields;
    }

    /**
     * Link
     *
     * @return string
     */
    public function link()
    {
        return $this->Blog()->Link('category/' . $this->URLSegment);
    }

    /**
     * Return only valid blog posts > PublishDate
     *
     * @return DataList
     */
    public function getVisibleBlogPosts()
    {
        return $this->BlogPosts()
            ->where(
                sprintf(
                    '"PublishDate" < \'%s\'',
                    Convert::raw2sql(DBDatetime::now())
                )
            );
    }

    /**
     * Validate the current object.
     *
     * @see    {@link ValidationResult}
     *
     * @return ValidationResult
     */
    public function validate()
    {
        $valid       = parent::validate();
        $this->Title = trim($this->Title);

        $filter = new URLSegmentFilter();

        $this->URLSegment = $filter->filter($this->Title);

        $exists = $this->Blog()->Categories()
            ->filter('URLSegment', $this->URLSegment)
            ->exclude('ID', $this->ID)
            ->count();

        if (!$this->Title) {
            $valid->addError('Enter a category name');
        } elseif ($exists) {
            $valid->addError('A category named ' . $this->Title . ' already exists');
        }

        return $valid;
    }

    /**
     * Permissions canEdit
     *
     * @param Member $member  SilverStripe member
     * @param array  $context Array
     *
     * @return bool
     */
    public function canEdit($member = null, $context = [])
    {
        $extended = $this->extendedCan('canEdit', $member);
        if (null !== $extended) {
            return $extended;
        }
        if (Permission::check('CMS_ACCESS_Weblog', 'any', $member)) {
            return true;
        }

        return parent::canEdit($member, $context);
    }

    /**
     * Permissions canView
     *
     * @param Member $member  SilverStripe member
     * @param array  $context Array
     *
     * @return bool
     */
    public function canView($member = null, $context = [])
    {
        $extended = $this->extendedCan('canView', $member);
        if (null !== $extended) {
            return $extended;
        }
        if (Permission::check('CMS_ACCESS_Weblog', 'any', $member)) {
            return true;
        }

        return parent::canView($member, $context);
    }

    /**
     * Permissions canDelete
     *
     * @param Member $member  SilverStripe member
     * @param array  $context Array
     *
     * @return bool
     */
    public function canDelete($member = null, $context = [])
    {
        $extended = $this->extendedCan('canDelete', $member);
        if (null !== $extended) {
            return $extended;
        }
        if (Permission::check('CMS_ACCESS_Weblog', 'any', $member)) {
            return true;
        }

        return parent::canDelete($member, $context);
    }
}
