<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="BrowsePage.css?v=<?=time();?>">
        <link rel="stylesheet" href="MyComponent.css?v=<?=time();?>">
        <link rel="stylesheet" href="../../bootstrap-4.4.1-dist/css/bootstrap.min.css">
        <script src="../../jquery-3.4.1.js"></script>
        <script src="../../popper-1.16.0/popper.min.js"></script>
        <script src="../../bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="MyCompnent.js?v=<?=time();?>"></script>  
    </head>  
    <body>
        <div id="BrowsePage_FileBrowser" class='Float-Left'>
            <!--
                Show a tree of files and folders
                Has button to back to root
                Has a up to parent button
                folder bring up open option when clicked
                file bring up edit, delete, rename option
            -->
        </div>
        <div>
            <!--edit area-->
        </div>
        <script>
            var BrowsePage_Variable = {
                folderPopup : {},
                filePopup : {},
                renameBox : {},
                disableCurtain : {},
                //rootFolder : {}
                init_folder_popup : function() {
                    var folderPopup = ComponentCreator.create_select_panel('Action');
                    $("body").append(folderPopup);
                    folderPopup.add_option(
                        "Open Folder",
                        function(me) {
                            me.summoner.open_folder();
                            me.gui.hide();
                        },
                        BrowsePage_Variable.folderPopup
                    );
                    folderPopup.add_option(
                        "Close Folder",
                        function(me) {
                            me.summoner.close();
                            me.gui.hide();
                        },
                        BrowsePage_Variable.folderPopup
                    );
                    folderPopup.add_option(
                        "Add Folder",
                        function(me) {
                            me.summoner.add_folder();
                            me.gui.hide();
                        },
                        BrowsePage_Variable.folderPopup
                    );
                    folderPopup.add_option(
                        "Add File",
                        function(me) {
                            me.summoner.add_file();
                            me.gui.hide();
                        },
                        BrowsePage_Variable.folderPopup
                    );
                    folderPopup.add_option(
                        "Rename Folder",
                        function(me) {
                            me.summoner.rename_item();
                            me.gui.hide();
                        },
                        BrowsePage_Variable.folderPopup
                    );
                    folderPopup.add_option(
                        "Delete Folder",
                        function(me) {
                            me.summoner.delete_item();
                            me.gui.hide();
                        },
                        BrowsePage_Variable.folderPopup
                    );
                    folderPopup.add_separator();
                    folderPopup.add_option(
                        "Cancel",
                        function(me) {
                            me.gui.hide();
                        },
                        BrowsePage_Variable.folderPopup
                    );
                    BrowsePage_Variable.folderPopup.gui = folderPopup;
                    $("#BrowsePage_FileBrowser").append(BrowsePage_Variable.folderPopup.gui);
                },
                init_file_popup : function() {

                },
                init_rename_box : function() {
                    var renameBox = $('<div />', {class:"Centered Z50"});
                    renameBox.hide();
                    var inputContainer = $('<div />');
                    var inputBox = $('<input />');
                    inputBox.attr({type: 'text'});
                    inputContainer.append(inputBox);
                    var setButton = $('<button />', {class:"FullWidth"});
                    setButton.text("Set");
                    renameBox.append(inputContainer);
                    renameBox.append(setButton);
                    BrowsePage_Variable.renameBox.gui = renameBox;
                    BrowsePage_Variable.renameBox.set_text = function(newText) {
                        BrowsePage_Variable.renameBox.gui.find('input').val(newText);
                    };
                    BrowsePage_Variable.renameBox.show = function(summoner, theText) {
                        BrowsePage_Variable.renameBox.summoner = summoner;
                        BrowsePage_Variable.renameBox.gui.show();
                        BrowsePage_Variable.renameBox.gui.find('input').val(theText);
                        BrowsePage_Variable.renameBox.gui.find('input').select();
                        BrowsePage_Variable.renameBox.gui.find('button').click(function(){
                            BrowsePage_Ajax.rename_item(BrowsePage_Variable.renameBox.summoner, BrowsePage_Variable.renameBox.gui.find('input').val());
                            BrowsePage_Variable.renameBox.hide();
                            BrowsePage_Variable.disableCurtain.hide();
                        });
                    };
                    BrowsePage_Variable.renameBox.hide = function() {
                        BrowsePage_Variable.renameBox.gui.hide();
                    };

                    $("body").append(BrowsePage_Variable.renameBox.gui);
                },
                init_disable_curtain : function() {
                    var theCurtain = $('<div />', {class: "DisableCurtain"});
                    BrowsePage_Variable.disableCurtain.gui = theCurtain;
                    BrowsePage_Variable.disableCurtain.show = function() {
                        BrowsePage_Variable.disableCurtain.gui.show();
                    };
                    BrowsePage_Variable.disableCurtain.hide = function() {
                        BrowsePage_Variable.disableCurtain.gui.hide();
                    };
                    $("body").append(BrowsePage_Variable.disableCurtain.gui);
                }
            };
            var BrowsePage_Build = {
                create_folder : function(folderName) {
                    var theFolder;
                    var wholeDiv = $('<span />');
                    var img = $('<img />');
                    img.attr('src', 'Icons/folder_close.jpeg');
                    img.width(30);
                    img.height(40);
                    wholeDiv.append(img);
                    var text = $('<span />', {class: "BranchContent"});
                    text.text(folderName);
                    wholeDiv.append(text);
                    theFolder = TreeViewCreator.create_branch(wholeDiv);
                    theFolder.set_click(function(event, me){
                        //var thePopup = $(me).data("Popup");
                        BrowsePage_Variable.folderPopup.gui.show_at_position(event.clientY, event.clientX);
                        BrowsePage_Variable.folderPopup.summoner = me;
                    }, theFolder);
                    $(theFolder).data("Name", folderName);
                    return theFolder;
                },
                add_root_function : function(theRoot) {
                    theRoot.open_folder = function() {
                        BrowsePage_Ajax.show_folder_content(this);
                    };
                    theRoot.add_folder = function() {
                        BrowsePage_Ajax.add_folder(this);
                    };
                    theRoot.add_file = function() {
                        BrowsePage_Ajax.add_file(this);
                    };
                    theRoot.rename_item = function() {
                        console.log("Root cannot be renamed");
                    };
                    theRoot.delete_item = function() {
                        console.log("Root cannot be deleted");
                    };
                },
                add_folder_function : function(theFolder) {
                    theFolder.open_folder = function() {
                        BrowsePage_Ajax.show_folder_content(this);
                    };
                    theFolder.add_folder = function() {
                        BrowsePage_Ajax.add_folder(this);
                    };
                    theFolder.add_file = function() {
                        BrowsePage_Ajax.add_file(this);
                    };
                    theFolder.rename_item = function() {
                        BrowsePage_Variable.disableCurtain.show();
                        BrowsePage_Variable.renameBox.show(this, $(this).data("Name"));
                    };
                    theFolder.change_name = function(newName) {
                        $(this).find(".BranchContent").text(newName);
                    };
                    theFolder.delete_item = function() {
                        BrowsePage_Ajax.delete_item(this);
                    };
                    theFolder.self_delete = function() {
                        $(this).remove();
                    }
                },
                create_file : function(fileName) {
                    var wholeDiv = $('<div />', {class:'Leaf'});
                    var img = $('<img />');
                    img.attr('src', 'Icons/file_icon.png');
                    img.width(40);
                    img.height(30);
                    wholeDiv.append(img);
                    var text = $('<span />');
                    text.text(fileName);
                    wholeDiv.append(text);
                    wholeDiv.click(function(){
                        var fileName = $(this).children('span').text();
                        console.log(fileName);
                    });
                    return wholeDiv;
                }
            };
            //All functions in BrowsePage_Ajax takes an object as parameter.  The object shall have success_calll_back(reply) and error_call_back(reply) function
            var BrowsePage_Ajax = {
                show_folder_content : function(theFolder) {
                    var postData = {};
                    postData.Command = "Show";
                    postData.Path = $(theFolder).data("Name");
                    $.ajax({
                        type: "POST",
                        url: "BrowsePageBackend.php",
                        data: "postData=" + JSON.stringify(postData),
                        success: function(data) {
                            theFolder.remove_all_branches();
                            var reply = JSON.parse(data);
                            console.log(reply);
                            if(reply.Content == undefined) {
                                return;
                            }
                            var content = reply.Content;
                            if(!Array.isArray(content)) {
                                return;
                            }
                            for(var i=0; i<content.length; ++i) {
                                if(content[i].Name == undefined || content[i].Type == undefined) {
                                    continue;
                                }
                                switch(content[i].Type) {
                                    case "Directory":
                                        console.log("Creating directory");
                                        //var newFolder = BrowsePage.create_folder(content[i].Name, folderPopup);
                                        var newFolder = BrowsePage_Build.create_folder(content[i].Name);
                                        BrowsePage_Build.add_folder_function(newFolder);
                                        theFolder.add_branch(newFolder);
                                        break;
                                    case "Regular":
                                        console.log("Creating regular");
                                        var newFile = BrowsePage_Build.create_file(content[i].Name);
                                        theFolder.add_branch(newFile);
                                        break;
                                }
                            }
                            theFolder.open();
                        },
                        error: function(data) {
                            console.log("Read Folder Error");
                        }
                    });
                },
                add_folder : function(theFolder) {
                    var postData = {};
                    postData.Command = "CreateFolder";
                    postData.Name = $(theFolder).data("Name") + "/NewFolder";
                    $.ajax({
                        type: "POST",
                        url: "BrowsePageBackend.php",
                        data: "postData=" + JSON.stringify(postData),
                        success: function(data) {
                            var reply = JSON.parse(data);
                            console.log(reply);
                            if(reply.Status == "Good") {
                                BrowsePage_Ajax.show_folder_content(theFolder);
                            }
                        },
                        error: function(data) {
                            console.log("Ajax create file failed.")
                        }
                    });
                },
                add_file : function(theFolder) {
                    var postData = {};
                    postData.Command = "CreateFile";
                    postData.Name = $(theFolder).data("Name") + "/NewFile";
                    $.ajax({
                        type: "POST",
                        url: "BrowsePageBackend.php",
                        data: "postData=" + JSON.stringify(postData),
                        success: function(data) {
                            var reply = JSON.parse(data);
                            console.log(reply);
                            if(reply.Status == "Good") {
                                console.log("Going to show contnet after add folder");
                                BrowsePage_Ajax.show_folder_content(theFolder);
                            }
                        },
                        error: function(data) {
                            console.log("Ajax create file failed.")
                        }
                    });
                },
                rename_item : function(theItem, newName) {
                    var postData = {};
                    postData.Command = "Rename";
                    postData.Name = $(theItem).data("Name");
                    postData.NewName = newName;
                    $.ajax({
                        type: "POST",
                        url: "BrowsePageBackend.php",
                        data: "postData=" + JSON.stringify(postData),
                        success: function(data) {
                            var reply = JSON.parse(data);
                            console.log(reply);
                            if(reply.Status == "Good") {
                                theItem.change_name(newName);
                            }
                        },
                        error: function(data) {
                            console.log("Ajax rename item failed");
                        }
                    });
                },
                delete_item : function(theItem) {
                    var postData = {};
                    postData.Command = "Delete";
                    postData.Name = $(theItem).data("Name");
                    $.ajax({
                        type: "POST",
                        url: "BrowsePageBackend.php",
                        data: "postData=" + JSON.stringify(postData),
                        success: function(data) {
                            var reply = JSON.parse(data);
                            console.log(reply);
                            if(reply.Status == "Good") {
                                //BrowsePage_Ajax.show_folder_content(theItem);
                                theItem.self_delete();
                            }
                        },
                        error: function(data) {
                            console.log("Ajax delete item failed");
                        }
                    });
                }
            };

            $(document).ready(function() {
                var rootFolder = BrowsePage_Build.create_folder('/');
                BrowsePage_Build.add_root_function(rootFolder);
                $("#BrowsePage_FileBrowser").append(rootFolder);
                BrowsePage_Variable.init_folder_popup();
                BrowsePage_Variable.init_rename_box();
                BrowsePage_Variable.init_disable_curtain();
            });
        </script>
    </body>
</html>