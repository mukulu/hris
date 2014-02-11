/**
 * Created with JetBrains PhpStorm.
 * User: administrator
 * Date: 8/27/13
 * Time: 8:06 PM
 * To change this template use File | Settings | File Templates.
 */

function tinymce_button_stfalcon(ed) {

    ed.focus();

    $('#showPopupModal').modal('show')

    $('#showPopupModal').one('hide', function(e) {

        ed.selection.setContent($('#content_field').val());

    })
}


