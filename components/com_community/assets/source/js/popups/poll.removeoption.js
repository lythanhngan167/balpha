(function( root, factory ) {

    joms.popup || (joms.popup = {});
    joms.popup.poll || (joms.popup.poll = {});
    joms.popup.poll.removeoption = factory( root );

    define([ 'utils/popup' ], function() {
        return joms.popup.poll.removeoption;
    });

})( window, function() {

var popup, elem, id;

function render( _popup, _id ) {
    if ( elem ) elem.off();
    popup = _popup;
    id = _id;

    joms.ajax({
        func: 'polls,ajaxConfirmDeletePollOption',
        data: [ '', id ],
        callback: function( json ) {
            popup.items[0] = {
                type: 'inline',
                src: buildHtml( json )
            };

            popup.updateItemHTML();

            elem = popup.contentContainer;
            elem.on( 'click', '[data-ui-object=popup-button-cancel]', cancel );
            elem.on( 'click', '[data-ui-object=popup-button-save]', save );
        }
    });
}

function cancel() {
    elem.off();
    popup.close();
}

function save() {
    joms.ajax({
        func: 'polls,ajaxDeletePollOption',
        data: [ '', id ],
        callback: function( json ) {
            var item;

            elem.off();
            popup.close();

            if ( json.success ) {
                item = joms.jQuery('.joms-poll-item-'+id);
                item.fadeOut( 500, function() {
                    item.remove();
                });
            }
        }
    });
}

function buildHtml( json ) {
    json || (json = {});

    return [
        '<div class="joms-popup joms-popup--whiteblock">',
        '<div class="joms-popup__title"><button class="mfp-close" type="button" title="',window.joms_lang.COM_COMMUNITY_CLOSE_BUTTON_TITLE,'">×</button>', json.title, '</div>',
        '<div>',
            '<div class="joms-popup__content">', ( json.error || json.message ), '</div>',
            '<div class="joms-popup__action">',
            '<a href="javascript:" class="joms-button--neutral joms-button--small joms-left" data-ui-object="popup-button-cancel">', json.btnCancel, '</a> &nbsp;',
            '<button class="joms-button--primary joms-button--small" data-ui-object="popup-button-save">', json.btnYes, '</button>',
            '</div>',
        '</div>',
        '</div>'
    ].join('');
}

// Exports.
return function( id ) {
    joms.util.popup.prepare(function( mfp ) {
        render( mfp, id );
    });
};

});
