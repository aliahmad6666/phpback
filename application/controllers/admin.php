<?php
/*********************************************************************
PHPBack
Ivan Diaz <ivan@phpback.org>
Copyright (c) 2014 PHPBack
http://www.phpback.org
Released under the GNU General Public License WITHOUT ANY WARRANTY.
See LICENSE.TXT for details.
 **********************************************************************/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(__DIR__ . "../../../vendor/autoload.php");
use \VisualAppeal\AutoUpdate;

class Admin extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('get');
        $this->load->model('post');
        $this->load->model('Rule_model');
        $this->load->model('Auth_item_model');
        $this->load->model('Auth_assignment_model');
        $this->load->model('User_rule_model');
        $this->version = '1.3.1';
    }

    public function index($error = 'no'){
        session_start();
        $data = array();
        if($error == "error"){
            $data['error'] = 'yes';
            $this->load->view('admin/header', $data);
            $this->load->view('admin/login', $data);
            return;
        }
        if(!isset($_SESSION['phpback_isadmin'])){
            $this->load->view('admin/header', $data);
            $this->load->view('admin/login', $data);
        }
        elseif($_SESSION['phpback_isadmin'] == 0){
            $data['error'] = 'noadmin';
            $this->load->view('admin/header', $data);
            $this->load->view('admin/login', $data);
        }
        else{
            header('Location: ' . base_url() . 'admin/dashboard/');
            exit;
        }
    }

    public function dashboard(){
        $this->start();
        $data = array();
        //$data['ideas'] = $this->get->get_new_ideas(10);
        //$data['flags'] = $this->get->get_flags();
        $data['logs'] = $this->get->get_last_logs();

        $this->load->view('admin/dashboard/header');
        $this->load->view('admin/dashboard/index', $data);
    }

    public function ideas(){
        $this->start();
        $data = array();
        $data['newideas'] = $this->get->get_new_ideas(150);
        $data['newideas_num'] = $this->get->get_new_ideas_num();
        $data['flags'] = $this->get->get_flags();
        $data['categories'] = $this->get->getCategories();
        if(!@isset($_POST['search'])){
            $data['form'] = array(
                "status-completed" => 0,
                "status-started" => 0,
                "status-planned" => 1,
                "status-considered" => 1,
                "status-declined" => 0,
                "orderby" => "votes",
                "isdesc" => 1
            );
            $cat = array();
            foreach ($data['categories'] as $t) {
                $cat[] = $t->id;
                $s = "category-".$t->id;
                $data['form'][$s] = 1;
            }
            $st = array("considered", "planned");
            $data['toall'] = 0;
        }
        else{
            $data['form'] = array(
                "status-completed" => ($this->input->post('status-completed', true)) ? 1 : 0,
                "status-started" => ($this->input->post('status-started', true)) ? 1 : 0,
                "status-planned" => ($this->input->post('status-planned', true)) ? 1 : 0,
                "status-considered" => ($this->input->post('status-considered', true)) ? 1 : 0,
                "status-declined" => ($this->input->post('status-declined', true)) ? 1 : 0,
                "orderby" => $this->input->post('orderby', true),
                "isdesc" => ($this->input->post('isdesc', true)) ? 1 : 0
            );
            $st = array();
            if($this->input->post('status-completed', true)) $st[] = "completed";
            if($this->input->post('status-started', true)) $st[] = "started";
            if($this->input->post('status-planned', true)) $st[] = "planned";
            if($this->input->post('status-considered', true)) $st[] = "considered";
            if($this->input->post('status-declined', true)) $st[] = "declined";

            $cat = array();
            foreach ($data['categories'] as $t) {
                $s = "category-".$t->id;
                if($this->input->post('category-' . $t->id, true)){
                    $cat[] = $t->id;
                    $data['form'][$s] = 1;
                }
                else{
                    $data['form'][$s] = 0;
                }
            }
            $data['toall'] = 1;
        }

        $this->redirectIfNotAlphaNumeric(array(
            $data['form']['orderby']
        ));

        $data['ideas'] = $this->get->getIdeas($data['form']['orderby'], $data['form']['isdesc'], 0, 150, $st, $cat);

        $this->load->view('admin/dashboard/header', $data);
        $this->load->view('admin/dashboard/ideas', $data);
    }
    public function users($idban=0){
        $this->start(2);
        $data = array();

        $data['users'] = $this->get->get_users();
        $data['banned'] = $this->get->get_users('banned', 100);

        if($idban) $data['idban'] = $idban;
        $this->load->view('admin/dashboard/header', $data);
        $this->load->view('admin/dashboard/users', $data);
    }

    public function system(){
        $this->start(3);
        $data = array();
        $data['settings'] = $this->get->get_all_settings();
        $data['adminusers'] = $this->get->get_admin_users();
        $data['categories'] = $this->get->getCategories();
        $data['tags'] = $this->get->getTags();
        $data['boards'] = $this->get->getBoards();
        $data['version'] = $this->version;

        if ($this->get->getAutoUpdaterEnabled()) {
            $update = new AutoUpdate(__DIR__ . '/temp', __DIR__ . '/../../', 60);
            $update->setCurrentVersion($this->version); // Current version of your application. This value should be from a database or another file which will be updated with the installation of a new version
            $update->setUpdateUrl('http://www.phpback.org/upgrade/'); //Replace the url with your server update url
            $update->checkUpdate();

            $data['lastVersion'] = $update->getLatestVersion();
            $data['isLastVersion'] = !$update->newVersionAvailable();
        } else {
            $data['lastVersion'] = $this->version;
            $data['isLastVersion'] = false;
        }

        $this->load->view('admin/dashboard/header', $data);
        $this->load->view('admin/dashboard/system', $data);
    }

    private function start($level = 1){
        session_start();
        if (!has_permission($_SESSION['phpback_userid'],$this->uri->segment(1),$this->uri->segment(2))) {
            $_SESSION['error_message'] = 'You do not have permission to access do this action.';
            header('Location: ' . base_url() . 'home/');
            exit;
        }

        if(!isset($_SESSION['phpback_isadmin']) || $_SESSION['phpback_isadmin'] < $level){
            header('Location: ' . base_url() . 'admin/');
            exit;
        }
    }

    private function redirectIfNotAlphaNumeric($textList) {
        foreach ($textList as $text) {
            if (!$this->isAlphaNumeric($text)) {
                header('Location: ' . base_url() . 'admin/');
                exit;
            }
        }
    }

    private function isAlphaNumeric($text) {
        return ctype_alnum($text);
    }



    public function permissions(){
        $this->start(3);
        $data['roles'] = $this->Rule_model->get_all_rules();
        $data['users'] = $this->get->getUsers();
        $data['permissions'] = $this->Auth_item_model->get_all_auth_items();
        $data['role_permissions'] = $this->Auth_assignment_model->get_all_role_permissions();
        $data['user_roles'] = $this->User_rule_model->get_all_user_roles();
        $this->load->view('admin/dashboard/header', $data);
        $this->load->view('admin/dashboard/permissions', $data);
    }

    public function addRule(){
        $this->start(3);
        $name = $this->input->post('name', true);

        if ($this->Rule_model->rule_exists($name)) {
            $this->post->log("Rule '$name' already exists.", 'rule', $_SESSION['phpback_userid']);
        } else {
            $this->Rule_model->add_rule($name);
            $this->post->log("Rule '$name' created.", 'rule', $_SESSION['phpback_userid']);
        }

        header('Location: ' . base_url() . 'admin/permissions');
    }

    public function deleteRole($rule_id){
        $this->start(3);

        $this->Rule_model->delete_rule($rule_id);
        $this->post->log("rule #$rule_id deleted.", 'Rule', $_SESSION['phpback_userid']);

        header('Location: ' . base_url() . 'admin/permissions');
    }
    public function addPermission(){
        $this->start(3);
        $name = $this->input->post('name', true);
        $controller = $this->input->post('controller', true);
        $action = $this->input->post('action', true);

        if ($this->Auth_item_model->permission_exists($name)) {
            $this->post->log("permission '$name' already exists.", 'permission', $_SESSION['phpback_userid']);
        } else {
            $this->Auth_item_model->add_auth_item($name,$controller,$action);
            $this->post->log("permission '$name' created.", 'permission', $_SESSION['phpback_userid']);
        }

        header('Location: ' . base_url() . 'admin/permissions');
    }
    public function deletePermission($permission_id){
        $this->start(3);

        $this->Auth_item_model->delete_auth_item($permission_id);
        $this->post->log("permission #$permission_id deleted.", 'permission', $_SESSION['phpback_userid']);

        header('Location: ' . base_url() . 'admin/permissions');
    }
    public function addRulePermission(){
        $this->start(3);
        $rule_id = $this->input->post('rule3', true);
        $permission_id = $this->input->post('permission3', true);
        if ($this->Auth_assignment_model->check_exists($rule_id,$permission_id)) {
            $this->post->log("permission '$permission_id' with rule ".$rule_id." already exists.", 'rule_permission', $_SESSION['phpback_userid']);
        } else {
            $this->Auth_assignment_model->assign_permission_to_role($rule_id,$permission_id);
            $this->post->log("permission '$permission_id' with rule ".$rule_id." created.", 'rule_permission', $_SESSION['phpback_userid']);
        }

        header('Location: ' . base_url() . 'admin/permissions');
    }

    public function deleteRolePermissions($rule_id,$permission_id){
        $this->start(3);

        $this->Auth_assignment_model->remove_permission_from_role($rule_id,$permission_id);
        $this->post->log("permission #$permission_id deleted from rule #$rule_id.", 'rule_permission', $_SESSION['phpback_userid']);

        header('Location: ' . base_url() . 'admin/permissions');
    }
    public function addRuleToUser(){
        $this->start(3);
        $rule_id = $this->input->post('rule_id', true);
        $user_id = $this->input->post('user_id', true);
        if ($this->User_rule_model->check_exists($user_id,$rule_id)) {
            $this->post->log("rule '$rule_id' for use ".$user_id." already exists.", 'user_rule', $_SESSION['phpback_userid']);
        } else {
            $this->User_rule_model->assign_role_to_user($user_id,$rule_id);
            $this->post->log("rule '$rule_id' for user ".$user_id." added.", 'user_rule', $_SESSION['phpback_userid']);
        }

        header('Location: ' . base_url() . 'admin/permissions');
    }

    public function deleteUserRoles($user_id,$rule_id){
        $this->start(3);

        $this->User_rule_model->remove_role_from_user($user_id,$rule_id);
        $this->post->log("rule #$rule_id deleted from user #$user_id.", 'user_rule', $_SESSION['phpback_userid']);

        header('Location: ' . base_url() . 'admin/permissions');
    }
}
