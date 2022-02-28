jQuery(document).ready(function () {
    jQuery('#crypto-fiats-table').DataTable({
        'order': [[ 0, 'desc' ]],
        columnDefs: [
            { orderable: true, className: 'reorder', targets: 0 },
            { orderable: true, className: 'reorder', targets: 1 },
            { orderable: true, className: 'reorder', targets: 2 },
            { orderable: true, className: 'reorder', targets: 3 },
            { orderable: false, targets: '_all' }
        ],
    });
});