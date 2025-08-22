<?php $this->load->view('templates/header'); ?>
<h2>Laporan Keuangan</h2>
<form method="get" class="form-inline mb-3">
    <label for="start_date" class="mr-2">Dari:</label>
    <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="<?php echo htmlspecialchars($start_date); ?>">
    <label for="end_date" class="mr-2">Sampai:</label>
    <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="<?php echo htmlspecialchars($end_date); ?>">
    <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>

<table class="table table-bordered">
    <tr>
        <th>Total Pendapatan Booking</th>
        <td>Rp <?php echo number_format($report['total_booking'], 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <th>Total Penjualan F&B</th>
        <td>Rp <?php echo number_format($report['total_sales'], 0, ',', '.'); ?></td>
    </tr>
    <tr>
        <th>Grand Total</th>
        <td><strong>Rp <?php echo number_format($report['grand_total'], 0, ',', '.'); ?></strong></td>
    </tr>
</table>

<?php $this->load->view('templates/footer'); ?>