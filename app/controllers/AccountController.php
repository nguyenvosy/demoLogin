<?php
use Phalcon\Session\Factory;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Filter;
class AccountController extends ControllerBase
{

    public function indexAction()
    {
        if ($this->request->isPost()) {
            $validate = $this->request->getQuery('validate');
            if($validate == "true") {
                $data = $this->request->getPost();
                $userName =$data['userName'];
                $passWord = $data['passWord'];
                $passWordHash = md5($passWord);
                $studentAccount = Account::findFirst([
                    'USERNAME =:userName: AND PASSWORD = :passWord:' ,
                    'bind'=>[
                        'userName' => $userName,
                        'passWord' => $passWordHash
                        
                    ]
                ]);
                
    
                if($studentAccount) {
                    $user = [];
                    $user['ID'] = $studentAccount->ID;
                    $user['GROUP_ID'] = $studentAccount->GROUP_ID;
                    $this->session->set('user', $user);
                    return  $this->response->setJsonContent(
                        [
                            "result" => "ok",
                        ]
                    );
                } else {
                    return  $this->response->setJsonContent(
                        [
                            "result" => "error",
                        ]
                    );
                }
                
            }else {
                
                $this->response->redirect('Account/getInfo');
            }
        
        }
    }

    public function getInfoAction () {
            if ($this->session->has('user')) {
                $user = $this->session->get('user');
                $id = $user['ID'];
            }
            $studentAccount = Account::findFirst([
                'ID =:id:' ,
                'bind'=>[
                    'id' => $id
                ]
            ]);
            $listStudent = Account::find();
            $this->view->setVar('studentAccount',$studentAccount);
            $this->view->setVar('listStudent',$listStudent);
        if ($this->request->isPost()) {
            if($studentAccount->GROUP_ID == 1) {
                $data = $this->request->getPost();
                $id = $data['id'];
                $nameNew = $this->changeData($studentAccount->NAME,$data['name']);
                $birthdayNew = $this->changeData($studentAccount->BIRTHDAY,$data['birthday']);
                $addressNew = $this->changeData($studentAccount->ADDRESS,$data['address']);
                $phoneNew = $this->changeData($studentAccount->PHONE,$data['phone']);
                $schoolNew = $this->changeData($studentAccount->SCHOOL,$data['school']);
                $hobbyNew = $this->changeData($studentAccount->HOBBY,$data['hobby']);
                $gmailNew = $this->changeData($studentAccount->GMAIL,$data['gmail']);
                

                if(Account::find(['']))
                if($nameNew)  $studentAccount->NAME =  $nameNew ;
                if($birthdayNew)  $studentAccount->BIRTHDAY =  $birthdayNew ;
                if($addressNew)  $studentAccount->ADDRESS =  $addressNew ;
                if($phoneNew)  $studentAccount->PHONE =  $phoneNew ;
                if($schoolNew)  $studentAccount->SCHOOL =  $schoolNew ;
                if($hobbyNew)  $studentAccount->HOBBY =  $hobbyNew ;
                if($gmailNew)  $studentAccount->GMAIL =  $gmailNew ;
                $studentAccount->save();
            } else if ($studentAccount->GROUP_ID == 9) {
                $validate = $this->request->getQuery('validate');
                if($validate) {
                    $data = $this->request->getPost();
                    $id = $data['id'];
                    $userAccount = Account::findFirst([
                        'ID =:id:' ,
                        'bind'=>[
                            'id' => $id
                        ]
                    ]);
                    $nameNew = $this->changeData($userAccount->NAME,$data['name']);
                    $birthdayNew = $this->changeData($userAccount->BIRTHDAY,$data['birthday']);
                    $addressNew = $this->changeData($userAccount->ADDRESS,$data['address']);
                    $phoneNew = $this->changeData($userAccount->PHONE,$data['phone']);
                    $schoolNew = $this->changeData($userAccount->SCHOOL,$data['school']);
                    $hobbyNew = $this->changeData($userAccount->HOBBY,$data['hobby']);
                    $gmailNew = $this->changeData($userAccount->GMAIL,$data['gmail']);
                    

                    if($nameNew)  $userAccount->NAME =  $nameNew ;
                    if($birthdayNew)  $userAccount->BIRTHDAY =  $birthdayNew ;
                    if($addressNew)  $userAccount->ADDRESS =  $addressNew ;
                    if($phoneNew)  $userAccount->PHONE =  $phoneNew ;
                    if($schoolNew)  $userAccount->SCHOOL =  $schoolNew ;
                    if($hobbyNew)  $userAccount->HOBBY =  $hobbyNew ;
                    if($gmailNew)  $userAccount->GMAIL =  $gmailNew ;

                    if($userAccount->save()) {
                        return  $this->response->setJsonContent(
                            [
                                "result" => "OK",
                            ]
                        );
                    } else {
                        return  $this->response->setJsonContent(
                            [
                                "result" => "error",
                            ]
                        );
                    }
                      
                }
            }

        }
    }


