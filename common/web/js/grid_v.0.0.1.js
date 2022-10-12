'use strict';

function deleteGroup() {
    let ids = $('#grid').yiiGridView('getSelectedRows');

    if (ids.length === 0) {
        noOneChosen.alert(noOneChosen.options.message);
    } else {
        deleteConfirmation.confirm(deleteConfirmation.options.message, function (result) {
            if (result) {
                $.ajax({
                    url: location.protocol + '//' + location.hostname + '/error-log/delete-group',
                    data: {ids: ids},
                    type: 'POST',
                    success: function () {
                        location.reload();
                    }
                });
            }
        });
    }
}
