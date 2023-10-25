@extends('layouts.master')

@section('content')
<h1 class="mt-3 mb-3 border-bottom border-info">Inventory</h1>

<ul class="nav nav-tabs mb-3" id="inventoryTab" role="tablist">
   <li class="nav-item" role="presentation">
    <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab" aria-controls="current" aria-selected="true">Current</button>
   </li>
   <li class="nav-item" role="presentation">
    <button class="nav-link" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction" type="button" role="tab" aria-controls="transaction" aria-selected="false">Transactions</button>
   </li>
</ul>

<div class="tab-content" id="inventoryTabContent">
   <div class="tab-pane fade show active" id="current" role="tabpanel" aria-labelledby="current-tab">
      <div class="table-responsive mt-3">
         <table id="currentTable" class="table table-hover">
            <thead>
               <th scope="col">Item</th>
               <th scope="col">Qty</th>
               <th scope="col">Unit</th>
            </thead>
            <tbody>
               <tr data-id="">
                  <td>Salt</td>
                  <td>100</td>
                  <td>Kilo</td>
               </tr>
               <tr data-id="">
                  <td>Sugar</td>
                  <td>100</td>
                  <td>Kilo</td>
               </tr>
               <tr data-id="">
                  <td>Rice</td>
                  <td>100</td>
                  <td>Kilo</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>

   <div class="tab-pane fade" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">
      <div class="table-responsive mt-3">
         <table id="transactionTable" class="table table-hover">
            <thead>
               <th scope="col">Item</th>
               <th scope="col">Qty</th>
               <th scope="col">Unit</th>
               <th scope="col">Status</th>
               <th scope="col">Date</th>
            </thead>
            <tbody>
               <tr data-id="">
                  <td>Salt</td>
                  <td>1</td>
                  <td>Kilo</td>
                  <td>Out</td>
                  <td>Jan 10, 2023 10:00am</td>
               </tr>
               <tr data-id="">
                  <td>Salt</td>
                  <td>1</td>
                  <td>Kilo</td>
                  <td>Out</td>
                  <td>Jan 10, 2023 10:00am</td>
               </tr>
               <tr data-id="">
                  <td>Salt</td>
                  <td>1</td>
                  <td>Kilo</td>
                  <td>Out</td>
                  <td>Jan 10, 2023 10:00am</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
   $('#currentTable').DataTable( {
         dom: 'Bfrtip',
         lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
         ],
         buttons: [
            'pageLength', 
            {
               extend: 'copy',
               split: ['print', 'csv', 'excel'],
            },
            {
                text: 'New Item',
                action: function ( e, dt, node, config ) {
                  (async () => {
                     const { value: formValues } = await Swal.fire({
                     title: 'New Item',
                     cancelButtonColor: '#d33', 
                     showCancelButton: true, 
                     confirmButtonText: 'Add',
                     html:
                         '<input id="name" class="form-control mb-3" Placeholder="Item Name" required>' +
                         '<input id="qty" type="number" class="form-control mb-3" Placeholder="Qty" required>' +
                         '<input id="unit" class="form-control mb-3" Placeholder="Unit" required>',
                     focusConfirm: false,
                     preConfirm: () => {
                      return [
                        document.getElementById('name').value,
                        document.getElementById('qty').value,
                        document.getElementById('unit').value
                      ]
                     }
                     })
                     if (formValues) {
                       Swal.fire(JSON.stringify(formValues))
                     }
                     })()
               }
            }
         ]
    });

   $('#transactionTable').DataTable( {
         dom: 'Bfrtip',
         lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
         ],
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
                        text: 'Last Week',
                        action: function ( e, dt, node, config ) {
                           alert('Last Week');
                        }
                    },
                    {
                        text: 'This Month',
                        action: function ( e, dt, node, config ) {
                           alert('This Month');
                        }
                    },
                    {
                        text: 'Last Month',
                        action: function ( e, dt, node, config ) {
                           alert('Last Month');
                        }
                    },
                    {
                        text: 'This Year',
                        action: function ( e, dt, node, config ) {
                           alert('This Year');
                        }
                    },
                    {
                        text: 'Last Year',
                        action: function ( e, dt, node, config ) {
                           alert('Last Year');
                        }
                    },
                    {
                        text: 'All',
                        action: function ( e, dt, node, config ) {
                           alert('Last Year');
                        }
                    }
                ]
            },
            'pageLength', 
            {
               extend: 'copy',
               split: ['print', 'csv', 'excel'],
            }
         ]
    });
});
</script>
@endsection