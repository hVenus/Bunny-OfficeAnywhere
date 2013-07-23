<?php
namespace Login\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class Login implements InputFilterAwareInterface {

    public $loginname;
    public $loginpassword;
    protected $inputFilter;

    public function exchangeArray($data){
        $this->loginname = (isset($data['loginname']))?$data['loginname']:null;
        $this->loginpassword = (isset($data['loginpassword']))?$data['loginpassword']:null;
        $e->getApplication()->getServiceManager()->get('Zend\Db\Adapter\Adapter');
        $dbAdapter = \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter();
//        $dbAdapter = $db;
//        $authAdapter = new AuthAdapter($dbAdapter,
//            'users',
//            'username',
//            'password'
//        );

        // Set the input credential values (e.g., from a login form)
//        $authAdapter
//            ->setIdentity($this->loginname)
//            ->setCredential($this->loginpassword);

// Print the identity
       // echo $result->getIdentity() . "\n\n";

// Print the result row
//        print_r($authAdapter->getResultRowObject());
    }

    public function setInputFilter(InputFilterInterface $inputFilter){
        throw new Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'loginname',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 128,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'loginpassword',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 128,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }
}