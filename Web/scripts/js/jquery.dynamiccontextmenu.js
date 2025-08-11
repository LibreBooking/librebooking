/*
Copyright 2011 Matías Fidemraizer (www.matiasfidemraizer.com).

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

jQuery Dynamic Context Menu plugin
----------------------------------
@Version: 2.0.0
@State: Stable
@Release date: 4/19/2011

MANUAL
----------------------------------
This jQuery plugin opens a context menu on some specified target element in some DOM tree.

It supports two ways of opening the menu:
- When user clicks on the target element on which the menu must be opened.
- When user moves over the target element on which the menu must be opened.

This plugin will be activated on any DOM element by calling jQuery "dynamicContextMenu" method:
$("#someElement").dynamicContextMenu(...);

Plugin constructor must be called by giving a parameter map. Available parameters are:
-   "style". A inner map of CSS classes:
- "containerCssClass", which is context menu container CSS class name. This is mandatory.
- "itemIconCssClass", which is context menu item CSS Class name. This is optional.
- "subLevelHolderItemCssClass", which is context menu item CSS class name having children. This is mandatory.

- "id". An unique identifier for the context menu container DOM element.
- "onItemClick". An event handler that will be called when some context menu item is clicked. It must implement two input parameters:
- "sender", which is context menu object (don't confuse it with DOM element).
- "item", which is clicked context menu item object (that's each item will have a text and value properties - explained later -).
- "items". A collection of menu item objects. Each object must have these properties:
- "text", which is the label of the item.
- "value", which is the identifier of the item.
- "iconUrl", which is some icon (JPEG, PNG, GIF... any supported image format supported by Web browsers).
- "onChildItemClick", which is a submenu item click handler. It has the same signature as on regular onItemClick handler.
- "children", which is an array of menu item objects in the same way as parent ones (those describen in this property list).
- "openMode". Sets the way context menu will be opened. Available modes:
- "click". This will open context menu when a click is performed on target element.
- "hover". This will open context menu when mouse is over target element.
- "waitTime". Defines a delay in miliseconds before opening context menu once target element has been reached in any open mode.
- "context". Gives some context object - a custom object that could be provided so menu item click handler sender (menu object) will be able to retrieve it.

*/

