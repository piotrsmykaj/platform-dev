<?php

/**
 * @file
 * Contains \Drupal\nexteuropa_token\Tests\ViewModeEntityTokenHandlerTest
 */

namespace Drupal\nexteuropa_token\Tests\Entity;

use Drupal\nexteuropa_token\Entity\ViewModeTokenHandler;
use Drupal\nexteuropa_token\Tests\TokenHandlerAbstractTest;

class ViewModeEntityTokenHandlerTest extends TokenHandlerAbstractTest {

  /**
   * Instance of HashTokenHandler.
   *
   * @var \Drupal\nexteuropa_token\Entity\ViewModeTokenHandler
   */
  protected $handler;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->handler = new ViewModeTokenHandler();
  }

  /**
   * Test that HashTokenHandler::hookTokenInfoAlter() produces well-formed array.
   *
   * @dataProvider entityTypeMachineNamesProvider
   */
  public function testHookTokenInfoAlter($entity_type) {
    $data = array();
    $this->handler->hookTokenInfoAlter($data);

    $this->assertArrayHasKey('tokens', $data);
    $this->assertArrayHasKey($entity_type, $data['tokens']);

    foreach ($this->handler->getEntityViewModes($entity_type) as $view_mode) {
      $token_name = $this->handler->getTokenName($view_mode);
      $this->assertArrayHasKey($token_name, $data['tokens'][$entity_type]);
      $this->assertArrayHasKey('name', $data['tokens'][$entity_type][$token_name]);
      $this->assertArrayHasKey('description', $data['tokens'][$entity_type][$token_name]);
    }
  }

  /**
   * Test we get entity view modes correctly.
   *
   * @dataProvider entityTypeMachineNamesProvider
   */
  function testGetEntityViewModes($entity_type) {

    $view_modes = $this->handler->getEntityViewModes($entity_type);
    $this->assertTrue(in_array('full', $view_modes));
  }

  /**
   * Test that nexteuropa_token_token_info_alter() actually works.
   *
   * @dataProvider entityTypeMachineNamesProvider
   */
  public function testAvailableTokens($entity_type) {

  }

  /**
   * Test HashTokenHandler::hookTokens()
   */
  public function testNodeHookTokens() {

  }

  /**
   * Test hook_nexteuropa_token_token_handlers() implementation.
   */
  public function testHookHandler() {
    $handlers = module_invoke_all('nexteuropa_token_token_handlers');
    $this->assertArrayHasKey('view_mode_entity_handler', $handlers);
  }

  /**
   * Test we get entity view modes correctly.
   *
   * @dataProvider tokenOriginalValues
   */
  public function testParseToken($original, $entity_id, $view_mode) {

    $this->assertEquals($entity_id, $this->handler->getEntityIdFromToken($original));
    $this->assertEquals($view_mode, $this->handler->getViewModeFromToken($original));
  }

  /**
   * Data provider: provides list of token $original values.
   *
   * @return array
   */
  public static function tokenOriginalValues() {
    return array(
      // Valid tokens
      array('[node:1:view-mode:teaser]', 1, 'teaser'),
      array('[user:1:view-mode:full]', 1, 'full'),
      array('[term:1:view-mode:token]', 1, 'token'),
      // Not valid tokens
      array('[comment:123:view-mode:full]', '', ''),
      array('[any-text:123:view-mode:full]', '', ''),
      array('[not:valid:token]', '', ''),
      array('[node:123:view:mode]', '', ''),
      array('[node:123:view-modeee:full]', '', ''),
    );
  }


  /**
   * Data provider: provides list of entity machine names.
   *
   * @return array
   */
  public static function entityTypeMachineNamesProvider() {
    return array(array('node'), array('user'), array('term'));
  }
}
