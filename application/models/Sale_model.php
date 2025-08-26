<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel sales (penjualan F&B).
 */
class Sale_model extends CI_Model
{
    protected $table = 'sales';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_all($from = null, $to = null)
    {
        $this->db->select('s.*, u.nama_lengkap AS customer_name');
        $this->db->from($this->table . ' s');
        $this->db->join('users u', 'u.id = s.customer_id', 'left');
        if ($from) {
            $this->db->where('DATE(s.tanggal_transaksi) >=', $from);
        }
        if ($to) {
            $this->db->where('DATE(s.tanggal_transaksi) <=', $to);
        }
        $this->db->order_by('s.tanggal_transaksi', 'DESC');
        return $this->db->get()->result();
    }
}
