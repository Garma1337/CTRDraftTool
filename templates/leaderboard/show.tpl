<h1><img src="{$leaderboard.image}" class="img-thumbnail mr-3" alt>{$leaderboard.name}</h1>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Added</th>
                <th scope="col">Teams</th>
                <th scope="col"></th>
            </tr>
        </thead>

        <tbody>
            {foreach from=$leaderboard.matches item=match}
                <tr>
                    <td>{$match.id}</td>
                    <td>
                        <span class="badge badge-{$match.badge}">{$match.title}</span>
                    </td>
                    <td>{$match.created}</td>
                    <td>{if $match.teams|count > 1}{$match.teams|count}{else}0{/if}</td>
                    <td>
                        <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#playerList{$match.id}" aria-expanded="false" aria-controls="playerList{$match.id}">
                            <img src="{$router->getBaseUrl()}web/images/icons-white/list.svg" alt>
                            Players
                        </button>

                        <a href="{$router->generateUrl('ranked', 'show', ['id' => $match.id])}" class="btn btn-secondary">
                            <img src="{$router->getBaseUrl()}web/images/icons-white/zoom-in.svg" alt>
                            {$translator->translate('leaderboard.show.buttonShowLabel')}
                        </a>

                        <div class="collapse my-2" id="playerList{$match.id}">
                            <div class="card card-body">
                                {foreach from=$match.players item=player key=key}
                                    {$key + 1}. {$player.name}<br>

                                    {if $match.teams|count > 1 && $key < ($match.players|count - 1)}
                                        {if ($key + 1) % ($match.players|count / $match.teams|count) === 0}
                                            <hr>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </div>
                        </div>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
