$(document).ready(function() {
    let modal = $('#ocn-callback-modal');
    let callback = $('#ocn-callback');
    const button = $('#ocn-callback-button');
    const url = button.attr('data-url');

    modal.on('show.bs.modal', function (e) {});
    modal.on('hide.bs.modal', function (e) {});

    $('#ocn-callback-store').on('click', function (e) {
        modal.modal('hide');
    })
});
