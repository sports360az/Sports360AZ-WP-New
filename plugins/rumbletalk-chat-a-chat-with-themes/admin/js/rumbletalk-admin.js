/* global _resources */

(function ($, document) {
    'use strict';

    $(function () {
        /**
         * dialog box class
         */
        var dialog = {
            /** @member {jQuery} jQuery object of the background and wrapper of the dialog */
            $background: null,

            /** @member {jQuery} jQuery object of the dialog body */
            $body: null,

            /** @member {jQuery} jQuery object of the dialog header */
            $header: null,

            /** @member {jQuery} jQuery object of the dialog contents */
            $contents: null,

            /** @member {jQuery} jQuery object of the dialog footer */
            $footer: null,

            /** @member {jQuery} jQuery object of the document body */
            _$body: null,

            /**
             * initiates the dialog instance; building its parts and appends it to the DOM's <body>
             */
            init: function () {
                dialog.$background = $('<div class="dialog-background">');
                dialog.$background.appendTo('body');
                dialog.$background.click(function (event) {
                    if (event.target === dialog.$background[0]) {
                        dialog.close();
                    }
                });

                dialog._$body = $(document.body);

                dialog.buildBody();

                /* close the dialog on "Esc" click */
                $(document).keydown(function (event) {
                    if (event.which == 27) {
                        dialog.close();
                    }
                });
            },

            /**
             * builds the dialog DOM
             */
            buildBody: function () {
                dialog.$body = $('<form class="dialog">');
                dialog.$header = $('<h3 class="dialog-header"></h3>');
                dialog.$contents = $('<div class="dialog-body"></div>');
                dialog.$footer = $('<div class="dialog-footer"></div>');
                dialog.$body.append(
                    dialog.$header,
                    dialog.$contents,
                    dialog.$footer
                );
                dialog.$background.append(dialog.$body);
            },

            /**
             * adds a close icon to the dialog's header
             */
            addHeaderCloseButton: function () {
                var $button = $('<span role="button" class="close-button">');
                $button.click(dialog.close);

                dialog.$header.append($button);
            },

            /**
             * empties the dialog contents. used after a "dialog.close"
             */
            cleanBody: function () {
                dialog.$header.empty();
                dialog.$contents.empty();
                dialog.$footer.empty();
            },

            /**
             * opens a dialog
             * @param {object} data - the dialog data
             * @param {string|jQuery} data.html - the content of the dialog body
             * @param {string} [data.class] - class names to add to the dialog body
             * @param {string} [data.title] - title of the dialog (in the header part)
             * @param {function|boolean} [data.close] - if set, a close button will be added to the dialog's footer.
             *        if set to a function, it will be called; if set to true, a simple dialog.close will be called
             * @param {string} [data.closeText] - the text on the close button
             * @param {function} [data.confirm] - if set, a confirm button will be added to the dialog's footer.
             *        the function will be called when clicked.
             * @param {string} [data.confirmText] - the text on the confirm button
             */
            open: function (data) {
                dialog.$contents.html(data.html);

                if (data['class']) {
                    dialog.$body.addClass(data['class']);
                }

                if (data.title) {
                    dialog.$header.text(data.title);
                }
                dialog.addHeaderCloseButton();

                dialog.cancel(data.close, data.closeText);

                var $confirmButton = dialog.confirm(data.confirm, data.confirmText);
                if ($confirmButton) {
                    dialog.$body.submit(
                        function (event) {
                            event.preventDefault();
                            data.confirm($confirmButton);
                        }
                    );
                }

                dialog.$background.show();

                dialog._$body.addClass('dialog-open');
            },

            /**
             * closes the dialog
             */
            close: function () {
                dialog.$background.hide();
                dialog.cleanBody();
                dialog.$body.off();
                dialog.$body.attr('class', 'dialog');
                dialog._$body.removeClass('dialog-open');
            },

            /**
             * adds a button to the footer
             * @param {string} text - the text on the button
             * @param {function} callback - the callback function
             * @param {string} type - the type of the button (e.g. "button" or "submit")
             * @returns {jQuery} the button handle that was added
             */
            addButtonToFooter: function (text, callback, type) {
                var $button = $('<button type="' + type + '">');
                $button.addClass(
                    type == 'submit'
                        ? 'button-main'
                        : 'button-sub'
                );
                $button.text(text);
                $button.click(callback);

                dialog.$footer.append($button);

                return $button;
            },

            /**
             * adds a close button to the footer
             * @param {function} callback - the callback function
             * @param {string} text - the text on the button
             */
            cancel: function (callback, text) {
                if (callback === true) {
                    callback = dialog.close;
                } else if (typeof callback != 'function') {
                    return;
                }

                dialog.addButtonToFooter(text || 'Close', callback, 'button');
            },

            /**
             * adds a confirm button to the footer
             * @param {function} callback - the callback function
             * @param {string} text - the text on the button
             * @returns {jQuery|boolean} the button that was created or false if not
             */
            confirm: function (callback, text) {
                if (typeof callback != 'function') {
                    return false;
                }

                return dialog.addButtonToFooter(text || 'Confirm', null, 'submit');
            }
        };

        /**
         * the admin page handler class
         */
        var admin = {
            /** @member {jQuery} reloads the chat */
            $chats: $('#chats'),

            /** @member {jQuery} view title handle */
            $viewTitle: $('#view-title'),

            /** @member {window} view title handle */
            upgradeWindow: null,

            init: function () {
                /* preload the loading gif */
                var img = new Image();
                img.src = _resources.rollingGif;

                /* Facebook SDK - used for the "Like" section in the sidebar */
                (function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {
                        return;
                    }
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=181184391902159";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));

                /* the chats are saved locally as JSON string; parsing to object is done */
                _resources.chats = JSON.parse(_resources.chats);

                /* add the chats to the DOM on load */
                admin.loadChats();

                admin.setPlanDetails();

                /* display the chats view if the plug-in is connected to an account; otherwise the create account view*/
                admin.switchView(
                    _resources.accessToken
                        ? 'manage-chats'
                        : 'create-account'
                );

                /* hide or display the account creation warning if the plug-in is connected to an account */
                $('#account-creation-warning').toggle(!!_resources.accessToken);

                dialog.init();

                /* add listener to account creation form submission */
                $('#create-account-form').submit(admin.validateAccountCreation);

                /* add handler to the keywords managing button */
                $('#manage-keywords').click(function () {
                    admin.handleIframeDialog('Manage Keywords', 'https://iframe.rumbletalk.com/keywords/public/');
                });

                /* add handler to the reload chats button */
                $('#refresh-chats').click(admin.reloadChats);

                /* add handler to the new chat creation button */
                $('#create-new-chat').click(admin.newChatDialog);

                /* attach event handler to troubleshooting toggle */
                $('#troubleshooting-header').click(
                    function () {
                        $('#troubleshooting-body').toggle();
                    }
                );

                /* used in different floating options */
                $(".hover-image").hover(
                    function () {
                        $(this).find('img').show();
                    },
                    function () {
                        $(this).find('img').hide();
                    }
                );

                /* attach event handler for the update token dialog */
                $('.update-token-button').click(admin.updateTokenDialog);

                /* connect upgrade button clicks to an upgrade dialog */
                $('.button-attention').click(admin.upgradeDialog);
            },

            /**
             * sends a request to the proxy server to be sent to the API
             * @param {object} parameters - the data that is sent to the server
             * @param {string} parameters.request - the request type ('SET_TOKEN', 'RELOAD_CHATS', etc.)
             * @param {object} [parameters.data] - the request data
             * @param {function} callback - the callback function
             */
            serverRequest: function (parameters, callback) {
                $.ajax(
                    {
                        url: ajaxurl,
                        data: {
                            action: 'rumbletalk_ajax',
                            data: parameters
                        },
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        complete: function (result) {
                            callback(result.responseJSON || result.responseText);
                        }
                    }
                );
            },

            /**
             * updates the plan details on the DOM
             */
            setPlanDetails: function () {
                if (_resources.account.plan) {
                    var $planInfo = $('.plan-info');
                    $planInfo.find('.account-email').text(_resources.account.email);
                    $planInfo.find('.users').text(_resources.account.plan.users);
                    $planInfo.find('.rooms').text(_resources.account.plan.rooms);
                    $planInfo.find('.keywords').text(_resources.account.plan.keywords);
                }
            },

            /**
             * toggles the display of a button and its loader
             * @param {jQuery} $button - the target button
             * @param {boolean} showLoader - if true displays the loader, if false displays the button
             */
            loaderDisplay: function ($button, showLoader) {
                if (showLoader) {
                    var $loader = $('<img>');
                    $loader.attr({
                        src: _resources.rollingGif,
                        alt: 'Loading',
                        'class': 'loading-gif'
                    });
                    $button.after($loader);
                    $button.hide();
                } else {
                    $button.show();
                    $button.next().remove();
                }
            },

            /**
             * validates account creation
             * @param {Event} event - the form submission event
             */
            validateAccountCreation: function (event) {
                event.preventDefault();

                function _validatePassword(password) {
                    return /^[^,]{6,12}$/.test(password);
                }

                function _validateEmail(email) {
                    return /^([\w!#$%&'\*\+\-\/=\?\^`{\|}~]+\.)*[\w!#$%&'\*\+\-\/=\?\^`{\|}~]+@((((([a-z0-9][a-z0-9\-]{0,62}[a-z0-9])|[a-z0-9])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(:\d{1,5})?)$/i.test(email);
                }

                var $email = $(this.elements['email']),
                    $password = $(this.elements['password']),
                    $passwordConfirmation = $(this.elements['password_confirmation']),
                    $error = $('#create-account-form').find('.error-note');

                $error.text('');

                if (!_validateEmail($email.val())) {
                    $error.text('Please enter a valid email address');
                    $email.focus();
                    return;
                }

                if (!_validatePassword($password.val())) {
                    $error.text('The password must be at least 6 characters long and not contain commas (spaces are ignored!)');
                    $password.focus();
                    return;
                }

                if ($password.val() != $passwordConfirmation.val()) {
                    $error.text('Password confirmation mismatched');
                    $passwordConfirmation.focus();
                    return;
                }

                var $button = $(this).find('[type="submit"]');
                admin.loaderDisplay($button, true);

                admin.serverRequest(
                    {
                        request: 'CREATE_ACCOUNT',
                        data: {
                            email: $email.val(),
                            password: $password.val()
                        }
                    },
                    function (response) {
                        admin.loaderDisplay($button, false);

                        if (response.status) {
                            _resources.tokenKey = response.token.key;
                            _resources.tokenSecret = response.token.secret;
                            _resources.accessToken = response.accessToken;

                            _resources.account = {
                                email: $email.val(),
                                plan: {
                                    users: 5,
                                    rooms: 1,
                                    keywords: 1
                                }
                            };
                            admin.setPlanDetails();

                            _resources.chats = {};
                            _resources.chats[response.hash] = {
                                id: response.chatId,
                                name: 'New Chat',
                                width: '',
                                height: '',
                                membersOnly: false,
                                floating: false
                            };

                            admin.loadChats();

                            admin.switchView('manage-chats');
                        } else {
                            dialog.open({html: 'Failed to create the account: ' + response.message});
                        }
                    }
                );
            },

            /**
             * opens the upgrade dialog
             */
            upgradeDialog: function () {
                if (admin.upgradeWindow && !admin.upgradeWindow.closed) {
                    admin.upgradeWindow.focus();

                    return;
                }

                var $iframe = $('<iframe>');

                $iframe.attr('src', 'https://www.rumbletalk.com/upgrade/?email=' + _resources.account.email);
                $iframe.height(520);

                admin.upgradeWindow = dialog.open(
                    {
                        html: $iframe,
                        'class': 'dialog-large',
                        title: 'Upgrade'
                    }
                );
            },

            /**
             * opens the update token dialog
             * @param {Event} event - the form submission event
             */
            updateTokenDialog: function (event) {
                event.preventDefault();

                var $html = $($('#update-token').text()),
                    $key = $html.find('#token-manage-key'),
                    $secret = $html.find('#token-manage-secret');

                $key.val(_resources.tokenKey);
                $secret.val(_resources.tokenSecret);

                dialog.open({
                    title: 'Update token',
                    html: $html,
                    close: true,
                    confirm: function ($button) {
                        admin.loaderDisplay($button, true);

                        admin.serverRequest(
                            {
                                request: 'UPDATE_TOKEN',
                                data: {
                                    key: $key.val(),
                                    secret: $secret.val()
                                }
                            },
                            function () {
                                location.reload();
                            }
                        );
                    },
                    confirmText: 'Save'
                });
            },

            /**
             * loads the chats into the DOM from the _resource.chats object
             */
            loadChats: function () {
                if (_resources.chats) {
                    admin.$chats.empty();

                    var hash,
                        $form;
                    for (hash in _resources.chats) {
                        if (_resources.chats.hasOwnProperty(hash)) {
                            $form = admin.editChatHTML(
                                {
                                    id: _resources.chats[hash].id || (new Date()).getTime(),
                                    hash: hash,
                                    name: _resources.chats[hash].name || 'New Chat',
                                    width: _resources.chats[hash].width,
                                    height: _resources.chats[hash].height,
                                    floating: _resources.chats[hash].floating,
                                    membersOnly: _resources.chats[hash].membersOnly,
                                    loginName: _resources.chats[hash].loginName
                                },
                                true
                            );

                            admin.$chats.append($form);
                        }
                    }
                }
            },

            /**
             * fetches the chats from the server and loads them into the DOM
             */
            reloadChats: function () {
                var $button = $(this);
                admin.loaderDisplay($button, true);

                admin.serverRequest(
                    {
                        request: 'RELOAD_CHATS'
                    },
                    function (response) {
                        _resources.chats = response.chats;
                        admin.loadChats();
                        admin.loaderDisplay($button, false);
                    }
                )
            },

            /**
             * handles update change button click
             * @param {MouseEvent} event - the event object initiating the click
             */
            updateChat: function (event) {
                event.preventDefault();

                var $button = $(this).find('[type="submit"]');
                admin.loaderDisplay($button, true);

                admin.serverRequest(
                    {
                        request: 'UPDATE_CHAT',
                        data: {
                            hash: $(this).find('.hash-display').text(),
                            name: this.elements.name.value,
                            width: this.elements.width.value,
                            height: this.elements.height.value,
                            floating: this.elements.floating.checked,
                            membersOnly: this.elements.membersOnly.checked,
                            loginName: this.elements.loginName.value
                        }
                    },
                    function () {
                        admin.loaderDisplay($button, false);
                    }
                );
            },

            /**
             * delete a chat
             * @param {object} chat - the chat object
             * @param {number} chat.id - the chat id
             * @param {string} chat.name - the chat name
             * @param {string} chat.hash - the chat hash
             */
            deleteChat: function (chat) {
                dialog.open(
                    {
                        html: 'Are you sure you want to delete the chat: ' + chat.name + '?',
                        close: true,
                        closeText: 'Cancel',
                        confirm: function ($button) {
                            admin.loaderDisplay($button, true);

                            admin.serverRequest(
                                {
                                    request: 'DELETE_CHAT',
                                    data: {
                                        id: chat.id
                                    }
                                },
                                function (response) {
                                    dialog.close();
                                    if (response.status) {
                                        delete _resources.chats[chat.hash];
                                        admin.loadChats();
                                    }
                                }
                            );
                        }
                    }
                );
            },

            /**
             * opens a dialog with an <iframe> that is submitted to using a POST form
             * @param {string} title - the title of the dialog
             * @param {string} url - the URL of the iframe
             * @param {number} [chatId] - the id of the chat in case the chat id is needed for submission
             */
            handleIframeDialog: function (title, url, chatId) {
                var $iframe = $('<iframe name="iframeWidget"></iframe>'),
                    $form = $('<form target="iframeWidget" action="' + url + '" method="post">'),
                    $window = $(window),
                    $input;

                $input = $('<input type="hidden" name="token">');
                $input.val(_resources.accessToken);
                $form.append($input);

                if (chatId) {
                    $input = $('<input type="hidden" name="chat_id">');
                    $input.val(chatId);
                    $form.append($input);
                }

                $form.appendTo('body');

                dialog.open(
                    {
                        html: $iframe,
                        'class': 'dialog-large',
                        title: title,
                        close: true
                    }
                );

                $iframe.load($form.remove);

                $form.submit();

                function adjustHeight() {
                    $iframe.height($window.height() - 200);
                }

                $window.resize(adjustHeight);

                adjustHeight();
            },

            /**
             * opens a chat edit settings dialog
             * @param {object} chat - the chat data
             * @param {number} chat.id - the chat id
             * @param {string} chat.name - the chat name
             */
            editChatDialog: function (chat) {
                admin.handleIframeDialog(
                    'Chat settings: ' + chat.name,
                    'https://iframe.rumbletalk.com/settings/public/',
                    chat.id
                );
            },

            /**
             * builds an edit chat DOM structure from a given chat
             * @param {object} chat - the chat object
             * @param {number} chat.id - the chat's hash
             * @param {string} chat.hash - the chat's id
             * @param {string} chat.name - the chat's name
             * @param {number} [chat.width] - the chat's name
             * @param {number} [chat.height] - the chat's name
             * @param {boolean} [chat.membersOnly] - the chat's name
             * @param {boolean} [chat.floating] - the chat's name
             * @param {boolean} [edit] - if set to true, the wrapping tag will be of form type; otherwise div is used
             * @returns {Node} the built DOM structure
             */
            editChatHTML: function (chat, edit) {
                var $wrapper,
                    $node;

                $wrapper = edit
                    ? $('<form>')
                    : $('<div>');
                $wrapper.addClass('chat-form');
                $wrapper.html($('#edit-chat').text());

                $wrapper.submit(admin.updateChat);

                $node = $wrapper.find('.chat-name label');
                $node.attr('for', 'chat-name-' + chat.id);

                $node = $wrapper.find('.hash-display');
                $node.text(chat.hash);

                $node = $wrapper.find('.shortcode-hash');
                $node.text(chat.hash);

                $node = $wrapper.find('.chat-open');
                $node.attr('href', 'https://www.rumbletalk.com/client/chat.php?' + chat.hash);

                $node = $wrapper.find('.chat-name input');
                $node.attr('id', 'chat-name-' + chat.id);
                $node.val(chat.name);

                $node = $wrapper.find('.label-width');
                $node.attr('for', 'chat-width-' + chat.id);

                $node = $wrapper.find('[name="width"]');
                $node.attr('id', 'chat-width-' + chat.id);
                $node.val(chat.width);

                $node = $wrapper.find('.label-height');
                $node.attr('for', 'chat-height-' + chat.id);

                $node = $wrapper.find('[name="height"]');
                $node.attr('id', 'chat-height-' + chat.id);
                $node.val(chat.height);

                $node = $wrapper.find('[name="membersOnly"]');
                $node.attr({checked: chat.membersOnly});
                $node.click(function () {
                    if ($(this).prop('checked')) {
                        $wrapper.find('.login-name-options').show();
                    } else {
                        $wrapper.find('.login-name-options').hide();
                    }
                });

                $node = $wrapper.find('.login-name-options');
                $node.val(chat.loginName);
                if (!chat.membersOnly) {
                    $node.hide();
                }

                $node = $wrapper.find('[name="floating"]');
                $node.attr({checked: chat.floating});
                $node.click(function () {
                    var $dimensions = $wrapper.find('.chat-dimensions'),
                        checked = $(this).prop('checked');
                    $dimensions.toggleClass('disabled', checked);
                    $dimensions.find('[name="width"]').prop('disabled', checked);
                    $dimensions.find('[name="height"]').prop('disabled', checked);
                });

                $node = $wrapper.find('.chat-delete');
                if (edit) {
                    $node.click(function () {
                        admin.deleteChat(chat);
                    });
                } else {
                    $node.remove();
                }

                $node = $wrapper.find('.chat-settings');
                if (edit) {
                    $node.click(
                        function () {
                            admin.editChatDialog(chat);
                        }
                    );
                } else {
                    $node.remove();
                }

                if (!edit) {
                    $node = $wrapper.find('[type="submit"]');
                    $node.remove();
                }

                return $wrapper;
            },

            /**
             * opens a create new chat dialog
             */
            newChatDialog: function () {
                /* check if rooms limit was reached */
                if (_resources.account.plan.rooms <= Object.keys(_resources.chats).length) {
                    dialog.open(
                        {
                            html: 'Maximum chat rooms limit reached<br>' +
                            'Please upgrade your account to create more chat rooms.',
                            close: true,
                            closeText: 'Cancel',
                            confirm: admin.upgradeDialog,
                            confirmText: 'Upgrade'
                        }
                    );

                    return;
                }

                var $html = admin.editChatHTML({
                    id: 0,
                    hash: '',
                    name: ''
                });

                dialog.open(
                    {
                        title: 'Create new chat room',
                        html: $html,
                        'class': 'dialog-small',
                        confirmText: 'Create new chat!',
                        close: true,
                        confirm: function ($button) {
                            admin.loaderDisplay($button, true);

                            var name = $html.find('.chat-name input').val(),
                                width = $html.find('[name="width"]').val(),
                                height = $html.find('[name="height"]').val(),
                                membersOnly = $html.find('[name="membersOnly"]').prop('checked'),
                                loginName = $html.find('[name="loginName"]').prop('checked'),
                                floating = $html.find('[name="floating"]').prop('checked');

                            if (!name) {
                                name = 'New Chat';
                            }

                            admin.serverRequest(
                                {
                                    request: 'CREATE_CHAT',
                                    data: {
                                        name: name,
                                        width: width,
                                        height: height,
                                        membersOnly: membersOnly,
                                        loginName: loginName,
                                        floating: floating
                                    }
                                },
                                function (response) {
                                    dialog.close();

                                    if (response.status) {
                                        _resources.chats[response.hash] = {
                                            id: response.chatId,
                                            name: name,
                                            width: width,
                                            height: height,
                                            membersOnly: membersOnly,
                                            floating: floating
                                        };

                                        admin.loadChats();
                                    } else {
                                        dialog.open(
                                            {
                                                html: response.message,
                                                close: true,
                                                closeText: 'OK'
                                            }
                                        );
                                    }
                                }
                            );
                        }
                    }
                );
            },

            /**
             * toggles between different views (create account, update tokens, and manage chats)
             * @param {string} target - the DOM id of the view to display
             */
            switchView: function (target) {
                if (target == 'manage-chats') {
                    admin.$viewTitle.text('My Account');
                    $('#manage-chats').show();
                    $('#create-account').hide();
                } else {
                    admin.$viewTitle.text('Account Set Up');
                    $('#create-account').show();
                    $('#manage-chats').hide();
                }
            }
        };

        admin.init();
    });
})(jQuery, document);