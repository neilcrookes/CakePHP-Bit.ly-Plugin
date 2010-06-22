<?php

// Import the rest data source from the rest plugin
App::import('Datasouce', 'Rest.RestSource');

/**
 * CakePHP DataSource for accessing the Bitly API.
 *
 * Extends the Rest DataSource from the Rest plugin
 *
 * @author Neil Crookes <neil@neilcrookes.com>
 * @link http://www.neilcrookes.com
 * @copyright (c) 2010 Neil Crookes
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class BitlySource extends RestSource {

  /**
   * Overloads the RestSource::request() method to add Bitly API specific
   * elements to the request property of the passed model before sending it off
   * to the RestSource::request() method that actually issues the request and
   * decodes the response.
   *
   * @param AppModel $model The model the call was made on. Expects the model
   * object to have a request property in the form of HttpSocket::request
   * @return mixed
   */
  public function request(&$model) {

    if (!isset($model->request['uri']['host'])) {
      $model->request['uri']['host'] = 'api.bit.ly';
    }

    if (!isset($model->request['uri']['query']['login'])) {
      $model->request['uri']['query']['login'] = $this->config['login'];
    }

    if (!isset($model->request['uri']['query']['apiKey'])) {
      $model->request['uri']['query']['apiKey'] = $this->config['apiKey'];
    }
    
    $response = parent::request($model);

    if (!$response) {
      return $response;
    }

    if (substr($model->request['uri']['path'], -4) == '.rss') {
      return $response;
    }

    if (is_string($response)) {
      $response = json_decode($response, true);
    }

    if (!is_array($response)) {
      return $response;
    }

    if (!isset($response['status_code'])) {
      return $response;
    }

    if ($response['status_code'] != 200) {
      return false;
    }

    if (!isset($response['data'])) {
      return $response;
    }

    return $model->response = $response['data'];

  }

}
?>