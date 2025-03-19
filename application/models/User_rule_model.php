<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_rule_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_user_roles($user_id) {
        $this->db->select('rules.*');
        $this->db->from('user_rules');
        $this->db->join('rules', 'user_rules.rule_id = rules.id');
        $this->db->where('user_rules.user_id', $user_id);
        return $this->db->get()->result();
    }
   public function get_all_user_roles() {
        $this->db->select('user_rules.user_id as user_id,user_rules.rule_id as rule_id, rules.name as role_name,users.name as username');
        $this->db->from('user_rules');
        $this->db->join('rules', 'user_rules.rule_id = rules.id');
        $this->db->join('users', 'user_rules.user_id = users.id');
        return $this->db->get()->result();
    }

    public function assign_role_to_user($user_id, $rule_id) {
        $data = [
            'user_id' => $user_id,
            'rule_id' => $rule_id
        ];
        return $this->db->insert('user_rules', $data);
    }
    public function check_exists($user_id,$rule_id) {
        $query = $this->db->get_where('user_rules', ['user_id' => $user_id, 'rule_id' => $rule_id]);
        return $query->num_rows() > 0;
    }

    public function remove_role_from_user($user_id, $rule_id) {
        return $this->db->delete('user_rules', [
            'user_id' => $user_id,
            'rule_id' => $rule_id
        ]);
    }

}
