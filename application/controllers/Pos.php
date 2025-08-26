<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk Point of Sale (kasir) penjualan F&B.
 */
class Pos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Product_model','Sale_model','Sale_detail_model','Payment_model','Store_model','Member_model']);
        $this->load->library('session');
        $this->load->helper(['url']);
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

    /**
     * Tampilkan produk dan keranjang belanja.
     */
    public function index()
    {
        $this->authorize();
        $kategori = $this->input->get('kategori');
        $search   = $this->input->get('q');
        $data['selected_category'] = $kategori;
        $data['search_query']      = $search;
        $data['products'] = $this->Product_model->get_all($kategori, $search);
        $data['categories'] = $this->Product_model->get_categories();
        $data['cart'] = $this->session->userdata('cart') ?: [];
        $data['total'] = 0;
        foreach ($data['cart'] as $item) {
            $data['total'] += $item['harga_jual'] * $item['qty'];
        }
        $nomor_nota = $this->session->userdata('nomor_nota');
        if (!$nomor_nota) {
            $nomor_nota = 'INV-' . time();
            $this->session->set_userdata('nomor_nota', $nomor_nota);
        }
        $data['nomor_nota'] = $nomor_nota;
        $data['members'] = $this->Member_model->get_all();
    }

    /**
     * Tambah produk ke keranjang.
     */
    public function add($id)
    {
        $this->authorize();
        $product = $this->Product_model->get_by_id($id);
        if (!$product) {
            redirect('pos');
        }
        $cart = $this->session->userdata('cart') ?: [];
        if (isset($cart[$id])) {
            $cart[$id]['qty'] += 1;
        } else {
            $cart[$id] = [
                'id'         => $product->id,
                'nama_produk'=> $product->nama_produk,
                'harga_jual' => $product->harga_jual,
                'qty'        => 1
            ];
        }
        $this->session->set_userdata('cart', $cart);
        redirect('pos');
    }

    /**
     * Hapus produk dari keranjang.
     */
    public function remove($id)
    {
        $this->authorize();
        $cart = $this->session->userdata('cart') ?: [];
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->session->set_userdata('cart', $cart);
        }
        redirect('pos');
    }

    /**
     * Update kuantitas banyak produk sekaligus.
     */
    public function update_cart()
    {
        $this->authorize();
        if ($this->input->method() !== 'post') {
            redirect('pos');
        }
        $cart = $this->session->userdata('cart') ?: [];
        $qtys = $this->input->post('qty');
        if (is_array($qtys)) {
            foreach ($qtys as $id => $qty) {
                if (isset($cart[$id])) {
                    $cart[$id]['qty'] = max(1, (int)$qty);
                }
            }
            $this->session->set_userdata('cart', $cart);
        }
        redirect('pos');
    }

    /**
     * Simpan transaksi penjualan dan kosongkan keranjang.
     */
    public function checkout()
    {
        $this->authorize();
        if ($this->input->method() !== 'post') {
            redirect('pos');
        }
        $error = $this->Store_model->validate_device_date($this->input->post('device_date'));
        if ($error) {
            $this->session->set_flashdata('error', $error);
            redirect('pos');
            return;
        }
        $cart = $this->session->userdata('cart') ?: [];
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang kosong.');
            redirect('pos');
            return;
        }
        // Hitung total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga_jual'] * $item['qty'];
        }
        $nomor_nota = $this->session->userdata('nomor_nota');
        $saleData = [
            'id_kasir'      => $this->session->userdata('id'),
            'nomor_nota'    => $nomor_nota,
            'total_belanja' => $total,
            'id_member'     => $this->input->post('member_id') ?: null,
            'atas_nama'     => $this->input->post('member_id') ? null : $this->input->post('atas_nama')
        ];
        $sale_id = $this->Sale_model->insert($saleData);
        // Simpan detail dan update stok
        foreach ($cart as $item) {
            $detail = [
                'id_sale'   => $sale_id,
                'id_product'=> $item['id'],
                'jumlah'    => $item['qty'],
                'subtotal'  => $item['harga_jual'] * $item['qty']
            ];
            $this->Sale_detail_model->insert($detail);
            // Kurangi stok
            $this->Product_model->decrease_stock($item['id'], $item['qty']);
        }
        // Buat pembayaran (tunai default)
        $payment = [
            'id_sale'        => $sale_id,
            'jumlah_bayar'   => $total,
            'metode_pembayaran' => 'tunai',
            'id_kasir'       => $this->session->userdata('id')
        ];
        $this->Payment_model->insert($payment);
        // Kosongkan keranjang dan nomor nota
        $this->session->unset_userdata('cart');
        $this->session->unset_userdata('nomor_nota');
        $this->session->set_flashdata('success', 'Transaksi berhasil disimpan.');
        redirect('pos');
    }
}
