<?php

    class user {

        private $data = array();
        private $auth = 0;

        function __construct() {
            session_start();
            if(isset($_SESSION) && !empty($_SESSION)) {
                $this->data = $_SESSION;
                $this->auth = 1;
            }
            else {
				session_destroy();
				$this->auth = 0;
			}
        }

        function logout() {
            if (!$this->auth) {
                print('You are not Logged In');
                redirect_sleep('main', 'home', 5);
                exit();
            }
            session_destroy();
            print("Log out Success");
            redirect_sleep('main','home', 3);
        }

        function login($arguments) {
            if (!isset($_POST) || empty($_POST)) {
                redirect('main', 'home');
                exit();
            }
            if ($this->auth) {
                print('You are already Logged In');
                redirect_sleep('main', 'home', 5);
                exit();
            }
            $result = loadModel('user', 'login', $arguments);
            if ($result === false) {
                print("Login Error");
                redirect_sleep('main','home', 5);
                exit();
            }
            print("Login Success");
            session_start();
            $_SESSION = $result;
            redirect_sleep('main','home', 5);
        }

        function signup($arguments) {
            if ($this->auth) {
                print('You are already Logged In');
                redirect_sleep('main', 'home', 5);
                exit();
            }
            loadView('header', array_merge($this->data, ['title' => 'SignUp - CodeShows']));
            loadView('signup');
            loadView('footer');
        }

        function register($arguments) {
            if (!isset($_POST) || empty($_POST)) {
                redirect('main', 'home');
                exit();
            }
            if ($this->auth) {
                print('You are already Logged In');
                redirect_sleep('main', 'home', 5);
                exit();
            }
            $result = loadModel('user', 'register', $arguments);
            if ($result === false) {
                print("Register Error");
                redirect_sleep('main','home', 5);
                exit();
            }
            print("register success");
            session_start();
            $_SESSION = $result;
            redirect_sleep('main', 'home', 5);
        }

    }

?>