document.addEventListener('DOMContentLoaded', function () {
    window.hapusSatu = function () {
        document.getElementById('hapus_semua').value = 0;
        document.getElementById('formHapus').submit();
    };

    window.hapusSemua = function () {
        if (confirm('Yakin ingin menghapus semua jadwal selama 1 semester?')) {
            document.getElementById('hapus_semua').value = 1;
            document.getElementById('formHapus').submit();
        }
    };
});
