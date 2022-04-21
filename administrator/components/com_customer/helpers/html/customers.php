<?php
defined('_JEXEC') or die;

/**
 * Extended Utility class for the Customers component.
 *
 * @since  2.5
 */
abstract class JHtmlCustomers
{
    public static function trashAprove($seft = false) {
		if($seft) {
			$states = array(
				0 => array(
					'task'           => 'trashapprove',
					'text'           => 'unapprove',
					'active_title'   => 'Duyệt',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'unpublish',
					'inactive_class' => 'unpublish',
                ),
                1 => array(
					'task'           => 'untrashapprove',
					'text'           => 'approve',
					'active_title'   => 'Duyệt',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'publish',
					'inactive_class' => 'publish',
				)
			);
		} else {
			$states = array(
				0 => array(
					'task'           => 'trashreject',
					'text'           => 'reject',
					'active_title'   => 'Từ chối',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'unpublish',
					'inactive_class' => 'unpublish',
					'backgroup_color'=>'red'
				),
                2 => array(
					'task'           => 'untrashreject',
					'text'           => 'reject',
					'active_title'   => 'Từ chối',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'publish',
					'inactive_class' => 'publish',
				)
			);
		}
		// $state  = JArrayHelper::getValue($states, 0, $states[1]);
        // $html   = '<span class="icon-'.$states[1]['text'].' aria-hidden="true"></span>';
        // //if ($canChange) {
        //     $html   = '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
        //             . $html.'</a>';
		// //}
		

        return $states;
	}
}
