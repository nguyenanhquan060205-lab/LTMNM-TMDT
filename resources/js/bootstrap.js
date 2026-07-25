const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

window.techSecond = {
    csrfToken: token,
    fetchDefaults: {
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(token ? { 'X-CSRF-TOKEN': token } : {}),
        },
    },
};
