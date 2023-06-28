{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Stany historyczne dla NIP {$nip}</h1>
  </div>
</div>

<div class="row">
  <div class="col">

    <div class="alerts"></div>

    <p>
      <a href="/dane/bir" class="btn btn-sm btn-primary">&lt;&lt; powrót do listy NIP</a>
    </p>

    <p>
      <button class="btn btn-primary" data-fill-alerts-for="myGetCurrentHistoryState">pobierz bieżące dane podmiotu</button>
    </p>

    <div id="myGetCurrentHistoryState" data-alertsApiUri="/bir/companies/{$nip}/refresh" data-alertsRefreshOnLoad="no"></div>

    <table class="table table-sm" data-rowsApiUri="/bir/companies/{$nip}/states">
      <thead>
        <tr>
          <th data-key="date" scope="col">data pobrania<a class="sA"></a><a class="sD"></a></th>
          <th data-key="name" scope="col">nazwa<a class="sA"></a><a class="sD"></a></th>
          <th data-key="regsCount" scope="col">rejestry<a class="sA"></a><a class="sD"></a></th>
          <th data-key="isActive" scope="col">aktywny?<a class="sA"></a><a class="sD"></a></th>
          <th data-key="type" scope="col">stan<a class="sA"></a><a class="sD"></a></th>
          <th class="text-center" scope="col">operacje</th>
        </tr>
        <tr class="js-ad-list-specimen" data-rowId="{ajax id}">
          <th scope="row" style="text-align:center;"><a href="/dane/bir/{$nip}/states/{ajax id}" title="stan pobrany {ajax date}">{ajax date}</a></th>
          <td>{ajax name}</td>
          <td><span style="display:inline-block; font-weight:bold; padding: 0px 10px; background-color:#dddddd; float:left; margin-right: 5px;border-radius: 10px;">{ajax regsCount}</span>{ajax regsLoc}</td>
          <td class="myTaskDone{ajax isActive} text-center">{ajax isActiveLoc}</td>
          <td style="text-align:center;">{ajax typeLoc}</td>
          <td class="align-middle text-center ad-operations">
            <a href="/dane/bir/{$nip}/states/{ajax id}" class="btn btn-success btn-sm">szczegóły</a>
          </td>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <th colspan="666">wyświetlam wyniki od <strong class="setFrom">0</strong> do <strong class="setTo">0</strong> z <strong class="setAll">0</strong><span class="js-ad-set-page">, przełącz do strony: <select class="js-ad-set-page form-select"></select></span></th>
      </tfoot>
    </table>

  </div>
</div>

{include file='elements/footer'}
