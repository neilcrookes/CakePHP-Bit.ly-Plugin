<?php
/**
 * Rudimentary code for the purpose of demonstrating plugin functionality and
 * model API through which the plugin functionality is intended to be accessed.
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2010 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
echo $this->Form->create('BitlyLink');
echo $this->Form->input('longUrl', array('after' => 'E.g. http://www.neilcrookes.com'));
echo $this->Form->input('domain', array('options' => array('bit.ly' => 'bit.ly', 'j.mp' => 'j.mp')));
echo $this->Form->end('Shorten');
if (isset($bitlyLink)) {
  pr($bitlyLink);
}
?>
