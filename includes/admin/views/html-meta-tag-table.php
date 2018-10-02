<?php
/*
 * Displays the meta tags in a table.
 */

defined('ABSPATH') || die();

?>



<div class="wrap dpmt-table">
    <h1>Meta Tags</h1>

    <p>Click on an item to edit its meta tags. You can also set all of them to <b>autopilot</b> mode.
    <b>Autopilot</b> means that the plugin will retrieve the informations from the page itself.
    <a href="#" class="dpmt-toggle" data-toggle="1">Click here to learn how!</a></p>
    
    <div class="dpmt-hidden" data-toggle="1">
        <p><code>Posts:</code> title will be the post title, description will be the excerpt (if set) or the first few sentences, image will be the featured image or the first attached image</p>
        <p><code>Pages:</code> title will be the page title, description will be the first few sentences, image will be the featured image or the first attached image</p>
        <p><code>Categories, tags:</code> title will be the category/tag name, description will be the category/tag description</p>
        <p><code>Authors:</code> title will be the author name, description will be the biographical info</p>
        <p><code>Woo Product:</code> title will be the product name, description will be the short description, image will be the product image</p>
    </div>

    <div class="nav-tab-wrapper">
    <?php
        echo '
        <a href="options-general.php?page='. $_GET['page'] .'" 
            class="nav-tab'. (empty($_GET['tab']) ? ' nav-tab-active' : '') .'">Pages</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=posts" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'posts' ? ' nav-tab-active' : '') .'">Posts</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=categories" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'categories' ? ' nav-tab-active' : '') .'">Post Categories</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=tags" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'tags' ? ' nav-tab-active' : '') .'">Post Tags</a>        
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=authors" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'authors' ? ' nav-tab-active' : '') .'">Authors</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-products" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-products' ? ' nav-tab-active' : '') .'">Woo Products</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-categories" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-categories' ? ' nav-tab-active' : '') .'">Woo Categories</a>
        
        <a href="options-general.php?page='. $_GET['page'] .'&tab=woo-tags" 
            class="nav-tab'. (!empty($_GET['tab']) && $_GET['tab'] == 'woo-tags' ? ' nav-tab-active' : '') .'">Woo Tags</a>
        ';
    ?>        
    </div>

    <form method="POST">
        <div class="table-holder">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <?php
                    
                    foreach ($dpmt_meta_tag_list as $item => $details){
                        echo '<th>'. $item .' 
                        <span class="dashicons dashicons-editor-help" data-tip="'. esc_attr($details['info']) .'"></span></th>';
                    }

                    ?>                
                    <th>Custom tags <span class="dashicons dashicons-editor-help" data-tip="<?php 
                    echo __('Insert your custom meta tags here.', 'dmpt-meta-tags'); ?>"></span></th>
                </tr>
            </thead>

            <tbody>
            <?php

                $taginfo = new DPMT_Retrieve_Tags( $dpmt_meta_tag_list );


                // list all items
                $items_per_page = -1;

                if ( isset($_GET['tab']) ){

                    switch ( $_GET['tab'] ){

                        case 'posts':
                            $list = get_posts( array(
                                'post_status' => 'publish',
                                'posts_per_page' => $items_per_page
                            ) );

                            $type = 'post';
                            $query_ID = 'ID';
                            $query_title = 'post_title';
                            
                            break;


                        case 'categories':
                            $list = get_categories();

                            $type = 'category';
                            $query_ID = 'term_id';
                            $query_title = 'name';

                            break;


                        case 'tags':
                            $list = get_tags();

                            $type = 'tag';
                            $query_ID = 'term_id';
                            $query_title = 'name';

                            break;


                        case 'authors':
                            $list = get_users( array(
                                'orderby' => 'display_name'
                            ) );

                            $type = 'author';
                            $query_ID = 'ID';
                            $query_title = 'display_name';

                            break;
                            break;


                        case 'woo-products':
                            $list = get_posts( array(
                                'post_type' => 'product', 
                                'posts_per_page' => $items_per_page,
                                'orderby' => 'name',
                                'order' => 'ASC'
                            ) );

                            $type = 'woo-product';
                            $query_ID = 'ID';
                            $query_title = 'post_title';

                            break;


                        case 'woo-categories':
                            $list = get_terms( array(
                                'taxonomy' => 'product_cat'
                            ) );

                            $type = 'woo-category';
                            $query_ID = 'term_id';
                            $query_title = 'name';

                            break;


                        case 'woo-tags':
                            $list = get_terms( array(
                                'taxonomy' => 'product_tag'
                            ) );

                            $type = 'woo-tag';
                            $query_ID = 'term_id';
                            $query_title = 'name';

                            break;


                        default:

                            $list = array();

                            break;

                    }

                }else{

                    $list = get_pages( array(
                        'post_type' => 'page',
                        'post_status' => 'publish', 
                        'posts_per_page' => $items_per_page
                    ) );


                    if ( get_option('page_on_front') == 0 ){
                        
                        $frontpage = (object) [
                            'ID' => 'front',
                            'post_title' => 'Frontpage'
                        ];
                        array_unshift($list, $frontpage);
                        
                    }

                    $type = 'page';
                    $query_ID = 'ID';
                    $query_title = 'post_title';

                }

                

                if ( ! empty($list) ){
                    foreach ( $list as $item ){

                        echo '                
                        <tr>
                            <td>';
                                if ($item->{$query_ID} == 'front'){
                                    echo '<i><b><a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item->{$query_ID} .'">'. $item->{$query_title} .'</a></b></i>
                                    <span class="dashicons dashicons-editor-help" data-tip="'. 
                                    esc_attr('Your homepage displays the latest posts, you\'ll need meta tags there as well.')
                                    .'"></span>';
                                }else{
                                    echo '<a href="options-general.php?page='. $_GET['page'] .'&type='. $type .'&edit='. 
                                    $item->{$query_ID} .'">'. $item->{$query_title} .'</a>';
                                }
                            echo '
                            </td>';

                            $statuses = $taginfo->get_status( $type, $item->{$query_ID} );
                            foreach ($statuses as $group => $status){  
                                echo '<td>'. $status .'</td>';
                            }

                            echo '
                        </tr>
                        ';

                    }
                }

            ?>
            </tbody>    

            <tfoot>
                <tr>
                    <th><input type="submit" id="doaction" class="button action" value="Apply Bulk Actions"  /></th>
                    <?php 

                        foreach ($dpmt_meta_tag_list as $group => $info){
                            echo '
                            <td>
                                <select name="bulk-'. esc_attr($info['var']) .'" id="bulk-action-selector-bottom">
                                    <option value="-1">Bulk Actions</option>
                                    <option value="autopilot">Set all to autopilot</option>
                                    <option value="delete">Delete all</option>
                                </select>
                            </td>
                            ';
                        }

                    ?>
                    <td>
                        <select name="bulk-custom" id="bulk-action-selector-bottom">
                            <option value="-1">Bulk Actions</option>
                            <option value="delete">Delete all</option>
                        </select>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </form>

</div>