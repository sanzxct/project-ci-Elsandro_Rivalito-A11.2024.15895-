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
            <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'        => 'voucher_code',
                'id'          => 'voucher_code',
                'class'       => 'form-control',
                'placeholder' => 'Contoh: PROMO2025'
            ]) ?>
            <small class="text-muted">Tersedia: PROMO2025 (10%), PROMO2026 (15%), AKHIRTAHUN (25%)</small>
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
                    <td class="text-danger">Diskon Voucher</td>
                    <td class="text-danger"><span id="diskon_voucher_display">-</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Biaya Jasa</td>
                    <td><span id="biaya_jasa_display">-</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-success">Free Mouse</td>
                    <td class="text-success"><span id="free_mouse_display">-</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-primary fw-bold">Subtotal (+Jasa-Voucher-Free Mouse)</td>
                    <td class="text-primary fw-bold"><span id="subtotal_promo_display">-</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="fw-bold">Grand Total (incl. Ongkir)</td>
                    <td class="fw-bold"><span id="total"><?= number_to_currency($total, 'IDR') ?></span></td>
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

        let biayaJasa = 0;
        let diskonVoucher = 0;
        let freeMouse = 0;
        let subtotalPromo = subtotal;
        let voucherPercent = 0;

        hitungPromo(); // panggil pertama kali saat halaman dimuat

        function formatIDR(angka) {
            return `IDR ${Math.round(angka).toLocaleString('id-ID')}`;
        }

        function hitungPromo() {
            let voucherCode = $("#voucher_code").val();

            $.ajax({
                url: "<?= site_url('ajax/promo') ?>",
                dataType: "json",
                data: {
                    total_harga: subtotal,
                    voucher_code: voucherCode
                },
                success: function(data) {
                    biayaJasa      = parseFloat(data.biaya_jasa);
                    diskonVoucher  = parseFloat(data.diskon_voucher);
                    freeMouse      = parseFloat(data.free_mouse);
                    subtotalPromo  = parseFloat(data.subtotal_promo);
                    voucherPercent = parseInt(data.voucher_percent);

                    $("#biaya_jasa_display").text(formatIDR(biayaJasa));

                    if (diskonVoucher > 0) {
                        $("#diskon_voucher_display").text('-' + formatIDR(diskonVoucher) + ` (${voucherPercent}%)`);
                    } else {
                        $("#diskon_voucher_display").text('-' + formatIDR(0));
                    }

                    if (freeMouse > 0) {
                        $("#free_mouse_display").text('-' + formatIDR(freeMouse));
                    } else {
                        $("#free_mouse_display").text(formatIDR(0));
                    }

                    $("#subtotal_promo_display").text(formatIDR(subtotalPromo));

                    hitungTotal();
                }
            });
        }

        function hitungTotal() {
            let total = subtotalPromo + ongkir;

            $("#ongkir").val(ongkir);
            $("#total").text(formatIDR(total));
            $("#total_harga").val(total);
        }

        let voucherTimer;
        $("#voucher_code").on('keyup', function() {
            clearTimeout(voucherTimer);
            voucherTimer = setTimeout(function() {
                hitungPromo();
            }, 400);
        });

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

            $("#layanan").empty();
            $("#layanan").append('<option value="" selected disabled>Pilih Layanan Pengiriman</option>');
            ongkir = 0;
            hitungTotal();

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
                                value: item.cost,
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
            let selectedCost = $(this).val();
            ongkir = parseInt(selectedCost) || 0;
            hitungTotal();
        });
    });
</script>
<?= $this->endSection() ?>