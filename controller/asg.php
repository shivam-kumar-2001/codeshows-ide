<?php

    class asg {

        private $data = array();
        private $auth = 0;

        function __construct() {
            session_start();
            if(isset($_SESSION) && !empty($_SESSION) && isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
                $this->data = $_SESSION;
                $this->auth = 1;
            }
            else $this->auth = 0;
        }

        function all($arguments) {
            if (!$this->auth) {
                //print('You are not Logged In');
                loadView('error', ['msg' => 'You are not Logged In']);
                exit();
            } else if ($this->data['type'] == 2) {
                //print('Only Students Allowed');
                loadView('error', ['msg' => 'Only Students Allowed']);
                exit();
            }
            $data = loadModel("asg", "fetch", $this->data);
            loadView("header", array_merge($this->data, ['title' => 'Assignments - CodeShows']));
            loadView("asg_collection", array_merge($data, ['len' => count($data)]));
            loadView("footer");
        }

        function fetch_asg($arguments) {
            $data = loadModel("problem", "fetch", $arguments);
			$len = count($data);
			$data = array_merge($data,
			loadModel('asg', 'fetch', ['asg_id' => $arguments['asg_id'], 'batch_id' => $this->data['batch_id']])[0]
			);
            loadView("header", array_merge($this->data, ['title' => $data['asg_name']." - CodeShows"]));
            loadView("asg_problems", array_merge($data, ['len' => $len]));
            loadView("footer");
        }

        function builder() {
            if ($this->auth == 0) {
                //print('You are not Logged In');
                loadView('error', ['msg' => 'You are not Logged In']);
                exit();
            } else if ($this->data['type'] != 2) {
                //print('Please check link');
                loadView('error', ['msg' => 'Please check link']);
                exit();
            }
            //loadView("header", array_merge($this->data, ['title' => "Assignment Builder - CodeShows"]));
            $data = loadModel('asg', 'fetch', ['user_id' => $this->data['user_id']]);
            $batch = loadModel('asg', 'fetch_batch_data', ['user_id' => $this->data['user_id']]);
            loadView("show_all_asg", array_merge($data, ['len' => count($data),'batch'=>$batch]));
            //loadView("footer");
        }

        function add_asg($arguments) {
            if ($this->auth == 0) {
                //print('You are not Logged In');
                loadView('error', ['msg' => 'You are not Logged In']);
                exit();
            } else if ($this->data['type'] != 2 || !isset($_POST) || empty($_POST)) {
                //print('Please check link');
                loadView('error', ['msg' => 'Please check link']);
                exit();
            }
            $result = loadModel('asg', 'add_asg', array_merge($arguments, $this->data));
            if (empty($result)) {
                //print('Error adding assignment');
                loadView('error', ['msg' => 'Error adding assignment']);
                exit();
            }
            print("<script>alert('Assignment added successfully');</script>");
            redirect_sleep('asg', 'builder',0);
        }

    }
?>
