WPBootstrap_Pager
=================

Fully-Featured, customizable class for creating Bootstrap-style pagination links for many page types in WordPress

### Default Arguments

```php
		$defaults = array(
			'before'           => '<ul class="pagination">',
			'after'            => '</ul>',
			'link_before'      => '<li>',
			'link_after'       => '</li>',
			'current_before'   => '<li class="active">',
			'current_after'    => '</li>',
			'currentlink'      => '% <span class="sr-only">'. __('(current)') . '</span>',
			'previouspagelink' => '&laquo;',
			'nextpagelink'     => '&raquo;',
			'disabled_before'  => '<li class="disabled">',
			'disabled_after'   => '</li>',
			'next_or_number'   => 'number',
			'pagelink'         => '%',
			'echo'             => 1,
			'previous_before'  => '<li class="previous">',
			'previous_after'   => '</li>',
			'next_before'      => '<li class="next">',
			'next_after'       => '</li>',
			'show_all'         => false,
			'end_size'         => 1,
			'mid_size'         => 2,
			'hellip'           => '<li class="disabled"><a href="#">&hellip;</a></li>',
		);
```

### Parameters

before
: (string) Text to put before all the links. Defaults to `<ul class="pagination">`.
