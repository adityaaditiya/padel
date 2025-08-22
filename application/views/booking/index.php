<?php $this->load->view('templates/header'); ?>
<h2>Jadwal Booking Lapangan</h2>
<form method="get" class="form-inline mb-3">
    <label for="date" class="mr-2">Tanggal:</label>
    <input type="date" id="date" name="date" class="form-control mr-2" value="<?php echo htmlspecialchars($date); ?>">
    <button type="submit" class="btn btn-primary">Lihat</button>
    <a href="<?php echo site_url('booking/create'); ?>" class="btn btn-success ml-2">Booking Baru</a>
</form>

<?php if (!empty($bookings)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Lapangan</th>
                <th>Pelanggan</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?php echo htmlspecialchars($b->id_court); ?></td>
                <td><?php echo htmlspecialchars($b->id_user); ?></td>
                <td><?php echo htmlspecialchars($b->jam_mulai); ?></td>
                <td><?php echo htmlspecialchars($b->jam_selesai); ?></td>
                <td><?php echo htmlspecialchars($b->status_booking); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada booking pada tanggal ini.</p>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>