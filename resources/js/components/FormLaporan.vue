<template>
    <div>
        <h1>Form Laporan</h1>
        <form>
            <div class="form-group">
                <label for="judul">Judul Laporan</label>
                <input type="text" id="judul" class="form-control" />
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi Laporan</label>
                <textarea id="deskripsi" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'FormLaporan',
};
</script>

<style>
/* Tambahkan gaya form */
</style>