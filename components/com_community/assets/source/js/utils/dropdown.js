(function( root, $, factory ) {

    joms.util || (joms.util = {});
    joms.util.dropdown = factory( root, $ );

    define([ 'utils/popup' ], function() {
        return joms.util.dropdown;
    });

})( window, joms.jQuery, function( window, $, undefined ) {

var

// Event list.
evtClick = 'click.dropdown',
evtHide = 'collapse.dropdown',

// Selectors.
slrButton = '[data-ui-object=joms-dropdown-button]',
slrDropdown = '.joms-dropdown,.joms-popover',

// Element cache.
lastbtn,
lastdd,
popup,
elem,
doc;

function hide() {
    lastdd && lastdd.hide();
    lastbtn && btnRemoveClass( lastbtn );
}

function toggle( e ) {
    var btn, dd, $elm, type, size;

    e.stopPropagation();
    e.preventDefault();

    $elm = $(e.currentTarget);
    type = $elm.attr('data-type');

    btn = $( e.currentTarget );
    dd = btn.siblings( slrDropdown );

    if ( !dd.length ) {
        return;
    }

    if ( dd.is(':visible') ) {
        dd.hide();
        btnRemoveClass( btn );
        return;
    }

    size = joms.screenSize();
    
    if (size !== 'large') {
        $elm.siblings('.joms-dropdown').css('right', 0);
    }

    if ( size === 'large' || type === 'no-popup' ) {

        hide();
        dd.show();
        btnAddClass( btn );
        lastbtn = btn;
        lastdd = dd;
        executeAdditionalFn( dd );
        return;
    }

    joms.util.popup.prepare(function( mfp ) {
        popup = mfp;
        popup.items[0] = {
            type: 'inline',
            src: buildHtml( dd )
        };

        popup.updateItemHTML();
        executeAdditionalFn( dd );

        elem = popup.contentContainer;
        elem.on( 'click', 'li > a', function() {
            popup.close();
        });
    });
}

function btnAddClass( btn ) {
    var par = btn.parent();
    if ( par.hasClass('.joms-focus__button--options--desktop') ) {
        par.addClass('active');
    } else {
        btn.addClass('active');
    }
}

function btnRemoveClass( btn ) {
    var par = btn.parent();
    if ( par.hasClass('.joms-focus__button--options--desktop') ) {
        par.removeClass('active');
    } else {
        btn.removeClass('active');
    }
}

function buildHtml( dd ) {
    return '<div class="joms-popup joms-popup--dropdown">' + dd[0].outerHTML + '</div>';
}

function executeAdditionalFn( dd ) {
    var className = dd.attr('class') || '',
        offset;

    // fix popup goes out of browser's window
    dd.css('left', '');
    offset = dd.offset();
    if ( offset.left < 0 ) {
        dd.css('left', -25 );
    }

    if ( className.match('joms-popover--toolbar-general') ) {
        joms.api.notificationGeneral();
        return;
    }
    if ( className.match('joms-popover--toolbar-friendrequest') ) {
        joms.api.notificationFriend();
        return;
    }
    if ( className.match('joms-popover--toolbar-pm') ) {
        joms.api.notificationPm();
        return;
    }
}

// Change privacy dropdown.
var selectPrivacy = joms._.debounce(function( e ) {
    var className = e.currentTarget.className || '',
        ul, li, btn, hidden, span, svg;

    if ( className.indexOf('joms-dropdown--privacy') < 0 ) {
        return;
    }

    ul  = $( e.currentTarget );
    li  = $( e.target ).closest('li');

    if ( li.length ) {
        btn    = $('.joms-button--privacy').filter('[data-name="' + ul.data('name') + '"]');
        hidden = btn.children('[data-ui-object=joms-dropdown-value]');
        span   = btn.children('span');
        svg    = btn.find('use');

        hidden.val( li.data('value') );
        span.html( li.children('span').html() );
        svg.attr( 'xlink:href', window.location.href.replace(/#.*$/, '') + '#' + li.data('classname') );
    }

    hide();
    popup && popup.close();

}, 100 );

function initialize() {
    uninitialize();

    doc || (doc = $( document.body ));
    doc.on( evtClick, hide );
    doc.on( evtClick, slrButton, toggle );
    doc.on( evtHide, slrButton, hide );
    doc.on( evtClick, slrDropdown, function( e ) {
        var shouldPropagate = $( e.target ).data( 'propagate' ) || $( e.currentTarget ).data( 'propagate' );
        if ( ! shouldPropagate ) {
            e.stopPropagation();
        }
        selectPrivacy( e );
        
        var $elm = $(e.currentTarget);
        if(!$elm.hasClass("joms-popover--toolbar-search")){
            $elm.hide();
        }
        
    });
}

function uninitialize() {
    if ( doc ) {
        doc.off( evtClick );
        doc.off( evtClick, slrButton );
        doc.off( evtHide, slrButton );
        doc.off( evtClick, slrDropdown );
    }
}

// Exports.
return {
    start: initialize,
    stop: uninitialize
};

});
