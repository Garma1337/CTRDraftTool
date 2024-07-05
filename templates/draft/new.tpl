<div class="draft-create-success d-none">
    <div class="alert alert-success">{$translator->translate('draft.new.successMessage')}</div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{$translator->translate('draft.new.spectatorLinkLabel')}</label>
        <div class="col-sm-10">
            <div class="input-group mb-3">
                <input type="text" class="form-control col-md-10" readonly value="test" id="inputSpectatorUrl">

                <div class="input-group-append">
                    <a href="" target="_blank">
                        <button type="button" class="btn btn-outline-secondary">
                            <img src="{$router->getBaseUrl()}web/images/icons-black/external-link.svg" alt>
                            {$translator->translate('draft.new.openLinkButtonLabel')}
                        </button>
                    </a>

                    <button type="button" class="btn btn-outline-secondary" data-clipboard-target="#inputSpectatorUrl">
                        <img src="{$router->getBaseUrl()}web/images/icons-black/copy.svg" alt>
                        {$translator->translate('draft.new.copyButtonLabel')}
                    </button>
                </div>
            </div>
        </div>

        <label class="col-sm-2 col-form-label">Link Team A</label>
        <div class="col-sm-10">
            <div class="input-group mb-3">
                <input type="text" class="form-control col-md-10" readonly value="" id="inputTeamAUrl">

                <div class="input-group-append">
                    <a href="" target="_blank">
                        <button type="button" class="btn btn-outline-secondary">
                            <img src="{$router->getBaseUrl()}web/images/icons-black/external-link.svg" alt>
                            {$translator->translate('draft.new.openLinkButtonLabel')}
                        </button>
                    </a>

                    <button type="button" class="btn btn-outline-secondary" data-clipboard-target="#inputTeamAUrl">
                        <img src="{$router->getBaseUrl()}web/images/icons-black/copy.svg" alt>
                        {$translator->translate('draft.new.copyButtonLabel')}
                    </button>
                </div>
            </div>
        </div>

        <label class="col-sm-2 col-form-label">Link Team B</label>
        <div class="col-sm-10">
            <div class="input-group mb-3">
                <input type="text" class="form-control col-md-10" readonly value="" id="inputTeamBUrl">

                <div class="input-group-append">
                    <a href="" target="_blank">
                        <button type="button" class="btn btn-outline-secondary">
                            <img src="{$router->getBaseUrl()}web/images/icons-black/external-link.svg" alt>
                            {$translator->translate('draft.new.openLinkButtonLabel')}
                        </button>
                    </a>

                    <button type="button" class="btn btn-outline-secondary" data-clipboard-target="#inputTeamBUrl">
                        <img src="{$router->getBaseUrl()}web/images/icons-black/copy.svg" alt>
                        {$translator->translate('draft.new.copyButtonLabel')}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <hr>
</div>

<div id="baseUrl" class="d-none">{$baseUrl}</div>
<div class="alerts"></div>

<form action="{$formAction}" method="post" id="draftCrateForm">
    <div class="form-group">
        <label for="inputMode">{$translator->translate('draft.new.modeLabel')}</label>
        <select class="form-control col-md-2" id="inputMode" name="mode">
            {foreach from=$modes key=key item=mode}
                <option value="{$mode.id}"{if $key === 0} selected{/if}>{$mode.name}</option>
            {/foreach}
        </select>
    </div>

    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="inputTeamA">Team A</label>
            <input type="text" class="form-control" id="inputTeamA" name="teamA" value="Team A">
        </div>

        <div class="form-group col-md-2">
            <label for="inputTeamB">Team B</label>
            <input type="text" class="form-control" id="inputTeamB" name="teamB" value="Team B">
        </div>
    </div>

    <div class="form-group">
        <label for="inputNumberBans">{$translator->translate('draft.new.numberBansLabel')}</label>
        <input type="number" class="form-control col-md-1" id="inputNumberBans" name="bans" value="3" min="0" max="17">
    </div>

    <div class="form-group">
        <label for="inputNumberPicks">{$translator->translate('draft.new.numberPicksLabel')}</label>
        <input type="number" class="form-control col-md-1" id="inputNumberPicks" name="picks" value="5" min="1" max="30">
    </div>

    <div class="form-group">
        <label for="inputTimeout">{$translator->translate('draft.new.timeoutLabel')}</label>
        <input type="number" class="form-control col-md-1" id="inputTimeout" name="timeout" min="15" max="60">
    </div>

    <div class="track-options">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="enableSpyroCircuit" id="enableSpyroCircuit">
            <label class="form-check-label" for="enableSpyroCircuit">
                {$translator->translate('draft.new.checkboxSpyroCircuitLabel')}
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="enableHyperSpaceway" id="enableHyperSpaceway">
            <label class="form-check-label" for="enableHyperSpaceway">
                {$translator->translate('draft.new.checkboxHyperSpacewayLabel')}
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="enableRetroStadium" id="enableRetroStadium">
            <label class="form-check-label" for="enableRetroStadium">
                {$translator->translate('draft.new.checkboxRetroStadiumLabel')}
            </label>
        </div>

        <div class="form-check d-none">
            <input class="form-check-input" type="checkbox" name="splitTurboRetro" id="splitTurboRetro">
            <label class="form-check-label" for="splitTurboRetro">
                {$translator->translate('draft.new.checkboxSplitTurboRetroLabel')}
            </label>
        </div>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="allowTrackRepeats" id="allowTrackRepeats">
        <label class="form-check-label" for="allowTrackRepeats">
            {$translator->translate('draft.new.checkboxTrackRepeatsLabel')}
        </label>
    </div>

    <div class="form-group row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-primary btn-lg submit-button">
                <img src="{$router->getBaseUrl()}web/images/icons-white/check.svg" width="24">
                {$translator->translate('draft.new.buttonSubmitLabel')}
            </button>
        </div>
    </div>
</form>