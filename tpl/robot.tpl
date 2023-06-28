{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Podgląd pracy robota aktualizującego dane</h1>
  </div>
</div>

<div class="row">
  <div class="col">

    <p>
      <a href="/dane/bir" class="btn btn-sm btn-primary">&lt;&lt; powrót do listy NIP</a>
    </p>
    <p>
      <button class="btn btn-success" data-fill-alerts-for="myRobotStart">uruchom</button>
      <button class="btn btn-primary" data-fill-alerts-for="myRobotCheck">odśwież</button>
      <button class="btn btn-danger" data-fill-alerts-for="myRobotStop">zatrzymaj</button>
    </p>

    <div id="myRobotStart" data-alertsApiUri="/bir/robot" data-alertApiMethod="POST" data-alertsRefreshOnLoad="no" data-alertsUseServer="logServer" style="display: none;"></div>
    <div id="myRobotCheck" data-alertsApiUri="/bir/robot" data-alertApiMethod="GET"></div>
    <div id="myRobotStop" data-alertsApiUri="/bir/robot" data-alertApiMethod="DELETE" data-alertsRefreshOnLoad="no" style="display: none;"></div>
  </div>
</div>

<div class="row">
  <div class="col">

    <p>Jeżeli mimo uruchomienia robot się wyłącza (lub się nie uruchamia) a pozostają jakieś NIP'y do pobrania należy w pierwszej kolejności zweryfikować dwie przyczyny:</p>
    <ol>
      <li>błędny klucz dostępu do usługi BIR,</li>
      <li>NIP'y które pozostają w kolejce to NIP'y nieistniejące w bazie podmiotów (czyli NIP'y błędne).</li>
    </ol>

  </div>
</div>

{include file='elements/footer'}
