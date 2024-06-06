<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-dark mb-0">Table Pengaturan Api Pesan</h2>
        </div>


        <div class="card-body p-4 table-responsive">
            <table class="table table-bordered table-hover datatable pt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Apikey</th>
                        <th>Phonekey</th>
                        <th>Title</th>
                        <th>Limit</th>
                        <th>Status</th>
                        <th>Phone Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value) : ?>

                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value->apikey ?></td>
                            <td><?= $value->phonekey ?></td>
                            <td><?= $value->type ?></td>
                            <td><?= $kuota[$key]['limit'] . '/' . $kuota[$key]['terkirim'] ?></td>
                            <td>
                                <?php if ($value->active) : ?>
                                    <span class="badge bg-success">Digunakan</span>
                                <?php else : ?>
                                    <a href="<?= base_url('pesan/pengaturan_use/' . $value->id) ?>" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-check"></i>
                                        Gunakan Apikey Ini
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('pesan/pengaturan_phone/'.$value->id) ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-phone"></i>
                                    Phone Info
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

    </div>
</div>