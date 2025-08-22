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
        $this->load->model(['Product_model','Sale_model','Sale_detail_model','Payment_model']);
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
        $data['products'] = $this->Product_model->get_all();
        $data['cart'] = $this->session->userdata('cart') ?: [];
        $data['total'] = 0;
        foreach ($data['cart'] as $item) {
            $data['total'] += $item['harga_jual'] * $item['qty'];
        }
        $this->load->view('pos/index', $data);
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
     * Simpan transaksi penjualan dan kosongkan keranjang.
     */
    public function checkout()
    {
        $this->authorize();
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
        // Buat nomor nota sederhana
        $nomor_nota = 'INV-' . time();
        $saleData = [
            'id_kasir'      => $this->session->userdata('id'),
            'nomor_nota'    => $nomor_nota,
            'total_belanja' => $total
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
        // Kosongkan keranjang
        $this->session->unset_userdata('cart');
        $this->session->set_flashdata('success', 'Transaksi berhasil disimpan.');
        redirect('pos');
    }
}
