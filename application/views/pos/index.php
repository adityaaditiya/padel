<?php $this->load->view('templates/header'); ?>
<h2>Point of Sale</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <h4>Daftar Produk</h4>
        <div class="list-group">
        <?php foreach ($products as $p): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong><?php echo htmlspecialchars($p->nama_produk); ?></strong><br>
                    <small>Rp <?php echo number_format($p->harga_jual, 0, ',', '.'); ?></small>
                </div>
                <a href="<?php echo site_url('pos/add/'.$p->id); ?>" class="btn btn-sm btn-success">Tambah</a>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <div class="col-md-6">
        <h4>Keranjang</h4>
        <?php if (!empty($cart)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                        <td><?php echo $item['qty']; ?></td>
                        <td>Rp <?php echo number_format($item['harga_jual'] * $item['qty'], 0, ',', '.'); ?></td>
                        <td><a href="<?php echo site_url('pos/remove/'.$item['id']); ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th colspan="2">Rp <?php echo number_format($total, 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
            <a href="<?php echo site_url('pos/checkout'); ?>" class="btn btn-primary">Checkout</a>
        <?php else: ?>
            <p>Keranjang kosong.</p>
        <?php endif; ?>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>