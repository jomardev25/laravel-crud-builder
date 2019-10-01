
/*$.iDataTable = function(options) {
    var createAccess = null
    var editAccess = null
    var deactivateAccess = null
    var modalFilterArray = {}

    var defaults = {
        tableName: null, 
        tableColumns: [],
        ajaxDataUrl: null,
        ajaxType: 'POST',
        createUrl: null,
        deleteUrl: null,
        editUrl: null,
        aaSorting: 1,
        searchBox: false,
        addButton: false,
        exportButton: false,
        printButton: false,
        colvisButton: false,
        tableStatusColumn: null, 
        tableLength: true,
        tableInfo: true,
        tablePagination: true,
        tableFilter: [],
        colReorder: false,
        scrollY: false,
        scrollX: false,
        scrollCollapse: false,
        iScrollLoadGap: 0,
        fixedColumns: [],
        fixedLeftColumns: 0,
        fixedRightColumns: 0,
        yenNoColumns: []
    }

    var opt = defaults

    $(document).on("click", ".datatable-delete", function(){
        $id = $(this).attr("data-id")
        $status = $(this).attr("data-status")
        let message = $status == 1 ? "Are sure you want to activate?" : "Are sure you want to deactivate?"
        $("#datatable-modal-message").html(message)
        $("#param").val($id)
        $("#status").val($status)
        $("#datatable-modal").modal("show")
    })
  
    $(document).on('click', '#datatable-modal-btn-yes', function() {
      $.ajax({
        url: opt.deleteUrl,
        type: "POST",
        dataType: "json",
        data: {
          param: $("#param").val(),
          status: $("#status").val()
        },
        success: (response) => {
          $("#datatable-modal").modal("toggle")
          datatable.ajax.reload()
          setTimeout(() => {
            if (response.success) {
              toast("success",response.success,"Success","top-center","3000",true)
            }
          }, 6)
        },
        error: (error, statusCode, jqXHR) => {
          $("#datatable-modal").modal("toggle")
          setTimeout(() => {
            toast( "error", jqXHR, "Error", "top-center","3000",true)
          }, 6)
        }
      })
    })
  
    $(document).on('click', '.datatable-filter-icon', function(e) {
        let index = $(this).attr("data-index")
        $(modalFilterArray[index]).css({ left: 0, top: 0 })
  
        let th = $(e.target).parent()
        let pos = th.offset()
        let width = parseInt(th.width() * 1.3) 
  
        if( th.width() < 200 ){
            $(modalFilterArray[index]).width(200)
        }else{
            $(modalFilterArray[index]).width(width)
        }      
  
        $(modalFilterArray[index]).css({ 'left': pos.left, 'top': '238px'})
        $(modalFilterArray[index]).modal("show", {backdrop: 'static', keyboard: false}) 
        $(modalFilterArray[index]).find(".modal-content").show()      
  
    })
  
    $(document).on('click', '.dataTable-filter-modal-btn-ok', function(event) {
  
        let tableName = $(this).attr("data-table")
        let rootNode = $(this).parent().parent()
        let searchString = '', counter = 0
        let index = $(this).attr("data-index")
         
        rootNode.find('input:checkbox').each(function(index, checkbox) {
          if (checkbox.checked) {
            searchString += (counter == 0) ? checkbox.value : '|' + checkbox.value
            counter++
          }
        })
  
        if(index == opt.tableStatusColumn){
          datatable.ajax.reload()
        }else{
          $('#' + tableName).DataTable().column($(this).attr("data-index")).search(searchString, true, false).draw();
        }
  
        if(counter > 0){
          $("[data-filter='"+index+"']").css({"color":"#f0ad4e"})
        }else{
          $("[data-filter='"+index+"']").css({"color":""})
        }
         
        rootNode.hide()
        $(modalFilterArray[index]).modal('hide')
  
    })
  
    $(document).on('click', '.dataTable-filter-modal-btn-clear', function(event) {
        let index = $(this).attr("data-index")
        let tableName = $(this).attr("data-table")
        let rootNode = $(this).parent().parent()
        rootNode.find(".filterSearchText").val('')
        rootNode.find('input:checkbox').each( function(index, checkbox) {
            checkbox.checked = false
            $(checkbox).parent().show()
        });
        $('#' + tableName).DataTable().column($(this).attr("data-index")).search('', true, false).draw();
        $(modalFilterArray[index]).find(".modal-content").hide()
        $(modalFilterArray[index]).modal('hide')
        $("[data-filter='"+index+"']").css({"color":""})
        rootNode.hide()
    })
  
    $(document).on('click', '#dataTable-search-box', function(event) {
      $(this).next('.tooltip').hide()
  
      if($("input[type='search']").css('display') == 'inline-block'){
        $("input[type='search']").val('')
        $("#table-category").dataTable().fnFilter('')
        $(this).html('<i class="fas fa-2x fa-search"></i>')
        $("input[type='search']").animate({
          width: "0",
        }, "fast", function() {
          $(this).hide()
        })
  
      }else{
        $(this).html('<i class="fas fa-2x fa-times"></i>')
        $("input[type='search']").animate({
          width: "100%",
        },  "fast")
      }   
    })
  
    $(document).on('keyup', '.dataTable-filter-input', function(event) {
      let searchString = $(this).val().toLowerCase().trim()
      let rootNode = $(this).parent().parent()
      if (searchString == '') {
          rootNode.find('div').not('.input-with-icons').show()
      } else {
          $(rootNode.find(".list-group-item")).each(function(){
            let searchItem = $(this).find("label").text().toLowerCase().trim()
            if(searchItem.indexOf(searchString) > -1){
              $(this).show()
            }else{
              $(this).hide()
            }
          })
      }
    })

    function configFilter($this, colArray) {
        setTimeout(function () {
              var tableName = $this[0].id
              var columns = $this.api().columns()
              $.each(colArray, function(i, arg) {

                if(opt.scrollX === true && opt.fixedColumns.length > 0){
                  if(!editAccess && !deactivateAccess){ 
                    $('.dataTables_scrollHead .table th:eq(' + arg + ')').prepend('<span class="fas fa-filter datatable-filter-icon" data-index="'+arg+'" data-filter="'+arg+'"></span>')
                  }else{
                    if(opt.fixedColumns.indexOf(arg) > -1){
                      $('.DTFC_LeftHeadWrapper .table th:eq(' + arg + ')').prepend('<span class="fas fa-filter datatable-filter-icon" data-index="'+arg+'" data-filter="'+arg+'"></span>');
                    }else{
                      $('.dataTables_scrollHead .table th:eq(' + arg + ')').prepend('<span class="fas fa-filter datatable-filter-icon" data-index="'+arg+'" data-filter="'+arg+'"></span>');
                    }
                  }
                }else{
                  $('.dataTables_scrollHead .table th:eq(' + arg + ')').prepend('<span class="fas fa-filter datatable-filter-icon" data-index="'+arg+'" data-filter="'+arg+'"></span>');
                }

              })
    
              if(!editAccess && !deactivateAccess){ 
                $this.api().column(0).visible(false) 
              }
              
              var template = `<div class="modal fad-scale dataTable-filter-container" tabindex="-1" 
                                    role="dialog" data-backdrop="static" data-keyboard="false" sty>
                                      <div class="modal-content">
                                          <div class="modal-body">
                                              <div class="list-group dataTable-filter-content">
                                                {0}
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-default btn-small dataTable-filter-modal-btn-clear" data-index="{1}" data-table="${tableName}">Clear</button>
                                              <button type="button" class="btn btn-default btn-small dataTable-filter-modal-btn-ok" data-index="{1}" data-table="${tableName}">OK</button>
                                          </div>
                                      </div>
                              </div>`;
    
              $.each(colArray, function(index, value){
                  columns.every( function(i) {
    
                      var column = this
                      var content = '<div class="input-with-icons"><i class="fas fa-search"></i><input type="text" class="form-control dataTable-filter-input" /></div>'
                      var columnName = $(this.header()).text().replace(/\s+/g, "_")
                      var distinctArray = []
                      var idYes = ""
                      var idNo  = ""
                      var newTemplate = ""

                      if((opt.yenNoColumns.indexOf(i) > -1) && value === i){

                        idYes = tableName + "_" + columnName + "_" + 1
                        idNo = tableName + "_" + columnName + "_" + 2
                        content += '<div class="list-group-item"><input type="checkbox" value="Yes" id="' + idYes + '"/><label for="' + idYes + '">Yes</label></div>'
                        content += '<div class="list-group-item"><input type="checkbox" value="" id="' + idNo + '"/><label for="' + idNo + '">No</label></div>'
                        newTemplate = $(template.replace('{0}', content).replace('{1}', value).replace('{1}', value).replace('{2}', tableName).replace('{2}', tableName));
                        $('body').append(newTemplate)
                        modalFilterArray[value] = newTemplate
                        content = ""

                      }else if( i === opt.tableStatusColumn && value === i ){
    
                        idYes = tableName + "_" + columnName + "_" + 1
                        idNo = tableName + "_" + columnName + "_" + 2
                        content += '<div class="list-group-item"><input name="active_ind" type="checkbox" value="1" id="' + idYes + '"/><label for="' + idYes + '">Active</label></div>'
                        content += '<div class="list-group-item"><input name="active_ind" type="checkbox" value="0" id="' + idNo + '"/><label for="' + idNo + '">Inactive</label></div>'
                        newTemplate = $(template.replace('{0}', content).replace('{1}', value).replace('{1}', value).replace('{2}', tableName).replace('{2}', tableName));
                        $('body').append(newTemplate)
                        modalFilterArray[value] = newTemplate
                        content = ""

                      }else{
                        if (value === i) {
                            column.data().each(function (d, j) {
                                if (distinctArray.indexOf(d) == -1) {
                                    let id = tableName + "_" + columnName + "_" + j
                                    let value = $.trim(d) 
                                    let text = value === "" ? "--" : value 
                                    content += '<div class="list-group-item"><input type="checkbox" value="' + value + '"  id="' + id + '"/><label for="' + id + '">' + text + '</label></div>'
                                    distinctArray.push(d)
                                }
                            })
                            newTemplate = $(template.replace('{0}', content).replace('{1}', value).replace('{1}', value).replace('{2}', tableName).replace('{2}', tableName));
                            $('body').append(newTemplate)
                            modalFilterArray[value] = newTemplate
                            content = ""
                        }
                      }                  
                  })
              })
          }, 50)
    }

    function getDom(){
      let dom = '<"top"'

      if(opt.tableLength){
        dom += 'l'
      }
      if(opt.searchBox)
        dom += 'f'

      if(opt.addButton || opt.exportButton || opt.printButton || opt.colvisButton)
        dom += 'B'
      
      dom += '>rt'

      if(opt.tableInfo)
        dom += '<"bottom"i'

      if(opt.tablePagination)
        dom += 'p'
      dom +=  '><"clear">'

      return dom
    }

    function getButtons(){
      let buttons = []
      if(opt.addButton && opt.createUrl){
        buttons.push({
          text: '<i class="fas fa-2x fa-plus-circle tooltip-success" data-toggle="tooltip" data-placement="top" title="Add"></i>',
          className: 'btn btn-link',
          action: function ( e, dt, node, config ) {
            window.location.href=opt.createUrl
          }
        })
      }

      if(opt.exportButton){
        buttons.push({
          extend: 'collection',
          text: '<i class="fas fa-download tooltip-success" data-toggle="tooltip" data-placement="top" title="Export"></i>',
          className: 'btn btn-link',
          background : false,
          buttons: [
              {
                extend: 'copyHtml5',
                title: '',
                text: '<i class="far fa-clipboard"></i> Copy'
              },
              {
                extend: 'excelHtml5',
                className: '',
                autoFilter: true,
                title: '',
                text: '<i class="far fa-file-excel"></i> Excel',
                exportOptions: {
                  columns: [1, 2, 3, 4]
                },
                customize: function ( xlsx ) {
                  var sheet = xlsx.xl.worksheets['sheet1.xml'];
                  $('row:first c', sheet).attr('s','42');
                }
              },
              {
                extend: 'csvHtml5',
                className: '',
                text: '<i class="far fa-file-alt"></i> CSV',
                exportOptions: {
                  columns: [1, 2, 3, 4]
                }
              },
              {
                extend: 'pdfHtml5',
                className: '', 
                text: '<i class="far fa-file-pdf"></i> PDF', 
                exportOptions: {
                  columns: [1, 2, 3, 4]
                }
              }
            ]
        })
      }

      if(opt.printButton){
        buttons.push({
          extend: 'print', 
          className: 'btn btn-link',
          text: '<i class="fas fa-2x fa-print tooltip-success" data-toggle="tooltip" data-placement="top" title="Print"></i>',
          exportOptions: {
            columns: [1, 2, 3, 4]
          }
        })
      }

      if(opt.colvisButton){
        buttons.push({
          extend: 'colvis',
          className: 'btn btn-link',
          background : false,
          text: '<i class="fas fa-2x fa-columns tooltip-success" data-toggle="tooltip" data-placement="top" title="View Columns"></i>'
        })
      }

      return buttons 
    }

    function getColumns(){
      let columns = []

      $.each(opt.tableColumns, function(){
        if(this.render && this.isActionColumn && opt.deleteUrl && opt.editUrl){ 
          var $this = this
          
          columns.push({
              data: $this.column,
              sClass: $this.className,
              defaultContent: '',
              render: function(data, type, statusColumn) {
                let actionButtons = ""
                let status = statusColumn.deleted_at === null ? 0 : 1
                if (editAccess) {
                  actionButtons += `<a href="${opt.editUrl}/${statusColumn[$this.primaryKeys]}" class="color-warning tooltip-success dataTable-action" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Edit record"><i class="fas fa-pencil-alt"></i></a>`
                }

                if (deactivateAccess) {
                  actionButtons += `<a href="javascript:void(0);" class="color-danger tooltip-success dataTable-action datatable-delete" data-id="${statusColumn[$this.primaryKeys]}" data-status="${status}" data-toggle="tooltip" data-container="body" data-placement="bottom" title="Delete record"><i class="fas fa-trash-alt"></i></a>`
                } 
                return actionButtons
              }
          })
        }else if(this.render && this.isStatus){ 
          var $this = this
          columns.push({
              data: $this.column,
              sClass: $this.className,
              defaultContent: '',
              render: function(data, type, statusColumn) {
                if (statusColumn.deleted_at === null)
                  return `<label class="label label-success">Active</label>`
                else return `<label class="label label-danger">Inactive</label>`
              }
          })
        }else{
          columns.push({ data: this.column, sClass: this.className, defaultContent: '', })
        }
      })   
     
      return columns
    }
   
    if (options) {
        opt = $.extend(defaults, options)
    }
 
    var dataTableOptions = {
        aaSorting: [[opt.aaSorting, 'asc']],
        colReorder: opt.colReorder,
        scrollY: opt.scrollY,
        scrollX: opt.scrollX,
        scrollCollapse: opt.scrollCollapse,
        iScrollLoadGap: opt.iScrollLoadGap,
        fixedColumns:{
          leftColumns: opt.fixedLeftColumns
        },
        dom: getDom(),
        language: { search: '', searchPlaceholder: "Search..." },
        buttons: getButtons(),
        columnDefs: [{
            defaultContent: "",  
            targets: "_all",
            orderable: false, 
            targets: [0]
          },
        ],
        ajax: {
          url: opt.ajaxDataUrl,
          type: opt.ajaxType,
          data: function(data) {
            let chkSelected = []
            if($("input:checkbox[name=active_ind]:checked").length > 0){
              $("input:checkbox[name=active_ind]:checked").each(function() {
                chkSelected.push($(this).val())
              })
            }else{
              chkSelected.push(1)
            }
            data.active_ind = chkSelected
          },
          dataSrc: function(json) {
            createAccess = json.create_access
            editAccess = json.edit_access
            deactivateAccess = json.deactivate_access
            return json.data
          }
        },
        initComplete: function() {
          if (opt.searchBox) {
            $("div.dataTables_filter").append(
              `<a href="javascript:void(0);" id="dataTable-search-box" class="tooltip-success" data-toggle="tooltip" data-placement="top" title="Search">
                <i class="fas fa-2x fa-search"></i>
              </a>
              `
            )
          }
          if(opt.tableFilter.length){
            configFilter(this, opt.tableFilter)  
          }
        },     
        columns: getColumns()
    }

    $('body').append(`<div class="modal fade-scale" id="datatable-modal" tabindex="-1" role="dialog">
                        <input type="hidden" id="param" name="param"/>
                        <input type="hidden" id="status" name="status"/>
                        <div class="modal-dialog modal-sm md-content" role="document">
                            <div class="modal-content">
                                <div class="modal-header primary">
                                    <h4 class="modal-title"><i class="far fa-lg fa-question-circle"></i>Confirm</h4>
                                </div>
                                <div class="modal-body">
                                    <h5 id="datatable-modal-message"></h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary btn-small" id="datatable-modal-btn-yes">Yes</button
                                    <button type="button" class="btn btn-default btn-small" id="datatable-modal-btn-no" data-dismiss="modal">No</button>
                                </div>
                            </div>
                        </div>
                      </div>`); */
$.iDataTable = function(options) {

     var defaults = {
        tableName: null, 
        tableColumns: [],
        ajaxDataUrl: null,
        ajaxType: 'POST',
        createUrl: null,
        deleteUrl: null,
        editUrl: null,
        aaSorting: 1,
        searchBox: false,
        addButton: false,
        exportButton: false,
        printButton: false,
        colvisButton: false,
        tableStatusColumn: null, 
        tableLength: true,
        tableInfo: true,
        tablePagination: true,
        tableFilter: [],
        colReorder: false,
        scrollY: false,
        scrollX: false,
        scrollCollapse: false,
        iScrollLoadGap: 0,
        fixedColumns: [],
        fixedLeftColumns: 0,
        fixedRightColumns: 0,
        yenNoColumns: []
    }

    var opt = defaults
    
    var datatable = $("#" + options.tableName).DataTable();   
}