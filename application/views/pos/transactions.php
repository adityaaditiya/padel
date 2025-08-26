<?php $this->load->view('templates/header'); ?>
<h2>Daftar Transaksi POS</h2>

<form method="get" class="form-inline mb-3">
    <div class="form-group mr-2">
        <label for="from" class="mr-2">Tanggal dari</label>
        <input type="date" class="form-control" id="from" name="from" placeholder="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($from ?? ''); ?>">
    </div>
    <div class="form-group mr-2">
        <label for="to" class="mr-2">Tanggal sampai</label>
        <input type="date" class="form-control" id="to" name="to" placeholder="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($to ?? ''); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
</form>

<?php if (!empty($sales)): ?>
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Nota</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($sales as $s): ?>
        <tr>
            <td><?php echo htmlspecialchars($s->nomor_nota); ?></td>
            <td><?php echo htmlspecialchars($s->customer_name); ?></td>
            <td>Rp <?php echo number_format($s->total_belanja, 0, ',', '.'); ?></td>
            <td><?php echo htmlspecialchars($s->tanggal_transaksi); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>Belum ada transaksi.</p>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>

