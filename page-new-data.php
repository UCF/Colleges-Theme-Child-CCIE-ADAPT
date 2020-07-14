<?php
/**
 * Template Name: New Data Page
 * Template Post Type: Page
 */
?>

<?php
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "new_post") {

    // Do some minor form validation to make sure there is content
    if (isset($_POST['title'])) {
        $title = sanitize_text_field( $_POST['title'] );
    } else {
        echo 'Please enter the wine name';
    }
    if (isset($_POST['content'])) {
        //$content = sanitize_text_field( htmlentities($_POST['content']) );
        $content = $_POST['content'];
    } else {
        echo 'Please enter some notes';
    }

    //$tags = $_POST['post_tags'];

    // ADD THE FORM INPUT TO $new_post ARRAY
    $new_post = array(
    'post_title'    =>  $title,
    'post_content'  =>  $content,
    'post_category' =>  $_POST['cat'],
    //'tax_input'     =>  array( 'filter_terms' => $_POST['term']),
    'tags_input'    =>  $_POST['tag'],
    'post_status'   =>  'publish',
    'post_type' =>  'post',
    );

    //SAVE THE POST
    $pid = wp_insert_post($new_post);
    wp_set_object_terms($pid, $_POST['term'], 'filter_terms');

    //KEEPS OUR COMMA SEPARATED TAGS AS INDIVIDUAL
    // wp_set_post_tags($pid, $_POST['post_tags']);

    // //REDIRECT TO THE NEW POST ON SAVE
    // $link = get_permalink( $pid );
    // wp_redirect( $link );
    //wp_redirect( 'https://cciecedhpcmsqa.smca.ucf.edu/adapt-cc/data-list' );
    wp_redirect( get_site_url() . '/data-list' );

} // END THE IF STATEMENT THAT STARTED THE WHOLE FORM

//POST THE POST YO
//do_action('wp_insert_post', 'wp_insert_post');

?>

<?php get_header(); the_post(); ?>

<div class="container mb-5 mt-3 mt-lg-5">
    
    <form id="new_post" name="new_post" method="post" action="" class="wpcf7-form">
        <div class="form-group">
            <label for="title">Title:</label>
            <input class="form-control" type="text" id="title" value="" tabindex="5" name="title" />
        </div>

        <div class="form-group">
            <?php wp_editor( "", 'content', $settings = array() ); ?>
            <!-- <label for="content">Contents</label>
            <textarea class="form-control" id="content" tabindex="15" name="content" cols="80" rows="10"></textarea> -->
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
                            echo "<input type='checkbox' name='cat[]' value='$category->term_id' /> ";
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
                            echo "<input type='checkbox' name='term[]' value='$term->term_taxonomy_id' /> ";
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
                            echo "<input type='checkbox' name='tag[]' value='$tag->name' /> ";
                            echo $tag->name; 
                            echo '<br>';
                    }
                    ?>
                    <!-- <input type="text" value="" tabindex="35" name="post_tags" id="post_tags" /> -->
                </div>
            </div>
        </div>

        <input type="submit" value="Add Data" tabindex="40" id="submit" class="btn btn-primary" name="submit" />
        <input type="hidden" name="action" value="new_post" />
        
        <?php wp_nonce_field( 'new-post' ); ?>
        </div>    
    </form>
</div>

<?php get_footer(); ?>


