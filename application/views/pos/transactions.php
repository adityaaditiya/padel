<?php $this->load->view('templates/header'); ?>
<h2>Daftar Transaksi POS</h2>
<?php if (!empty($sales)): ?>
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Nota</th>
            <th>Total</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($sales as $s): ?>
        <tr>
            <td><?php echo htmlspecialchars($s->nomor_nota); ?></td>
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

