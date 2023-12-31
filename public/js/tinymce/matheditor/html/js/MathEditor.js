﻿
var this_editor_window = top.tinymce.activeEditor;
 var MathEditor = {
        insert: function (Latex) {
            $('#editable-math').mathquill('write', Latex).focus();
        },
    
        initialize: function () {
            MathEditor.initializeMath();
        },
    
        initializeMath: function () {
            $('#editable-math').focus();
    
            $('#editable-math').bind('keydown keypress', function () {
                MathEditor.EditableMathChange();
            }).keydown().focus();
        },
    
        EditableMathChange: function () {
            setTimeout(function () {
                var latex = $('#editable-math').mathquill('latex');
                $('#latex-source').val(latex);
            });
        },
    
        close: function () {
            this_editor_window.windowManager.close();
        },
    
        insertEditor: function () {
            var span = '&nbsp;<span class="math-editor mathquill-rendered-math">' + $('#editable-math').mathquill('html') + '</span>&nbsp;';
            this_editor_window.insertContent(span);
            MathEditor.close();
        },
    
      
        
        insertMatrix: function () {
            var rows = parseInt($('#ssRows').val());
            var cols = parseInt($('#ssColumns').val());
            var strMatrix = '\\left| \\begin{array}{' + this.repeat("c",cols) + '} ';
            var tempCols = new Array();
            while (tempCols.length + 1 <= cols) tempCols.push('\\mathrm{}');
            tempCols = tempCols.join('&');
    
            var tempRows = new Array();
            while (tempRows.length + 1 <= rows) tempRows.push(tempCols);
            tempRows = tempRows.join(' \\\\ ');
            this.insert(strMatrix + tempRows + ' \\end{array}  \\right|');
            this.closeMatrixWindow();
        },
        openMatrixWindow: function () {
            $('#wnMatrix').window({
                width: 182,
                height: 150,
                modal: true,
                title: 'Insert Matrix',
                collapsible: false,
                minimizable: false,
                maximizable: false,
                resizable: false
            });
        },
    
        closeMatrixWindow: function () {
            $('#wnMatrix').window('close')
        },
    
        repeat: function (str, times) {
            return (new Array(times + 1)).join(str);
        },
        
        makeCssInline: function(){
            this.each    
        }
    }; 
    
    MathEditor.initialize();  



