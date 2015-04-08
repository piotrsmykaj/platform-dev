<?php

/**
 * @file
 * Contains \Drupal\nexteuropa_token\TokenAbstractHandler
 */

namespace Drupal\nexteuropa_token;

/**
 * Class TokenAbstractHandler
 * @package Drupal\nexteuropa_token
 */
abstract class TokenAbstractHandler implements TokenHandlerInterface {

  /**
   * Returns true if passed argument is a valid token type.
   *
   * @param $type
   * @return bool
   */
  protected function isValidTokenType($type) {
    $types = $this->getEntityTokenTypes();
    return isset($types[$type]);
  }

  /**
   * Get list of entity info arrays, keyed by the entity's token-like name.
   *
   * @return array
   */
  protected function getEntityTokenTypes() {
    $return = array();
    foreach (entity_get_info() as $entity_type => $entity_info) {
      $entity_type = ($entity_type == 'taxonomy_term') ? 'term' : $entity_type;
      $return[$entity_type] = $entity_info;
    }
    return $return;
  }

  /**
   * Get an entity key ID given the entity's token-like name.
   *
   * @param $type
   * @return mixed
   */
  protected function getEntityKeysId($type) {
    $token_types = $this->getEntityTokenTypes();
    return $token_types[$type]['entity keys']['id'];
  }
}
