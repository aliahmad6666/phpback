<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rule_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_rules() {
        return $this->db->get('rules')->result();
    }

    public function add_rule($name) {
        $data = ['name' => $name];
        return $this->db->insert('rules', $data);
    }
    public function rule_exists($name) {
        $query = $this->db->get_where('rules', array('name' => $name));
        return $query->num_rows() > 0;
    }

    public function delete_rule($id) {
        return $this->db->delete('rules', ['id' => $id]);
    }
}
