<!DOCTYPE html>
 
<html lang="en">
<head>
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title></title>

</head>
<body>
 
<div class="container">
<h2>Belajar terus sampe sukses<a href="https://www.komikcast.com" target="_blank">Eins</a></h2>
<br>
<a href="javascript:void(0)" class="btn btn-info ml-3" id="create-new-user">Add New</a>
<a href="javascript:void(0)" class="btn btn-danger ml-3" id="delete-all">Delete all</a>
<input type="text" id="name-search">
<input type="text" id="email-search">

<br><br>
 
<table class="table table-bordered table-striped" id="table">
   <thead>
      <tr>
         <th>No</th>
         <th>ID User</th>
         <th>Name</th>
         <th>Email</th>
         <th>Created at</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody>
       
   </tbody>
</table>
</div>
 
<div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="userCrudModal"></h4>
    </div>
    <div class="modal-body">
        <form id="userForm" name="userForm" class="form-horizontal">
           <input type="hidden" name="user_id" id="user_id">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                </div>
            </div>
 
            <div class="form-group">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-12">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="" required="">
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10">
             <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save changes
             </button>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        
    </div>
</div>
</div>
</div>

<script>
	
	var SITEURL = '{{URL::to('')}}';

	$(document).ready(function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		// Read data in table
		var t = $('#table').DataTable({
			processing : true,
			serverside : true,
			ajax :'user/json',
      dom: 'Bfrtip',
			columns :[
				{data:'id',name:'id',orderable:false,"searchable": false},
				{data:'id',name:'id',orderable:false,"searchable": false,"visible": false},
				{data:'name',name:'name'},
				{data:'email',name:'email'},
				{data:'created_at',name:'created_at',"searchable":false},
				{data:'action',name:'action',orderable:false},
			],
      "order": [[ 5, "desc" ]],
      buttons: [
            {
                extend: 'colvis',
                columns: ':not(.noVis)'
            }
        ],
		});

    // make search manual
    $('#name-search').on( 'keyup', function () {
      t.columns(3).search( this.value ).draw();
    } );

    $('#email-search').on( 'keyup', function () {
      t.columns(4).search( this.value ).draw();
    } );

    // Make auto increment number
     t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    }).draw();

		// add button action modal
		$('#create-new-user').click(function () {
			$('#userCrudModal').html("Add New User");
          $('#btn-save').val("create-user");
          $('#user_id').val('');
          $('#userForm').trigger("reset");
          $('#userCrudModal').html("Add New User");
	        $('#ajax-crud-modal').modal('show');
    });

    // Save Input
    if ($("#userForm").length > 0) {
      $("#userForm").validate({
        submitHandler : function(form){
          var actionType = $('#btn-save').val();
          $('#btn-save').html('Saving...');

          $.ajax({
            data : $('#userForm').serialize(),
            url : SITEURL + "/ajax-crud-list/store",
            type : 'POST',
            dataType : 'JSON',
            success : function(data){
              $('#userForm').trigger("reset");
              $('#ajax-crud-modal').modal('hide');
              $('#btn-save').html('Save Changes');
              $('#table').DataTable().ajax.reload();
            },
            error : function(data){
              alert("Koneksi ke database gagal");
              ('#btn-save').html('Save Changes');
            }
          })
        }
      });
    }

		// Edit interaction modal
    	$('body').on('click', '.edit-user', function () {
			var user_id = $(this).data('id');
			$.get('ajax-crud-list/' + user_id +'/edit', function (data) {
					$('#name-error').hide();
					$('#email-error').hide();
					$('#userCrudModal').html("Edit User");
					$('#btn-save').val("edit-user");
					$('#ajax-crud-modal').modal('show');
					$('#user_id').val(data.id);
					$('#name').val(data.name);
					$('#email').val(data.email);
				})
			});

    // Delete action
    $('body').on('click', '#delete-user', function () {
 
        var user_id = $(this).data("id");
        confirm("Are You sure want to delete !");
 
        $.ajax({
            type: "get",
            url: SITEURL + "/ajax-crud-list/delete/"+user_id,
            success: function (data) {
            $('#table').DataTable().ajax.reload();
            console.log("success");
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });   

    $('#delete-all').click(function(){
      confirm("Apakah anda yakin akan menghapus seluruh data ??");

      $.ajax({
        type:"GET",
        url: SITEURL + "/ajax-crud-list/delete-all",
        success:function(data){
          $('#table').DataTable().ajax.reload();
          alert('sukses');
        },
        error:function(data){
          console.log('Error:',data);
          alert('gagal');
        }
      });
    });

	})

</script>

</body>
</html>

<!-- <script>
var SITEURL = '{{URL::to('')}}';

 $(document).ready( function () {
   $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'user/json',
        columns: [
            { data: 'id', name: 'id',orderable:false},
            { data: 'id', name: 'id'},
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },
            {data: 'action', name: 'action', orderable: false},
        ]
      });
 /*  When user click add user button */
    $('#create-new-user').click(function () {
        $('#btn-save').val("create-user");
        $('#user_id').val('');
        $('#userForm').trigger("reset");
        $('#userCrudModal').html("Add New User");
        $('#ajax-crud-modal').modal('show');
    });
    
 
/* When click edit user */
    $('body').on('click', '.edit-user', function () {
      var user_id = $(this).data('id');
      $.get('ajax-crud-list/' + user_id +'/edit', function (data) {
         $('#name-error').hide();
         $('#email-error').hide();
         $('#userCrudModal').html("Edit User");
          $('#btn-save').val("edit-user");
          $('#ajax-crud-modal').modal('show');
          $('#user_id').val(data.id);
          $('#name').val(data.name);
          $('#email').val(data.email);
      })
   });
    $('body').on('click', '#delete-user', function () {
 
        var user_id = $(this).data("id");
        confirm("Are You sure want to delete !");
 
        $.ajax({
            type: "get",
            url: SITEURL + "ajax-crud-list/delete/"+user_id,
            success: function (data) {
            var oTable = $('#table').dataTable(); 
            oTable.fnDraw(false);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });   
   });
 
if ($("#userForm").length > 0) {
      $("#userForm").validate({
 
     submitHandler: function(form) {
 
      var actionType = $('#btn-save').val();
      $('#btn-save').html('Sending..');
      
      $.ajax({
          data: $('#userForm').serialize(),
          url: SITEURL + "/ajax-crud-list/store",
          type: "POST",
          dataType: 'json',
          success: function (data) {
 
              $('#userForm').trigger("reset");
              $('#ajax-crud-modal').modal('hide');
              $('#btn-save').html('Save Changes');
              var oTable = $('#table').dataTable();
              oTable.fnDraw(true);
              // $('#table').DataTable().ajax.reload();
              
          },
          error: function (data) {
              console.log('Error:', data);
              $('#btn-save').html('Save Changes');
          }
      });
    }
  })
}
</script> -->