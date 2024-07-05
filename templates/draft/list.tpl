{if $drafts|count > 0}
    {include file='draft/_pagination.tpl'}

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{$translator->translate('draft.list.tableHeadMode')}</th>
                    <th scope="col">Team A</th>
                    <th scope="col">Team B</th>
                    <th scope="col">{$translator->translate('draft.list.tableHeadNumberBans')}</th>
                    <th scope="col">{$translator->translate('draft.list.tableHeadNumberPicks')}</th>
                    <th scope="col"></th>
                </tr>
            </thead>

            <tbody>
                {foreach from=$drafts item=draft}
                    <tr>
                        <td>{$draft.id}</td>
                        <td>{$draft.modeName}</td>
                        <td>{$draft.teams[0].teamName}</td>
                        <td>{$draft.teams[1].teamName}</td>
                        <td>{$draft.bans}</td>
                        <td>{$draft.picks}</td>
                        <td>
                            <a href="{$router->generateUrl('draft', 'show', ['id' => $draft.id])}" class="btn btn-secondary">
                                <img src="{$router->getBaseUrl()}web/images/icons-white/zoom-in.svg" alt>
                                {$translator->translate('draft.list.buttonShowLabel')}
                            </a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>

    {include file='draft/_pagination.tpl'}
{else}
    <div class="alert alert-primary">{$translator->translate('draft.list.noDraftsNotice')|replace:'#1':$router->generateUrl('draft', 'new') nofilter}</div>
{/if}

