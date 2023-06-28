{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Dane rejestrowe firm z Bazy Internetowej Regon (BIR)</h1>
  </div>
</div>

<div class="row">
  <div class="col">

    <p>Poniżej przedstawiona jest lista podmiotów, które zostaną pobrane z BIR.</p>

    <div class="alerts"></div>

    <a class="btn mb-3 btn-primary" href="/dane/bir/dodawanie">dodaj nowe podmioty</a>
    <a class="btn mb-3 btn-warning" href="/dane/bir/robot">podgląd robota</a>

    <table class="table table-sm" data-rowsApiUri="/bir/companies">
      <thead>
        <tr>
          <th data-key="nip" scope="col">NIP<a class="sA"></a><a class="sD"></a></th>
          <th data-key="regon" scope="col">REGON<a class="sA"></a><a class="sD"></a></th>
          <th data-key="regsCount" scope="col">rejestry<a class="sA"></a><a class="sD"></a></th>
          <th data-key="name" scope="col">nazwa podmiotu<a class="sA"></a><a class="sD"></a></th>
          <th data-key="isActive" scope="col">aktywny?<a class="sA"></a><a class="sD"></a></th>
          <th data-key="done" scope="col">pobrane?<a class="sA"></a><a class="sD"></a></th>
          <th data-key="lastRefreshDate" scope="col">data pobrania<a class="sA"></a><a class="sD"></a></th>
          <th data-key="refreshOn" scope="col">odświeżenie<a class="sA"></a><a class="sD"></a></th>
          <th data-key="refreshDeadline" scope="col">termin odświeżenia<a class="sA"></a><a class="sD"></a></th>
          <th class="text-center" scope="col">operacje</th>
        </tr>
        <tr class="js-ad-list-specimen" data-rowId="{ajax id}">
          <th scope="row" style="text-align:center;"><a href="/dane/bir/{ajax nip}" title="{ajax nip}">{ajax nip}</a></th>
          <td>{ajax regon}</td>
          <td><span style="display:inline-block; font-weight:bold; padding: 0px 10px; background-color:#dddddd; float:left; margin-right: 5px;border-radius: 10px;">{ajax regsCount}</span>{ajax regsLoc}</td>
          <td>{ajax name}</td>
          <td class="myTaskDone{ajax isActive} text-center">{ajax isActiveLoc}</td>
          <td class="myTaskDone{ajax done} text-center">{ajax doneLoc}</td>
          <td class="text-center" style="white-space:nowrap;">{ajax lastRefreshDate}</td>
          <td class="text-center" style="white-space:nowrap;">{ajax refreshOnLoc}</td>
          <td class="text-center" style="white-space:nowrap;">{ajax refreshDeadline}</td>
          <td class="align-middle text-center ad-operations">
            <a href="/dane/bir/{ajax id}" class="btn btn-success btn-sm">pobrania</a>
            <a href="/dane/bir/{ajax id}/kasuj" class="btn btn-danger btn-sm">usuń</a>
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
