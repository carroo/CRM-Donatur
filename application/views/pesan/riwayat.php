<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-dark mb-0">Table Riwayat Pesan</h2>
        </div>


        <div class="card-body p-4">
            <table class="table table-bordered table-hover datatable pt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Waktu</th>
                        <th>Dari phone key</th>
                        <th>Ke</th>
                        <th>Pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value) : ?>

                        <tr>
                            <td><?= $value->kwid ?></td>
                            <td><?= $value->time ?></td>
                            <td><?= $value->sender ?></td>
                            <td><?= $value->receiver ?></td>
                            <td><?= strlen($value->message) > 100 ? substr($value->message, 0, 100) . '...' : $value->message ?></td>
                            <td>
                                <?php if ($value->status == 1) : ?>
                                    <span class="badge bg-success">Terkirim</span>
                                <?php elseif ($value->status == 0) : ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php elseif ($value->status == -1) : ?>
                                    <span class="badge bg-danger">Gagal</span><br>
                                    <span class=""><?= $value->note ?></span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

    </div>
</div>