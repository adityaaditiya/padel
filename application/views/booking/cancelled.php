<?php $this->load->view('templates/header'); ?>
<h2>Booking Batal</h2>
<?php if (!empty($bookings)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Lapangan</th>
                <th>Pelanggan</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?php echo htmlspecialchars($b->tanggal_booking); ?></td>
                <td><?php echo htmlspecialchars($b->id_court); ?></td>
                <td><?php echo htmlspecialchars($b->id_user); ?></td>
                <td><?php echo htmlspecialchars($b->jam_mulai); ?></td>
                <td><?php echo htmlspecialchars($b->jam_selesai); ?></td>
                <td><?php echo htmlspecialchars($b->keterangan); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada booking batal.</p>
<?php endif; ?>
<?php $this->load->view('templates/footer'); ?>