    private function changeData ($oldData,$data) {
        $data = trim($oldData, ' ') != trim($data, ' ') ? $data : null;
        return $data;
    }

    public function registerAction () {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $userName = $data['userName'];
            $studentAccount = Account::findFirst([
                'USERNAME =:userName:' ,
                'bind'=>[
                    'userName' => $userName
                ]
            ]);
            $validate = $this->request->getQuery('validate');
            if($validate) {
                if(!$studentAccount) {
                    return  $this->response->setJsonContent(
                            [
                                "result" => "OK",
                            ]
                        );
                } else {
                    return  $this->response->setJsonContent(
                        [
                            "result" => "error",
                        ]
                    );
                }
            }
            // TÌm hiểu cách flash message
            $studentAccount = new Account();
            if($data['name'])  $studentAccount->NAME =  $data['name'] ;
            if($data['birthday'])  $studentAccount->BIRTHDAY =  $data['birthday'] ;
            if($data['address'])  $studentAccount->ADDRESS =  $data['address'] ;
            $studentAccount->GROUP_ID =  1 ;
            if($data['phone'])  $studentAccount->PHONE =  $data['phone'] ;
            if($data['school'])  $studentAccount->SCHOOL =  $data['school'] ;
            if($data['hobby'])  $studentAccount->HOBBY =  $data['hobby'] ;
            if($data['gmail'])  $studentAccount->GMAIL =  $data['gmail'] ;
            if($data['userName'])  $studentAccount->USERNAME =  $data['userName'] ;
            if($data['password'])  $studentAccount->PASSWORD =  md5($data['password']) ;
            if($studentAccount->save()) {
                $this->response->redirect('Account/index');
            }

        } 

    }

    public function forgotPassWordAction() {
        if ($this->request->isPost()) {
            $validate = $this->request->getQuery('validate');
            
            $data = $this->request->getPost();
            $studentAccount = Account::findFirst([
                'USERNAME =:userName: AND GMAIL = :gmail:' ,
                'bind'=>[
                    'userName' => $data['userName'],
                    'gmail' => $data['gmail']
                ]
            ]);
            if($validate) {
                if($studentAccount) {
                    return  $this->response->setJsonContent(
                        [
                            "result" => "OK",
                        ]
                    );
                }else {
                    return  $this->response->setJsonContent(
                        [
                            "result" => "error",
                        ]
                    );
                }
            }
            $studentAccount->PASSWORD = md5($data['passWord']);
            if($studentAccount->save())  $this->response->redirect('Account/');;

        }
    }

    public function logOutAction () {
        if($this->session->destroy()) {
            return  $this->response->setJsonContent(
                [
                    "result" => "OK",
                ]
            );
        } else {
            return  $this->response->setJsonContent(
                [
                    "result" => "error",
                ]
            );
            }
    }


    public function deleteAction() {
        if ($this->session->has('user')) {
            $user = $this->session->get('user');
            $id = $user['ID'];
            $groupId = $user['GROUP_ID'];
        }
        if($groupId != 9) {
            $this->response->redirect('Account/unauthorized');
        }
        $data = $this->request->getPost();
        $studentAccount = Account::findFirst([
            'ID =:id:' ,
            'bind'=>[
                'id' => $data['id']
            ]
        ]);
        if( $studentAccount->delete()) {
            return  $this->response->setJsonContent(
                [
                    "result" => "OK",
                ]
            );
        }else {
            return  $this->response->setJsonContent(
                [
                    "result" => "error",
                ]
            );
        }

       
    }

    public function unauthorizedAction () {

    }
}
