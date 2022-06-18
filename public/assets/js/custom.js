function logout() {
    Swal.fire({
        title: 'Yakin ingin keluar ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Keluar',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('form-logout').submit();
        }
      });
}

function deleteData(url) {
    Swal.fire({
      title: 'Konfirmasi hapus data ?',
      text: 'Data yang telah dihapus tidak dapat dikembalikan',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('form-delete-data').setAttribute('action', url);
        document.getElementById('form-delete-data').submit();
      }
    });
}