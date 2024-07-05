<a href="{$router->generateUrl('leaderboard', 'add')}" class="btn btn-primary">
    <img src="{$router->getBaseUrl()}web/images/icons-white/plus.svg" alt>
    {$translator->translate('leaderboard.index.addLeaderboardButtonLabel')}
</a>

<div class="row">
    {foreach from=$leaderboards item=leaderboard key=key}
        {if $leaderboard.active}
            <div class="col-6 py-3">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-3">
                                <img src="{$leaderboard.image}" class="img-thumbnail" alt>
                            </div>

                            <div class="col-9">
                                <strong>{$leaderboard.name}</strong>

                                <p>
                                    • {$translator->translate('leaderboard.index.defaultRatingLabel')} {$leaderboard.initialRating}
                                    <br>
                                    • {$translator->translate('leaderboard.index.ranksLabel')} {$leaderboard.ranks|count}
                                    <br>
                                    • {$translator->translate('leaderboard.index.matchesLabel')} {$leaderboard.matches|count}
                                    <br>
                                    • {$translator->translate('leaderboard.index.playersLabel')} {$leaderboard.players|count}
                                </p>

                                <a class="btn btn-success" href="{$router->generateUrl('ranked', 'new', ['leaderboardId' => $leaderboard.id])}" role="button">
                                    <img src="{$router->getBaseUrl()}web/images/icons-white/plus.svg" alt>
                                    {$translator->translate('leaderboard.index.addMatchButtonLabel')}
                                </a>

                                <a class="btn btn-secondary" href="{$router->generateUrl('leaderboard', 'show', ['id' => $leaderboard.id])}" role="button">
                                    <img src="{$router->getBaseUrl()}web/images/icons-white/zoom-in.svg" alt>
                                    {$translator->translate('leaderboard.index.showMatchesButtonLabel')}
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            {if $key > 0 && $key % 2 === 1}
                <div class="w-100"></div>
            {/if}
        {/if}
    {/foreach}
</div>
