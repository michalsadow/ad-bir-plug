{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Kasowanie NIP <strong>{$nip}</strong></h1>
  </div>
</div>
<div class="row">
  <div class="col">

    <div class="alerts"></div>

    <form data-fieldsApiUri="/form-fields/delete" data-formApiUri="/bir/companies/{$nip}" data-successUri="/dane/bir">
        <div class="form mb-3">
        </div>
        <a href="/dane/bir" type="button" class="btn btn-secondary">&lt;&lt; anuluj</a>
        <button type="button" class="btn btn-success" data-send-method="DELETE">skasuj podmiot</button>
    </form>

  </div>
</div>

{include file='elements/footer'}
