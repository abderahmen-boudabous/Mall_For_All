$( document ).ready(function() {
    $("#btn-search").on('click', function(){
        $.ajax({
            type: 'POST',
            url: '/search/',
            data: {
                "search": $("#search").val()
            },
            dataType: 'json',
            success: function(data){
                $("#search_tab").html('');
                data.forEach(element => $("#search_tab").append("<tr>\n" +
                    "                            <th scope=\"col\"><input class=\"form-check-input\" type=\"checkbox\"></th>\n" +
                    "                            <td>"  +element['id']+ "</td>\n" +
                    "                            <td>"  +element['nom']+ "</td>\n" +
                    "                            <td>"  +element['email']+ "</td>\n" +
                    "                            <td>"  +element['tel']+ "</td>\n" +
                    "                            <td>"  +element['libelle']+ "</td>\n" +
                    "                            <td>\n" +
                    "                                <a class=\"btn btn-sm btn-primary\" href=\"Delete_Supplier/"+element['id']+"\">Delete</a>\n" +
                    "                                <a class=\"btn btn-sm btn-primary\" href=\"Update_Supplier/"+element['id']+"\">Update</a>\n" +
                    "                                <a class=\"btn btn-sm btn-primary\" href=\"detail/"+element['id']+"\">Details</a>\n" +
                    "                            </td>\n" +
                    "                        </tr>"));
            }
        });
    })
});