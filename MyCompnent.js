var TreeViewCreator = {
    create_branch : function (rootData) {
        var root = $('<ul />', {class:"Branch"});
        var content = $('<span />');
        content.append(rootData);
        root.append(content);
        root.isOpen = false;
        root.add_branch = function(element){
            var anLi = $('<li />');
            if(!this.isClosed) {
                anLi.addClass("Nested");
            }
            anLi.append(element);
            this.append(anLi);
        };
        root.open = function() {
            this.isOpen = true;
            $(this).removeClass("Branch");
            $(this).addClass("Branch-Down");
            var myContent = $(this).children('li');
            myContent.removeClass("Nested");
            myContent.addClass("Active");
        };
        root.close = function() {
            this.isOpen = false;
            $(this).removeClass("Branch-Down");
            $(this).addClass("Branch");
            var myContent = $(this).children('li');
            myContent.removeClass("Active");
            myContent.addClass("Nested");
        };
        root.trigger = function() {
            if(this.isOpen) {
                console.log("Closing");
                root.close();
            }
            else {
                console.log("Opening");
                root.open();
            }
        };
        root.set_selection = function(newSelection) {
            root.selection = newSelection;
        };
        root.show_selection = function(xPos, yPos) {
            if(this.selection == undefined) {
                return;
            }
            //Later selection know who summon it.
            this.selection.summoner = this;
            //Set position of panel;

            //Panel show
        };
        root.set_click = function(aFunction, parameter) {
            content.click(function(event, ) {
                aFunction(event, parameter);
            });
        };
        root.remove_all_branches = function() {
            var allChildren = $(this).children('li');
            for(let key in allChildren) {
                if(allChildren.hasOwnProperty(key)) {
                    if(allChildren[key].nodeName == "LI") {
                        //console.log(allChildren[key]);
                        $(allChildren[key]).remove();
                    }
                }
            }
            //console.log(allChildren);
        };
        return root;
    }
};

var ComponentCreator = {
    create_select_panel : function(label) {
        var panel = $('<ul />', {class:"SelectPanel"});
        panel.text(label);
        panel.show_at_position = function(leftPos, topPos) {
            this.css({top:leftPos, left:topPos, display:"block"});
        };
        panel.hide = function() {
            this.css({display:"none"});
        };
        panel.add_option = function(label, aFunction, aParameter) {
            var item = $('<li />', {class:"SelectOption"});
            item.text(label);
            item.data("parent", this);
            if(aFunction != undefined) {
                item.click(function(){
                    aFunction(aParameter);
                });
            }
            this.append(item);
        };
        panel.add_separator = function() {
            var item = $('<li />', {class:"SelectSeparator"});
            item.append('<hr />');
            this.append(item);
        }
        return panel;
    }
};
