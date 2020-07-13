<?php
/**
 * Template Name: Edit Data Page
 * Template Post Type: Page
 */
?>
<?php get_header(); the_post(); ?>

<?php

if ( isset( $_GET['post'] ) ) {
    $current_post = get_post( $_GET['post']);
    $title = $current_post->post_title;
    $content = $current_post->post_content;
}

else wp_redirect( '/adapt-cc/database/' );

if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "update_post" && wp_verify_nonce( $_REQUEST['_wpnonce'], 'update_post' )) {

    // Do some minor form validation to make sure there is content
    if (isset ($_POST['title'])) {
        $title =  $_POST['title'];
    } else {
        echo 'Please enter the title';
    }
    if (isset ($_POST['content'])) {
        $content = $_POST['content'];
    } else {
        echo 'Please enter contents';
    }

    $tags = $_POST['post_tags'];

    // ADD THE FORM INPUT TO $new_post ARRAY
    $update_post = array(
    'ID'            =>  $_GET['post'],
    'post_title'    =>  $title,
    'post_content'  =>  $content,
    'post_category' =>  $_POST['cat'],
    'tax_input'     =>  array( 'filter_terms' => $_POST['term']),
    'tags_input'    =>  $_POST['tag'],
    'post_status'   =>  'publish',
    'post_type' =>  'post',
    );

    //Update THE POST
    wp_update_post($update_post);

    wp_redirect( 'http://localhost:8888/wordpress/data-list/' );

} // END THE IF STATEMENT THAT STARTED THE WHOLE FORM

?>

<div class="container mb-5 mt-3 mt-lg-5">

    <form id="new_post" name="new_post" method="post" action="" class="wpcf7-form">
        <div class="form-group">
            <label for="title">Title:</label>
            <input class="form-control" type="text" id="title" value="<?php echo $title ?>" tabindex="5" name="title" />
        </div>

        <div class="form-group">
            <?php wp_editor( html_entity_decode($content), 'content', $settings = array() ); ?>
            <!-- <label for="content">Contents</label>
            <textarea class="form-control" id="content" tabindex="15" name="content" cols="80" rows="10"><?php //echo $content; ?></textarea> -->
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cat">Category</label><br>
                    <?php //wp_dropdown_categories( 'tab_index=10&taxonomy=category&hide_empty=0' );
                        $categories = get_categories( array(
                            'orderby' => 'name',
                            'hide_empty' => false,
                        ));   
                        foreach($categories as $category) {
                            $checked = has_category($category->cat_name,$_GET['post'])?'checked':'';
                            echo "<input type='checkbox' name='cat[]' value='$category->term_id' $checked /> ";
                            echo $category->cat_name; 
                            echo '<br>';
                        }
                     ?>
                </div>
            </div>
            <div class="col-md-4">
                <!-- filter terms -->
                <div class="form-group">
                    <label for="post_tags">Filter Terms</label><br>
                    <?php $filter_terms = get_terms( array(
                            'taxonomy' => 'filter_terms',
                            'hide_empty' => false,
                        ));

                        foreach($filter_terms as $term) {
                            $checked = has_term($term->name, 'filter_terms', $_GET['post'])?'checked':'';     
                            echo "<input type='checkbox' name='term[]' value='$term->term_taxonomy_id' $checked /> ";
                            echo $term->name; 
                            echo '<br>';
                        }
                     ?>
                </div>
            </div>
            <div class="col-md-4">
                <!-- post tags -->
                <div class="form-group">
                    <label for="post_tags">Tags</label><br>
                    <?php $tags = get_terms( array(
                            'taxonomy' => 'post_tag',
                            'hide_empty' => false,
                    ));
                    foreach($tags as $tag) {
                            $checked = has_tag($tag->name, $_GET['post'])?'checked':'';     
                            echo "<input type='checkbox' name='tag[]' value='$tag->name' $checked /> ";
                            echo $tag->name; 
                            echo '<br>';
                    }
                    ?>
                    <!-- <input type="text" value="" tabindex="35" name="post_tags" id="post_tags" /> -->
                </div>
            </div>
        </div>
        <input type="submit" value="Update Data" tabindex="40" id="submit" class="btn btn-primary" name="submit" />
        <input type="hidden" name="action" value="update_post" />
        
        <?php wp_nonce_field( 'update_post' ); ?>
        </div>    
    </form>
</div>

<?php get_footer(); ?>


