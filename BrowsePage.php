<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="BrowsePage.css?v=<?=time();?>">
        <link rel="stylesheet" href="TreeView.css?v=<?=time();?>">
        <link rel="stylesheet" href="../../bootstrap-4.4.1-dist/css/bootstrap.min.css">
        <script src="../../jquery-3.4.1.js"></script>
        <script src="../../popper-1.16.0/popper.min.js"></script>
        <script src="../../bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="CreateTreeView.js?v=<?=time();?>"></script>  
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
            var BrowsePage = {
                translate_to_tree : function (fileData, path) {
                    //console.log(fileData);
                    var retVal = {};
                    retVal.label = fileData.name;
                    retVal.userdata = {};
                    retVal.userdata.path = path;
                    if(fileData.type == "File") {
                        retVal.type = "Leaf";
                    }
                    else if(fileData.type == "Folder") {
                        retVal.type = "Branch";
                        if(fileData.children != 'undefine') {
                            retVal.children = [];
                            for(var i=0; i<fileData.children.length; ++i) {
                                retVal.children.push(DesignerBrowsePage.translate_to_tree(fileData.children[i], path + fileData.name + "/"));
                            }
                        }
                    }
                    return retVal;
                },
                create_file_image : function(filename) {
                    var wholeDiv = $('<div />', {class:'Leaf'});
                    var img = $('<img />');
                    img.attr('src', 'Icons/file_icon.png');
                    img.width(40);
                    img.height(30);
                    wholeDiv.append(img);
                    var text = $('<span />');
                    text.text(filename);
                    wholeDiv.append(text);
                    wholeDiv.click(function(){
                        var filename = $(this).children('span').text();
                        console.log(filename);
                        //console.log('click');
                    });
                    return wholeDiv;
                },
                create_folder : function(foldername) {
                    var wholeDiv = $('<span />');
                    var img = $('<img />');
                    img.attr('src', 'Icons/folder_close.jpeg');
                    img.width(30);
                    img.height(40);
                    wholeDiv.append(img);
                    var text = $('<span />');
                    text.text(foldername);
                    wholeDiv.append(text);
                    var folder = TreeViewCreator.create_branch(wholeDiv);
                    $(folder).data("FolderName", foldername);
                    folder.set_click(function(folder){
                        console.log($(folder).data("FolderName"));
                        if(!folder.isOpen) {
                            BrowsePage.update_folder_content($(folder).data("FolderName"), folder);
                        }
                        else {
                            folder.close();
                        }
                        //folder.trigger();
                    }, folder);
                    return folder;
                },
                update_folder_content : function(folderName, theFolder) {
                    var postData = {};
                    postData.Command = "Show";
                    postData.Path = folderName;
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
                                        var newFolder = BrowsePage.create_folder(content[i].Name);
                                        theFolder.add_branch(newFolder);
                                        break;
                                    case "Regular":
                                        console.log("Creating regular");
                                        var newFile = BrowsePage.create_file_image(content[i].Name);
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
                }
            };

            $(document).ready(function() {
                root = BrowsePage.create_folder('');
                $("#BrowsePage_FileBrowser").append(root);
                //BrowsePage.update_folder_content("", root);
                //Load the folder file list
                /*
                console.log("Ready");
                var postData = {};
                postData.Command = "Show";
                $.ajax({
                    type: "POST",
                    url: 'BrowsePageBackend.php',
                    data: "postData=" + JSON.stringify(postData),
                    success: function(data) {
                        console.log(data);
                        var reply = JSON.parse(data);
                        if(reply.Status == "Good") {
                            var content = reply.Content;
                        }
                        else {

                        }
                    },
                    error: function(data) {
                        console.log("Error");
                    }
                });
                */
                /*
                var root = BrowsePage.create_folder('Folder_A');
                root.add_branch(BrowsePage.create_file_image('File_A'));
                var root2 = BrowsePage.create_folder('Folder_B');
                root2.add_branch(BrowsePage.create_file_image('File_B'));
                root.add_branch(root2);
                root.add_branch(BrowsePage.create_file_image('File_C'));
                $("#BrowsePage_FileBrowser").append(root);
                */
            });
        </script>
    </body>
</html>