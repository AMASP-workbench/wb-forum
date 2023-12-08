$.get(WB_URL+"/modules/forum/script/markitup/jquery.markitup.js", function() {
    $.get(WB_URL+"/modules/forum/script/markitup/sets/bbcode/set.js", function() {
        $("#messagebox").markItUp(mySettings);
   });
});
