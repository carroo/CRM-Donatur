<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-dark mb-0">Table Master api</h2>
            <div class="btn-group">
                <button id="exportExcel" class="btn btn-info">
                    <i class="bi bi-file-arrow-down"></i>
                    Export Excel
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Api
                </button>
            </div>
        </div>


        <div class="card-body p-4">
            <table class="table table-bordered table-hover datatable pt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Apikey</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value) : ?>

                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value->type ?></td>
                            <td><?= $value->apikey ?></td>
                            <td class="">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-<?= $value->id ?>" title="update">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php
                                    $linkhapus = base_url('master/api_hapus/' . $value->id);
                                    ?>
                                    <a href="#" onclick="confirmAlert('<?= $linkhapus ?>','Apa anda yakin ingin menghapus?')" type="button" class="btn btn-danger btn-sm" title="hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Api</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?= base_url('master/api_tambah') ?>" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="type" class="form-label">Title</label>
                                <input type="text" class="form-control"  name="type" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Api</label>
                                <input type="text" class="form-control"  name="apikey" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <?php foreach ($data as $key => $value) : ?>
            <div class="modal fade" id="edit-<?= $value->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Api</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('master/api_update/' . $value->id) ?>" method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="type" class="form-label">title</label>
                                    <input type="text" class="form-control" name="type" value="<?= $value->type ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">apikey</label>
                                    <input type="text" class="form-control" name="apikey" value="<?= $value->apikey ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>