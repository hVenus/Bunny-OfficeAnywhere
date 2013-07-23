<?php
namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Login\Model\Login;
use Login\Form\LoginForm;

class LoginController extends AbstractActionController {

    public function indexAction(){
        $form = new LoginForm(); // Login form
        $form->get('submit')->setValue('GO'); // 设置提交按钮的显示文本

        $request = $this->getRequest();
        if ($request->isPost()) {
            // 如果有值传进来
            $login = new Login();
            $form->setInputFilter($login->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

//                $sm = $this->getServiceLocator();
//                $adapter = $sm->get('Zend\Db\Adapter\Adapter');

                $login->exchangeArray($form->getData());



                // Redirect to list of albums
                //return $this->redirect()->toRoute('home');
            }
        }

        return array('form' => $form); // 显示Form
    }
}