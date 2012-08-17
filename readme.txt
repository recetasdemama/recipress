=== ReciPress ===
Contributors: tammyhart
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=tammyhartdesigns%40gmail%2ecom&item_name=Recipe%20Box%20Plugin%20Latte%20Fund&no_shipping=0&no_note=1&tax=0&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: recipe, hRecipe, ingredients, cuisine, course, skill level, taxonomy, widget
Requires at least: 2.9
Tested up to: 3.4
Stable tag: 1.9.4


== Description ==

Create recipes in your posts with a clean interface and layout that are easy to organize.

[youtube http://www.youtube.com/watch?v=3ShvmQdYYOk]

= Features Include: =
* Custom meta box to create a recipe with the following fields: photo, title, summary, cuisine, course, skill level, yield, servings, prep time, cook time, ingredients, and instructions.
* Sortable ingredient rows with fields for amount, measurement, ingredient, and notes
* Ingredient suggestion just like post tags to prevent duplication
* Sortable, numbered instructions
* Custom taxonomies for Ingredients, Cuisines, Courses, and Skill Levels
* Output automatically or with a shortcode
* Sidebar widgets and template tags for listing Recipes, Cuisines, Courses, Skill Levels, and an Ingredient Cloud
* Three basic recipe output designs to choose from
* Supports themes with or without post-thumbnails for a recipe photo selection
* hRecipe optimized for Search Engine Optimization
* Complete plugin localization
* [ReciPress.com](http://www.recipress.com) dedicated support, documentation, and demo.

= Languages Available =
* English (Supported by Author)
* German
* French
* Japanese
* Slovenian
* Hungarian
* Italian

== Installation ==

1. Upload the 'recipress' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start adding recipes to your blog posts
4. Click on 'ReciPress' in the left admin bar to set up options

See our [Installation Guide](http://recipress.com/docs/using-recipress/installation-guide/) for more information.

== Frequently Asked Questions ==

See our [Documentation](http://recipress.com/docs) for more information.

= Q: How quickly does the plugin work? =
**A:** The plugin will automatically add some cuisines, courses, skill levels, and ingredients so that you can start adding recipes right away.

= Q: Can I edit the Cuisines, Courses, Skill Levels, and Ingredients? =
**A:** Yes! You can find the management pages in the ReciPress menu. They work just like post tags and categories.

= Q: Can I output ReciPress term lists without using a widget? =
**A:** Yes! Use the template tag `recipress_terms($taxonomy, $args)`. The `$args` variable accepts the same attributs as [`get_terms()`](http://codex.wordpress.org/Function_Reference/get_terms). **Note:** This only supports a list output, not a cloud.

= Q: Can I output recent Recipes without using a widget? =
**A:** Yes! Use the template tag `recipress_recent($num , $image)`. 
* `$num` should be set to the number of posts you want to output and is set to 5 if ommitted.
* `$image` should be set for displaying an image(1) thumbnail in the list or not(0) ans is set to 1 by default.

= Q: Can I request a feature or report a bug? =
**A:** Yes please! I have big plans for this recipe to keep developing it into a multifaceted solution for food and recipe bloggers. [ReciPress.com](http://www.recipress.com) will have a support forum, upgraded support for "Pro" customers, and even custom themes and add-ons! It is my goal to offer a fully supported solution and I would love your feedback on what features seem broken, incomplete, uneccessary, or missing. Until I open the official support forum, please use the forums here and tag your posts with "ReciPress".

== Screenshots ==

1. Add a recipe
2. Recipe output - light theme
3. Recipe output - dark theme
4. Recipe output - ReciPress theme
5. ReciPress Options

== Changelog ==

= 1.9.4 (June 26, 2012) =
* changed "Upload Image" to a more suitable "Add Image"
* made options and meta box field behave more similarly
* added option to change post types
* added an "other time" input for proper "ready time" calculation.
* fixed Quick Edit error of removing hasRecipe

= 1.9.3 (May 31, 2012) =
* Added functions to add and remove items from arrays
* Cleaned up recipress_fields array and changed the way items are added
* Cleaned up recipress_options_fields array and changed the way items are added
* Converted the output into a filterable array
* Added default to recipress_recipe

= 1.9.2 (May 19, 2012) =
* fixed error for adding widgets after ReciPress taxononmy widgets
* fixed recipress_recent count
* made the_recipe() filterable
* removed all HTML from recipress_recipe
* added shortcode [recipress_recent]
* updated English language file
* added new translations: French, Japanese, Slovenian, Hungarian, & Italian

= 1.9.1 (February 2, 2012) =
* fixed load_plugin_textdomain
* fixed image uploader js and added the gallery tab
* added Instruction Image Size option

= 1.9 (Janury 31, 2012) =
* fixed image uploader js
* added $type to recipress_terms()
* fixed widget constructors
* fixed get_term_link
* added de_DE lang
* fixed load_plugin_textdomain
* fixed meta box Ingredients description to use sprintf

= 1.8 (January 13, 2012) =
* Code cleanups
* ReciPress.com links added

= 1.7 (January 2, 2012) =
* Code cleanups
* Localization

= 1.6 (December 21, 2011) =
* Fixed closing tags in meta_box.php
* Fixed missing ingredient error

= 1.5 (December 12, 2011) =
* Fixed Add Image javascript
* Fixed missing image field in new instructions

= 1.4 (December 10, 2011) =
* Made the Featured Image optional
* Added ability to set a photo for each instruction
* Added Taxonomy and Recent Recipes widget
* Linked ingredients in recipe output to their archive pages
* Made all taxonomies optional except Ingredients
* Added an optional "cost of recipe" field

= 1.3 (December 6, 2011) =
* Fixed broken images on Options page

= 1.2 (December 2, 2011) =
* Replaced PHP short tags with full PHP tags
* Polished up the ReciPress Options page
* Changed ingredient and instruction tables to unordered lists
* Changed ingredient and instruction tables to percentage widths for better flexibility
* Changed ingredient measurements to text field for simplicity's sake

= 1.1 (November 16, 2011) =
* Fixed the autoadd filter conditional

= 1.0 (November 14, 2011) =
* First release.