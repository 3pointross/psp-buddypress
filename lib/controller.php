<?php
add_filter( 'psp_project_access_meta_query', 'psp_bb_group_access_meta_query' );
function psp_bb_group_access_meta_query( $meta_query ) {

     if( current_user_can('edit_others_psp_projects') ) {
          return $meta_query;
     }

     $group_ids = BP_Groups_Member::get_group_ids( get_current_user_id() );

     if( empty($group_ids['groups']) ) {
          return $meta_query;
     }

     $groups = array();

     foreach( $group_ids['groups'] as $group_id ) {
          $meta_query[] = array(
               'key'     => 'buddypress_groups',
               'value'   => $group_id,
               'compare' => 'LIKE'
          );
     }

     return $meta_query;

}

add_filter( 'panorama_check_access', 'psp_bb_groups_allow_project_access', 10, 2 );
function psp_bb_groups_allow_project_access( $result, $post_id ) {

     if( $result == true ) {
          return $result;
     }

     $buddypress_groups = get_field( 'buddypress_groups', $post_id );
     $group_ids = BP_Groups_Member::get_group_ids( get_current_user_id() );

     if( empty($group_ids['groups']) ) {
          return $result;
     }

     foreach( $group_ids as $group_id ) {
          if( in_array( $group_id, $buddypress_groups ) ) {
               return true;
          }
     }

     return $result;

}

add_filter( 'acf_users_value', 'psp_bb_add_group_users_to_users_field', 10, 2 );
function psp_bb_add_group_users_to_users_field( $users, $post_id = null ) {

     if( !$post_id ) {
          $post_id = get_the_ID();
     }

     $buddypress_groups = get_field( 'buddypress_groups', $post_id );

     if( empty($buddypress_groups) ) {
          return $users;
     }

     foreach( $buddypress_groups as $group_id ) {

          $query = apply_filters( 'psp_bb_add_group_users_to_user_field_query_string', 'group_id=' . $group_id . '&per_page=999&exclude_admins_mods=false' );

          if( bp_group_has_members($query) ) {
               while ( bp_group_members($query) ) { bp_group_the_member();
                    $users[] = bp_get_group_member_id();
               }
          }

     }

     return $users;

}

add_filter( 'acf/fields/user/query/key=field_532b8da69c46c', 'psp_bb_update_user_admin_list', 100, 3 );
function psp_bb_update_user_admin_list( $args, $field, $post_id ) {

     $group_ids = BP_Groups_Member::get_group_ids( get_current_user_id() );

     if( empty($group_ids['groups']) ) {
          return $args;
     }

     $user_ids = array();

     foreach( $group_ids as $group_id ) {

          $query = apply_filters( 'psp_bb_add_group_users_to_user_field_query_string', 'group_id=' . $group_id . '&per_page=999&exclude_admins_mods=false' );

          if( bp_group_has_members($query) ) {
               while ( bp_group_members($query) ) { bp_group_the_member();
                    $user_ids = bp_get_group_member_id();
               }
          }

     }

     if( empty($user_ids) ) {
          return $args;
     }

     if( isset($args['include'] ) ) {
          $args['include'] = array_merge( $args['include'], $user_ids );
     } else {
          $args['include'] = $user_ids;
     }

     return $args;

}
