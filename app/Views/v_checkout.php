<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

        <?= form_hidden('username', session()->get('username')) ?>

        <?= form_input([
            'type' => 'hidden',
            'name' => 'total_harga',
            'id' => 'total_harga'
        ]) ?>

        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'nama',
                'id'       => 'nama',
                'class'    => 'form-control',
                'value'    => session()->get('username'),
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'alamat',
                'id'    => 'alamat',
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?>
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'ongkir',
                'id'       => 'ongkir',
                'class'    => 'form-control',
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-12">
            <?= form_submit(
                'submit',
                'Buat Pesanan',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?= form_close() ?>
    </div>
    <div class="col-lg-6">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($items)) :
                    foreach ($items as $index => $item) :
                ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                        </tr>
                <?php
                    endforeach;
                endif;
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Total</td>
                    <td><span id="total"><?= number_to_currency($total, 'IDR') ?></span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        let ongkir = 0;
        let subtotal = <?= $total ?>;
        hitungTotal();

        function hitungTotal() {
            let total = subtotal + ongkir;

            $("#ongkir").val(ongkir);
            $("#total").text(`IDR ${total.toLocaleString('id-ID')}`);
            $("#total_harga").val(total);
        }

        // 1. Inisialisasi Select2
        $('#kelurahan').select2({
            placeholder: 'Cari daerah tujuan',
            minimumInputLength: 3,
            ajax: {
                url: '<?= site_url('ajax/destinations') ?>',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        // 2. Ketika Kelurahan dipilih -> Hit API Ongkir
        $("#kelurahan").on('change', function() {
            let id_kelurahan = $(this).val();

            // Reset dropdown layanan dan total
            $("#layanan").empty();
            $("#layanan").append('<option value="" selected disabled>Pilih Layanan Pengiriman</option>');
            ongkir = 0;
            hitungTotal();

            // Pindahkan AJAX Cost ke dalam sini
            $.ajax({
                url: "<?= site_url('ajax/costs') ?>",
                dataType: "json",
                data: {
                    destination: id_kelurahan
                },
                success: function(data) {
                    data.forEach(function(item) {
                        $("#layanan").append(
                            $('<option>', {
                                value: item.cost, // Memasukkan harga ke dalam value option
                                text: `${item.description} (${item.service}) : IDR ${parseInt(item.cost).toLocaleString('id-ID')} (Est: ${item.etd})`
                            })
                        );
                    });
                },
                error: function() {
                    $("#layanan").append('<option value="">Gagal mengambil layanan</option>');
                }
            });
        });

        // 3. Ketika Layanan dipilih -> Update Ongkir dan Total Harga
        $("#layanan").on('change', function() {
            // Ambil value dari dropdown layanan (berisi nominal cost)
            let selectedCost = $(this).val();
            
            // Ubah string menjadi angka (integer), jika kosong jadikan 0
            ongkir = parseInt(selectedCost) || 0; 
            
            // Jalankan fungsi hitung ulang
            hitungTotal();
        });
    });
</script>
<?= $this->endSection() ?>