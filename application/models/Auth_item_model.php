<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_item_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_auth_items() {
        return $this->db->get('auth_item')->result();
    }

    public function add_auth_item($name, $controller, $action) {
        $data = [
            'name' => $name,
            'controller' => $controller,
            'action' => $action
        ];
        return $this->db->insert('auth_item', $data);
    }

    public function permission_exists($name) {
        $query = $this->db->get_where('auth_item', array('name' => $name));
        return $query->num_rows() > 0;
    }
    public function delete_auth_item($id) {
        return $this->db->delete('auth_item', ['id' => $id]);
    }
}
