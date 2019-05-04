$(document).ready(function(){
	var editor = CodeMirror.fromTextArea(document.getElementById("csscode"), {
      lineNumbers: true,
      mode: "text/css",
      matchBrackets: true
    });
    $("#page-header-desc-configuration-modules-save-css").click(function(e){
    	e.preventDefault();
		$('#csseditor').submit();
    })
})