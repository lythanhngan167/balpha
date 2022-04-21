<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
?>

<h3><?php
  $active = JFactory::getApplication()->getMenu()->getActive();
echo $active->title;
 ?></h3>
 <?php
$user    = JFactory::getUser();
//print_r($user);
if($user->id){
	//$username = $user->username;
  $username = $user->id;
	echo '
	<span>Link Landingpage của bạn: </span>
	<a href="'.JUri::root().'agent/'.$username.'.html'.'">';
	echo JUri::root()."agent/".$username.".html";
	echo '</a>';
	echo '
	<br><br>
	<span>Link Landingpage mẫu: </span>
	<a href="'.JUri::root().'agent/581.html'.'">';
	echo JUri::root()."agent/581.html";
	echo '</a>';
}

 ?>
