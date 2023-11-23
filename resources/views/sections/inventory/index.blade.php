@extends('layouts.master')

@section('content')
<h1 class="border-bottom border-success border-3">Inventory</h1>

<ul class="nav nav-tabs mb-3" id="inventoryTab" role="tablist">
   <li class="nav-item" role="presentation">
    <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab" aria-controls="current" aria-selected="true">Overview</button>
   </li>
   <li class="nav-item" role="presentation">
    <button class="nav-link" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction" type="button" role="tab" aria-controls="transaction" aria-selected="false">Transactions</button>
   </li>
   <li class="nav-item" role="presentation">
    <button class="nav-link" id="item-tab" data-bs-toggle="tab" data-bs-target="#item" type="button" role="tab" aria-controls="item" aria-selected="false">Items</button>
   </li>
</ul>

<div class="tab-content" id="inventoryTabContent">
   <div class="tab-pane fade show active" id="current" role="tabpanel" aria-labelledby="current-tab">
      <div class="table-responsive">
         <table id="currentTable" class="stripe hover">
            <thead>
               <tr>
                  <th width="5%"></th>
                  <th>Item</th>
                  <th>Qty</th>
                  <th>Unit</th>
               </tr>
            </thead>
            <tbody>
               @foreach (json_decode($current) as $current)
                  @if($current->qty > 0)
                  <tr>
                     <td></td>
                     <td>{{ !empty($current->name) ? $current->name : '' }}</td>
                     <td>{{ $current->qty }}</td>
                     <td>
                        {{ $current->qty > 1 ? \Illuminate\Support\Str::plural($current->unit) : $current->unit }}
                     </td>
                  </tr>
                  @endif
               @endforeach
            </tbody>
         </table>
      </div>
   </div>

   <div class="tab-pane fade" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">
      <div class="table-responsive">
         <table id="transactionTable" class="stripe hover">
            <thead>
               <tr>
                  <th>Item Code</th>
                  <th>Item</th>
                  <th>Qty</th>
                  <th>Status</th>
                  <th>Date</th>
               </tr>
            </thead>
            <tbody>
               @foreach (json_decode($transactions) as $transaction)
               <tr data-id="{{ $transaction->id }}">
                  <td>{{ $transaction->item->item_code }}</td>
                  <td>{{ $transaction->item->name }}</td>
                  <td>
                     {{ $transaction->qty }} 
                     {{ $transaction->qty > 1 ? \Illuminate\Support\Str::plural($transaction->unit) : $transaction->unit }}
                  </td>
                  <td>{{ $transaction->operator == '+' ? 'New Stock' : 'Destock' }}</td>
                  <td>{{ date('M-d-Y h:ia', strtotime($transaction->created_at)) }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>

   <div class="tab-pane fade" id="item" role="tabpanel" aria-labelledby="item-tab">
      <div class="table-responsive">
         <table id="itemTable" class="stripe hover">
            <thead>
               <tr>
                  <th>Item Code</th>
                  <th>Name</th>
                  <th>Qty</th>
                  <th>Value</th>
                  <th>Added</th>
                  <th data-description="Days in Inventory">DII</th>
                  <th>Expires On</th>
               </tr>
            </thead>
            <tbody>
               @foreach (json_decode($items) as $item)
               
                  <?php 
                     $origPricePerQty = $item->price / $item->orig_qty;
                     $itemCurrentValue = $origPricePerQty * $item->qty;
                     $DII = \Carbon\Carbon::parse($item->created_at)->diffInDays(\Carbon\Carbon::now());

                     $bgClass = '';
                     if ($DII >= 0 && $DII <= 90) { $bgClass = 'excellent'; }
                     elseif ($DII > 90 && $DII <= 180) { $bgClass = 'good'; }
                     elseif ($DII > 180 && $DII <= 270) { $bgClass = 'fair'; }
                     elseif ($DII > 270 && $DII <= 360) { $bgClass = 'fair'; }
                     elseif ($DII > 360) { $bgClass = 'poor'; }

                     $expiredClass = '';
                     if (!empty($item->expired_at)) {
                        $daysToExpire = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->expired_at), false);

                        if ($daysToExpire <= 0) {
                           $bgClass = "bg-danger text-white";
                           $expiredClass = "bg-danger text-white";
                        }
                     }
                  ?>

                  @if($item->qty > 0)
                  <tr class="{{ $expiredClass }}" data-id="{{ $item->item_id }}" data-qty="{{ $item->qty }}" data-unit="{{ $item->unit }}">
                     <td>{{ strtoupper($item->item_code) }}</td>
                     <td>{{ $item->name }}</td>
                     <td>
                        <span class="fw-bold">{{ $item->qty }} 
                        {{ $item->qty > 1 ? \Illuminate\Support\Str::plural($item->unit) : $item->unit }}</span>
                        <br>
                        <small>Origin: {{ $item->orig_qty }} 
                        {{ $item->orig_qty > 1 ? \Illuminate\Support\Str::plural($item->unit) : $item->unit }}</small>
                     </td>
                     <td>
                        <span class="fw-bold">
                          {{ $item->currency }}{{ number_format($itemCurrentValue, 2, '.', '') }}
                        </span>
                        <br>
                        <small>{{ $item->currency }}{{ number_format($origPricePerQty, 2, '.', '') }} / {{ $item->unit }}</small>
                     </td>
                     <td>
                        <!-- To trick dataTable date sorting -->
                        <span style="display:none;">{{ strtotime($item->created_at) }}</span>

                        {{ date('M-d-Y', strtotime($item->created_at)) }}</td>
                     <td class="{{ $bgClass }}">
                        {{ $DII }} {{ $DII > 1 ? 'Days' : 'Day' }}
                     </td>
                     <td>
                        {{ !empty($item->expired_at) ? date('M-d-Y', strtotime($item->expired_at)) : '' }} 
                        {{ $expiredClass != '' ? 'Expired!' : '' }}
                     </td>
                  </tr>
                  @endif
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

   let currentTable = $('#currentTable').DataTable( {
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
            { data: 'qty' },
            { data: 'unit' }
         ],
         order: [[0, 'asc']],
         buttons: [
            'pageLength', 
            {
               extend: 'copy',
               split: ['print', 'csv', 'excel'],
            }
         ],
         drawCallback: function() {
            var api = this.api();
            var rowCount = api.rows({page: 'current'}).count();
            
            for (var i = 0; i < api.page.len() - (rowCount === 0? 1 : rowCount); i++) {
              $('#currentTable tbody').append($("<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>"));
            }
         }
   });

   function format(d) {
       return (
           '<h4>Storage Life <small class="text-secondary">'+d.name+' in '+d.unit+'</small></h4>' + 
           '<table class="table table-sm display mb-3">' +
           '<thead>' + 
           '<tr>' + 
           '<th class="bg-light">No. of days</th>' + 
           '<th class="bg-success text-white">0 - 90</th>' + 
           '<th class="bg-success text-white">91 - 180</th>' + 
           '<th class="bg-warning">181 - 270</th>' + 
           '<th class="bg-warning">271 - 360</th>' + 
           '<th class="bg-warning">360+</th>' + 
           '<th class="bg-danger text-white">Expired</th>' + 
           '</tr>' +
           '</thead>' + 
           '<tbody class="bg-light">' + 
           '<tr>' +
           '<td>No. of items</td>' + 
           '<td>0</td>' + 
           '<td>0</td>' + 
           '<td>0</td>' + 
           '<td>0</td>' + 
           '<td>0</td>' + 
           '<td>0</td>' + 
           '</tr>' +
           '</tbody>' + 
           '</table>'
       );
   }

   currentTable.on('click', 'td.dt-control', function (e) {
       let tr = e.target.closest('tr');
       let row = currentTable.row(tr);
    
       if (row.child.isShown()) {
           row.child.hide();
       }
       else {
           row.child(format(row.data())).show();
       }
   });

   var transactionTable = $('#transactionTable').DataTable( {
         dom: 'Bfrtip',
         lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
         ],
         order: [[4, 'desc']],
         buttons: [
            {
                extend: 'collection',
                text: 'Filter',
                buttons: [
                    {
                        text: 'Today',
                        action: function ( e, dt, node, config ) {
                           alert('Today');
                        }
                    },
                    {
                        text: 'Yesterday',
                        action: function ( e, dt, node, config ) {
                           alert('Yesterday');
                        }
                    },
                    {
                        text: 'This Week',
                        action: function ( e, dt, node, config ) {
                           alert('This Week');
                        }
                    },
                    {
                        text: 'This Month',
                        action: function ( e, dt, node, config ) {
                           alert('This Month');
                        }
                    },
                    {
                        text: 'This Year',
                        action: function ( e, dt, node, config ) {
                           alert('This Year');
                        }
                    }
                ]
            },
            'pageLength', 
            {
               extend: 'copy',
               split: ['print', 'csv', 'excel'],
            }
         ],
         drawCallback: function() {
            var api = this.api();
            var rowCount = api.rows({page: 'current'}).count();
            
            for (var i = 0; i < api.page.len() - (rowCount === 0? 1 : rowCount); i++) {
              $('#transactionTable tbody').append($("<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>"));
            }
         }
    });

   $('#transactionTable').on('click', 'tbody tr', function () {
      var id = $(this).data('id');
      var row = transactionTable.row($(this)).data();

      var inputOptionsPromise = new Promise(function (resolve) {
        setTimeout(function () {
          resolve({
            'delete': 'Delete transaction'
          })
        }, 1000)
      })


      Swal.fire({
         input: 'select',
         inputOptions: inputOptionsPromise,
         inputPlaceholder: 'Select an option',
         title: 'Options',
         showConfirmButton: true,
         showCancelButton: true,
         confirmButtonText: 'Confirm',
         html:
             '<div class="col-md-12 text-start mb-3">TXN #: ' + id + '</div>' +
             '<div class="col-md-12 text-start">Name: ' + row[0] + '</div>' +
             '<div class="col-md-12 text-start">Qty: ' + row[1] + '</div>' +
             '<div class="col-md-12 text-start">Status: ' + row[2] + '</div>' +
             '<div class="col-md-12 text-start">Date: ' + row[3] + '</div>'
      }).then((result) => {
         if (result.isConfirmed) {
            if (result.value === "delete") {
               Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
               }).then((result) => {
                  if (result.isConfirmed) {
                     $.ajax({
                        type: 'DELETE',   
                        url: "/inventories/" + id,
                        data:   
                        {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (response) {
                           Swal.fire(
                              'Deleted!',
                              'Transaction has been deleted.',
                              'success'
                           ).then((result) => {
                              if (result.isConfirmed) {
                                 location.reload();
                              }
                           });
                        }
                     });
                  }
               })
            }
         }
      });
   });

   let itemTable = $('#itemTable').DataTable( {
      dom: 'Bfrtip',
      lengthMenu: [
         [ 10, 25, 50, -1 ],
         [ '10 rows', '25 rows', '50 rows', 'Show all' ]
      ],
      order: [[4, 'desc']],
      buttons: [
         'pageLength', 
         {
            extend: 'copy',
            split: ['print', 'csv', 'excel'],
         },
         {
             text: 'New Stock',
             action: function ( e, dt, node, config ) {
               (async () => {
                  const { value: formValues } = await Swal.fire({
                  title: 'New Stock',
                  cancelButtonColor: '#d33', 
                  showCancelButton: true, 
                  confirmButtonText: 'Add',
                  html:
                      '<input id="name" class="form-control mb-3" Placeholder="Item Name" list="listid" required autocomplete="off">' +
                      '<input id="qty" type="number" class="form-control mb-3" Placeholder="Qty" required autocomplete="off">' +
                      '<input id="unit" class="form-control mb-3" Placeholder="Unit" required autocomplete="off">' + 
                      '<select id="newStockCurrency" class="form-select mb-3"></select>' +
                      '<input type="number" id="price" class="form-control mb-3" Placeholder="Price" required autocomplete="off">' +
                      '<input type="date" id="expired_at" class="form-control mb-3" Placeholder="Expiration" required autocomplete="off">',
                  focusConfirm: false,
                  didOpen: () => {
                     var currencies =  {!! json_encode($currencies) !!};

                     $("#newStockCurrency").append("<option selected>Select Currency</option>");

                     for (var key in currencies) {
                        $("#newStockCurrency").append("<option value='"+currencies[key]+"'>"+key+"</option>");
                     }
                  },
                  preConfirm: () => {
                     return [
                        document.getElementById('name').value,
                        document.getElementById('qty').value,
                        document.getElementById('unit').value,
                        document.getElementById('newStockCurrency').value,
                        document.getElementById('price').value,
                        document.getElementById('expired_at').value
                     ]
                  }
                  })
                  if (formValues) {
                     let name = formValues[0];
                     let qty = formValues[1];
                     let unit = formValues[2];
                     let currency = formValues[3];
                     let price = formValues[4];
                     let expired_at = formValues[5];

                     $.ajax({
                        type: 'POST',   
                        url: "/inventories",
                        data:   
                        {
                            "_token": "{{ csrf_token() }}",
                            "action":"NewStock",
                            "name":name,
                            "qty":qty,
                            "unit":unit,
                            "currency":currency,
                            "price":price,
                            "expired_at":expired_at
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
           $('#itemTable tbody').append($("<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>"));
         }
      }
   });

   $('#itemTable').on('dblclick', 'tbody tr', function () {
      var id = $(this).data('id');
      var qty = $(this).data('qty');
      var unit = $(this).data('unit');
      var row = itemTable.row($(this)).data();

      (async () => {
         const { value: formValues } = await Swal.fire({
            title: 'Destock',
            cancelButtonColor: '#d33', 
            showCancelButton: true, 
            confirmButtonText: 'Confirm',
            html:
               '<div class="col-md-12 text-start mb-3">Tracking Code: ' + row[0] + '</div>' +
               '<div class="col-md-12 text-start mb-3">Name: ' + row[1] + '</div>' +
               '<div class="col-md-12"><input id="qty" type="number" value="0" max="'+qty+'" class="form-control mb-3" Placeholder="Qty" required autocomplete="off"></div>',
            focusConfirm: false,
            preConfirm: () => {
               return [
                  document.getElementById('qty').value
               ]
            }
         })

         if (formValues) {
            let qtyInput = formValues[0];

            if (qtyInput > 0 && qtyInput <= qty) {
               $.ajax({
                  type: 'POST',   
                  url: "/inventories",
                  data:   
                  {
                      "_token": "{{ csrf_token() }}",
                      "action":"Destock",
                      "item_id":id,
                      "qty":qtyInput,
                      "unit":unit
                  },
                  success: function (response) {
                     location.reload();
                  },
                  error: function (response) {
                      
                  }
               });
            }
         }
      })()
   });
});
</script>
@endsection