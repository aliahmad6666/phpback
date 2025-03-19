<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_assignment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_permissions_by_role($rule_id) {
        $this->db->select('auth_item.*');
        $this->db->from('auth_assignment');
        $this->db->join('auth_item', 'auth_assignment.auth_item_id = auth_item.id');
        $this->db->where('auth_assignment.rule_id', $rule_id);
        return $this->db->get()->result();
    }

    public function assign_permission_to_role($rule_id, $auth_item_id) {
        $data = [
            'rule_id' => $rule_id,
            'auth_item_id' => $auth_item_id
        ];
        return $this->db->insert('auth_assignment', $data);
    }
    public function check_exists($rule_id, $auth_item_id) {
        $query = $this->db->get_where('auth_assignment', ['auth_item_id' => $auth_item_id, 'rule_id' => $rule_id]);
        return $query->num_rows() > 0;
    }

    public function remove_permission_from_role($rule_id, $auth_item_id) {
        return $this->db->delete('auth_assignment', [
            'rule_id' => $rule_id,
            'auth_item_id' => $auth_item_id
        ]);
    }

    public function get_all_role_permissions() {
        $this->db->select('rules.id as rule_id ,auth_item.id as auth_id,rules.name as role_name,auth_item.name as permission_name');
        $this->db->from('auth_assignment');
        $this->db->join('auth_item', 'auth_assignment.auth_item_id = auth_item.id');
        $this->db->join('rules', 'auth_assignment.rule_id = rules.id');
        return $this->db->get()->result();
    }

    public function has_permission($user_roles, $controller, $action) {
        $this->db->where_in('role_id', $user_roles);
        $this->db->where('controller', $controller);
        $this->db->where('action', $action);
        $query = $this->db->get('auth_assignment');

        return $query->num_rows() > 0;
    }
}
