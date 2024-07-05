<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- External Libraries -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

        <title>CTR Draft Tool v2</title>

        <!-- Local Files --->
        <link rel="stylesheet" href="{$router->getBaseUrl()}web/css/draft-tool.css">
        <script src="{$router->getBaseUrl()}web/js/draft-tool.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-xl main-container">
                <a class="navbar-brand" href="{$router->generateUrl('draft', 'index')}">
                    <img src="{$router->getBaseUrl()}web/images/icons-white/globe.svg" alt> CTR Central
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{$router->getBaseUrl()}web/images/icons-white/grid.svg" alt> Drafting
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item{if $controller === 'draft' && $action === 'new'} active{/if}" href="{$router->generateUrl('draft', 'new')}">
                                    {$translator->translate('action.index.navigationCreateDraft')}
                                </a>

                                <a class="dropdown-item{if $controller === 'draft' && $action === 'list'} active{/if}" href="{$router->generateUrl('draft', 'list')}">
                                    {$translator->translate('action.index.navigationDraftList')}
                                </a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{$router->getBaseUrl()}web/images/icons-white/award.svg" alt> Ranked
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item{if $controller === 'mogi' && $action === 'new'} active{/if}" href="{$router->generateUrl('mogi', 'new')}">
                                    {$translator->translate('action.index.navigationCreateMogi')}
                                </a>

                                <a class="dropdown-item{if $controller === 'mogi' && $action === 'list'} active{/if}" href="{$router->generateUrl('mogi', 'list')}">
                                    {$translator->translate('action.index.navigationMogiList')}
                                </a>
                            </div>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLanguageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{$router->getBaseUrl()}web/images/icons-white/flag.svg" alt>
                                {$translator->translate('action.index.navigationLanguage')}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                {foreach from=$translator->getLanguages() item=language}
                                    <a class="dropdown-item" href="{$router->generateUrl('draft', 'index', ['language' => $language])}">{$language|ucfirst}</a>
                                {/foreach}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container main-container main-body controller-{$controller} action-{$action}">
            <div id="currentLanguage" class="d-none">{$translator->getCurrentLanguage}</div>

            {$content nofilter}
        </div>
    </body>
</html>