document.addEventListener('submit', async (event) => {
    const form = event.target.closest('[data-place-delete]');
    if (!form) return;

    event.preventDefault();
    const result = await window.Swal.fire({
        title: 'Hapus tempat menarik?',
        text: `${form.dataset.placeName} dan fotonya akan dihapus.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus tempat',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#085041',
        cancelButtonColor: '#444441',
    });
    if (result.isConfirmed) form.submit();
});
