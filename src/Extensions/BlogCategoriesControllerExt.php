<?php

namespace Axllent\Weblog\Extensions;

use SilverStripe\Core\Extension;

class BlogCategoriesControllerExt extends Extension
{
    /**
     * Allowed actions
     *
     * @var array
     */
    private static $allowed_actions = [
        'displayCategory',
    ];

    /**
     * URL handlers
     *
     * @var array
     */
    private static $url_handlers = [
        'category//$Category!' => 'displayCategory',
    ];

    /**
     * Template for overriding the page title with category information
     *
     * @var string
     */
    private static $title_template = 'Viewing category "[category]" | [page]';

    /**
     * Display category
     *
     * @param HTTPRequest $request Request
     *
     * @return string
     */
    public function displayCategory($request)
    {
        $urlsegment = $request->param('Category');
        $category   = $this->owner->Categories()
            ->filter('URLSegment', $urlsegment)
            ->first();

        if (!$category) {
            return $this->owner->httpError(404);
        }

        $posts = $category->getVisibleBlogPosts();

        if (!$posts->count()) {
            return $this->owner->httpError(404);
        }

        $orig_title = $this->owner->dataRecord->Title;

        $this->owner->dataRecord->Title = str_replace(
            ['[category]', '[page]'],
            [$category->Title, $orig_title],
            $this->owner->config()->title_template
        );

        $this->owner->blogPosts = $posts;

        return $this->owner->render();
    }

    /**
     * Return current category (templating)
     *
     * @return mixed
     */
    public function getCurrentCategory()
    {
        $category = $this->owner->request->param('Category');
        if ($category) {
            return $this->owner->Categories()
                ->filter('URLSegment', $category)
                ->first();
        }

        return null;
    }
}
