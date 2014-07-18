<?php
/**
 * WPBootstrap Pager Class
 * 
 * @author Jamin VanderBerg
 * @copyright 2014 Jamin VanderBerg
 * @version 1.1
 * @license http://opensource.org/licenses/MIT
 */

/**
 * Functions to Create Bootstrap Pagination Links for Wordpress Pages
 * 
 * This class contains two methods for creating Bootstrap pagination
 * links in Wordpress.  The method post_pager() creates pagination
 * links for posts and pages.  The method archive_pager() creates
 * pagination links for archive-type pages (archives, search, and blog pages).
 * 
 * I've created them as a class with static methods, because I prefer this
 * pattern.  This design also allows for protected function accessible to both
 * methods.  For simplifying calls to these methods, I've added the global
 * functions wpbootstrap_post_pager() and wpbootstrap_archive_pager() that call
 * the static methods of Bootstrap_Pager.
 * 
 */
class WPBootstrap_Pager {
	/**
	 * Override wp_link_pages to allow for Bootstrap classes
	 * 
	 * This is a modified version of wp_link_pages(), designed to suit Bootstrap
	 * pagination better. 
	 *  
	 * @param array $args See README for a list of arguments.
	 * @return string Pager output
	 */
	public static function post_pager( $args = array() ) {
		$args = self::_parse_args( $args );
		
		global $page, $numpages;
		
		return self::_pager_output( $page, $numpages, $args, 'post' );
	}

	/**
	 * Provide pager interface for archive-type pages
	 * 
	 * This method is designed to create pagination similar to paginate_links(),
	 * but using the same interface as post_pager()
	 * 
	 * @param array $args See README for a list of arguments.
	 * @return string Pager output
	 */
	public static function archive_pager( $args = array() ) {
		$args = self::_parse_args( $args );
		
		global $wp_query;
		
		// Get the current page
		$paged = get_query_var( 'paged' );
		if ( ! $paged ) { $paged = 1; }
		
		// Get the number of pages
		$max_num_pages = intval( $wp_query->max_num_pages );
		
		return self::_pager_output( $paged, $max_num_pages, $args, 'archive' );
	}
	
	/**
	 * Parse Arguments
	 * 
	 * This protected method provides a consistent list of default arguments
	 * for all page types.
	 * 
	 * @access protected
	 * @param array $args
	 * @return array
	 */	
	protected static function _parse_args( $args ) {
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
			'currentlink'      => '% <span class="sr-only">'. __('(current)') . '</span>',
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
		
		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'wp_link_pages_args', $args );
		
