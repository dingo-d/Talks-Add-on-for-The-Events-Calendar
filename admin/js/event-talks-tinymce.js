/* TinyMCE button */

(function() {
    tinymce.PluginManager.add('event_talks', function( editor, url ) {
        editor.addButton( 'event_talks', {
            text: event_talks_tinyMCE_object.event_talks_label,
            icon: false,
            onclick: function() {
                editor.windowManager.open( {
                    title: event_talks_tinyMCE_object.event_talks_label,
                    body: [
                        {
                            type   : 'listbox',
                            name   : 'post_id',
                            label  : event_talks_tinyMCE_object.post_id_label,
                            values : event_talks_tinyMCE_pid_object,
                            value : ''
                        },
                        {
                            type: 'textbox',
                            name: 'class',
                            label: event_talks_tinyMCE_object.post_class_label,
                            value: '',
                        },

                    ],
                    onsubmit: function( e ) {
                        editor.insertContent( '[event_talks post_id="' + e.data.post_id + '" class="' + e.data.class + '"]');
                    }
                });
            },
        });
    });

})();
