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
        <form class="form-inline mb-2" onsubmit="return false;">
            <select name="kategori" id="category-filter" class="form-control mr-2">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c->kategori; ?>" <?php echo ($selected_category == $c->kategori) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c->kategori); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="q" id="product-search" value="<?php echo htmlspecialchars($search_query); ?>" class="form-control mr-2" placeholder="Cari produk">
        </form>
        <table id="products-table" class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p->nama_produk); ?></td>
                    <td>Rp <?php echo number_format($p->harga_jual, 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($p->kategori); ?></td>
                    <td><a href="<?php echo site_url('pos/add/'.$p->id); ?>" class="btn btn-sm btn-success">Tambah</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h4>Keranjang</h4>
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
                            <td><input type="number" name="qty[<?php echo $item['id']; ?>]" value="<?php echo $item['qty']; ?>" min="1" class="form-control form-control-sm"></td>
                            <td>Rp <?php echo number_format($item['harga_jual'] * $item['qty'], 0, ',', '.'); ?></td>
                            <td><a href="<?php echo site_url('pos/remove/'.$item['id']); ?>" class="btn btn-sm btn-danger">Hapus</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><button type="submit" class="btn btn-secondary btn-sm">Update Qty</button></td>
                            <th>Total</th>
                            <th>Rp <?php echo number_format($total, 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </form>
            <form method="post" action="<?php echo site_url('pos/checkout'); ?>">
                <input type="hidden" name="device_date" id="device_date">
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

var searchInput = document.getElementById('product-search');
var categorySelect = document.getElementById('category-filter');
var productsBody = document.querySelector('#products-table tbody');
var searchUrl = '<?php echo site_url('pos/search'); ?>';
var addUrl = '<?php echo site_url('pos/add/'); ?>';

function renderProducts(items) {
    productsBody.innerHTML = '';
    items.forEach(function(p) {
        var tr = document.createElement('tr');
        tr.innerHTML = '<td>' + p.nama_produk + '</td>' +
                       '<td>Rp ' + Number(p.harga_jual).toLocaleString('id-ID') + '</td>' +
                       '<td>' + p.kategori + '</td>' +
                       '<td><a href="' + addUrl + p.id + '" class="btn btn-sm btn-success">Tambah</a></td>';
        productsBody.appendChild(tr);
    });
}

function updateProducts() {
    var params = new URLSearchParams();
    if (categorySelect.value) params.append('kategori', categorySelect.value);
    if (searchInput.value) params.append('q', searchInput.value);
    fetch(searchUrl + '?' + params.toString())
        .then(function(r){ return r.json(); })
        .then(renderProducts);
}

if (searchInput && categorySelect) {
    searchInput.addEventListener('input', updateProducts);
    categorySelect.addEventListener('change', updateProducts);
}
</script>
<?php $this->load->view('templates/footer'); ?>
