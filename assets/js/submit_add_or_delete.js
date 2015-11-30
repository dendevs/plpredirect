jQuery( '.submit_to_delete' ).click( function() {
    if( jQuery('.submit_to_delete').is(':checked') )
    {
        // delete
        jQuery( '#type_submit' ).val( 'delete_rule' ); 
        jQuery( '#add_or_delete' ).val( labels.delete ); 

        jQuery( 'input[type=text]' ).prop( 'disabled', true );

    }
    else
    {
        // add
        jQuery( '#type_submit' ).val( 'add_rule' ); 
        jQuery( '#add_or_delete' ).val( labels.add ); 

        jQuery( 'input[type=text]' ).prop( 'disabled', false );
    }
}); 
