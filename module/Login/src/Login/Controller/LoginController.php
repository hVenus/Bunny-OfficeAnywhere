<?php
namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Login\Model\Login;
use Login\Form\LoginForm;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

class LoginController extends AbstractActionController {

    private $adapter;

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

                $data = $form->getData();
                $db = $this->getAdapter(); // 获取db
                $auth = new AuthenticationService();
                $auth->setStorage(new SessionStorage('OASession')); // 用来存储用户Session
                // 新建一个auth adapter
                $AuthAdapter = new AuthAdapter($db,
                    'users', // 表名
                    'username', // 用户名字段
                    'password', // 密码字段
                    'MD5(?) AND status = 0' // 加密算法
                );
                // 设置验证的数据,用传进来的登录表单数据。
                $AuthAdapter->setIdentity($data['loginname'])->setCredential($data['loginpassword']);
                $result = $auth->authenticate($AuthAdapter); // 验证
                switch ($result->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        /** do stuff for nonexistent identity **/
                        echo "FAILURE_IDENTITY_NOT_FOUND";
                        break;
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        /** do stuff for invalid credential **/
                        echo "FAILURE_CREDENTIAL_INVALID";
                        break;
                    case Result::SUCCESS:
                        // Redirect to home
                        return $this->redirect()->toRoute('home');
                        break;
                    default:
                        /** do stuff for other failure **/
                        echo "Failure.";
                        break;
                }
            }
        }
        return array('form' => $form); // 显示Form
    }


    public function getAdapter(){
        if (!$this->adapter) {
            $sm = $this->getServiceLocator();
            $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }
}