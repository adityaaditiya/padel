<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel products.
 */
class Product_model extends CI_Model
{
    protected $table = 'products';

    /**
     * Ambil semua produk dengan opsi filter kategori dan pencarian nama.
     */
    public function get_all($kategori = null, $search = null)
    {
        if ($kategori) {
            $this->db->where('kategori', $kategori);
        }
        if ($search) {
            $this->db->like('nama_produk', $search);
        }
        return $this->db->get($this->table)->result();
    }

    /**
     * Ambil daftar kategori yang tersedia.
     */
    public function get_categories()
    {
        return $this->db->select('kategori')->distinct()->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Kurangi stok produk setelah penjualan.
     */
    public function decrease_stock($id, $qty)
    {
        $product = $this->get_by_id($id);
        if ($product) {
            $newStock = max(0, $product->stok - $qty);
            $this->db->where('id', $id)->update($this->table, ['stok' => $newStock]);
        }
    }
}
