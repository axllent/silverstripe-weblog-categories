# Configuration

## Page title override in category view

When navigating to a specific category, the blog page title is wrapped with
information about that category. If the default formatting of this wrapper
isn't suitable for your site, you can override it with configuration, using
the provided `[page]` and `[category]` variables:

```yml
Axllent\Weblog\Extensions\BlogCategoriesControllerExt:
  title_template: "[page]: [category]"
```
