<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel sale_details (detail penjualan).
 */
class Sale_detail_model extends CI_Model
{
    protected $table = 'sale_details';

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_sale($sale_id)
    {
        return $this->db->get_where($this->table, ['id_sale' => $sale_id])->result();
    }
}
