{{-- Modal --}}
<div class="modal fade" id="updateProfileImageModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="updateProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered center">
        <form action="{{ route('panel.admin.profile.update.profile-img', secureToken($user->id)) }}" method="POST"
            enctype="multipart/form-data" onsubmit="return checkCoords();">
            @csrf

            <x-input type="hidden" name="request_with" value="profile_img" validation="empty" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileImageModalLabel">@lang('ui.update_avatar')</h5>
                    <x-button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </x-button>
                </div>
                <div class="modal-body text-center">
                    <div class="form-group mt-5">
                        <x-file name="avatar" version="image" class="cyBlogBannerImage" validation="profile_img" accept=".jpg,.jpeg,.png"/>
                    </div>
                    <img id="imagePreview" class="d-none" src="#" alt="your image" />
                    <div class="demo"></div>
                    <x-input type="hidden" id="croppedImageData" name="croppedImageData" validation="empty" value="" />
                </div>
                <div class="modal-footer">
                    <x-button type="submit" class="btn btn-primary">@lang('ui.update')</x-button>
                </div>
            </div>
        </form>
    </div>
</div>
