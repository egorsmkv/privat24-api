<?php

namespace Privat24;

class Merchant
{
    /** @var bool|integer The ID */
    private $_id;

    /** @var bool|string The password */
    private $_password;

    /** @var bool Is it testing? */
    private $_test;

    // todo: add comment here
    private $_wait;

    /**
     * @var array Accounts of the merchant
     */
    private $_account = [
        'default' => null,
    ];

    /**
     * Merchant constructor.
     *
     * @param array $conf Configuration array for the merchant. Required keys are 'id', 'password', 'test', 'wait'
     */
    public function __construct(array $conf)
    {
        if (is_array($conf)) {
            $this->_id = array_key_exists('id', $conf) ? $conf['id'] : false;
            $this->_password = array_key_exists('password', $conf) ? $conf['password'] : false;
            $this->_test = array_key_exists('test', $conf) ? $conf['test'] : false;
            $this->_wait = array_key_exists('wait', $conf) ? $conf['wait'] : 0;
        }

        $this->_account['default'] = new Account($this);
    }

    /**
     * The merchant ID
     *
     * @return bool|int|mixed
     */
    public function id()
    {
        return $this->_id;
    }

    /**
     * Is it a test merchant?
     *
     * @return bool|mixed
     */
    public function test()
    {
        return $this->_test;
    }

    // todo: add comment here
    public function wait()
    {
        return $this->_wait;
    }

    /**
     * Get an account by its name
     *
     * @param null|string $acc Account name
     *
     * @return Account|mixed
     */
    public function account($acc = null)
    {
        if (isset($acc) && !empty($acc)) {
            if (array_key_exists($acc, $this->_account)) {
                return $this->_account[$acc];
            }

            return new Account($this, $acc);
        }

        return $this->_account['default'];
    }

    /**
     * Get balance of default merchant account
     *
     * @see https://api.privatbank.ua/balance.html)
     *
     * @return array Info about current balance
     */
    public function balance()
    {
        return $this->account()->balance();
    }

    /**
     * Get info about default merchant account
     *
     * @see https://api.privatbank.ua/balance.html)
     *
     * @return array Info about the default account
     */
    public function info()
    {
        return $this->account()->info();
    }

    /**
     * @string Convert data to signature
     *
     * @param string $data
     *
     * @return string A calculated signature
     */
    public function calcSignature($data)
    {
        return sha1(md5($data . $this->_password));
    }
}
