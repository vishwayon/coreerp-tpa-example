ktp = (window.ktp || {});

/**
 * @link http://www.vishwayon.com/
 * @copyright Copyright (c) 2020 Vishwayon Software Pvt Ltd
 * @license MIT
 */

(function (ktp) {

    function print() {
        $.ajax({
            url: 'Print.php',
            method: 'GET',
            dataType: 'json',
            data: {'doc-type': 'DSP', 'doc-id': ($('#doc-id').val() != '' ? $('#doc-id').val() : '-1')},
            success: function (result) {
                if (result.status === 'OK') {
                    if (result.filePath !== '') {
                        $('#print-info').show();
                        $('#print-link').html(result.filePath);
                        $('#print-link').attr("href", result.filePath);
                        //window.open(result.filePath, '_blank');
                    }
                }
                console.log(result);
            },
            error: function (err) {
                console.log(err);
            }
        })
    }
    ktp.print = print;

}(window.ktp));


