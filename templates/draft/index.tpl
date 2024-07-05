{$translator->translate('draft.index.welcomeText')}

<br><br>

<p>{$translator->translate('draft.index.explanation')}</p>

<a href="{$router->generateUrl('draft', 'new')}">
    <button class="btn btn-primary">
        <img src="{$router->getBaseUrl()}web/images/icons-white/play.svg" alt>
        {$translator->translate('draft.index.getStartedButtonLabel')}
    </button>
</a>