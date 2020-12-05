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
        root.set_click = function(aFunction, parameter) {
            content.click(function() {
                aFunction(parameter);
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
