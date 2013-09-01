<?php
namespace Login\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Login\Model\Login;
use Login\Form\LoginForm;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

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
                // 新建一个auth adapter
                $auth = new AuthAdapter($db,
                    'users', // 表名
                    'username', // 用户名字段
                    'password', // 密码字段
                    'MD5(?) AND status = 0' // 加密算法
                );
                // 设置验证的数据,用传进来的登录表单数据。
                $auth->setIdentity($data['loginname'])->setCredential($data['loginpassword']);
                $result = $auth->authenticate(); // 验证
                $r = $result->getCode(); // 返回验证结果
                if($r == 1){
                    // 1为验证通过, -3是密码不对, -1是用户名不存在.
                    // Redirect to home
                    return $this->redirect()->toRoute('home');
                }
                //$message = $result->getMessages();
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