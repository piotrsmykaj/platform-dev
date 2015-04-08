<?php

/**
 * @file
 * Contains \Drupal\nexteuropa_token\Tests\TokenHandlerAbstractTest
 */

namespace Drupal\nexteuropa_token\Tests;

abstract class TokenHandlerAbstractTest extends \PHPUnit_Framework_TestCase {

  /** Test fixtures */
  protected static $content_type = NULL;
  protected static $vocabulary = NULL;

  /**
   * Keep list of entities created during a test in an array keyed by
   * entity type. Entities will be deleted in tearDown()
   */
  protected $entities = NULL;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    if (!module_exists('nexteuropa_token')) {
      throw new \Exception('NextEuropa Token module must be enabled before running tests.');
    }
    $this->clearCache();
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown() {
    $this->deleteTestNodes();
  }

  /**
   * {@inheritdoc}
   */
  public static function setUpBeforeClass() {

    self::createTestContentType();
    self::createTestVocabulary();
  }

  /**
   * {@inheritdoc}
   */
  public static function tearDownAfterClass() {

    self::deleteTestContentType();
    self::deleteTestVocabulary();
  }

  /**
   * Clear only relevant caches for performance reasons.
   */
  protected function clearCache() {
    token_flush_caches();
    token_clear_cache();
  }

  /**
   * Get test content type.
   *
   * @return
   *    Content type object.
   */
  protected function getTestContentType() {
    return self::$content_type;
  }

  /**
   * Create test content type.
   */
  protected static function createTestContentType() {
    $name = self::randomName(8);
    $type = (object) array(
      'type' => $name,
      'name' => $name,
      'base' => 'node_content',
      'description' => '',
      'help' => '',
      'title_label' => 'Title',
      'body_label' => 'Body',
      'has_title' => 1,
      'has_body' => 1,
      'orig_type' => '',
      'old_type' => '',
      'module' => 'node',
      'custom' => 1,
      'modified' => 1,
      'locked' => 0,
    );

    node_type_save($type);
    node_types_rebuild();
    node_add_body_field($type);
    self::$content_type = $type;
  }

  /**
   * Delete text content type.
   */
  protected static function deleteTestContentType() {
    node_type_delete(self::$content_type->type);
  }

  /**
   * Get test vocabulary.
   *
   * @return
   *    Vocabulary object.
   */
  protected function getTestVocabulary() {
    return self::$vocabulary;
  }

  /**
   * Create test vocabulary.
   */
  protected static function createTestVocabulary() {
    $name = self::randomName(8);
    $vocabulary = (object) array(
      'name' => $name,
      'machine_name' => $name,
    );

    taxonomy_vocabulary_save($vocabulary);
    self::$vocabulary = $vocabulary;
  }

  /**
   * Delete test vocabulary.
   *
   * @throws \Exception
   */
  protected static function deleteTestVocabulary() {
    taxonomy_vocabulary_delete(self::$vocabulary->vid);
  }

  /**
   * Create and return test node.
   *
   * @return \stdClass
   * @throws \Exception
   */
  protected function getTestNode() {
    $content_type = $this->getTestContentType();
    $node = new \stdClass();
    $node->title = self::randomName(8);
    $node->type = $content_type->name;
    node_object_prepare($node);
    node_save($node);

    $this->entities['node'][] = $node->nid;
    return $node;
  }

  /**
   * Remove test content.
   */
  protected function deleteTestNodes() {
    if (isset($this->entities['node'])) {
      foreach ($this->entities['node'] as $nid) {
        node_delete($nid);
      }
    }
  }

  /**
   * Borrowed from DrupalTestCase::randomName().
   *
   * @param int $length
   * @return string
   */
  public static function randomName($length = 8) {
    $values = array_merge(range(65, 90), range(97, 122), range(48, 57));
    $max = count($values) - 1;
    $str = chr(mt_rand(97, 122));
    for ($i = 1; $i < $length; $i++) {
      $str .= chr($values[mt_rand(0, $max)]);
    }
    return $str;
  }
}
