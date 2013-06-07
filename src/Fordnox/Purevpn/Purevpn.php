<?php

/**
 * A simple wrapper for the PureVPN API.
 *
 * @package Fordnox
 * @author  Andrius Putna <fordnox@gmail.com>
 * @license MIT
 */

namespace Fordnox\Purevpn;

class Purevpn
{
    protected $options = array(
        'period'        =>  '#30',
        'api_url'       =>  'http://83.222.249.94/reseller_test/rs/api.php',
        'api_user'      =>  '',
        'api_password'  =>  '',
    );

    public function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);
    }

    public function createAccount(Account $account)
    {
        $params = array(
            'package_type'  =>  $account->getType(),
            'period'        =>  $account->getPeriod(),
            'method'        =>  'create',
        );
        $result = $this->_request($params);

        $account->setStatus('enabled');
        $account->setUsername($result['user']);
        $account->setPassword($result['vpn_password']);

        return $account;
    }

    public function renewAccount(Account $account)
    {
        $params = array(
            'username'      =>  $account->getUsername(),
            'period'        =>  $account->getPeriod(),
            'method'        =>  'renew',
        );
        $result = $this->_request($params);
        return ($result['result'] == 1);
    }

    public function disableAccount(Account $account)
    {
        $params = array(
            'username'      =>  $account->getUsername(),
            'update_status' =>  'disable',
            'method'        =>  'update_status',
        );
        $result = $this->_request($params);
        return ($result['result'] == 1);
    }

    public function enableAccount(Account $account)
    {
        $params = array(
            'username'      =>  $account->getUsername(),
            'update_status' =>  'enable',
            'method'        =>  'update_status',
        );
        $result = $this->_request($params);
        return ($result['result'] == 1);
    }

    public function changeAccountPassword(Account $account)
    {
        $params = array(
            'username'      =>  $account->getUsername(),
            'new_pass'      =>  $account->getPassword(),
            'method'        =>  'change_password',
        );
        $result = $this->_request($params);
        return ($result['result'] == 1);
    }

    public function deleteAccount(Account $account)
    {
        $params = array(
            'username'      =>  $account->getUsername(),
            'method'        =>  'delete_account',
        );
        $result = $this->_request($params);
        return ($result['result'] == 1);
    }

    public function getAccountStatus(Account $account)
    {
        $params = array(
            'username'      =>  $account->getUsername(),
            'method'        =>  'status',
        );
        $result = $this->_request($params);

        if($result['status'] == 'Deleted/Not Found') {
            $account->setStatus('deleted');
            return $account;
        }

        list($status, $time) = explode(':', $result['status']);

        if(strtolower($status) == 'disabled') {
            $account->setStatus('disabled');
        } else {
            $account->setStatus('enabled');
        }
        return $account;
    }

    protected function _request(array $params)
    {
        $params['API']          = 'True';
        $params['api_user']     = $this->options['api_user'];
        $params['api_password'] = $this->options['api_password'];

        //period param is always required for some strange reason
        if(!isset($params['period'])) {
            $params['period'] = $this->options['period'];
        }

        $url = $this->options['api_url'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,               $url);
        curl_setopt($ch, CURLOPT_REFERER,           $url);
        curl_setopt($ch, CURLOPT_POST,              true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,        http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
        $result = curl_exec($ch);

        if($result === false) {
            $e = new Exception(sprintf('Curl Error: "%s"', curl_error($ch)));
            curl_close($ch);
            throw $e;
        }

        curl_close($ch);

        return $this->__parseResponse($result);
    }

    protected function __parseResponse($string)
    {
        $xml = @simplexml_load_string($string);
        if(!$xml) {
            throw new Exception($string);
        }

        $array = array();
        foreach($xml as $k => $v) {
            $array[$k] = (string)$v;
        }

        return $array;
    }
}