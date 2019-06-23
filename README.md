SysXRay 1.0.3
==============

## What is this??
Questa classe è da intendersi come un coltellino svizzero per ottenere info
sul sistema su cui gira php e qualche trick per visualizzare le 
configurazioni del client che visualizza direttamente la pagina. 
 
**Only One Big Page!!!**

Puoi usare i metodi di questa classe includendo questo file dove vuoi o 
puoi raggiungerelo direttamente (in quest'ultimo modo attualmente non è 
il massimo perchè non ci sono controlli lato sicurezza. EG: password o captha) 
Sappiate che se raggiungerete questa pagina direttamente verranno fatti 
partire a cascata tutti i check tra cui un portscanner rudimentale, quindi, 
consiglio di commentare o cancellare i controlli che non vi interessano.

Usate tutto quello messo a disposizione in questo file a vostro rischio e 
pericolo, se verrete bannati dal vostro hosting o qualsiasi altra cosa vi 
possa capitare di male la responsabilità non sarà nostra :grey_exclamation:

Attualmente **SysXRay** è assolutamente work in progress quindi è possibile che 
molte funzionalità non siano utilizzabili nell'infrastruttura nella quale 
lo farete girare, dipende anche dalla vostra fortuna e/o dalla capacità 
modificare questo file secondo esigenze.

## Disclaimer
Use this at your own risk :grey_exclamation:

## Requirements
- PHP (Tested with **PHP ^7.x** but should be work also with **PHP 5.x** and others)
- Javascript enabled on Client browser for more detailed client infos (OPTIONAL)

## Installation
```
$ git clone git://github.com/n4d46t3m/sysXRay.git
```
or
```
$ git clone https://github.com/n4d46t3m/sysXRay
```
Read the code and do something :stuck_out_tongue_closed_eyes:

## TODO :expressionless:
- Cercare di inviare mail
- Far partire il portscan solo dopo che la pagina è caricata nel caso in cui l'utente lo voglia
- Far partire i check sul client solo dopo che la pagina è caricata nel caso in cui l'utente lo voglia

## Special TnX :kissing_cat:
- https://www.php.net/docs.php
- Zolik Panik
