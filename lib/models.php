<?php
add_filter( 'psp_overview_fields', 'psp_add_buddyboss_groups_to_permissions' );
function psp_add_buddyboss_groups_to_permissions( $fields ) {

     $choices = array();

     if( current_user_can('edit_others_psp_projects') ) {

          $args = array(
               'type'    =>   'alphabetical',
               'per_page'     =>   -1
          );
          $groups = BP_Groups_Group::get($args);

          if( !empty($groups['groups']) ) {

               foreach( $groups['groups'] as $group ) {
                    $choices[ $group->id ] = $group->name;
               }

          }

     } else {

          $group_ids = BP_Groups_Member::get_group_ids( get_current_user_id() );

          if( !empty($group_ids['groups']) ) {

               foreach( $group_ids['groups'] as $group_id ) {

                    $group = groups_get_group(array( 'group_id' => $group_id ));

                    if( empty($group) ) {
                         continue;
                    }
                    $choices[$group_id] = $group->name;
               }

          }

     }

     $new_fields = array();

     $buddyboss_field = 	array(
			'key'       => 'psp_bb_groups',
			'label'     => __('Groups','psp_projects'),
			'name'      => 'buddypress_groups',
			'type'      => 'select',
               'choices'   => $choices,
               'multiple'  => 1,
               'ui'        =>  1,
	);


     foreach( $fields['fields'] as $field ) {

          if( $field['name'] == 'allowed_users' ) {
               $new_fields[] = $buddyboss_field;
          }

          $new_fields[] = $field;

     }

     $fields['fields'] = $new_fields;

     return $fields;

}
