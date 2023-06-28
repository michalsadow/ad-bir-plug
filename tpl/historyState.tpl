{include file='elements/header'}

<div class="row mt-3 mb-3">
  <div class="col">
    <h1>Szczegółowe dane NIP {$nip}</h1>
  </div>
</div>

<div class="row mt-3 mb-3">
  <div class="col">
    <p>
      <a href="/dane/bir/{$nip}" class="btn btn-sm btn-primary">&lt; powrót do stanów historycznych</a>
      <a href="/dane/bir" class="btn btn-sm btn-primary">&lt;&lt; powrót do listy NIP</a>
    </p>
  </div>
</div>

<div class="row mt-3 mb-3">
  <div class="col">

    <h2 name="top">Dostępne informacje</h2>
    <ol>
{if present $activities.pkd}
      <li><a href="#pkd">Kody PKD podmiotu</a></li>
{fi}
{if present $activities.krs}
      <li><a href="#krs">Wpis do KRS</a></li>
{fi}
{if present $activities.ceidg}
      <li><a href="#ceidg">Działalność gospodarcza podlegająca zgłoszeniu do CEIDG</a></li>
{fi}
{if present $activities.rural}
      <li><a href="#rural">Działalność rolnicza</a></li>
{fi}
{if present $activities.other}
      <li><a href="#other">Inna działalność gospodarcza niepodlegająca zgłoszeniu do CEIDG</a></li>
{fi}
    </ol>

  </div>
</div>

{if present $activities.pkd}
<div class="row mt-3 mb-3">
  <div class="col">
    <h2 id="pkd">Kody PKD podmiotu <small style="display:inline-block; font-size:70%!important; margin-left:10px;"><a href="#top">do góry</a></small></h2>

    <table class="my-data">
      <tr>
        <td class="my-key-1">przeważąjący</td>
        <td colspan="2" class="my-value">{$activities.pkd.main}</td>
      </tr>
      <tr>
        <td class="my-key-1">pozostałe</td>
        <td colspan="2" class="my-value">{$activities.pkd.other}</td>
      </tr>
    </table>
  </div>
</div>
{fi}

