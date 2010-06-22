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
echo $this->Form->create('BitlyLink', array('url' => array('action' => 'view')));
echo $this->Form->input('hash', array('type' => 'text', 'after' => 'I.e. the path part of the bit.ly link e.g. http://bit.ly/<b>dd2Ndi</b>'));
echo $this->Form->end('View clicks');
if (isset($bitlyLink)) {
  pr($bitlyLink);
}
?>