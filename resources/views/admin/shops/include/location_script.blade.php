<script>
    function getContents (type,id,selectedId) {
        var __url = `/locations/provinces`;
        // __url = __url.replace('__type', type);
        $.ajax({
            url:__url,
            async: false
        }).done(function(data){
            dataOptions = $.map(data[id], function (item) {
                return {
                    text: item.name,
                    id: item.id,
                    selected: (item.id == selectedId) ? true : false,
                    // disabled: true
                }
            });
            $(`.${type}`).html(`<option value=""></option>`);
            $.map(data[id], function (item) {
                $(`.${type}`).append(`<option value="${item.id}">${item.name}</option>`);
            });
        });
    }

    $(document).ready(function (){
        var element = $("#country_id");
        $("#country_id").val(216).change();
    })

    $(document).on('change', '.countries', function(){
        var id = $(this).val();
        var selectedId = $(this).data('selected');
        getContents('provinces', id, selectedId);
    });

    // $(document).on('change', '.provinces', function(){
    //     var id = $(this).val();
    //     var selectedId = $(this).data('selected');
    //     $('.districts').html(``);
    //     $('.subdistricts').html(``);
    //     // getContents('districts',id, selectedId);
    // });

    // $(document).on('change', '.districts', function(){
    //     var id = $(this).val();
    //     var selectedId = $(this).data('selected');
    //     $('.subdistricts').html(``);
    //     getContents('subdistricts', id, selectedId);
    // });

    $(document).on('change', '.subdistricts', function(){

    });
</script>