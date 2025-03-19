<?php

namespace hooks;
defined('BASEPATH') or exit('No direct script access allowed');

class AuthMiddleware
{
    public function check_permission()
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $CI->load->helper('url');

        $allowed_routes = ['auth/login', 'auth/register'];
        $current_route = $CI->router->class . '/' . $CI->router->method;

        if (in_array($current_route, $allowed_routes)) {
            return;
        }

        if (!$CI->session->userdata('user_id')) {
            redirect('auth/login');
            exit;
        }

        $CI->load->model('User_rule_model');
        $user_roles = $CI->User_rule_model->get_user_roles($CI->session->userdata('user_id'));

        if (!$user_roles) {
            show_error('ليس لديك الصلاحيات للوصول إلى هذه الصفحة.', 403);
            exit;
        }

        $CI->load->model('Auth_assignment_model');
        if (!$CI->Auth_assignment_model->has_permission($user_roles, $CI->router->class, $CI->router->method)) {
            show_error('ليس لديك الصلاحيات للوصول إلى هذه الصفحة.', 403);
            exit;
        }
    }
}
