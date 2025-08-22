<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model untuk tabel bookings.
 */
class Booking_model extends CI_Model
{
    protected $table = 'bookings';

    public function get_by_date($date)
    {
        return $this->db->get_where($this->table, ['tanggal_booking' => $date])->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Periksa apakah lapangan tersedia pada tanggal dan jam tertentu.
     * Mengembalikan TRUE jika tersedia, FALSE jika ada bentrok.
     */
    public function is_available($id_court, $date, $start, $end)
    {
        /*
         * Cek ketersediaan jadwal. Bentrok jika rentang waktu overlap:
         * tidak bentrok jika (jam_selesai <= start) OR (jam_mulai >= end)
         * maka kondisi bentrok adalah negasi dari kondisi tersebut.
         */
        $this->db->where('id_court', $id_court);
        $this->db->where('tanggal_booking', $date);
        $this->db->where("NOT (jam_selesai <= '{$start}' OR jam_mulai >= '{$end}')", NULL, FALSE);
        $conflict = $this->db->get($this->table)->num_rows();
        return $conflict == 0;
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
}
