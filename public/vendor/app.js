// ====== Sidebar toggles ======
const toggleButton = document.getElementById('c2g-toggle-btn');
const sidebar      = document.getElementById('c2g-sidebar');

function toggleSidebar(){
  sidebar.classList.toggle('close');
  toggleButton.classList.toggle('rotate');
  closeAllSubMenus();
}

if (toggleButton) {
  toggleButton.addEventListener('click', toggleSidebar);
}


function toggleSubMenu(button){
  // buka/tutup sub-menu pada item yang ditekan
  if(!button.nextElementSibling.classList.contains('show')){
    closeAllSubMenus();
  }
  button.nextElementSibling.classList.toggle('show');
  button.classList.toggle('rotate');

  // jika sidebar sedang close, otomatis buka biar sub-menu terlihat
  if(sidebar.classList.contains('close')){
    sidebar.classList.toggle('close');
    toggleButton.classList.toggle('rotate');
  }
}

function closeAllSubMenus(){
  Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
    ul.classList.remove('show');
    if(ul.previousElementSibling){
      ul.previousElementSibling.classList.remove('rotate');
    }
  });
}

// ====== User dropdown toggle ======
(function(){
  const menuWrap = document.getElementById('c2g-userMenu');
  const trigger  = document.getElementById('c2g-userTrigger');
  if(!menuWrap || !trigger) return;

  const close = () => { menuWrap.classList.remove('open'); trigger.setAttribute('aria-expanded','false'); };
  const open  = () => { menuWrap.classList.add('open');    trigger.setAttribute('aria-expanded','true');  };

  trigger.addEventListener('click', (e) => {
    e.stopPropagation();
    menuWrap.classList.contains('open') ? close() : open();
  });

  document.addEventListener('click', (e) => {
    if(!menuWrap.contains(e.target)) close();
  });

  document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape') close();
  });
})();

// ====== Fullscreen toggle ======
(function(){
  const fullscreenBtn   = document.getElementById("fullscreenBtn");
  if(!fullscreenBtn) return;
  const fullscreenIcon  = fullscreenBtn.querySelector("i");

  fullscreenBtn.addEventListener("click", () => {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen();
      fullscreenIcon.classList.remove("bi-arrows-fullscreen");
      fullscreenIcon.classList.add("bi-fullscreen-exit");
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
        fullscreenIcon.classList.remove("bi-fullscreen-exit");
        fullscreenIcon.classList.add("bi-arrows-fullscreen");
      }
    }
  });
})();


// ====== Bootstrap Tooltip Initialization ======
document.addEventListener('DOMContentLoaded', function () {

    // Inisialisasi Tooltip Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

    tooltipTriggerList.map(function (el) {

        // Tooltip instance
        var tt = new bootstrap.Tooltip(el, {
            trigger: 'hover focus' // bawaan Bootstrap (paling baik untuk UX)
        });

        // Hilangkan tooltip saat tombol diklik
        el.addEventListener('click', function () {
            tt.hide();
        });

        // Jika mouse keluar, tooltip juga hilang
        el.addEventListener('mouseleave', function () {
            tt.hide();
        });

        return tt;
    });
});


$(document).ready(function () {

    // Cek apakah ada tabel dengan class ini
    if ($('.datatable-myasset').length) {

        $('.datatable-myasset').DataTable({
            responsive: true,
            autoWidth: false,

            dom: '<"row mb-3"' +
                    '<"col-sm-6 d-flex align-items-center"l>' +
                    '<"col-sm-6 d-flex align-items-center justify-content-end"f>' +
                '>' +
                'rt' +
                '<"row mt-3"' +
                    '<"col-sm-6"i>' +
                    '<"col-sm-6 d-flex justify-content-end"p>' +
                '>',

            // Tambahkan opsi default disini
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "‹",
                    next: "›"
                }
            }
        });

    }
});

