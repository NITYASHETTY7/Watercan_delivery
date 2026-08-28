 <!-- Modal -->
 <style>
     .alert-info-custom {
         padding: 0.75rem 1rem !important;
     }
 </style>
 <div class="modal fade" id="UserBulkUpload" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-md" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLongTitle">Bulk Import</h5>
                 <x-button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </x-button>
             </div>
             <div class="modal-body">
                 <form action="{{ route('panel.admin.bulk.user') }}" method="post" enctype="multipart/form-data">
                     @csrf
                     <x-input type="hidden" name="request_with" value="upload" validation="empty" />
                     <div>
                         <div class="alert alert-info alert-info-custom">
                             <p class="mb-0">First letter of role name should be capital.There are
                                 {{ @$roles->count() }} Role in our platform.</p>
                             <ul>
                                 @foreach (@$roles as $role)
                                     <li>{{ @$role }}</li>
                                 @endforeach
                             </ul>
                         </div>
                     </div>
                     <div class="d-flex justify-content-end">
                         <a href="{{ asset('utility/bulk-user/user-agent.xlsx') }}" class="btn btn-link"
                             download=""><i class="ik ik-arrow-down"></i> Download Template</a>
                     </div>
                     <div class="form-group">
                         <label for="">Upload Updated Excel Template</label>
                         <x-file name="file" version="choose" class="" validation="excel" accept=".xlsx,.xls" />
                     </div>
                     <x-button>
                         Upload
                     </x-button>
                 </form>
             </div>
         </div>
     </div>
 </div>
