<?php
add_action( 'bp_setup_nav', 'psp_bb_projects_tab', 100 );
function psp_bb_projects_tab() {

     if( !bp_is_groups_component() ) {
          return;
     }

     global $bp;

     $slug  = psp_get_option( 'psp_slug', 'panorama' );

     bp_core_new_subnav_item( array(
          'name' => __( 'Projects', 'psp_projects' ),
          'slug' => $slug,
          'screen_function' => 'psp_bb_projects_screen',
          'position' => 40,
          'parent_url'      => bp_get_group_permalink( groups_get_current_group() ),
          'parent_slug'     => bp_get_current_group_slug(),
          'default_subnav_slug' => $slug
     ) );

}

function psp_bb_projects_screen() {

     // Add title and content here - last is to call the members plugin.php template.
     add_action( 'bp_template_title', 'psp_bb_projects_title' );
     add_action( 'bp_template_content', 'psp_bb_projects_content' );
     bp_core_load_template( 'members/single/plugins' );

}
function psp_bb_projects_title() {
     echo __( 'Projects', 'psp_projects' );
}

function psp_bb_projects_content() {

     $projects_per_page  = intval( psp_get_option( 'psp_projects_per_page', 10 ) );

     // TODO: Gotta pass in the status, type and search
     $status = apply_filters( 'psp_archive_project_listing_status', ( get_query_var('psp_status_page') ? get_query_var('psp_status_page') : 'active' ) );

     $meta_query = apply_filters( 'psp_groups_meta_query', array(
          array(
               'key'     => 'buddypress_groups',
               'value'   =>  bp_get_group_id(),
               'compare' => 'LIKE'
          )
     ) );

     $args = array(
          'post_type'         =>  'psp_projects',
          'posts_per_page'    =>  ( isset($_GET['count'] ) ? $_GET['count'] : $projects_per_page ),
          'meta_query'        =>  $meta_query
     );

     // Enqueue the assets!
     psp_front_assets(true);
     psp_bb_front_assets();

     $projects = new WP_Query($args); ?>

     <div id="psp-projects">
          <div class="pspbb-group-list">
               <?php
               if( function_exists('psp_fe_section_nav_items') && current_user_can('publish_psp_projects') ): ?>
                    <div class="pspbb-group-list__actions">
                         <?php
                         $url = get_post_type_archive_link('psp_projects') . 'manage/new/';
                         echo wp_kses_post('<a href="' . $url . '" class="psp-btn">' . __( 'New Project', 'psp_projects' ) . '</a>'); ?>
                    </div>
               <?php endif; ?>
               <div class="psp-archive-section">
               	<div class="psp-table-header">
               		<div class="psp-table-header__heading">
               			<div class="psp-h2"><?php esc_html_e( 'Projects', 'psp_projects' ); ?></div>
               		</div>
               	</div>
               	<div class="psp-archive-list-wrapper">
               		<?php echo psp_archive_project_listing( $projects ); ?>
               	</div>
               </div>
          </div>
     </div>

     <?php
}

function psp_bb_front_assets() {

     wp_register_style( 'psp-buddypress', PSP_BB_URL . 'assets/css/psp-buddypress.css', array(), PSP_BB_VER );
     wp_enqueue_style( 'psp-buddypress' );

}
