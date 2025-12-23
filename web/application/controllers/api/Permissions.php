<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Permission_model', 'Role_model']);
        $this->output->set_content_type('application/json');
        // Here you should add your authentication and authorization check
    }

    /**
     * Main entry point for /api/permissions
     */
    public function index($id = null)
    {
        switch ($this->input->method(TRUE)) {
            case 'GET':
                $this->_get_permission($id);
                break;
            case 'POST':
                $this->_create_permission();
                break;
            case 'PUT':
                $this->_update_permission($id);
                break;
            case 'DELETE':
                $this->_delete_permission($id);
                break;
            default:
                $this->output->set_status_header(405);
                echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
                break;
        }
    }

    /**
     * Main entry point for /api/roles/{role_id}/permissions
     */
    public function role_permissions($role_id, $permission_id = null)
    {
        switch ($this->input->method(TRUE)) {
            case 'GET':
                $this->_get_role_permissions($role_id);
                break;
            case 'POST':
                $this->_add_permission_to_role($role_id);
                break;
            case 'DELETE':
                $this->_remove_permission_from_role($role_id, $permission_id);
                break;
            default:
                $this->output->set_status_header(405);
                echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
                break;
        }
    }

    private function _get_permission($id = null)
    {
        if ($id === null) {
            $permissions = $this->Permission_model->get_all_permissions();
            echo json_encode(['status' => 'success', 'data' => $permissions]);
        } else {
            $permission = $this->Permission_model->get_permission_by_id($id);
            if ($permission) {
                echo json_encode(['status' => 'success', 'data' => $permission]);
            } else {
                $this->output->set_status_header(404);
                echo json_encode(['status' => 'error', 'message' => 'Permission not found.']);
            }
        }
    }

    private function _create_permission()
    {
        $data = json_decode($this->input->raw_input_stream, true);
        if (empty($data['permission_name'])) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Permission name is required.']);
            return;
        }
        $permission_data = [
            'permission_name' => $data['permission_name'],
            'category' => $data['category'] ?? 'general',
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? 1
        ];
        $permission_id = $this->Permission_model->create_permission($permission_data);
        if ($permission_id) {
            $this->output->set_status_header(201);
            echo json_encode(['status' => 'success', 'message' => 'Permission created successfully.', 'id' => $permission_id]);
        } else {
            $this->output->set_status_header(409); // 409 Conflict
            echo json_encode(['status' => 'error', 'message' => 'Failed to create permission. A permission with this name likely already exists.']);
        }
    }

    private function _update_permission($id)
    {
        if ($id === null) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Permission ID is required for update.']);
            return;
        }
        // Check if permission exists
        if (!$this->Permission_model->get_permission_by_id($id)) {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'Permission not found.']);
            return;
        }
        $data = json_decode($this->input->raw_input_stream, true);
        $success = $this->Permission_model->update_permission($id, $data);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Permission updated successfully.']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update permission. The data might be the same or an error occurred.']);
        }
    }

    private function _delete_permission($id)
    {
        if ($id === null) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Permission ID is required for deletion.']);
            return;
        }
        // Check if permission exists
        if (!$this->Permission_model->get_permission_by_id($id)) {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'Permission not found.']);
            return;
        }
        $success = $this->Permission_model->delete_permission($id);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Permission deleted successfully.']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete permission.']);
        }
    }

    private function _get_role_permissions($role_id)
    {
        $permissions = $this->Role_model->get_role_permissions($role_id);
        echo json_encode(['status' => 'success', 'data' => $permissions]);
    }

    private function _add_permission_to_role($role_id)
    {
        // Check if role exists
        if (!$this->Role_model->get_role_by_id($role_id)) {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'Role not found.']);
            return;
        }
        $data = json_decode($this->input->raw_input_stream, true);
        if (empty($data['permission_id'])) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Permission ID is required.']);
            return;
        }
        // Check if permission exists
        if (!$this->Permission_model->get_permission_by_id($data['permission_id'])) {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'Permission not found.']);
            return;
        }
        $success = $this->Role_model->add_permission_to_role($role_id, $data['permission_id']);
        if ($success) {
            $this->output->set_status_header(201);
            echo json_encode(['status' => 'success', 'message' => 'Permission added to role.']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to add permission to role. It might already be assigned.']);
        }
    }

    private function _remove_permission_from_role($role_id, $permission_id)
    {
        if ($permission_id === null) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Permission ID is required.']);
            return;
        }
        $success = $this->Role_model->remove_permission_from_role($role_id, $permission_id);
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Permission removed from role.']);
        } else {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'Failed to remove permission. It may not have been assigned to this role.']);
        }
    }
}
