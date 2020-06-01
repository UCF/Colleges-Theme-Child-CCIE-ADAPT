<?php
/**
 * Template Name: Database Page
 * Template Post Type: Page
 */
?>
<?php get_header(); the_post(); ?>

<div class="container mb-5 mt-3 mt-lg-5">
    <article class="<?php echo $post->post_status; ?> post-list-item">
        <?php the_content(); ?>
    </article>

    <?php
        
        $categories = get_categories( array(
            'orderby' => 'name',
            'hide_empty' => false,
        ));
        
        $tags = get_terms( array(
            'taxonomy' => 'post_tag',
            'hide_empty' => false,
        ));

        $terms = get_terms( array(
            'taxonomy' => 'filter_terms',
            'hide_empty' => false,
            ));
    ?>

    <div class="row pt-5">
        <div class="col-3 mt-5">
            <h5>Filters and Search</h5>
            <!-- Search form -->
            <form id="search-form">
                <div class="form-group form-inline">
                    <div id="left-dt-search"></div>
                    <!-- <input type="text" class="form-control" placeholder="search" aria-label="search" id="left-search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="left-search-btn">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div> -->
                </div>
                
                <h6 class="pt-3">Strategies</h6>
                <?php foreach( $categories as $category ): ?>
                <div class="form-check">
                    <input type="checkbox" name="categories[]" value="<?php echo $category->term_taxonomy_id; ?>">
                    <?php echo $category->name; ?>
                </div>
                <?php endforeach; ?>

                <h6 class="pt-3">Filter terms</h6>
                <?php foreach( $terms as $term ): ?>
                    <div class="form-check">
                        <input type="checkbox" name="terms[]" value="<?php echo $term->term_taxonomy_id; ?>">
                        <?php echo $term->name; ?>
                    </div>
                <?php endforeach; ?>
                
                <h6 class="pt-3">Tags</h6>
                <?php foreach( $tags as $tag ): ?>
                    <div class="form-check">
                            <input type="checkbox" name="tags[]" value="<?php echo $tag->term_taxonomy_id; ?>">
                            <?php echo $tag->name; ?>
                    </div>
                <?php endforeach; ?>

                <button type="reset" class="btn btn-primary btn-sm search-filter-reset mt-3"><i class="fa fa-times-circle"></i> Reset Search</button>
            </form>

            <div id="left-dt-export" class="pt-3"></div>
            
</button>
        </div>

        <div class="col-9">

            <table id="adapt-data" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Strategies</th>
                        <th>Term</th>
                        <th>Slug</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Term</th>
                        <th>Slug</th>
                    </tr>
                </tfoot>
            </table>
        
        </div>
    </div>


</div>

<?php get_footer(); ?>


