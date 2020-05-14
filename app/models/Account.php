<?php


use Phalcon\Mvc\Model\Query;

class Account extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $iD;

    /**
     *
     * @var string
     */
    public $nAME;

    /**
     *
     * @var string
     */
    public $bIRTHDAY;

    /**
     *
     * @var string
     */
    public $aDDRESS;

    /**
     *
     * @var integer
     */
    public $pHONE;

    /**
     *
     * @var string
     */
    public $sCHOOL;

    /**
     *
     * @var string
     */
    public $hOBBY;

    /**
     *
     * @var string
     */
    public $gMAIL;

    /**
     *
     * @var string
     */
    public $uSERNAME;

    /**
     *
     * @var string
     */
    public $pASSWORD;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("student_management");
        $this->setSource("account");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'account';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Account[]|Account|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Account|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
