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
}
