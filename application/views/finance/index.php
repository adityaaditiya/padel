<?php $this->load->view('templates/header'); ?>
<h2>Laporan Keuangan</h2>
<form method="get" class="form-inline mb-3">
    <label for="start_date" class="mr-2">Dari:</label>
    <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="<?php echo htmlspecialchars($start_date); ?>">
    <label for="end_date" class="mr-2">Sampai:</label>
    <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="<?php echo htmlspecialchars($end_date); ?>">
    <label for="category" class="mr-2">Kategori:</label>
    <select name="category" id="category" class="form-control mr-2">
        <option value="booking" <?php echo $category === 'booking' ? 'selected' : ''; ?>>Booking</option>
        <option value="batal" <?php echo $category === 'batal' ? 'selected' : ''; ?>>Batal Booking</option>
        <option value="product" <?php echo $category === 'product' ? 'selected' : ''; ?>>Penjualan Produk</option>
        <option value="cash_in" <?php echo $category === 'cash_in' ? 'selected' : ''; ?>>Tambah Uang Kas</option>
        <option value="cash_out" <?php echo $category === 'cash_out' ? 'selected' : ''; ?>>Ambil Uang Kas</option>
    </select>
    <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Uang Masuk</th>
            <th>Uang Keluar</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($report['details'])): ?>
        <?php foreach ($report['details'] as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
            <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
            <td>Rp <?php echo number_format($row['uang_masuk'], 0, ',', '.'); ?></td>
            <td>Rp <?php echo number_format($row['uang_keluar'], 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">Tidak ada data</td>
        </tr>
    <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total</th>
            <th>Rp <?php echo number_format($report['total_masuk'], 0, ',', '.'); ?></th>
            <th>Rp <?php echo number_format($report['total_keluar'], 0, ',', '.'); ?></th>
        </tr>
        <tr>
            <th colspan="2">Saldo</th>
            <th colspan="2">Rp <?php echo number_format($report['saldo'], 0, ',', '.'); ?></th>
        </tr>
    </tfoot>
</table>

<?php $this->load->view('templates/footer'); ?>

