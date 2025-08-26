<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk manajemen data member (kasir).
 */
class Members extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Member_model','User_model']);
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') !== 'kasir') {
            redirect('dashboard');
        }
    }

    public function index()
    {
        $this->authorize();
        $data['members'] = $this->Member_model->get_all();
        $this->load->view('members/index', $data);
    }

    public function create()
    {
        $this->authorize();
        $this->load->view('members/create');
    }

    public function store()
    {
        $this->authorize();
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('no_telepon', 'No Telepon', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('kode_member', 'Kode Member', 'required');
        if ($this->form_validation->run() === TRUE) {
            $user_data = [
                'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                'email'        => $this->input->post('email', TRUE),
                'no_telepon'   => $this->input->post('no_telepon', TRUE),
                'password'     => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role'         => 'pelanggan'
            ];
            $member_data = [
                'kode_member' => $this->input->post('kode_member', TRUE),
                'alamat'      => $this->input->post('alamat', TRUE),
                'kecamatan'   => $this->input->post('kecamatan', TRUE),
                'kota'        => $this->input->post('kota', TRUE),
                'provinsi'    => $this->input->post('provinsi', TRUE)
            ];
            $this->Member_model->insert($user_data, $member_data);
            $this->session->set_flashdata('success', 'Member berhasil ditambahkan.');
            redirect('members');
            return;
        }
        $this->create();
    }

    public function edit($id)
    {
        $this->authorize();
        $data['member'] = $this->Member_model->get_by_id($id);
        if (!$data['member']) {
            show_404();
        }
        $this->load->view('members/edit', $data);
    }

    public function update($id)
    {
        $this->authorize();
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('no_telepon', 'No Telepon', 'required');
        $this->form_validation->set_rules('kode_member', 'Kode Member', 'required');
        if ($this->form_validation->run() === TRUE) {
            $user_data = [
                'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                'email'        => $this->input->post('email', TRUE),
                'no_telepon'   => $this->input->post('no_telepon', TRUE)
            ];
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
                $user_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }
            $member_data = [
                'kode_member' => $this->input->post('kode_member', TRUE),
                'alamat'      => $this->input->post('alamat', TRUE),
                'kecamatan'   => $this->input->post('kecamatan', TRUE),
                'kota'        => $this->input->post('kota', TRUE),
                'provinsi'    => $this->input->post('provinsi', TRUE)
            ];
            $this->Member_model->update($id, $user_data, $member_data);
            $this->session->set_flashdata('success', 'Member berhasil diperbarui.');
            redirect('members');
            return;
        }
        $this->edit($id);
    }
}
?>