{if present $activities.krs}
<div class="row mt-3 mb-3">
  <div class="col">
    <h2 id="krs">Wpis do KRS <small style="display:inline-block; font-size:70%!important; margin-left:10px;"><a href="#top">do góry</a></small></h2>

    <table class="my-data">
      <tr>
        <td class="my-key-1">REGON</td>
        <td colspan="2" class="my-value">{$activities.krs.regon}</td>
      </tr>
      <tr>
        <td class="my-key-1">nazwa pełna</td>
        <td colspan="2" class="my-value">{$activities.krs.name.long}</td>
      </tr>
      <tr>
        <td class="my-key-1">nazwa skrócona</td>
        <td colspan="2" class="my-value">{$activities.krs.name.short}</td>
      </tr>
      <tr>
        <td rowspan="10" class="my-key-1">daty</td>
        <td class="my-key-2">założenia</td>
        <td class="my-value">{$activities.krs.dates.creation}</td>
      </tr>
      <tr>
        <td class="my-key-2">rozpoczędzia działalności</td>
        <td class="my-value">{$activities.krs.dates.operationStarted}</td>
      </tr>
      <tr>
        <td class="my-key-2">rejestracji w REGON</td>
        <td class="my-value">{$activities.krs.dates.regonReg}</td>
      </tr>
      <tr>
        <td class="my-key-2">zawieszenia</td>
        <td class="my-value">{$activities.krs.dates.suspended}</td>
      </tr>
      <tr>
        <td class="my-key-2">odwieszenia</td>
        <td class="my-value">{$activities.krs.dates.renewed}</td>
      </tr>
      <tr>
        <td class="my-key-2">ostatniej zmiany</td>
        <td class="my-value">{$activities.krs.dates.lastChange}</td>
      </tr>
      <tr>
        <td class="my-key-2">zakończenia działalności</td>
        <td class="my-value">{$activities.krs.dates.ended}</td>
      </tr>
      <tr>
        <td class="my-key-2">wyrejestrowania z REGON</td>
        <td class="my-value">{$activities.krs.dates.regonUnreg}</td>
      </tr>
      <tr>
        <td class="my-key-2">ogłoszenia upadłości</td>
        <td class="my-value">{$activities.krs.dates.bankruptcyDeclared}</td>
      </tr>
      <tr>
        <td class="my-key-2">zak. post. upadłościowego</td>
        <td class="my-value">{$activities.krs.dates.bankruptcyProceeded}</td>
      </tr>
      <tr>
        <td rowspan="15" class="my-key-1">adres rejestrowy</td>
        <td class="my-key-2">kraj</td>
        <td class="my-value">{$activities.krs.regAddress.country}</td>
      </tr>
      <tr>
        <td class="my-key-2">województwo</td>
        <td class="my-value">{$activities.krs.regAddress.voivodeship}</td>
      </tr>
      <tr>
        <td class="my-key-2">powiat</td>
        <td class="my-value">{$activities.krs.regAddress.county}</td>
      </tr>
      <tr>
        <td class="my-key-2">gmina</td>
        <td class="my-value">{$activities.krs.regAddress.municipality}</td>
      </tr>
      <tr>
        <td class="my-key-2">miasto</td>
        <td class="my-value">{$activities.krs.regAddress.city}</td>
      </tr>
      <tr>
        <td class="my-key-2">ulica</td>
        <td class="my-value">{$activities.krs.regAddress.street}</td>
      </tr>
      <tr>
        <td class="my-key-2">nr nieruchomości</td>
        <td class="my-value">{$activities.krs.regAddress.propNumber}</td>
      </tr>
      <tr>
        <td class="my-key-2">nr lokalu</td>
        <td class="my-value">{$activities.krs.regAddress.flatNumber}</td>
      </tr>
      <tr>
        <td class="my-key-2">nietypowa lokalizacja</td>
        <td class="my-value">{$activities.krs.regAddress.untypical}</td>
      </tr>
      <tr>
        <td class="my-key-2">miasto poczty</td>
        <td class="my-value">{$activities.krs.regAddress.postCity}</td>
      </tr>
      <tr>
        <td class="my-key-2">kod pocztowy (PNA)</td>
        <td class="my-value">{$activities.krs.regAddress.zipCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT TERC</td>
        <td class="my-value">{$activities.krs.regAddress.teryt.terc}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT SIMC poczty</td>
        <td class="my-value">{$activities.krs.regAddress.teryt.simcPost}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT SIMC</td>
        <td class="my-value">{$activities.krs.regAddress.teryt.simc}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT ULIC</td>
        <td class="my-value">{$activities.krs.regAddress.teryt.ulic}</td>
      </tr>
      <tr>
        <td rowspan="8" class="my-key-1">forma prawna</td>
        <td class="my-key-2">podstawowa (kod)</td>
        <td class="my-value">{$activities.krs.form.baseCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">szczegółowa (kod)</td>
        <td class="my-value">{$activities.krs.form.detailedCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">finansowania (kod)</td>
        <td class="my-value">{$activities.krs.form.financialCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">własności (kod)</td>
        <td class="my-value">{$activities.krs.form.ownershipCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">podstawowa (nazwa)</td>
        <td class="my-value">{$activities.krs.form.baseName}</td>
      </tr>
      <tr>
        <td class="my-key-2">szczegółowa (nazwa)</td>
        <td class="my-value">{$activities.krs.form.detailedName}</td>
      </tr>
      <tr>
        <td class="my-key-2">finansowania (nazwa)</td>
        <td class="my-value">{$activities.krs.form.financialName}</td>
      </tr>
      <tr>
        <td class="my-key-2">własności (nazwa)</td>
        <td class="my-value">{$activities.krs.form.ownershipName}</td>
      </tr>
      <tr>
        <td rowspan="2" class="my-key-1">organ założycielski</td>
        <td class="my-key-2">symbol</td>
        <td class="my-value">{$activities.krs.mother.symbol}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa</td>
        <td class="my-value">{$activities.krs.mother.name}</td>
      </tr>
      <tr>
        <td rowspan="5" class="my-key-1">dane kontaktowe</td>
        <td class="my-key-2">telefon</td>
        <td class="my-value">{$activities.krs.contact.phone}</td>
      </tr>
      <tr>
        <td class="my-key-2">numer wewn.</td>
        <td class="my-value">{$activities.krs.contact.internalPhone}</td>
      </tr>
      <tr>
        <td class="my-key-2">fax</td>
        <td class="my-value">{$activities.krs.contact.fax}</td>
      </tr>
      <tr>
        <td class="my-key-2">e-mail</td>
        <td class="my-value">{$activities.krs.contact.email}</td>
      </tr>
      <tr>
        <td class="my-key-2">www</td>
        <td class="my-value">{$activities.krs.contact.web}</td>
      </tr>
      <tr>
        <td rowspan="8" class="my-key-1">rejestr</td>
        <td class="my-key-2">typ</td>
        <td class="my-value">{$activities.krs.registry.type}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa</td>
        <td class="my-value">{$activities.krs.registry.name.formal}</td>
      </tr>
      <tr>
        <td class="my-key-2">numer w rejestrze</td>
        <td class="my-value">{$activities.krs.registry.number}</td>
      </tr>
      <tr>
        <td class="my-key-2">data wpisania</td>
        <td class="my-value">{$activities.krs.registry.addedDate}</td>
      </tr>
      <tr>
        <td class="my-key-2">data wykreślenia</td>
        <td class="my-value">{$activities.krs.registry.deletedDate}</td>
      </tr>
      <tr>
        <td class="my-key-2">symbol organu prowadzącego</td>
        <td class="my-value">{$activities.krs.registry.registree.symbol}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa organu prowadzącego</td>
        <td class="my-value">{$activities.krs.registry.registree.name}</td>
      </tr>
      <tr>
        <td class="my-key-1">liczba jedn. lokalnych</td>
        <td colspan="2" class="my-value">{$activities.krs.localCount}</td>
      </tr>
    </table>
  </div>
</div>
{fi}

{if present $activities.ceidg}
<div class="row mt-3 mb-3">
  <div class="col">
    <h2 id="ceidg">Działalność gospodarcza podlegająca zgłoszeniu do CEIDG <small style="display:inline-block; font-size:70%!important; margin-left:10px;"><a href="#top">do góry</a></small></h2>

    <table class="my-data">
      <tr>
        <td class="my-key-1">REGON</td>
        <td colspan="2" class="my-value">{$activities.ceidg.regon}</td>
      </tr>
      <tr>
        <td class="my-key-1">nazwa pełna</td>
        <td colspan="2" class="my-value">{$activities.ceidg.name.long}</td>
      </tr>
      <tr>
        <td class="my-key-1">nazwa skrócona</td>
        <td colspan="2" class="my-value">{$activities.ceidg.name.short}</td>
      </tr>
      <tr>
        <td rowspan="10" class="my-key-1">daty</td>
        <td class="my-key-2">założenia</td>
        <td class="my-value">{$activities.ceidg.dates.creation}</td>
      </tr>
      <tr>
        <td class="my-key-2">rozpoczędzia działalności</td>
        <td class="my-value">{$activities.ceidg.dates.operationStarted}</td>
      </tr>
      <tr>
        <td class="my-key-2">rejestracji w REGON</td>
        <td class="my-value">{$activities.ceidg.dates.regonReg}</td>
      </tr>
      <tr>
        <td class="my-key-2">zawieszenia</td>
        <td class="my-value">{$activities.ceidg.dates.suspended}</td>
      </tr>
      <tr>
        <td class="my-key-2">odwieszenia</td>
        <td class="my-value">{$activities.ceidg.dates.renewed}</td>
      </tr>
      <tr>
        <td class="my-key-2">ostatniej zmiany</td>
        <td class="my-value">{$activities.ceidg.dates.lastChange}</td>
      </tr>
      <tr>
        <td class="my-key-2">zakończenia działalności</td>
        <td class="my-value">{$activities.ceidg.dates.ended}</td>
      </tr>
      <tr>
        <td class="my-key-2">wyrejestrowania z REGON</td>
        <td class="my-value">{$activities.ceidg.dates.regonUnreg}</td>
      </tr>
      <tr>
        <td class="my-key-2">ogłoszenia upadłości</td>
        <td class="my-value">{$activities.ceidg.dates.bankruptcyDeclared}</td>
      </tr>
      <tr>
        <td class="my-key-2">zak. post. upadłościowego</td>
        <td class="my-value">{$activities.ceidg.dates.bankruptcyProceeded}</td>
      </tr>
      <tr>
        <td rowspan="15" class="my-key-1">adres rejestrowy</td>
        <td class="my-key-2">kraj</td>
        <td class="my-value">{$activities.ceidg.regAddress.country}</td>
      </tr>
      <tr>
        <td class="my-key-2">województwo</td>
        <td class="my-value">{$activities.ceidg.regAddress.voivodeship}</td>
      </tr>
      <tr>
        <td class="my-key-2">powiat</td>
        <td class="my-value">{$activities.ceidg.regAddress.county}</td>
      </tr>
      <tr>
        <td class="my-key-2">gmina</td>
        <td class="my-value">{$activities.ceidg.regAddress.municipality}</td>
      </tr>
      <tr>
        <td class="my-key-2">miasto</td>
        <td class="my-value">{$activities.ceidg.regAddress.city}</td>
      </tr>
      <tr>
        <td class="my-key-2">ulica</td>
        <td class="my-value">{$activities.ceidg.regAddress.street}</td>
      </tr>
      <tr>
        <td class="my-key-2">nr nieruchomości</td>
        <td class="my-value">{$activities.ceidg.regAddress.propNumber}</td>
      </tr>
      <tr>
        <td class="my-key-2">nr lokalu</td>
        <td class="my-value">{$activities.ceidg.regAddress.flatNumber}</td>
      </tr>
      <tr>
        <td class="my-key-2">nietypowa lokalizacja</td>
        <td class="my-value">{$activities.ceidg.regAddress.untypical}</td>
      </tr>
      <tr>
        <td class="my-key-2">miasto poczty</td>
        <td class="my-value">{$activities.ceidg.regAddress.postCity}</td>
      </tr>
      <tr>
        <td class="my-key-2">kod pocztowy (PNA)</td>
        <td class="my-value">{$activities.ceidg.regAddress.zipCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT TERC</td>
        <td class="my-value">{$activities.ceidg.regAddress.teryt.terc}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT SIMC poczty</td>
        <td class="my-value">{$activities.ceidg.regAddress.teryt.simcPost}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT SIMC</td>
        <td class="my-value">{$activities.ceidg.regAddress.teryt.simc}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT ULIC</td>
        <td class="my-value">{$activities.ceidg.regAddress.teryt.ulic}</td>
      </tr>
      <tr>
        <td rowspan="8" class="my-key-1">forma prawna</td>
        <td class="my-key-2">podstawowa (kod)</td>
        <td class="my-value">{$activities.ceidg.form.baseCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">szczegółowa (kod)</td>
        <td class="my-value">{$activities.ceidg.form.detailedCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">finansowania (kod)</td>
        <td class="my-value">{$activities.ceidg.form.financialCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">własności (kod)</td>
        <td class="my-value">{$activities.ceidg.form.ownershipCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">podstawowa (nazwa)</td>
        <td class="my-value">{$activities.ceidg.form.baseName}</td>
      </tr>
      <tr>
        <td class="my-key-2">szczegółowa (nazwa)</td>
        <td class="my-value">{$activities.ceidg.form.detailedName}</td>
      </tr>
      <tr>
        <td class="my-key-2">finansowania (nazwa)</td>
        <td class="my-value">{$activities.ceidg.form.financialName}</td>
      </tr>
      <tr>
        <td class="my-key-2">własności (nazwa)</td>
        <td class="my-value">{$activities.ceidg.form.ownershipName}</td>
      </tr>
      <tr>
        <td rowspan="2" class="my-key-1">organ założycielski</td>
        <td class="my-key-2">symbol</td>
        <td class="my-value">{$activities.ceidg.mother.symbol}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa</td>
        <td class="my-value">{$activities.ceidg.mother.name}</td>
      </tr>
      <tr>
        <td rowspan="5" class="my-key-1">dane kontaktowe</td>
        <td class="my-key-2">telefon</td>
        <td class="my-value">{$activities.ceidg.contact.phone}</td>
      </tr>
      <tr>
        <td class="my-key-2">numer wewn.</td>
        <td class="my-value">{$activities.ceidg.contact.internalPhone}</td>
      </tr>
      <tr>
        <td class="my-key-2">fax</td>
        <td class="my-value">{$activities.ceidg.contact.fax}</td>
      </tr>
      <tr>
        <td class="my-key-2">e-mail</td>
        <td class="my-value">{$activities.ceidg.contact.email}</td>
      </tr>
      <tr>
        <td class="my-key-2">www</td>
        <td class="my-value">{$activities.ceidg.contact.web}</td>
      </tr>
      <tr>
        <td rowspan="8" class="my-key-1">rejestr</td>
        <td class="my-key-2">typ</td>
        <td class="my-value">{$activities.ceidg.registry.type}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa</td>
        <td class="my-value">{$activities.ceidg.registry.name.formal}</td>
      </tr>
      <tr>
        <td class="my-key-2">numer w rejestrze</td>
        <td class="my-value">{$activities.ceidg.registry.number}</td>
      </tr>
      <tr>
        <td class="my-key-2">data wpisania</td>
        <td class="my-value">{$activities.ceidg.registry.addedDate}</td>
      </tr>
      <tr>
        <td class="my-key-2">data wykreślenia</td>
        <td class="my-value">{$activities.ceidg.registry.deletedDate}</td>
      </tr>
      <tr>
        <td class="my-key-2">symbol organu prowadzącego</td>
        <td class="my-value">{$activities.ceidg.registry.registree.symbol}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa organu prowadzącego</td>
        <td class="my-value">{$activities.ceidg.registry.registree.name}</td>
      </tr>
      <tr>
        <td class="my-key-1">liczba jedn. lokalnych</td>
        <td colspan="2" class="my-value">{$activities.ceidg.localCount}</td>
      </tr>
    </table>
  </div>
</div>
{fi}

{if present $activities.rural}
<div class="row mt-3 mb-3">
  <div class="col">
    <h2 id="rural">Działalność rolnicza <small style="display:inline-block; font-size:70%!important; margin-left:10px;"><a href="#top">do góry</a></small></h2>

    <table class="my-data">
      <tr>
        <td class="my-key-1">REGON</td>
        <td colspan="2" class="my-value">{$activities.rural.regon}</td>
      </tr>
      <tr>
        <td class="my-key-1">nazwa pełna</td>
        <td colspan="2" class="my-value">{$activities.rural.name.long}</td>
      </tr>
      <tr>
        <td class="my-key-1">nazwa skrócona</td>
        <td colspan="2" class="my-value">{$activities.rural.name.short}</td>
      </tr>
      <tr>
        <td rowspan="10" class="my-key-1">daty</td>
        <td class="my-key-2">założenia</td>
        <td class="my-value">{$activities.rural.dates.creation}</td>
      </tr>
      <tr>
        <td class="my-key-2">rozpoczędzia działalności</td>
        <td class="my-value">{$activities.rural.dates.operationStarted}</td>
      </tr>
      <tr>
        <td class="my-key-2">rejestracji w REGON</td>
        <td class="my-value">{$activities.rural.dates.regonReg}</td>
      </tr>
      <tr>
        <td class="my-key-2">zawieszenia</td>
        <td class="my-value">{$activities.rural.dates.suspended}</td>
      </tr>
      <tr>
        <td class="my-key-2">odwieszenia</td>
        <td class="my-value">{$activities.rural.dates.renewed}</td>
      </tr>
      <tr>
        <td class="my-key-2">ostatniej zmiany</td>
        <td class="my-value">{$activities.rural.dates.lastChange}</td>
      </tr>
      <tr>
        <td class="my-key-2">zakończenia działalności</td>
        <td class="my-value">{$activities.rural.dates.ended}</td>
      </tr>
      <tr>
        <td class="my-key-2">wyrejestrowania z REGON</td>
        <td class="my-value">{$activities.rural.dates.regonUnreg}</td>
      </tr>
      <tr>
        <td class="my-key-2">ogłoszenia upadłości</td>
        <td class="my-value">{$activities.rural.dates.bankruptcyDeclared}</td>
      </tr>
      <tr>
        <td class="my-key-2">zak. post. upadłościowego</td>
        <td class="my-value">{$activities.rural.dates.bankruptcyProceeded}</td>
      </tr>
      <tr>
        <td rowspan="15" class="my-key-1">adres rejestrowy</td>
        <td class="my-key-2">kraj</td>
        <td class="my-value">{$activities.rural.regAddress.country}</td>
      </tr>
      <tr>
        <td class="my-key-2">województwo</td>
        <td class="my-value">{$activities.rural.regAddress.voivodeship}</td>
      </tr>
      <tr>
        <td class="my-key-2">powiat</td>
        <td class="my-value">{$activities.rural.regAddress.county}</td>
      </tr>
      <tr>
        <td class="my-key-2">gmina</td>
        <td class="my-value">{$activities.rural.regAddress.municipality}</td>
      </tr>
      <tr>
        <td class="my-key-2">miasto</td>
        <td class="my-value">{$activities.rural.regAddress.city}</td>
      </tr>
      <tr>
        <td class="my-key-2">ulica</td>
        <td class="my-value">{$activities.rural.regAddress.street}</td>
      </tr>
      <tr>
        <td class="my-key-2">nr nieruchomości</td>
        <td class="my-value">{$activities.rural.regAddress.propNumber}</td>
      </tr>
      <tr>
        <td class="my-key-2">nr lokalu</td>
        <td class="my-value">{$activities.rural.regAddress.flatNumber}</td>
      </tr>
      <tr>
        <td class="my-key-2">nietypowa lokalizacja</td>
        <td class="my-value">{$activities.rural.regAddress.untypical}</td>
      </tr>
      <tr>
        <td class="my-key-2">miasto poczty</td>
        <td class="my-value">{$activities.rural.regAddress.postCity}</td>
      </tr>
      <tr>
        <td class="my-key-2">kod pocztowy (PNA)</td>
        <td class="my-value">{$activities.rural.regAddress.zipCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT TERC</td>
        <td class="my-value">{$activities.rural.regAddress.teryt.terc}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT SIMC poczty</td>
        <td class="my-value">{$activities.rural.regAddress.teryt.simcPost}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT SIMC</td>
        <td class="my-value">{$activities.rural.regAddress.teryt.simc}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT ULIC</td>
        <td class="my-value">{$activities.rural.regAddress.teryt.ulic}</td>
      </tr>
      <tr>
        <td rowspan="8" class="my-key-1">forma prawna</td>
        <td class="my-key-2">podstawowa (kod)</td>
        <td class="my-value">{$activities.rural.form.baseCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">szczegółowa (kod)</td>
        <td class="my-value">{$activities.rural.form.detailedCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">finansowania (kod)</td>
        <td class="my-value">{$activities.rural.form.financialCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">własności (kod)</td>
        <td class="my-value">{$activities.rural.form.ownershipCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">podstawowa (nazwa)</td>
        <td class="my-value">{$activities.rural.form.baseName}</td>
      </tr>
      <tr>
        <td class="my-key-2">szczegółowa (nazwa)</td>
        <td class="my-value">{$activities.rural.form.detailedName}</td>
      </tr>
      <tr>
        <td class="my-key-2">finansowania (nazwa)</td>
        <td class="my-value">{$activities.rural.form.financialName}</td>
      </tr>
      <tr>
        <td class="my-key-2">własności (nazwa)</td>
        <td class="my-value">{$activities.rural.form.ownershipName}</td>
      </tr>
      <tr>
        <td rowspan="2" class="my-key-1">organ założycielski</td>
        <td class="my-key-2">symbol</td>
        <td class="my-value">{$activities.rural.mother.symbol}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa</td>
        <td class="my-value">{$activities.rural.mother.name}</td>
      </tr>
      <tr>
        <td rowspan="5" class="my-key-1">dane kontaktowe</td>
        <td class="my-key-2">telefon</td>
        <td class="my-value">{$activities.rural.contact.phone}</td>
      </tr>
      <tr>
        <td class="my-key-2">numer wewn.</td>
        <td class="my-value">{$activities.rural.contact.internalPhone}</td>
      </tr>
      <tr>
        <td class="my-key-2">fax</td>
        <td class="my-value">{$activities.rural.contact.fax}</td>
      </tr>
      <tr>
        <td class="my-key-2">e-mail</td>
        <td class="my-value">{$activities.rural.contact.email}</td>
      </tr>
      <tr>
        <td class="my-key-2">www</td>
        <td class="my-value">{$activities.rural.contact.web}</td>
      </tr>
      <tr>
        <td rowspan="8" class="my-key-1">rejestr</td>
        <td class="my-key-2">typ</td>
        <td class="my-value">{$activities.rural.registry.type}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa</td>
        <td class="my-value">{$activities.rural.registry.name.formal}</td>
      </tr>
      <tr>
        <td class="my-key-2">numer w rejestrze</td>
        <td class="my-value">{$activities.rural.registry.number}</td>
      </tr>
      <tr>
        <td class="my-key-2">data wpisania</td>
        <td class="my-value">{$activities.rural.registry.addedDate}</td>
      </tr>
      <tr>
        <td class="my-key-2">data wykreślenia</td>
        <td class="my-value">{$activities.rural.registry.deletedDate}</td>
      </tr>
      <tr>
        <td class="my-key-2">symbol organu prowadzącego</td>
        <td class="my-value">{$activities.rural.registry.registree.symbol}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa organu prowadzącego</td>
        <td class="my-value">{$activities.rural.registry.registree.name}</td>
      </tr>
      <tr>
        <td class="my-key-1">liczba jedn. lokalnych</td>
        <td colspan="2" class="my-value">{$activities.rural.localCount}</td>
      </tr>
    </table>
  </div>
</div>
{fi}

{if present $activities.other}
<div class="row mt-3 mb-3">
  <div class="col">
    <h2 id="other">Inna działalność gospodarcza niepodlegająca zgłoszeniu do CEIDG <small style="display:inline-block; font-size:70%!important; margin-left:10px;"><a href="#top">do góry</a></small></h2>

    <table class="my-data">
      <tr>
        <td class="my-key-1">REGON</td>
        <td colspan="2" class="my-value">{$activities.other.regon}</td>
      </tr>
      <tr>
        <td class="my-key-1">nazwa pełna</td>
        <td colspan="2" class="my-value">{$activities.other.name.long}</td>
      </tr>
      <tr>
        <td class="my-key-1">nazwa skrócona</td>
        <td colspan="2" class="my-value">{$activities.other.name.short}</td>
      </tr>
      <tr>
        <td rowspan="10" class="my-key-1">daty</td>
        <td class="my-key-2">założenia</td>
        <td class="my-value">{$activities.other.dates.creation}</td>
      </tr>
      <tr>
        <td class="my-key-2">rozpoczędzia działalności</td>
        <td class="my-value">{$activities.other.dates.operationStarted}</td>
      </tr>
      <tr>
        <td class="my-key-2">rejestracji w REGON</td>
        <td class="my-value">{$activities.other.dates.regonReg}</td>
      </tr>
      <tr>
        <td class="my-key-2">zawieszenia</td>
        <td class="my-value">{$activities.other.dates.suspended}</td>
      </tr>
      <tr>
        <td class="my-key-2">odwieszenia</td>
        <td class="my-value">{$activities.other.dates.renewed}</td>
      </tr>
      <tr>
        <td class="my-key-2">ostatniej zmiany</td>
        <td class="my-value">{$activities.other.dates.lastChange}</td>
      </tr>
      <tr>
        <td class="my-key-2">zakończenia działalności</td>
        <td class="my-value">{$activities.other.dates.ended}</td>
      </tr>
      <tr>
        <td class="my-key-2">wyrejestrowania z REGON</td>
        <td class="my-value">{$activities.other.dates.regonUnreg}</td>
      </tr>
      <tr>
        <td class="my-key-2">ogłoszenia upadłości</td>
        <td class="my-value">{$activities.other.dates.bankruptcyDeclared}</td>
      </tr>
      <tr>
        <td class="my-key-2">zak. post. upadłościowego</td>
        <td class="my-value">{$activities.other.dates.bankruptcyProceeded}</td>
      </tr>
      <tr>
        <td rowspan="15" class="my-key-1">adres rejestrowy</td>
        <td class="my-key-2">kraj</td>
        <td class="my-value">{$activities.other.regAddress.country}</td>
      </tr>
      <tr>
        <td class="my-key-2">województwo</td>
        <td class="my-value">{$activities.other.regAddress.voivodeship}</td>
      </tr>
      <tr>
        <td class="my-key-2">powiat</td>
        <td class="my-value">{$activities.other.regAddress.county}</td>
      </tr>
      <tr>
        <td class="my-key-2">gmina</td>
        <td class="my-value">{$activities.other.regAddress.municipality}</td>
      </tr>
      <tr>
        <td class="my-key-2">miasto</td>
        <td class="my-value">{$activities.other.regAddress.city}</td>
      </tr>
      <tr>
        <td class="my-key-2">ulica</td>
        <td class="my-value">{$activities.other.regAddress.street}</td>
      </tr>
      <tr>
        <td class="my-key-2">nr nieruchomości</td>
        <td class="my-value">{$activities.other.regAddress.propNumber}</td>
      </tr>
      <tr>
        <td class="my-key-2">nr lokalu</td>
        <td class="my-value">{$activities.other.regAddress.flatNumber}</td>
      </tr>
      <tr>
        <td class="my-key-2">nietypowa lokalizacja</td>
        <td class="my-value">{$activities.other.regAddress.untypical}</td>
      </tr>
      <tr>
        <td class="my-key-2">miasto poczty</td>
        <td class="my-value">{$activities.other.regAddress.postCity}</td>
      </tr>
      <tr>
        <td class="my-key-2">kod pocztowy (PNA)</td>
        <td class="my-value">{$activities.other.regAddress.zipCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT TERC</td>
        <td class="my-value">{$activities.other.regAddress.teryt.terc}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT SIMC poczty</td>
        <td class="my-value">{$activities.other.regAddress.teryt.simcPost}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT SIMC</td>
        <td class="my-value">{$activities.other.regAddress.teryt.simc}</td>
      </tr>
      <tr>
        <td class="my-key-2">TERYT ULIC</td>
        <td class="my-value">{$activities.other.regAddress.teryt.ulic}</td>
      </tr>
      <tr>
        <td rowspan="8" class="my-key-1">forma prawna</td>
        <td class="my-key-2">podstawowa (kod)</td>
        <td class="my-value">{$activities.other.form.baseCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">szczegółowa (kod)</td>
        <td class="my-value">{$activities.other.form.detailedCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">finansowania (kod)</td>
        <td class="my-value">{$activities.other.form.financialCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">własności (kod)</td>
        <td class="my-value">{$activities.other.form.ownershipCode}</td>
      </tr>
      <tr>
        <td class="my-key-2">podstawowa (nazwa)</td>
        <td class="my-value">{$activities.other.form.baseName}</td>
      </tr>
      <tr>
        <td class="my-key-2">szczegółowa (nazwa)</td>
        <td class="my-value">{$activities.other.form.detailedName}</td>
      </tr>
      <tr>
        <td class="my-key-2">finansowania (nazwa)</td>
        <td class="my-value">{$activities.other.form.financialName}</td>
      </tr>
      <tr>
        <td class="my-key-2">własności (nazwa)</td>
        <td class="my-value">{$activities.other.form.ownershipName}</td>
      </tr>
      <tr>
        <td rowspan="2" class="my-key-1">organ założycielski</td>
        <td class="my-key-2">symbol</td>
        <td class="my-value">{$activities.other.mother.symbol}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa</td>
        <td class="my-value">{$activities.other.mother.name}</td>
      </tr>
      <tr>
        <td rowspan="5" class="my-key-1">dane kontaktowe</td>
        <td class="my-key-2">telefon</td>
        <td class="my-value">{$activities.other.contact.phone}</td>
      </tr>
      <tr>
        <td class="my-key-2">numer wewn.</td>
        <td class="my-value">{$activities.other.contact.internalPhone}</td>
      </tr>
      <tr>
        <td class="my-key-2">fax</td>
        <td class="my-value">{$activities.other.contact.fax}</td>
      </tr>
      <tr>
        <td class="my-key-2">e-mail</td>
        <td class="my-value">{$activities.other.contact.email}</td>
      </tr>
      <tr>
        <td class="my-key-2">www</td>
        <td class="my-value">{$activities.other.contact.web}</td>
      </tr>
      <tr>
        <td rowspan="8" class="my-key-1">rejestr</td>
        <td class="my-key-2">typ</td>
        <td class="my-value">{$activities.other.registry.type}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa</td>
        <td class="my-value">{$activities.other.registry.name.formal}</td>
      </tr>
      <tr>
        <td class="my-key-2">numer w rejestrze</td>
        <td class="my-value">{$activities.other.registry.number}</td>
      </tr>
      <tr>
        <td class="my-key-2">data wpisania</td>
        <td class="my-value">{$activities.other.registry.addedDate}</td>
      </tr>
      <tr>
        <td class="my-key-2">data wykreślenia</td>
        <td class="my-value">{$activities.other.registry.deletedDate}</td>
      </tr>
      <tr>
        <td class="my-key-2">symbol organu prowadzącego</td>
        <td class="my-value">{$activities.other.registry.registree.symbol}</td>
      </tr>
      <tr>
        <td class="my-key-2">nazwa organu prowadzącego</td>
        <td class="my-value">{$activities.other.registry.registree.name}</td>
      </tr>
      <tr>
        <td class="my-key-1">liczba jedn. lokalnych</td>
        <td colspan="2" class="my-value">{$activities.other.localCount}</td>
      </tr>
    </table>
  </div>
</div>
{fi}

{include file='elements/footer'}
