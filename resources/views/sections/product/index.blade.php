@extends('layouts.master')

@section('content')
<h1 class="border-bottom border-success border-3">Products</h1>

<ul class="nav nav-tabs mb-3" id="salesTab" role="tablist">
   <li class="nav-item" role="presentation">
    <button class="nav-link active" id="product-tab" data-bs-toggle="tab" data-bs-target="#product" type="button" role="tab" aria-controls="product" aria-selected="true">Lists</button>
   </li>
</ul>

<div class="tab-content" id="productsTabContent">
   <div class="tab-pane fade show active" id="product" role="tabpanel" aria-labelledby="product-tab">
      <div class="table-responsive">
         <table id="productTable" class="stripe hover">
            <thead>
               <tr>
                  <th width="5%"></th>
                  <th>Name</th>
                  <th>Short Name</th>
                  <th>Category</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach (json_decode($products) as $product)
               <tr>
                  <td></td>
                  <td>{{ $product->long_name }}</td>
                  <td>{{ $product->short_name }}</td>
                  <td></td>
                  <td>
                     <button id="edit-item" class="btn btn-warning btn-sm">Edit</button>
                     <button id="add-item" class="btn btn-danger btn-sm">Delete</button>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>

</div>

<script type="text/javascript">
$(document).ready(function(){
   $(function(){
      let currentTab = "{{ request()->get('tab') }}";

      if (currentTab != "") {
         $(".nav-link").removeClass("active");
         $(".tab-pane").removeClass("show active");

         $("#"+currentTab+"-tab").addClass("active");
         $("#"+currentTab).addClass("show active");
      }
   });

   $(".nav-link").click(function(){
      let currentTab = $(this).attr("aria-controls");

      window.history.replaceState(null, null, "?tab="+currentTab);
   });

   let productTable = $('#productTable').DataTable( {
         dom: 'Bfrtip',
         lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
         ],
         columns: [
            {
               className: 'dt-control',
               orderable: false,
               data: null,
               defaultContent: ''
            },
            { data: 'name' },
            { data: 'short_name' },
            { data: 'category' },
            { data: 'action' }
         ],
         order: [[1, 'asc']],
         buttons: [
            'pageLength', 
            {
               extend: 'copy',
               split: ['print', 'csv', 'excel'],
            },
            {
               text: 'New Product',
               action: function ( e, dt, node, config ) {
               (async () => {
                  const { value: formValues } = await Swal.fire({
                  title: 'New Product',
                  cancelButtonColor: '#d33', 
                  showCancelButton: true, 
                  confirmButtonText: 'Add',
                  html:
                      '<input id="long_name" class="form-control mb-3" Placeholder="Long Name" list="listid" required autocomplete="off">' +
                      '<input id="short_name" class="form-control mb-3" Placeholder="Short Name" list="listid" required autocomplete="off">' +
                      '<select id="newProductCurrency" class="form-select mb-3"></select>' +
                      '<input type="number" id="price" class="form-control mb-3" Placeholder="Price" required autocomplete="off">',
                  focusConfirm: false,
                  didOpen: () => {
                     var currencies =  {!! json_encode($currencies) !!};

                     $("#newProductCurrency").append("<option selected>Select Currency</option>");

                     for (var key in currencies) {
                        $("#newProductCurrency").append("<option value='"+currencies[key]+"'>"+key+"</option>");
                     }
                  },
                  preConfirm: () => {
                     return [
                        document.getElementById('long_name').value,
                        document.getElementById('short_name').value,
                        document.getElementById('newProductCurrency').value,
                        document.getElementById('price').value
                     ]
                  }
                  })
                  if (formValues) {
                     let long_name = formValues[0];
                     let short_name = formValues[1];
                     let currency = formValues[2];
                     let price = formValues[3];
                     $.ajax({
                        type: 'POST',   
                        url: "/products",
                        data:   
                        {
                            "_token": "{{ csrf_token() }}",
                            "long_name":long_name,
                            "short_name":short_name,
                            "currency":currency,
                            "price":price
                        },
                        success: function (response) {
                           location.reload();
                        },
                        error: function (response) {
                            
                        }
                     });
                  }
               })()
            }
            }
         ],
         drawCallback: function() {
            var api = this.api();
            var rowCount = api.rows({page: 'current'}).count();
            
            for (var i = 0; i < api.page.len() - (rowCount === 0? 1 : rowCount); i++) {
              $('#productTable tbody').append($("<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>"));
            }
         }
   });
});
</script>
@endsection