/*global define*/
define([
    'underscore',
    'jquery',
    'app',
    'collections/notes',
], function (_, $, App, Notes) {
    'use strict';

    var Install = App.module('App.Install', {startWithParent: false});

    Install.on('start', function () {
        Install.API.start();
    });

    Install.API = {
        start: function () {
            /*
            if (App.firstStart  === true) {
                this.createDoc();
            }
            else if (App.settings.appVersion !== App.constants.VERSION) {
                App.log('New version of application is available');

                // Increase appVersion
                configs.create(new configs.model({ name: 'appVersion', value: App.constants.VERSION }));
            }*/
            /*
            if (App.settings.appVersion !== App.constants.VERSION) {
                App.log('New version of application is available');

                // Increase appVersion
                configs.create(new configs.model({ name: 'appVersion', value: App.constants.VERSION }));
            }*/
            
        },

        createDoc: function () {
            var notes = new Notes({}),
                note;

            $.when(notes.fetch({limit: 2})).then(function () {
                /*
                // Do not create doc if collection is not empty
                if (notes.length === 0) {
                    $.ajax({
                        url: App.constants.URL + 'docs/howto.md',
                        dataType: 'text'
                    }).done(function (text) {
                        note = new notes.model();

                        $.when(
                            note.save({
                                title: 'How to use tags and tasks',
                                content: text
                            })
                        ).done(
                            // Reload notes list
                            function () {
                                App.trigger('notes:list');
                            }
                        );
                    });
                }*/
            });
        }

    };

    return Install.API;
});
