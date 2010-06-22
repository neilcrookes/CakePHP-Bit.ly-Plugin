<?php
/**
 * BitlyLink model for bit.ly CakePHP plugin
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2010 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class BitlyLink extends BitlyAppModel {

  /**
   * Name of the model
   * 
   * @var string
   */
  public $name ='BitlyLink';

  /**
   * Model schema
   * 
   * @var array
   */
  public $_schema = array(
    'hash' => array('type' => 'string', 'length' => '6'),
    'longUrl' => array('type' => 'text'),
    'domain' => array('type' => 'string', 'length' => '20'),
  );

  /**
   * Model primary key
   *
   * @var string
   */
  public $primaryKey = 'hash';

  /**
   * Model validation rules
   * 
   * @var array
   */
  public $validate = array(
    'longUrl' => array(
      'notEmpty' => array(
        'rule' => 'notEmpty',
        'message' => 'Please enter a long url',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create',
        'last' => true,
      ),
      'url' => array(
        'rule' => 'url',
        'message' => 'Please enter a valid long url',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create',
        'last' => true,
      ),
    ),
    'domain' => array(
      'notEmpty' => array(
        'rule' => 'notEmpty',
        'message' => 'Please select a preferred domain',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create',
        'last' => true,
      ),
      'inList' => array(
        'rule' => array('inList', array('bit.ly', 'j.mp')),
        'message' => 'Please select a valid domain',
        'required' => true,
        'allowEmpty' => false,
        'on' => 'create',
        'last' => true,
      ),
    ),
  );

  /**
   * The model's custom find types
   * 
   * @var array
   */
  public $_findMethods = array(
    'expand' => true,
    'clicks' => true,
    'recent' => true,
  );

  /**
   * Custom find type to expand a given short url
   * @param string $state "before" or "after"
   * @param array $query Can include conditions for hash or shortUrl
   * @param array $results The results of the query
   * @return array The results of the query
   */
  protected function _findExpand($state, $query = array(), $results = array()) {
    if ($state == 'before') {
      $this->_setCommonFindRequestParams('expand', $query);
      return $query;
    } else {
      return $results;
    }
  }

  /**
   * Custom find type to clicks for a given hash or short url
   *
   * @param string $state
   * @param array $query
   * @param array $results
   * @return array
   */
  protected function _findClicks($state, $query = array(), $results = array()) {
    if ($state == 'before') {
      if (isset($query['conditions']['hash'])) {
        $this->request['uri']['query']['hash'] = $query['conditions']['hash'];
      } elseif ($query['conditions']['shortUrl']) {
        $this->request['uri']['query']['shortUrl'] = $query['conditions']['shortUrl'];
      } else {
        return false;
      }
      $this->request['uri']['path'] = '/v3/clicks';
      return $query;
    } else {
      return $results;
    }
  }

  /**
   * Custom find type to fetch recent links from user's bitly recent links rss
   * feed
   * 
   * @param string $state "before" or "after"
   * @param array $query
   * @param array $results
   * @return array
   */
  protected function _findRecent($state, $query = array(), $results = array()) {
    if ($state == 'before') {
      $this->request['uri']['host'] = 'bit.ly';
      $this->request['uri']['path'] = 'u/';
      if (isset($query['conditions']['login'])) {
        $this->request['uri']['path'] .= $query['conditions']['login'];
      } elseif (isset($this->getDataSource($this->useDbConfig)->config['login'])) {
        $this->request['uri']['path'] .= $this->getDataSource($this->useDbConfig)->config['login'];
      } else {
        return false;
      }
      $this->request['uri']['path'] .= '.rss';
      return $query;
    } else {
      App::import('Core', 'Xml');
      $Xml = new Xml($results);
      return $Xml->toArray();
    }
  }

  /**
   * Overrides Model::save() as that just returns boolean - we need to return
   * the full response from bit.ly after creating a short url.
   */
  public function save($data = null, $validate = true, $fieldList = array()) {

    $this->request = array(
      'method' => 'GET',
      'uri' => array(
        'path' => 'v3/shorten',
        'query' => array(
          'longUrl' => $data['BitlyLink']['longUrl'],
        ),
      ),
    );

    if (isset($data['BitlyLink']['domain'])) {
      $this->request['uri']['query']['domain'] = $data['BitlyLink']['domain'];
    }

    if (isset($data['BitlyLink']['x_login'])) {
      $this->request['uri']['query']['x_login'] = $data['BitlyLink']['x_login'];
    }

    if (isset($data['BitlyLink']['x_apiKey'])) {
      $this->request['uri']['query']['x_apiKey'] = $data['BitlyLink']['x_apiKey'];
    }

    $result = parent::save($data, $validate, $fieldList);

    if ($result) {
      $result = $this->response;
    }

    return $result;
    
  }

}

?>