  <?php
// Previous/next page navigation.
ps_the_posts_pagination ( array (
'prev_text' => __ ( 'Previous', 'padsquad' ),
'next_text' => __ ( 'Next', 'padsquad' ),
'before_page_number' => '<span class="meta-nav screen-reader-text">' . __ ( 'Page', 'padsquad' ) . ' </span>'
) );
?>