/**
 * Created with JetBrains PhpStorm.
 * User: administrator
 * Date: 9/14/13
 * Time: 4:09 PM
 * To change this template use File | Settings | File Templates.
 */
var record = {};

function recordSet(data_record){
    $('#showPopupModal').one('hide', function(e) {

        ed.selection.setContent($('#content_field').val());

    })
}

function changeForm(record_uid) {

    $('#showPopupModal').modal('show')

    $('#showPopupModal').one('hide', function(e) {

        var form_id = $('#content_field').val();

        $.ajax({//Make the Ajax Request
            type: "POST",
            url: "../../changeform",
            data: "record_uid="+ record_uid + "&form_id=" + form_id,
            success: function(html){
                $("#" + record_uid).remove();//Remove the div with id=more

            }
        });

    })
}
/*
function offLineDataStorage(){
    $(function () {
        $('form').on('submit', function (e) {

            console.log($('form').serialize());

            $.ajax({
                type: 'POST',
                url: '../',
                data: $('form').serialize(),
                success: function () {
                    alert('form was submitted');
                    $('form').trigger("reset");
                },
                error: function(){
                    alert('form was not submitted');
                }
            });
            e.preventDefault();
        });

    });
}
    */
