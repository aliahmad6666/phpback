<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function has_permission($user_id, $controller, $action) {
    if (!in_array($action,['addtag','comment','idea'])){
        return true;
    }
    $CI =& get_instance();
    $CI->load->model('Auth_assignment_model');
    $roles = $CI->User_rule_model->get_user_roles($user_id);

    foreach ($roles as $role) {
        $permissions = $CI->Auth_assignment_model->get_permissions_by_role($role->id);
        foreach ($permissions as $perm) {
            if ($perm->controller == $controller && $perm->action == $action) {
                return true;
            }
        }
    }
    return false;
}