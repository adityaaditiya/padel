<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_status_model extends CI_Model
{
    protected $table = 'store_status';

    /**
     * Ambil tanggal toko terakhir.
     */
    public function get_store_date()
    {
        $row = $this->db->select('store_date')
                        ->order_by('store_date', 'DESC')
                        ->get($this->table, 1)
                        ->row();
        return $row ? $row->store_date : NULL;
    }
}
