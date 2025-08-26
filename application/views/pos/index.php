<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('store/overlay'); ?>
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
        <form method="get" class="form-inline mb-2">
            <select name="kategori" class="form-control mr-2">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c->kategori; ?>" <?php echo ($selected_category == $c->kategori) ? 'selected' : ''; ?>><?php echo ucfirst($c->kategori); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="q" class="form-control mr-2" placeholder="Cari produk" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
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
        <h4>Keranjang (Nota: <?php echo $nomor_nota; ?>)</h4>
        <?php if (!empty($cart)): ?>
            <form method="post" action="<?php echo site_url('pos/update_cart'); ?>">
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
                        <td><input type="number" name="qty[<?php echo $item['id']; ?>]" value="<?php echo $item['qty']; ?>" min="1" class="form-control"></td>
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
            <button type="submit" class="btn btn-secondary mb-3">Update Qty</button>
            </form>
            <form method="post" action="<?php echo site_url('pos/checkout'); ?>">
                <input type="hidden" name="device_date" id="device_date">
                <div class="form-group">
                    <label>Pembeli</label>
                    <select name="member_id" id="member_id" class="form-control">
                        <option value="">Non Member</option>
                        <?php foreach ($members as $m): ?>
                            <option value="<?php echo $m->id; ?>"><?php echo htmlspecialchars($m->nama_lengkap); ?> (<?php echo $m->kode_member; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" id="non_member_name">
                    <label>Atas Nama</label>
                    <input type="text" name="atas_nama" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Checkout</button>
            </form>
        <?php else: ?>
            <p>Keranjang kosong.</p>
        <?php endif; ?>
    </div>
</div>

<script>
var deviceInput = document.getElementById('device_date');
if (deviceInput) {
    var now = new Date();
    deviceInput.value = now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2);
}
var memberSelect = document.getElementById('member_id');
var nonMember = document.getElementById('non_member_name');
if (memberSelect && nonMember) {
    memberSelect.addEventListener('change', function() {
        if (this.value) {
            nonMember.style.display = 'none';
        } else {
            nonMember.style.display = 'block';
        }
    });
    memberSelect.dispatchEvent(new Event('change'));
}
</script>
<?php $this->load->view('templates/footer'); ?>
