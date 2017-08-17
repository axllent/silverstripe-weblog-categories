<?php

namespace Axllent\Weblog\Extensions;

use SilverStripe\ORM\DataExtension;

class BlogCategoriesControllerExt extends DataExtension
{
    private static $allowed_actions = [
        'displayCategory'
    ];

    private static $url_handlers = [
        'category//$Category!' => 'displayCategory',
    ];

    public function displayCategory($request)
    {
        $urlsegment = $request->param('Category');
        $category = $this->owner->Categories()->filter('URLSegment', $urlsegment)
            ->first();

        if (!$category) {
            return $this->owner->httpError(404);
        }

        $posts = $category->getVisibleBlogPosts();

        if (!$posts->count()) {
            return $this->owner->httpError(404);
        }

        $orig_title = $this->owner->dataRecord->Title;

        $this->owner->dataRecord->Title = 'Viewing category "' . $category->Title . '" | ' . $orig_title;

        $this->owner->blogPosts = $posts;

        return $this->owner->render();
    }

    /* For use in templating */
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
