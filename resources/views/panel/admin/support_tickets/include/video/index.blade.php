<div class="modal fade" id="agoraVideoCallModal" tabindex="-1" role="dialog" aria-labelledby="agoraVideoCallModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agoraVideoCallModalLabel">@lang('ui.live_call')</h5>
                <x-button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </x-button>
            </div>
            <div class="modal-body">
                <!-- Video Containers for Local and Remote Streams -->
                <div class="row">
                    <div class="col-6">
                        <p>You</p>
                        <div id="local-placeholder" style="text-align: center; padding-top: 130px;">Waiting for you to join...</div>
                        <video id="local-player" style="width: 100%;display:none;" autoplay muted playsinline></video>
                    </div>
                    <div class="col-6">
                        <p>{{ @$supportTicket->user->full_name ?? '--' }}</p>
                        <div id="remote-placeholder" style="text-align: center; padding-top: 130px;">Waiting for {{ @$supportTicket->user->full_name ?? '--' }} to join...</div>
                        <video id="remote-player" style="width: 100%; display: none;" autoplay playsinline></video>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <x-button type="button" id="join-btn" class="btn btn-secondary" onclick="joinCall()">@lang('ui.join')</x-button>
                <x-button type="button" id="leave-btn" class="btn btn-secondary" style="display:none;" onclick="leaveCall()" data-dismiss="modal">@lang('ui.leave')</x-button>
            </div>
        </div>
    </div>
</div>