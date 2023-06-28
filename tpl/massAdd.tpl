{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Masowe dodawanie podmiotów do pobrania z BIR</h1>
  </div>
</div>
<div class="row">
  <div class="col">

    <div class="alerts"></div>
    <div class="js-ad-form-groups"></div>

    <form data-fieldsApiUri="/bir/form-fields/mass-add" data-formApiUri="/bir/companies" data-successUri="/dane/bir">
        <div class="form mb-3">
        </div>

        <a href="/dane/bir" type="button" class="btn btn-secondary" onClick="return confirm('Mogą występować niezapisane zmiany. Na pewno chcesz anulować?');">&lt;&lt; anuluj</a>
        <button type="button" class="btn btn-success" data-send-method="POST">dodaj i wyjdź</button>
        <button type="button" class="btn btn-success" data-send-method="POST" data-redirect="false">dodaj i pozostań</button>
    </form>

  </div>
</div>

{include file='elements/footer'}
