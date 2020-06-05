<?php
use Phalcon\Session\Factory;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\View;
use JasonGrimes\Paginator;


class AccountController extends ControllerBase
{

    public function initialize(){
        parent::initialize();
        
	}
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
                    // $this->response->redirect('Account/getInfo');
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

    public function getInfoAction ($page = 1) {
        if ($this->session->has('user')) {
            $user = $this->session->get('user');
            $id = $user['ID'];
        } else {
            $this->response->redirect('Account');
        }
        $studentAccount = Account::findFirst([
            'ID =:id:' ,
            'bind'=>[
                'id' => $id
            ]
        ]);
        $limit = 5;
        $sql = 'SELECT * FROM Account LIMIT '.($page-1)*$limit.','.$limit;
        $listStudent = $this->modelsManager->executeQuery($sql);
        $count = Account::count();
        $url = "/devtest/Account/getInfo/(:num)";
        $paginator = new Paginator($count, $limit, $page, $url);

            $this->view->setVars(['paginator' => $paginator,'listStudent' => $listStudent,'studentAccount' => $studentAccount,'limit' => $limit,'page' => $page ]);
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
        }else {
            $this->response->redirect('Account');
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

    public function apiTestingAction () {
        
        header("Content-type: application/json; charset=utf-8");
        $data = $this->request->getQuery();
        $time = $data['time'];
        $key = $data['key'];
        $id =  $data['id'];
        $currentTime = time();
        $currentKey = md5('testApi'.$time);
        if($currentTime - $time <= 3000 && $key == $currentKey) {
            $studentAccount = Account::findFirst([
                'ID =:id:' ,
                'bind'=>[
                    'id' => $data['id'],
                ]
            ]);
            $this->view->setVar('studentAccount',$studentAccount);
            if($studentAccount) {
                echo json_encode($studentAccount);
            } else {
                echo json_encode('Không có dữ liệu nào tồn tại');
            }
        }
    }

    public function testGetDataAction () {
        $mySqlLi = mysqli_connect('127.0.0.1', 'root','', 'student_management');

        $sql = 'SELECT * FROM Account WHERE ID = 14';
        $data = $mySqlLi->query($sql)->fetch_assoc();
        // $result = $this->modelsManager->executeQuery($sql);
        
        $this->view->setVar('result', $result);
    }

    public function testPaginatorAction($currentPage = 1) {
        $totalItems = Account::count();
        $itemsPerPage = 4;
        $sql = 'SELECT * FROM Account LIMIT '.($currentPage-1)*$itemsPerPage.','.$itemsPerPage;

        $data = $this->modelsManager->executeQuery($sql)->toArray();

        $urlPattern = '/devtest/Account/testPaginator/(:num)';
        $currentPage = ((!empty($currentPage)) ? $currentPage : 1);
        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
        $paginator->setMaxPagesToShow(6);
        $this->view->setVars(['data' => $data, 'paginator' => $paginator, 'itemsPerPage' => $itemsPerPage, 'currentPage' => $currentPage]);

    }
}

//file index.php để test Api

// <?php 


// $time = time();
// $key = md5('testApi'.$time);
// $data = '?time='.$time.'&key='.$key.'&id=14';
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "http://localhost/devtest/Account/apiTesting".$data);
// curl_exec($ch);
// curl_close($ch);
