<!-- Modal -->
<div class="modal fade p-2" id="AddSpeechText" tabindex="" role="dialog" aria-labelledby="addSummary" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('ui.speak_now')</strong></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <x-button id="speech" class="custom-btn mb-2 speech-pulse">
                            <div class="pulse-ring"></div>
                            <i class="fa fa-microphone text-center mb-1" aria-hidden="true"></i>
                        </x-button>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="radio d-none"><x-input type="radio" name="lang" value="en-US" checked="checked" validation="empty" /> @lang('ui.us_english')
                            </div>

                            <p class="output speech-pulse">@lang('ui.you_said'): <strong class="output_result"></strong>
                            </p>
                            <x-textarea name="said" id="said" class="form-control" cols="30" rows="10" validation="empty" />
                            <span class="my-3 output_log float-right"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <x-button class="btn btn-danger" id="restart-activateMic" type="button"> <i
                            class="fa fa-microphone"></i> @lang('ui.listen_me')</x-button>
                    <x-button class="btn btn-outline-primary ml-1" id="addText-VoiceCommand"
                        type="button">@lang('ui.use_text_close')</x-button>
                </div>
            </div>
        </div>
    </div>
</div>
