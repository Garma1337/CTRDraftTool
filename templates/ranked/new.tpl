<script type="text/javascript" src="{$router->getBaseUrl()}lib/lorenzi/src/table.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $('#textareaData').on('change', function() {
        const data = parseData($(this).val());
        $('input[name="result"]').attr('value', JSON.stringify(data));
    });
  });
</script>

{if $success}
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {$success}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
{/if}

{if $error}
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {$error}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
{/if}

<div class="row">
    <div class="col-4">
        <form action="{$router->generateUrl('ranked', 'save')}" method="post">
            <div class="form-group">
                <label for="textareaData" class="form-label">Enter the table</label>
                <textarea name="table" class="form-control" id="textareaData" cols="30" rows="20" onkeyup="queueRefresh()"></textarea>
            </div>

            <input type="hidden" name="result" value="">
            <input type="hidden" name="leaderboardId" value="{$leaderboardId}">

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <img src="{$router->getBaseUrl()}web/images/icons-white/save.svg">
                    Save table
                </button>

                <button type="reset" class="btn btn-danger">
                    <img src="{$router->getBaseUrl()}web/images/icons-white/trash-2.svg">
                    Reset form
                </button>
            </div>
        </form>
    </div>

    <div class="col-8">
        <label class="form-label">Result image</label>

        <canvas id="canvasTable" width="731" height="358"></canvas>
        <div id="divTable">
            <img id="imgTable" alt="" class="d-none">
        </div>

        <span id="spanTotal" class="d-none"></span>
        <span id="spanWarning" class="d-none"></span>
    </div>
</div>
