<?php
namespace Framework;

use Framework\Session;

class Authorization {
  /**
   * Check if current logged in user owns a resource
   * 
   * @param int $resourceId
   * @return bool
   * 
   */
  public static function isOwner($resourceId) {
    // Get the session
    $sessionUser = Session::get('user');
    
    // Check session is not null and it has an id property set
    if ($sessionUser !== null && isset($sessionUser['id'])) {
      // Typecast the id property into an int same as:
      $sessionUserId = (int) $sessionUser['id'];

      // Return if the current user's session id is equal to the resource id
      return $sessionUserId === $resourceId;
    }

    return false;
  }
}
?>