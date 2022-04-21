<?php
if (($_REQUEST['user_id']) > 0 && ($_REQUEST['user_rating_id']) > 0 && ($_REQUEST['star']) > 0) {
  $user = JFactory::getUser($_REQUEST['user_id']);
  if($user->id > 0){
      //updating star for user in table user
      $object = new stdClass();
      $object->id = $user->id;
      $object->rating_times = $user->rating_times + 1;
      $object->star = (($user->star + $_REQUEST['star'])/($object->rating_times));
      $object = JFactory::getDbo()->updateObject('#__users', $sale, 'id');

      // insert rating times in table user_star_rating
      $rating = new stdClass();
      $rating->user_id = $user->id;
      $rating->user_id_rating = $_REQUEST['user_rating_id']);
      $rating->star = $_REQUEST['star']);
      $result2 = JFactory::getDbo()->insertObject('#__user_star_rating', $rating);

      if ($result && $result2) {
        echo  '1';
      }else{
        echo '0';
      }
    }else{
       echo '0';
    }
  }
  else {
    echo "0";
  }

    // insert rating times in table user_star_rating
//     $rating = new stdClass();
//     $rating->user_id = $user->id;
//     $rating->user_id_rating = $_REQUEST['user_rating_id']);
//     $rating->star = $_REQUEST['star']);
//     $result2 = JFactory::getDbo()->insertObject('#__user_star_rating', $rating);
//     if ($result2) {
//       echo  '1';
//     }else{
//       echo '0';
//     }
//
//   }else{
//      echo '0';
//   }
// }
// else {
//   echo "0";
// }

		exit();
 ?>