		return $args;
	}
	
	/**
	 * Create the pager output
	 * 
	 * This protected method creates the pager output for each type of page.
	 * It provides consistancy across all page types.
	 * 
	 * @todo Break into _pager_output_next() and _pager_output_number() methods.
	 * @param int $current_page Current page number
	 * @param int $num_pages Total number of pages
	 * @param array $args Arguments passed from main function
	 * @param string $type Page type ('post' or 'archive')
	 */
	protected static function _pager_output( $current_page, $num_pages, $args, $type ) {
		$output = '';
		
		if ( 1 < $num_pages ) {
			// Extract our arguments
			extract( $args, EXTR_SKIP );
			
			if ( 'number' == $next_or_number ) {
				// Display page numbers instead of next/previous links.
				// This is the default behavior
				// This implementation includes next/previous links regardless.
					
				// Add the `before` argument
				$output .= $before;
		
				// Link to previous page
				if(1 == $current_page) {
					// This is page 1
					// Use `disabled_before` and `disabled_after`
					$b = $disabled_before; $a = $disabled_after;
				} else {
					// This is not page 1
					// Use normal `link_before` and `link_after` arguments
					$b = $link_before; $a = $link_after;
				}
				$output .= $b . self::_get_page_link($current_page - 1, $type) . $previouspagelink . '</a>' . $a;
		
				// Loop through the pages
				$dots = false;
				for ( $i = 1; $i < $num_pages + 1; $i++ ) {
		
					if ( $i == $current_page ) {
						// Current page
						// Use `currentlink` argument instead of `pagelink`
						$j = str_replace( '%', $i, $currentlink );
		
						// User `current_before` and `current_after`
						$output .= $current_before
							. self::_get_page_link($i, $type) . $j . '</a>'
							. $current_after;
						$dots = true;
					} /* if ( $i == $current_page ) */ else {
						if ( $show_all 
							|| ( $i <= $end_size )
							|| ( ( $i >= ( $current_page - $mid_size ) ) && ( $i <= ( $current_page + $mid_size ) ) )
							|| ( $i > ( $num_pages - $end_size ) )
						) {
							// Display this page number
							$j = str_replace( '%', $i, $pagelink );
			
							$output .= $link_before
								. self::_get_page_link($i, $type) . $j . '</a>'
								. $link_after;
							$dots = true;
						} /* if ( <display page> ) */ else if ( $dots ) {
							$output .= $hellip;
							$dots = false;
						} // else if ( $dots )
					} // if ( $i == $current_page ) : else
				} // for ( $i = 1; $i < $num_pages + 1; $i++ )
		
				// Link to next page
				if($current_page == $num_pages) {
					// This is the last page
					// Use `disabled_before` and `disabled_after`
					$b = $disabled_before; $a = $disabled_after;
				} else {
					// This not the last page
					// Use normal `link_before` and `link_after` arguments
					$b = $link_before; $a = $link_after;
				}
				$output .= $b . self::_get_page_link($current_page + 1, $type) . $nextpagelink . '</a>' . $a;
		
				// Add the final `after` argument
				$output .= $after;
			} else { // if ( 'number' == $next_or_number )
				// Display next/previous links instead of page numbers

				// Add the `before` argument
				$output .= $before;
	
				// Link to previous page
				$i = $current_page - 1;
				if( $i ) {
					$output .= $previous_before
					. self::_get_page_link($i, $type) . $previouspagelink . '</a>'
									. $previous_after;
				}
					
				// Link to next page
				$i = $current_page + 1;
				if( $i <= $num_pages ) {
					$output .= $next_before
					. self::_get_page_link($i, $type) . $nextpagelink . '</a>'
									. $previous_after;
				}
					
				// Add the final `after` arguement
				$output .= $after;
			} // if ( 'number' == $next_or_number )
		} // if ( $multipage )
		
		if ( $echo ) {
			echo $output;
		}
			
		return $output;
	}
	
	/**
	 * Create the link for each page
	 * 
	 * This protect member calls the relevant WordPress function to create
	 * the appropriate page link for the page type.
	 * 
	 * NOTE: This calls _wp_link_page, which is considered a __private__
	 * WordPress function.  This may cause compatibility issues with future
	 * versions of WordPress.
	 * 
	 * @access protected
	 * @param int $pagenum
	 * @param string $type
	 * @return string Page link
	 */
	protected static function _get_page_link( $pagenum, $type ) {
		if ( 'post' == $type ) {
			return _wp_link_page( $pagenum );
		} else if ( 'archive' == $type ) {
			return '<a href="' . get_pagenum_link( $pagenum ) . '">';
		}
	}
}

/**
 * Creates Bootstrap pagination for posts and pages
 * 
 * Calls BootstrapPager::post_pager()
 *  
 * @param array $args See README for a list of arguments.
 * @return string Pager output
 */
function wpbootstrap_post_pager( $args = array() ) {
	WPBootstrap_Pager::post_pager( $args );
}
/**
 * Creates Bootstrap pagination for archive-type pages,
 * which includes archives, search, and blog pages.
 * 
 * Calls BootstrapPager::archive_pager()
 *  
 * @param array $args See README for a list of arguments.
 * @return string Pager output
 */
function wpbootstrap_archive_pager( $args = array() ) {
	WPBootstrap_Pager::archive_pager( $args );
}
