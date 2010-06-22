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
class BitlyLinksController extends BitlyAppController {

  /**
   * The name of this controller
   *
   * @var string
   * @access public
   */
  public $name = 'BitlyLinks';

  /**
   * Demo action for showing the most recent bitly links from your account. Note
   * these are fetched from your account's recent links RSS feeds.
   *
   * @return void
   * @access public
   */
  public function index() {
    $bitlyLinks = $this->BitlyLink->find('recent');
    $this->set(compact('bitlyLinks'));
  }

  /**
   * Demo action for creating a bitly link in your account
   * 
   * @return void
   * @access public
   */
  public function add() {
    
    if (!empty($this->data)) {
      $bitlyLink = $this->BitlyLink->save($this->data);
      $this->set(compact('bitlyLink'));
    }

  }

  /**
   * Demo action for displaying the click information for a given bitly hash
   */
  public function view() {
    
    // If form submitted, redirect to same action with the form data in the url
    if (!empty($this->data['BitlyLink'])) {
      $this->redirect($this->data['BitlyLink']);
    }

    // If there is form data in the url, add it to Controller::data so it's
    // repopulated in the form, and specify it in the conditions option when
    // fetching the details from the web service.
    if (!empty($this->passedArgs)) {
      $this->data['BitlyLink'] = $this->passedArgs;
      $bitlyLink = $this->BitlyLink->find('clicks', array('conditions' => $this->passedArgs));
      $this->set(compact('bitlyLink'));
    }

  }
  
}
?>
