<?php
/**
 * Bitly Plugin App Model
 *
 * Configures all models in the plugin with respect to the datasource and table
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2010 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class BitlyAppModel extends AppModel {

  /**
   * The datasource all models in this plugin use
   *
   * @var string
   */
  var $useDbConfig = 'bitly';

  /**
   * The models in the plugin get data from the web service, so they don't need
   * a table.
   *
   * @var string
   */
  var $useTable = false;

  /**
   * The methods in the models affect this request property which is then used
   * in the datasource. The request property value set in each of the methods is
   * in the format of HttpSocket::request.
   *
   * @var array
   */
  var $request = array();

  /**
   * Overloads the Model::find() method. Resets request array in between calls
   * to Model::find()
   *
   * @param string $type
   * @param array $options
   * @return mixed
   */
  public function find($type, $options = array()) {
    $this->request = array();
    return parent::find($type, $options);
  }
  
}
?>
