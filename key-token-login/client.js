ktl = (window.ktl || {});

/**
 * @link http://www.vishwayon.com/
 * @copyright Copyright (c) 2020 Vishwayon Software Pvt Ltd
 * @license MIT
 */

(function(ktl) {
    
    function login(target) {
        $.ajax({
            url: 'Server.php',
            method: 'GET',
            dataType: 'json',
            success: function(result) {
                if (result.status == 'OK') {
                    var uri = result.url + '?' + $.param({
                        r: result.route,
                        'auth-token': result['auth-token'],
                        'core-sessionid': result['core-sessionid'],
                        'doc-type': 'DSP',
                        'doc-id': ($('#doc-id').val() != '' ?  $('#doc-id').val() : '-1'), // Or the linked document id that needs to be opened automatically
                        'proposal-id': $('#proposal-id').val()
                    });
                    if (target) {
                        $(target).attr('src', uri);
                    } else {
                        window.open(uri);
                    }
                } else {
                    alert(result.msg);
                }
            },
            error: function(err) {
                console.log(err);
            }
        })
    }
    ktl.login = login;
    
} (window.ktl));


