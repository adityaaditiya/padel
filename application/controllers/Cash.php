<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cash_model');
        $this->load->library('session');
        $this->load->helper(['url','form']);
    }

    private function authorize()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $role = $this->session->userdata('role');
        if (!in_array($role, ['kasir','admin_keuangan','owner'])) {
            redirect('dashboard');
        }
    }

    public function add()
    {
        $this->authorize();
        if ($this->input->method() === 'post') {
            $data = [
                'tanggal'  => date('Y-m-d H:i:s'),
                'type'     => 'in',
                'category' => $this->input->post('category'),
                'amount'   => (float) $this->input->post('amount'),
                'note'     => $this->input->post('note')
            ];
            $this->Cash_model->insert($data);
            $this->session->set_flashdata('success', 'Kas masuk berhasil disimpan');
            redirect('cash/add');
        }
        $this->load->view('cash/add');
    }

    public function withdraw()
    {
        $this->authorize();
        if ($this->input->method() === 'post') {
            $data = [
                'tanggal'  => date('Y-m-d H:i:s'),
                'type'     => 'out',
                'category' => $this->input->post('category'),
                'amount'   => (float) $this->input->post('amount'),
                'note'     => $this->input->post('note')
            ];
            $this->Cash_model->insert($data);
            $this->session->set_flashdata('success', 'Kas keluar berhasil disimpan');
            redirect('cash/withdraw');
        }
        $this->load->view('cash/withdraw');
    }
}
