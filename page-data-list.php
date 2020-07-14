<?php
/**
 * Template Name: Data List Page
 * Template Post Type: Page
 */
?>
<?php get_header(); the_post(); ?>

<div class="container mb-5 mt-3 mt-lg-5">
    <?php if( ! post_password_required( $post ) ): ?>
        <article class="<?php echo $post->post_status; ?> post-list-item">
            <?php the_content(); ?>
        </article>
        
        <?php
            $query = new WP_Query( array(
                'post_type' => 'post',
                'posts_per_page' => '-1',
                'post_status' => array(
                    'publish'
                )
            ) );
        ?>
        
        <table class="table table-bordered">
            <tr>
                <th>Post Title</th>
                <th>Post Excerpt</th>
                <!-- <th>Post Status</th> -->
                <th> </th>
                <!-- <th>Actions</th> --->
            </tr>
         
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                <tr>
                    <td><?php echo get_the_title(); ?></td>
                    <td><?php the_excerpt(); ?></td>
                    <!-- <td><?php //echo get_post_status( get_the_ID() ) ?></td> -->
                    <td><a href="/adapt-cc/edit-data?post=<?php echo get_the_ID();?>">Edit</a>
                        <!-- <?php //if( !(get_post_status() == 'trash') ) : ?>
                            <a onclick="return confirm('Are you sure you wish to delete post: <?php //echo get_the_title() ?>?')" href="<?php //echo get_delete_post_link( get_the_ID() ); ?>">Delete</a> -->
                         
                        <?php //endif; ?>
                    </td>
                </tr>    
            <?php endwhile; endif; ?>
        </table>
    <?php else: ?>
        <?php echo get_the_password_form(); ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>


