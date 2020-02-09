<div class="container">
<h2>Data Category</h2>
<br>
<a href="javascript:void(0)" class="btn btn-info ml-3" id="create-category">Add New</a>

<br><br>
 
<table class="table table-bordered table-striped" id="table">
   <thead>
      <tr>
         <th>ID</th>
         <th>Name</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody>
       
   </tbody>
</table>
</div>
 
<div class="modal fade" id="category-modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="userCrudModal"></h4>
    </div>
    <div class="modal-body">
        <form id="categoryForm" name="categoryForm" class="form-horizontal">
           <input type="hidden" name="category_id" id="category_id">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
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
    var mainLink = '{{URL::to('')}}'

    $(document).ready(function (){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#table').DataTable({
            processing : true,
            serverside : true,
            ajax :'category_json',
            columns :[
                {data:'id',name:'id',orderable:false,"searchable": false},
                {data:'name',name:'name'},
                {data:'action',name:'action'},
            ]
        });

        $('#create-category').click(function(){
            $('#category-modal').modal('show');
            $('#btn-save').val('create-category');
            $('#btn-save').html('Save');
            $('#category_id').val();
            $('#categoryForm').trigger('reset');
            $('.modal-title').html('Add new category');
        });

        if($('#categoryForm').length > 0){
            $('#categoryForm').validate({
                submitHandler: function(form){
                    var actionType = $("#btn-save").val();
                    $('#btn-save').html("Saving...");

                    $.ajax({
                        data : $('#categoryForm').serialize(),
                        url : mainLink + "/category_store",
                        type : "POST",
                        dataType : "JSON",
                        success : function(data){
                            console.log("SUCCESS");
                            $('#category-modal').modal('hide');
                            $("#table").DataTable().ajax.reload();
                        },
                        error : function(data){
                            console.log("ERROR");
                        }
                    })
                }
            })
        }

        $('body').on('click','.edit-category',function(){
            $('#category-modal').modal('show');
            $('.modal-title').html('Edit category');
            $('#btn-save').html('Save Update');
        });

        $('body').on('click','#delete-category',function(){
            var idnya = $(this).data('id');
            confirm("Are you sure to delete this data ?");

            $.ajax({
                url : mainLink+"/category_destroy/"+idnya,
                type : "GET",
                success : function(data){
                    $('#table').DataTable().ajax.reload();
                    console.log("SUCCESS");
                },
                error : function(data){
                    console.log("ERROR");
                }
            })


        });
    })
</script>