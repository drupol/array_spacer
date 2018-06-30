<?php

namespace drupol\array_spacer;

class ArraySpacer implements \ArrayAccess, \IteratorAggregate, \Countable, \JsonSerializable {
  /**
   * The storage.
   *
   * @var array
   */
  private $storage;

  /**
   * The configuration.
   *
   * @var array
   */
  private $configuration;

  /**
   * ArraySpacer constructor.
   *
   * @param int $spacer
   * @param int $spacer_start
   */
  public function __construct($spacer = 1, $spacer_start = 0) {
    $this->configuration['spacer'] = $spacer;
    $this->configuration['start_at'] = $spacer_start;
  }

  /**
   * Whether a offset exists
   *
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   *
   * @param mixed $offset <p>
   * An offset to check for.
   * </p>
   *
   * @return boolean true on success or false on failure.
   * </p>
   * <p>
   * The return value will be casted to boolean if non-boolean was returned.
   * @since 5.0.0
   */
  public function offsetExists($offset) {
    return array_key_exists($offset, $this->storage);
  }

  /**
   * Offset to retrieve
   *
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   *
   * @param mixed $offset <p>
   * The offset to retrieve.
   * </p>
   *
   * @return mixed Can return all value types.
   * @since 5.0.0
   */
  public function offsetGet($offset) {
    return $this->storage[$offset];
  }

  /**
   * Offset to set
   *
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   *
   * @param mixed $offset <p>
   * The offset to assign the value to.
   * </p>
   * @param mixed $value <p>
   * The value to set.
   * </p>
   *
   * @return void
   * @since 5.0.0
   */
  public function offsetSet($offset, $value) {
    if (is_null($offset)) {
      $this->storage[] = $value;
    } else {
      $this->storage[$offset] = $value;
    }
  }

  /**
   * Offset to unset
   *
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   *
   * @param mixed $offset <p>
   * The offset to unset.
   * </p>
   *
   * @return void
   * @since 5.0.0
   */
  public function offsetUnset($offset) {
    unset($this->storage[$offset]);
  }

  /**
   * Retrieve an external iterator
   *
   * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
   * @return Traversable An instance of an object implementing <b>Iterator</b> or
   * <b>Traversable</b>
   * @since 5.0.0
   */
  public function getIterator() {
    return new \ArrayIterator($this->get());
  }

  /**
   * @return array
   */
  public function toArray() {
    return $this->get();
  }

  /**
   * @param array $input
   * @param int $spacer
   * @param int $start
   *
   * @return array
   */
  private function spacer(array $input, $spacer = 1, $start = 0) {
    $return = array();

    $i = 0;
    foreach ($input as $key => $value) {
      $key = is_int($key) ?
        ($i++ * $spacer) + $start :
        $key;

      $return[$key] = $value;
    }

    return $return;
  }

  /**
   * @return array
   */
  private function get() {
    return $this->spacer(
      $this->storage,
      $this->configuration['spacer'],
      $this->configuration['start_at']
    );
  }

  /**
   * Count elements of an object
   *
   * @link http://php.net/manual/en/countable.count.php
   * @return int The custom count as an integer.
   * </p>
   * <p>
   * The return value is cast to an integer.
   * @since 5.1.0
   */
  public function count() {
    return count($this->get());
  }

  /**
   * Specify data which should be serialized to JSON
   *
   * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
   * @return mixed data which can be serialized by <b>json_encode</b>,
   * which is a value of any type other than a resource.
   * @since 5.4.0
   */
  public function jsonSerialize() {
    return $this->get();
  }
}