(function ($) {

    // Constructor
    $.fn.dynamicContextMenu = function (params) {
        // Defines inner class members
        var members = {
            get_paramsCopy: function () {
                return this._paramsCopy;
            },

            get_mousePointerLocationCorrection: function () {
                return 10;
            },

            set_paramsCopy: function (value) {
                this._paramsCopy = value;
            },

            get_parentMenu: function () {
                return this._parentMenu;
            },

            set_parentMenu: function (value) {
                this._parentMenu = value;
            },

            // Gets a context object.
            get_context: function () {
                return this._context;
            },

            // Sets a context object.
            set_context: function (value) {
                this._context = value;
            },

            // Gets the element on which the menu has been opened.
            get_targetElement: function () {
                return this._targetElement;
            },

            // Sets the element on which the menu has been opened.
            set_targetElement: function (value) {
                this._targetElement = value;
            },

            // Gets menu element identifier.
            get_id: function () {
                return this._id;
            },

            // Sets menu element identifier.
            set_id: function (value) {
                this._id = value;
            },

            // Gets a wait time in miliseconds that defines a delay to open the menu.
            get_waitTime: function () {
                return this._waitTime;
            },

            // Sets a wait time in miliseconds that defines a delay to open the menu.
            set_waitTime: function (value) {
                this._waitTime = value;
            },

            // Gets a group of styling options.
            get_style: function () {
                return this._style;
            },

            // Sets a group of styling options.
            set_style: function (value) {
                this._style = value;
            },

            // Gets menu container's CSS selector.
            get_containerSelector: function () {
                return "ol#" + this.get_id();
            },

            // Gets the bound items to this menu.
            get_items: function () {
                return this._items;
            },

            // Sets the items to bind to this menu.
            set_items: function (value) {
                this._items = value;
            },

            // Gets the menu item click handler.
            get_itemClickHandler: function () {
                return this._itemClickHandler;
            },

            // Sets the menu item click handler.
            set_itemClickHandler: function (value) {
                this._itemClickHandler = value;
            },

            // Gets a boolean flag specifying if menu is open.
            get_isOpen: function () {
                return this._isOpen;
            },

            // Sets a boolean flag specifying if menu is open.
            set_isOpen: function (value) {
                this._isOpen = value;
            },

            // Gets mouse pointer position (top/left values).
            get_mousePosition: function () {
                return this._mousePosition;
            },

            // Sets mouse pointer position (top/left values).
            set_mousePosition: function (value) {
                this._mousePosition = value;
            },

            // Gets a string giving the active open mode.
            get_openMode: function () {
                return this._openMode;
            },

            // Sets a string giving the active open mode.
            // Valid strings: "hover" and "click".
            set_openMode: function (value) {
                this._openMode = value;
                var targetElement = $(this.get_targetElement());

                switch (value) {
                    case "click":
                        targetElement.click({ sender: members }, members.targetElement_Action);

                        break;

                    case "hover":
                        targetElement.mouseover({ sender: members }, members.targetElement_Action);

                        break;
                }
            },

            // Gets a boolean flag which provides if mouse pointer is within current menu.
            get_pointerIsInContextMenu: function () {
                var contextMenu = $(this.get_containerSelector());

                if (contextMenu.length == 0) {
                    return false;
                }

                var currentPosition = this.get_mousePosition();
                currentPosition.top -= this.get_mousePointerLocationCorrection();
                currentPosition.left -= this.get_mousePointerLocationCorrection();

                var contextMenuOffset = contextMenu.offset();
                contextMenuOffset.top -= this.get_mousePointerLocationCorrection();
                contextMenuOffset.left -= this.get_mousePointerLocationCorrection();

                return (currentPosition.top >= contextMenuOffset.top
                    && currentPosition.top <= contextMenuOffset.top + contextMenu.outerHeight())

                    &&

                    (currentPosition.left >= contextMenuOffset.left
                    && currentPosition.left <= contextMenuOffset.left + contextMenu.outerWidth()
                    );
            },

            // Opens the menu.
            open: function () {
                if (this.get_isOpen()) {
                    this.close();
                }

                var contextMenu = document.createElement("ol");
                contextMenu.setAttribute("id", this.get_id());
                contextMenu.setAttribute("class", this.get_style().containerCssClass);

                var tempItem = null;
                var currentObject = this;

                $.each(this.get_items(),
                    function (itemIndex, item) {
                        contextMenu.appendChild(currentObject.createMenuItem(item));
                    }
                );

                document.body.appendChild(contextMenu);

                contextMenu = $(contextMenu);

                contextMenu.css({ top: this.get_mousePosition().top - 10, left: this.get_mousePosition().left - 10 });
                contextMenu.fadeIn("fast", function () { currentObject.set_isOpen(true) });
            },

            createMenuItem: function (item) {
                var tempItem = document.createElement("li");
                tempItem.id = item.value;

                if (item.iconUrl != undefined && item.iconUrl != null) {
                    var imgIcon = new Image();
                    imgIcon.src = item.iconUrl;
                    imgIcon.alt = item.text;
                    imgIcon.className = this.get_style().itemIconCssClass;

                    tempItem.appendChild(imgIcon);
                }

                $(tempItem).click({ sender: this }, this.contextMenu_Click);

                tempItem.appendChild(document.createTextNode(item.text));

                if (item.children != undefined && item.children != null && item.children.length > 0) {
                    tempItem.className += " " + this.get_style().subLevelHolderItemCssClass;

                    this.get_paramsCopy().id = tempItem.id + "_children";
                    this.get_paramsCopy().items = item.children;
                    this.get_paramsCopy().parentMenu = this;
                    this.get_paramsCopy().onItemClick = item.onChildItemClick;

                    $(tempItem).dynamicContextMenu(
                        this.get_paramsCopy()
                    );
                }

                return tempItem;
            },

            // Closes the menu.
            close: function () {
                var contextMenu = $(this.get_containerSelector());
                var currentObject = this;

                contextMenu.fadeOut("fast", function () { contextMenu.remove(); currentObject.set_isOpen(false); });
            },

            // Occurs when some menu item is clicked.
            contextMenu_Click: function (e) {
                e.data.sender.get_itemClickHandler()(
                    e.data.sender,
                    { text: $(e.target).text(), value: e.target.id }
                );

                if (!$(this).hasClass(e.data.sender.get_style().subLevelHolderItemCssClass)) {
                    e.data.sender.close();

                    if (e.data.sender.get_parentMenu() != undefined && e.data.sender.get_parentMenu() != null) {
                        e.data.sender.get_parentMenu().close();
                    }
                }
            },

            // Occurs when target element fires an event to open this menu.
            targetElement_Action: function (e) {
                setTimeout(
                function () {
                    e.data.sender.open();
                },
                e.data.sender.get_waitTime()
            );
            },

            // Occurs whenever mouse pointer is moved within document body.
            body_MouseMove: function (e) {
                e.data.sender.set_mousePosition({ top: e.pageY - e.data.sender.get_mousePointerLocationCorrection(), left: e.pageX - e.data.sender.get_mousePointerLocationCorrection() });

                if (e.data.sender.get_isOpen()) {
                    var contextMenu = $(e.data.sender.get_containerSelector());

                    // Menu will be closed if mouse pointer isn't within menu or target element.
                    if (
                !e.data.sender.get_pointerIsInContextMenu() && e.data.sender.get_pointerIsInContextMenu() != undefined
                &&
                e.target != e.data.sender.get_targetElement()) {
                        e.data.sender.close();
                    }
                }
            }
        };

        members.set_targetElement(this[0]);
        members.set_style(params.style);
        members.set_id(params.id);
        members.set_itemClickHandler(params.onItemClick);
        members.set_items(params.items);
        members.set_openMode(params.openMode);
        members.set_waitTime(params.waitTime);
        members.set_context(params.context);
        members.set_paramsCopy(params);
        members.set_parentMenu(params.parentMenu);

        $("body").live("mousemove", { sender: members }, members.body_MouseMove);
    };
})(jQuery);