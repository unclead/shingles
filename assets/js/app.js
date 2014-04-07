$(function() {
    $('#process-btn').click(function(e){
        e.preventDefault();

        $('#result').hide();

        var eles = ['url', 'token', 'search_text'],
            i = null,
            ele = null,
            is_valid = true;

        for(i in eles) {
            ele = $('#' + eles[i]);
            if($(ele).val() == '') {
                $(ele).closest('.control-group').addClass('error');
                is_valid = false;
            } else {
                $(ele).closest('.control-group').removeClass('error');
            }
        }

        ele = $('#limit');

        if(!/^\d*$/.test($(ele).val())) {
            $(ele).closest('.control-group').addClass('error');
            is_valid = false;
        } else {
            $(ele).closest('.control-group').removeClass('error');
        }

        if(is_valid) {
            $.ajax({
                url: $('#search-form').attr('action'),
                data: {
                    url: $('#url').val(),
                    token: $('#token').val(),
                    limit: $('#limit').val(),
                    search_text: $('#search_text').val()
                },
                type: 'POST',
                dataType: 'json',
                beforeSend: function() {
                    $('#error').hide();
                    $('#progress-bar').show();
                },
                success: function(data) {
                    $('#progress-bar').hide();
                    $('#duplicates').text(data.duplicates);
                    $('#percent').text(data.percent);
                    $('#time').text(data.time);
                    $('#result').show();

                },
                error: function(){
                    $('#progress-bar').hide();
                    $('#error').text('Ошибка!').show();
                }
            });
        }
    });
});

