# Krimiland, opfølgning
Dette er relateret til Krimiland afsnit 10. Hør programmerne her https://www.radio4.dk/program/krimiland/

Der bliver i afsnittet omtalt et interessant website, [estoniaferrydisaster.net](https:/www.estoniaferrydisaster.net)
, som desværre er skæmmet af at være svært tilgængelig. Orris ærgrer sig over, at man ikke kan få fat i rapporten i papirformat eller PDF, og der jokes med om ikke nogle af lytterne kan tage opgaven på sig. Undertegnede tog handsken op, og dette er hvad man sådan lige kunne automatisere via konsollen. 

Resultatet er ikke komplet!!! Og hvorfor og hvordan det er gjort beskrives nedenfor. Den genererede PDF er pt. 374 sider, men den er langt fra færdig, og der er uorden i indholdet. Der mangler (tilsyneladende) en masse enkeltstående sider. 

Så hvis nogle er interesserede i at forbedre PDF'en, opret et issue med et link (eller det der ligner), til den eller de sider der mangler, så kan jeg hente dem ned og køre de 3 step forfra og dermed fabrikere en bedre PDF. Det gælder også hvis du mener der skal ændres i ordenen af indholdet, eller whatever. 

De manglende sider kan heller ikke indlæses / indekseres af t.ex Googles søgerobot. Men inden man spekulerer alt for meget over dette, så er det min bedømmelse, at sitet var state of the art dengang det blev bygget / lanceret. Så det er ikke en "villet" skyggetilværelse, teknikken har blot udviklet sig enormt siden.
 
Er du dedikeret kan du blive collaborator / medejer af projektet, så du kan lave opdateringer selv. Jeg er også parat til at overdrage hele molevitten til Krimiland. 

Krimiland kan "clone" projektet og køre det videre, så sletter/skjuler jeg dette repositorie.


# estoniaferrydisaster dot net rapport som PDF

### Fremgangsmåde

Som det blev antydet i programmet, så er hjemmesiden med den tyske rapport ikke blot "gammeldags", men også særdeles vanskelig at navigere i. Der benyttes noget der kaldes `<frameset>` og `<iframe>`'s, samt total oldschool navigeringsmarkup placeret i `<map>` og `<area>`'s. Det gør i praksis sitet umulig at crawle.


#### Download
Følger man de enkelte links manuelt kan man dog finde den relative sti til rapportens index - `estonia final report/Contents.htm` - og via den downloade "hele" rapporten. Ja, altså kun de sider der direkte linkes til, og så deres undersider rekursivt. Men visse dele af rapporten er kun tilgængelig via disse `<area>` tags. I Linux kan man skrive:

```bash
$ wget -r -l 1 https://www.estoniaferrydisaster.net/estonia%20final%20report/Contents.htm
```

Det resulterer i et `/www.estoniaferrydisaster.net/..` katalog (se ovenover), hvor hele rapporten ligger i 123 (spøjst) forskellige filer, med navne som `chapter42.htm` og `germanxperts.htm`. Altså i uskøn orden, men trods alt med sigende logiske filnavne, man kan relatere til ift. indekset.

#### Samling
Inde fra kataloget genereres en liste over samtlige filer:

```bash
$ ls -R > filer.txt
```

Fordi filerne har sigende navne er det nemt (men omstændeligt, og har opgivet for nærværende at blive færdig) at sortere i listen så rækkefølgen stemmer overens med indekset og en kapitel 1-43 orden. Når det er gjort kan man med lidt mere konsolmagi samle hele rapporten: 

```bash
$ xargs < filer.txt cat > ../estoniaferrydisaster.net.html
```
#### Billeder
Det er kun selve teksten der er hentet ned og samlet, og desværre er samtlige billedreferencer baseret på relative `src`-stier, så rapporten er fuld af "tomme ruder".  For at fikse dette kan man hive samtlige billeder ned lokalt, og lægge dem i `/estonia final report/`-kataloget. Det giver mening hvis man vil sikre sin egen komplette kopi, men for at generere en PDF er det tilstrækkeligt at ændre de relative stier til absolutte:

```php
<?php
$from = 'www.estoniaferrydisaster.net/estoniaferrydisaster.net.html';
$to = 'www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html';
$dom = new DOMDocument('1.0');
$dom->loadHTMLFile($from);
$images = $dom->getElementsByTagName('img');
foreach($images as $image) {
   $src = $image->getAttribute('src'); 
   $image->setAttribute('src', 'https://www.estoniaferrydisaster.net/'.$src);
}
$dom->save($to);
?>
```

#### PDF
Brug chromium-browserens indbyggede print -> destination -> save as PDF. 

