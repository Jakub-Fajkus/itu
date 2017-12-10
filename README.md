#Instalace

Aplikace je vyvíjena pro docker.
Pro spuštění aplikace je nutné mít nainstalované aplikace docker a docker-compose.

##Postup spuštění
Ve složce, kam se rozbalil archiv, je nutné spustit:
1. "docker-compose build" - stáhne image virtuálního stroje(pár minut trvá) a nainstaluje do vnitřního systému vše potřebné
2. "docker-compose up" - spustí aplikaci v aktuálním terminálu na popředí. Při prvním spuštění inicializuje mysql server.
3. "docker-compose exec php bash" - V novém terminálu: přihlásí se do vitruálního stroje a spustí v něm terminál
4. "composer install" - stáhne všechny php knihovny a provede nastavení aplikace 
5. Aplikace je dostupná na portu 8000

Po těchto krocích by měla být aplikace funkční. 
Může se stát, že v kroku 2 se nepovede spustit virtuální stroj. 
To se projeví v kroku 3, kdy se nelze připojit do virtuálního stroje. 
V tomto případě může pomoct, když se v terminálu, kde byl spuštěn krok 2. zmáčkne CTRL+C,
čímž se vypne virtuální stroj. Poté je nutné pokračovat znovu od kroku 2.

Obecně může nastat mnoho chyb(např. nedostatečná oprávnění, již zabrané síťové porty, apod.), 
které se obecně těžko popisují, ale prakticky jsou s malými zkušenostmi snadno řešitelné.

Pokud by se nepodařilo aplikaci na lokálním stroji stroji spustit, 
je k dispozici záložní aplikace na adrese(je ovšem poněkud pomalejší) http://iis.jakubfajkus.cz:8000
