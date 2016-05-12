$('#table-characters').on('click','.remove', function(event){
    var name = $(this).parents("tr").children(".name").text();
    var server = $(this).parents("tr").children(".server").text();
    console.log(name + " " + server);
    var tr = $(this).parents('tr');
    $( "#dialog-confirm" ).dialog({
        resizable: false,
        height:200,
        weight:700,
        modal: true,
        buttons: {
            "Oui": function() {
                $.post(Routing.generate('wc_character_remove'),
                    {name : name , server : server },
                    function(response)
                    {
                        console.log(response);
                        if(response.sucess == true)
                        {
                            tr.remove();
                        }
                        else if(response.why == "Chef")
                        {
                            alert("Vous ne pouvez pas supprimer ce personnage tant qu'il est chef d'une guilde");
                        }
                    });
                //console.log("Après Post");
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }
    });
});
