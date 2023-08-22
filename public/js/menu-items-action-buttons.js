function showMenuItemExport() {
  $('#modal-menu-item-export').modal('show');
}

function menuIngredientsExport() {
  $('#modal-menu-ingredients-export').modal('show');
}

$('.view-menu-details').on('click', function () {
  const dbId = $(this).attr('href')?.replace('#', '');
  const currentMainPath = window.location.origin;
  Swal.fire({
    title: 'Which details do you want to see?',
    html:
      '<button detail-to-view="detail" style="margin-right: 5px;" class="swal-view-menu btn btn-success">' +
      '🍕 Ingredients' +
      '</button>' +
      '<button detail-to-view="packaging-detail" style="margin-right: 5px;" class="swal-view-menu btn btn-danger">' +
      '🛍️ Packaging' +
      '</button>' +
      '<button detail-to-view="costing-detail" style="margin-right: 5px;" class="swal-view-menu btn btn-primary">' +
      '💲 Costing' +
      '</button>' +
      '<button detail-to-view="menu-data" class="swal-view-menu btn btn-warning">' +
      '📄 Menu Data' +
      '</button>',
    showConfirmButton: false,
    showCloseButton: true,
    didOpen: () => {
      $('.swal-view-menu').on('click', function () {
        const detail = $(this).attr('detail-to-view');
        location.assign(
          `${currentMainPath}/admin/menu_items/${detail}/${dbId}`
        );
      });
    },
  });
});

$('.edit-menu-item').on('click', function () {
  const dbId = $(this).attr('href')?.replace('#', '');
  const currentMainPath = window.location.origin;
  Swal.fire({
    title: 'Which details do you want to edit?',
    html:
      '<button detail-to-edit="ingredients" style="margin-right: 5px;" class="swal-edit-menu btn btn-success">' +
      '🍕 Ingredients' +
      '</button>' +
      '<button detail-to-edit="packagings" style="margin-right: 5px;" class="swal-edit-menu btn btn-danger">' +
      '🛍️ Packaging' +
      '</button>' +
      '<button detail-to-edit="costing" style="margin-right: 5px;" class="swal-edit-menu btn btn-primary">' +
      '💲 Costing' +
      '</button>' +
      '<button detail-to-edit="menu-data" class="swal-edit-menu btn btn-warning">' +
      '📄 Menu Data' +
      '</button>',
    showConfirmButton: false,
    showCloseButton: true,
    didOpen: () => {
      $('.swal-edit-menu').on('click', function () {
        const detail = $(this).attr('detail-to-edit');
        location.assign(
          `${currentMainPath}/admin/menu_items/edit/${dbId}/${detail}`
        );
      });
    },
  });
});

$('.user-footer .pull-right a').on('click', function () {
  const currentMainPath = window.location.origin;
  Swal.fire({
    title: 'Do you want to logout?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#b9b9b9',
    confirmButtonText: 'Logout',
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      location.assign(`${currentMainPath}/admin/logout`);
    }
  });
});
