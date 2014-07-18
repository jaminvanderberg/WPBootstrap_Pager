WPBootstrap_Pager
=================

Fully-Featured, customizable class for creating Bootstrap-style pagination links for many page types in WordPress

Usage
-----

### Post-Type Pager

In a WordPress post or page template (like single.php), the following code would be placed __inside__ the loop:

```php
<?php wpbootstrap_post_pager( $args ); ?>
```

This will create a pagination element for paging through multiple pages in a single post.

This usage is a replacement for calls to [wp_link_pages()](http://codex.wordpress.org/Function_Reference/wp_link_pages),
and is called in a similar manner (with additional arguments that provide extra customization).

### Archive-Type Pager
    
In a WordPress archive-type template (archive.php, category.php, tag.php, etc.), the following code would
be placed __before or after__ the loop:

```php
<?php wpbootstrap_archive_pager( $args ); ?>
```

This will create a pagination element for paging through multiple pages of posts.
    
This usage is a replacement for calls to [paginate_links()](http://codex.wordpress.org/Function_Reference/paginate_links),
although it is called with a different set of arguments that is more similar to those from
[wp_link_pages()](http://codex.wordpress.org/Function_Reference/wp_link_pages).  

The argument structure for both functions is identical, and described below.

### Default Arguments

```php
$defaults = array(
	'before'           => '<ul class="pagination">',
	'after'            => '</ul>',
	'link_before'      => '<li>',
	'link_after'       => '</li>',
	'next_or_number'   => 'number',
	'nextpagelink'     => '&raquo;',
	'previouspagelink' => '&laquo;',
	'pagelink'         => '%',
	'echo'             => true,
	'current_before'   => '<li class="active">',
	'current_after'    => '</li>',
	'currentlink'      => '% <span class="sr-only">'. __( '(current)', 'wp_bootstap_pager' ) . '</span>',
	'disabled_before'  => '<li class="disabled">',
	'disabled_after'   => '</li>',
	'previous_before'  => '<li class="previous">',
	'previous_after'   => '</li>',
	'next_before'      => '<li class="next">',
	'next_after'       => '</li>',
	'show_all'         => false,
	'end_size'         => 1,
	'mid_size'         => 2,
	'hellip'           => '<li class="disabled"><a>&hellip;</a></li>',
);
```

### Parameters

The `$args` parameter is not required.  If omitted, a default Bootstrap pagination element
will be created.

#### before

(string) Text to put before all the links. Defaults to `<ul class="pagination">`.

#### after 

(string) Text to put after all the links. Defaults to `</ul>`.

#### link_before

(string) Text that goes before the text of the link. Defaults to `<li>`.

#### link_after 

(string) Text that goes after the text of the link. Defaults to `</li>`.

#### next_or_number 

(string) Indicates whether page numbers should be used. Valid values are:

* `number` (Default) - Display page numbers.
* `next` - Display previous/next links only.

#### nextpagelink 

(string) Text for link to next page. Defaults to `&raquo;` (&raquo;)

#### previouspagelink

(string) Text for link to previous page. Defaults to `&laquo;` (&laquo;)

pagelink 
(string) Format string for page numbers.  % in the string will be replaced with the number, so Page % would generate "Page 1", "Page 2", etc. Defaults to %.
echo 
(boolean) Toggles whether to echo or return the result. The default is true. Valid values:
1 (True) - Default
0 (False)


	/**
	 * Override wp_link_pages to allow for Bootstrap classes
	 * 
	 * This is a modified version of wp_link_pages, designed to suit Bootstrap
	 * pagination better.  It is fully customizable, and includes options to 
	 * style the current and disabled elements for a `pagination` component,
	 * and the previous and next elements for a `pager` component.
	 * 
	 * This implementation is left open-ended, with no hard-coded tags.  All
	 * tags are provided through the arguments, therefore there might be other 
	 * implementations of this function beyond Bootstrap.  The default arguments,
	 * however, are designed to work with Bootstrap with little or no customization.
	 * 
	 * To center the pagination, call this function from inside a 
	 * `<div class="text-center">` block.
	 *
	 * Arguments not included in wp_link_pages:
	 * 
	 * * current_before - As `link_before`, but used only on the current page. (Default: '<li class="active">').
	 * * current_after - As `link_after`, but used only on the current page. (Default: '</li>').
	 * * currentlink - As `pagelink`, but used only on the current page. (Default: '% <span class="sr-only">' . __('(current)') . '</span>').
	 * * disabled_before - As `link_before`, but used only on disabled next/previous links. (Default: '<li class="disabled">').
	 * * disabled_after - As `link_after`, but used only on disabled next/previous links. (Default: '</li>').
	 * * previous_before - As `link_before`, but used only on previous link when `next_or_number` = 'next'. (Default: '<li class="previous">').
	 * * previous_after - As `link_after`, but used only on previous link when `next_or_number` = 'next'. (Default: '</li>').
	 * * next_before - As `link_before`, but used only on next link when `next_or_number` = 'next'. (Default: '<li class="next">').
	 * * next_after - As `link_after`, but used only on next link when `next_or_number` = 'next'. (Default: '</li>').
	 * 
	 * Additionally, many of the other arguments have default values that
	 * better suit a bootstrap implementation:
	 * 
	 * * before = '<ul class="pagination">
	 * * after = '</ul>'
	 * * link_before = '<li>'
	 * * link_after = '</li>'
	 * * previouspagelink = '&laquo;'
	 * * nextpagelink = '&raquo;'
	 * 
	 * And the remaining arguments retain their normal defaults from wp_link_pages:
	 * 
	 * * next_or_number = 'number'
	 * * pagelink = '%'
	 * * echo = 1
	 * 
	 * A few arguments from paginate_links() were also used:
	 * 
	 * * show_all = false
	 * * end_size = 1
	 * * mid_size = 2
	 * 
	 * These default settings will cause a maximum of 7 page links to be displayed.
	 * That is: the first and last pages (`end_size = 1` times two for each end),
	 * two pages on each side of the current (`mid_size = 2` times two for each side),
	 * plus the current page.  This is to prevent the pager from becomming too large.
	 * These arguments apply to both post- and archive-type pages, but do not
	 * apply if `next_or_number = 'next'`.
	 * 
	 * In most cases, this function can be used without arguments, however,
	 * if using the argument `next_or_number` = 'next', the following arguments
	 * are recommended:
	 * 
	 * * next_or_number = 'next'
	 * * before = '<ul class="pager">'
	 * * previouspagelink = __('Previous')
	 * * nextpagelink = __('Next')
	 * 
	 * @param array $args
	 * @return string Page links output
	 */
