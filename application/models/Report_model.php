<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model laporan keuangan dan ringkasan bisnis.
 */
class Report_model extends CI_Model
{
    /**
     * Mengambil ringkasan pendapatan booking dan penjualan F&B pada rentang tanggal.
     *
     * @param string $start Tanggal awal (YYYY-MM-DD)
     * @param string $end   Tanggal akhir (YYYY-MM-DD)
     * @return array        Associative array dengan total_booking dan total_sales
     */
    public function get_financial_summary($start, $end)
    {
        // total booking
        $this->db->select_sum('total_harga', 'total_booking');
        $this->db->where('tanggal_booking >=', $start);
        $this->db->where('tanggal_booking <=', $end);
        $booking = $this->db->get('bookings')->row()->total_booking ?: 0;

        // total sales
        $this->db->select_sum('total_belanja', 'total_sales');
        $this->db->where('tanggal_transaksi >=', $start);
        $this->db->where('tanggal_transaksi <=', $end . ' 23:59:59');
        $sales = $this->db->get('sales')->row()->total_sales ?: 0;

        return [
            'total_booking' => $booking,
            'total_sales'   => $sales,
            'grand_total'   => $booking + $sales
        ];
    }

    /**
     * Ringkasan bisnis untuk owner: jumlah booking, jumlah pelanggan, dan produk terlaris.
     */
    public function get_business_summary($start, $end)
    {
        // Jumlah booking
        $this->db->where('tanggal_booking >=', $start);
        $this->db->where('tanggal_booking <=', $end);
        $total_bookings = $this->db->count_all_results('bookings');

        // Jumlah pelanggan unik
        $this->db->select('id_user');
        $this->db->where('tanggal_booking >=', $start);
        $this->db->where('tanggal_booking <=', $end);
        $this->db->group_by('id_user');
        $customers = $this->db->get('bookings')->num_rows();

        // Produk terlaris (banyak terjual)
        $this->db->select('products.nama_produk, SUM(sale_details.jumlah) as qty');
        $this->db->from('sale_details');
        $this->db->join('products', 'products.id = sale_details.id_product');
        $this->db->join('sales', 'sales.id = sale_details.id_sale');
        $this->db->where('sales.tanggal_transaksi >=', $start);
        $this->db->where('sales.tanggal_transaksi <=', $end . ' 23:59:59');
        $this->db->group_by('sale_details.id_product');
        $this->db->order_by('qty', 'DESC');
        $this->db->limit(5);
        $best_products = $this->db->get()->result();

        return [
            'total_bookings'   => $total_bookings,
            'total_customers'  => $customers,
            'best_products'    => $best_products
        ];
    }
}
